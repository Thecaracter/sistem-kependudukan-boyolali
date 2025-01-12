<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KartuKeluarga extends Model
{
    use HasUuids;

    protected $table = 'kartu_keluarga';
    protected $primaryKey = 'id_kk';

    protected $fillable = [
        'nomor_kk',
        'kepala_keluarga_id',
        'tanggal_pembuatan',
        'id_rumah'
    ];

    protected $casts = [
        'tanggal_pembuatan' => 'date'
    ];

    // Relationship dengan IdentitasRumah
    public function identitasRumah(): BelongsTo
    {
        return $this->belongsTo(IdentitasRumah::class, 'id_rumah', 'id_rumah');
    }

    // Relationship dengan Penduduk (Kepala Keluarga)
    public function kepalaKeluarga(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id', 'id_penduduk');
    }

    // Relationship dengan semua anggota keluarga
    public function anggotaKeluarga(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'id_kk', 'id_kk');
    }

    // Relationship dengan VerifikasiPenduduk
    public function verifikasi(): HasOne
    {
        return $this->hasOne(VerifikasiPenduduk::class, 'id_kk', 'id_kk');
    }
}