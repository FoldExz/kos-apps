<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamarModel extends Model
{
    use HasFactory;

    protected $table = 't_kamar';
    protected $primaryKey = 'id_kamar';

    public $timestamps = true;

    protected $fillable = [
        'id_tipe_kamar',
        'no_kamar',
        'status'
    ];

    // kamar → tipe kamar
    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamarModel::class, 'id_tipe_kamar');
    }

    // kamar → transaksi banyak
    public function transaksi()
    {
        return $this->hasMany(TransaksiSewaModel::class, 'id_kamar');
    }
}