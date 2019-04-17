<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'default',
    ];

    public function users() {
        return $this->belongsToMany('App\User');
    }
}
