<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PortedCheese\BaseSettings\Events\UserUpdate;
use PortedCheese\BaseSettings\Notifications\CustomResetPasswordNotify;
use PortedCheese\BaseSettings\Traits\HasImage;
use PortedCheese\BaseSettings\Traits\ShouldGallery;
use PortedCheese\BaseSettings\Traits\ShouldImage;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, ShouldImage, ShouldGallery;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'middle_name',
        "phone_number",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function(\App\User $model) {
            // Чистим таблицу ролей.
            $model->roles()->sync([]);
            // Забыть кэш ролей.
            $model->forgetRoleIdsCache();
        });

        static::creating(function(\App\User $model) {
            if (
                ! empty(Auth::user()) &&
                Auth::user()->hasRole(\App\Role::SUPER)
            ) {
                $model->email_verified_at = Carbon::now();
            }
            if (! empty(app('request')->getClientIp())) {
                $model->creator_address = app('request')->getClientIp();
            }
            if (empty($model->name)) {
                $exploded = explode("@", $model->email);
                if (! empty($exploded[0])) {
                    $model->name = $exploded[0];
                }
                else {
                    $model->name = "";
                }
            }
        });

        static::updated(function (\App\User $model) {
            event(new UserUpdate($model));
        });
    }

    /**
     * У пользователя один аватар.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar() {
        return $this->belongsTo(\App\Image::class, 'image_id');
    }

    /**
     * У пользователя много ролей.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany(\App\Role::class);
    }

    /**
     * Хэшируем пароль при сохранении.
     *
     * @param $value
     */
    public function setPasswordAttribute($value) {
        if (Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotify($token));
    }

    /**
     * Выводим либо дату подтверждения либо то что он не подтвержден.
     *
     * @return mixed|string
     */
    public function getVerifiedAttribute() {
        if (!empty($this->email_verified_at)) {
            return datehelper()->format($this->email_verified_at);
        }
        else {
            return 'Ожидается подтверждение';
        }
    }

    /**
     * Собираем ФИО.
     *
     * @return string
     */
    public function getFullNameAttribute() {
        $fullName = [
            $this->last_name,
            $this->name,
            $this->middle_name,
        ];
        $fullName = implode(' ', $fullName);
        return trim($fullName);
    }

    /**
     * Проверяем есть ли у пользователя роль.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role) {
        return $this->roles->where('name', $role)->count();
    }

    /**
     * Задаем роли пользователя по массиву ролей.
     *
     * @param $roles
     */
    public function setRoles($roles) {
        $adminRole = Role::query()
            ->where('name', \App\Role::SUPER)
            ->first();
        // Если админ пытается снять у себя роль админа, вернуть ее.
        if (
            $this->id == Auth::id() &&
            !in_array($adminRole->id, $roles) &&
            $this->hasRole(\App\Role::SUPER)
        ) {
            $roles[] = $adminRole->id;
        }
        //Если не админ пытается снять роль у админа, вернуть ее
        if (
            !Auth::user()->hasRole(\App\Role::SUPER) &&
            $this->hasRole(\App\Role::SUPER) &&
            !in_array($adminRole->id, $roles)
        ) {
            $roles[] = $adminRole->id;
        }
        //Если не админ пытается поставить роль админа, отменяем изменения
        if (
            !Auth::user()->hasRole(\App\Role::SUPER) &&
            !$this->hasRole(\App\Role::SUPER) &&
            in_array($adminRole->id, $roles)
        ) {
            return;
        }

        $this->roles()->sync($roles);
        $this->forgetRoleIdsCache();
    }

    /**
     * Обновить или задать токен для пользователя.
     *
     * @return string
     */
    public function setBaseToken()
    {
        $token = Str::random(60);
        $this->base_token = $token;
        $this->save();
        return $token;
    }

    /**
     * Проверить право доступа.
     *
     * @param \App\RoleRule $rule
     * @param $permission
     * @return bool
     */
    public function hasPermission(\App\RoleRule $rule, $permission)
    {
        $roleIds = $this->getRoleIds();
        $rules = $rule->getPermissionsByRoles($roleIds);
        $condition = false;
        foreach ($rules as $rights) {
            if ($rights & $permission) {
                $condition = true;
                break;
            }
        }
        return $condition;
    }

    /**
     * Пользователь с доступом ко всему.
     *
     * @return bool
     */
    public function isSuperUser()
    {
        return $this->hasRole(\App\Role::SUPER);
    }

    /**
     * Пользователь с доступом к основному редактированию.
     *
     * @return bool
     */
    public function isEditorUser()
    {
        return $this->hasRole(\App\Role::EDITOR) || $this->hasRole(\App\Role::SUPER);
    }

    /**
     * Получить id ролей.
     *
     * @return mixed
     */
    public function getRoleIds()
    {
        $user = $this;
        return Cache::rememberForever("user-roles:{$this->id}", function () use ($user) {
            $roles = $user
                ->roles()
                ->select("id")
                ->get();
            $roleIds = [];
            foreach ($roles as $role) {
                $roleIds[] = $role->id;
            }
            return $roleIds;
        });
    }
    /**
     * Получить пользователей без роли Админа
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     */
    public static function getNoSuperUsers()
    {

        $users = DB::table("role_user")->select("user_id")->where("role_id", "!=", \App\Role::getSuperId())->get();
        $users = $users->toArray();
        $users_ids=[];
        foreach ($users as $user) {
            foreach ($user as $key => $value) {
                $users_ids[]=$value;
            }
        }
        return \App\User::query()->whereIn("id", $users_ids);
    }

    /**
     * Забыть кэш ролей.
     */
    public function forgetRoleIdsCache()
    {
        Cache::forget("user-roles:{$this->id}");
    }
}
