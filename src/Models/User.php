<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PortedCheese\BaseSettings\Events\UserUpdate;
use PortedCheese\BaseSettings\Notifications\CustomResetPasswordNotify;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

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

        static::deleting(function($model) {
            // Удаляем аватар.
            $model->clearAvatar();
            // Чистим таблицу ролей.
            $model->roles()->sync([]);
            // Удаляем изображения.
            $model->clearImages();
        });

        static::creating(function($model) {
            if (
                ! empty(Auth::user()) &&
                Auth::user()->hasRole('admin')
            ) {
                $model->email_verified_at = Carbon::now();
            }
            if (! empty(app('request')->getClientIp())) {
                $model->creator_address = app('request')->getClientIp();
            }
        });

        static::updated(function ($model) {
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
     * У пользователя может быть много картинок.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images() {
        return $this->morphMany(\App\Image::class, 'imageable');
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
            ->where('name', 'admin')
            ->first();
        if (
            $this->id == Auth::id() &&
            !in_array($adminRole->id, $roles) &&
            $this->hasRole('admin')
        ) {
            $roles[] = $adminRole->id;
        }
        $this->roles()->sync($roles);
    }

    /**
     * Удалить все изображения.
     */
    public function clearImages()
    {
        $images = $this->images;
        foreach ($images as $image) {
            $image->delete();
        }
    }

    /**
     * Очистить аватар.
     */
    public function clearAvatar() {
        $image = $this->avatar;
        if (!empty($image)) {
            $image->delete();
        }
        $this->avatar()->dissociate();
        $this->save();
    }

    /**
     * Изменить/создать аватар.
     *
     * @param Request $request
     */
    public function uploadAvatar(Request $request) {
        if ($request->hasFile('avatar')) {
            $this->clearAvatar();
            $path = $request->file('avatar')->store('avatars');
            $image = Image::create([
                'path' => $path,
                'name' => 'avatar-' . $this->id,
            ]);
            $this->avatar()->associate($image);
            $this->save();
        }
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
}
