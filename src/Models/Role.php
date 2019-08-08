<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    protected $fillable = [
        'name',
        'default',
        'title',
    ];

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public static function getForAdmin()
    {
        $roles = self::query();
        if (! Auth::user()->hasRole('admin')) {
            $roles->where('name', '!=', 'admin');
        }
        return $roles->get();
    }
}
