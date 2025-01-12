<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiPenduduk extends Model
{
    use HasUuids;

    protected $table = 'verifikasi_penduduk';
    protected $primaryKey = 'id_verifikasi';

    protected $fillable = [
        'id_kk',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Status verifikasi yang tersedia
    public const STATUS = [
        'pending',
        'verified',
        'rejected'
    ];

    // Relationship dengan KartuKeluarga
    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'id_kk', 'id_kk');
    }

    // Scope untuk pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk verified
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    // Scope untuk rejected
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}