<?php

namespace App\Models;

use Ajifatur\FaturHelper\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends \Ajifatur\FaturHelper\Models\User
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the attribute associated with the user.
     */
    public function attribute()
    {
        return $this->hasOne(UserAttribute::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Monitoring.
     */
    // public function monitoring()
    // {
    //     return $this->hasMany(Monitoring::class);
    // }

    public function datainput()
    {
        return $this->hasMany(DataDebitur::class, 'id_input', 'id');
    }

    public function dataverif()
    {
        return $this->hasMany(DataDebitur::class, 'id_verif', 'id');
    }

    public function dataapp()
    {
        return $this->hasMany(DataDebitur::class, 'id_approve', 'id');
    }
}
