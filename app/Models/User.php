<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable, HasUuids;

    protected $table = 'users';
    protected $primaryKey = 'id_pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'foto',
        'id_desa'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id_pengguna';
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id');
    }
}