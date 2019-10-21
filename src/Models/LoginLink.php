<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LoginLink extends Model
{
    use Notifiable;

    protected $fillable = [
        "email",
        "send",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->token = Str::uuid();
        });

        static::created(function (self $model) {
            if (! empty($model->send)) {
                $model->notify(new \PortedCheese\BaseSettings\Notifications\LoginLink($model));
            }
        });
    }

    /**
     * Получить пользователя.
     *
     * @param $token
     * @return mixed|null
     * @throws \Exception
     */
    public static function validFromToken($token)
    {
        try {
            $link = self::query()
                ->where("token", $token)
                ->where("created_at", ">", Carbon::parse("-15 minutes"))
                ->firstOrFail();
            $user = $link->user;
            $link->delete();
            return $user;
        }
        catch (\Exception $exception) {
            $links = self::query()
                ->where("created_at", "<=", Carbon::parse("-15 minutes"))
                ->get();
            foreach ($links as $link) {
                $link->delete();
            }
            return null;
        }
    }

    public function routeNotificationForMail($notification)
    {
        return $this->send ? $this->send : $this->email;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, "email", "email");
    }
}
