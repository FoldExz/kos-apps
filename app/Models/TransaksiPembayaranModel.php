<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembayaranModel extends Model
{
    use HasFactory;

    protected $table = 't_transaksi_pembayaran';
    protected $primaryKey = 'id_transaksi_pembayaran';

    protected $fillable = [
        'id_transaksi_sewa',
        'tanggal_bayar',
        'tanggal_jatuh_tempo',
        'uraian',
        'nominal',
        'bukti_bayar',
        'status'
    ];

    public function transaksiSewa()
    {
        return $this->belongsTo(TransaksiSewaModel::class, 'id_transaksi_sewa');
    }
}