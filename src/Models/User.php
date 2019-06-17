<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        'login',
        'email',
        'password',
        'firstname',
        'surname',
        'fathername',
        'sex',
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
                !empty(Auth::user()) &&
                Auth::user()->hasRole('admin')
            ) {
                $model->email_verified_at = Carbon::now();
            }
        });

        static::updated(function ($model) {
            event(new UserUpdate($model));
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const SEX = [
        0 => "Не выбранно",
        1 => "Мужской",
        2 => "Женский",
    ];

    /**
     * У пользователя один аватар.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar() {
        return $this->belongsTo('App\Image', 'avatar_id');
    }

    /**
     * У пользователя может быть много картинок.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images() {
        return $this->morphMany('App\Image', 'imageable');
    }

    /**
     * У пользователя много ролей.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('App\Role');
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
            return $this->email_verified_at;
        }
        else {
            return 'Ожидается подтверждение';
        }
    }

    /**
     * Определяем пол по значению, это по стандарту DB.
     *
     * @return string
     */
    public function getSexTextAttribute() {
        if (!empty(self::SEX[$this->sex])) {
            return self::SEX[$this->sex];
        }
        else {
            return "Error";
        }
    }

    /**
     * Список для селектов выбора пола.
     *
     * @return array
     */
    public function getSexList() {
        return self::SEX;
    }

    /**
     * Собираем ФИО.
     *
     * @return string
     */
    public function getFullNameAttribute() {
        $fullName = [
            $this->surname,
            $this->firstname,
            $this->fathername,
        ];
        return implode(' ', $fullName);
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
        $adminRole = Role::where('name', 'admin')->first();
        if (
            $this->id == Auth::user()->id &&
            !in_array($adminRole->id, $roles)
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

}
