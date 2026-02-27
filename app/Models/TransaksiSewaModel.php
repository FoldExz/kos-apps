<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSewaModel extends Model
{
    use HasFactory;

    protected $table = 't_transaksi_sewa';
    protected $primaryKey = 'id_transaksi_sewa';

    public $timestamps = true;

    protected $fillable = [
        'id_penyewa',
        'id_kamar',
        'lama_sewa',
        'tanggal_sewa',
        'tanggal_batas_sewa',
        'status'
    ];
    protected $casts = [
    'tanggal_sewa' => 'datetime',
    'tanggal_batas_sewa' => 'datetime',
];


    // transaksi → penyewa
    public function penyewa()
    {
        return $this->belongsTo(PenyewaModel::class, 'id_penyewa');
    }

    // transaksi → kamar
    public function kamar()
    {
        return $this->belongsTo(KamarModel::class, 'id_kamar');
    }
    
    public function pembayaran()
    {
        return $this->hasMany(TransaksiPembayaranModel::class, 'id_transaksi_sewa', 'id_transaksi_sewa');
    }
}