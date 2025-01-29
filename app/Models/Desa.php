<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_desa'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_desa', 'id');
    }

    public function kartuKeluarga()
    {
        return $this->hasMany(KartuKeluarga::class, 'id_desa', 'id');
    }
}