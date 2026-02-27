<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    use HasFactory;

    protected $table = 't_transaksi';
    protected $primaryKey = 'id_transaksi';

    public $timestamps = true;

    protected $fillable = [
        'id_penyewa',
        'id_kamar',
        'tanggal_bayar',
        'tanggal_masuk',
        'tanggal_keluar',
        'status'
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
}