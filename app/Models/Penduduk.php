<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penduduk extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'penduduk';
    protected $primaryKey = 'id_penduduk';

    protected $fillable = [
        'nama',
        'nik',
        'tanggal_lahir',
        'alamat',
        'status_keluarga',
        'pendidikan',
        'id_kk'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'status_keluarga' => 'string',
        'pendidikan' => 'string'
    ];

    // Status Keluarga yang tersedia
    public const STATUS_KELUARGA = [
        'kepala_keluarga',
        'istri',
        'anak',
        'menantu',
        'cucu',
        'orangtua',
        'mertua',
        'famili_lain',
        'lainnya'
    ];

    // Pendidikan yang tersedia
    public const PENDIDIKAN = [
        'tidak_sekolah',
        'sd',
        'smp',
        'sma',
        'd1',
        'd2',
        'd3',
        's1',
        's2',
        's3'
    ];

    // Relationship dengan KartuKeluarga
    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'id_kk', 'id_kk');
    }

    // Relationship dengan KartuKeluarga sebagai Kepala Keluarga
    public function kartuKeluargaSebagaiKepala(): HasOne
    {
        return $this->hasOne(KartuKeluarga::class, 'kepala_keluarga_id', 'id_penduduk');
    }

    // Scope untuk kepala keluarga
    public function scopeKepalaKeluarga($query)
    {
        return $query->where('status_keluarga', 'kepala_keluarga');
    }

    // Scope untuk anggota keluarga aktif
    public function scopeAktif($query)
    {
        return $query->whereNull('deleted_at');
    }
}