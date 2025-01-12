<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IdentitasRumah extends Model
{
    use HasUuids;

    protected $table = 'identitas_rumah';
    protected $primaryKey = 'id_rumah';

    protected $fillable = [
        'id_kk',
        'alamat_rumah',
        'tipe_lantai',
        'jumlah_kamar_tidur',
        'jumlah_kamar_mandi',
        'atap',
        'barcode'
    ];

    protected $casts = [
        'jumlah_kamar_tidur' => 'integer',
        'jumlah_kamar_mandi' => 'integer',
        'tipe_lantai' => 'string',
        'atap' => 'string'
    ];

    // Relationship dengan KartuKeluarga
    public function kartuKeluarga(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class, 'id_rumah', 'id_rumah');
    }

    // Relationship dengan KartuKeluarga yang aktif
    public function kartuKeluargaAktif(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'id_kk', 'id_kk');
    }
}