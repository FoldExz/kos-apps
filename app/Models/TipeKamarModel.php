<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeKamarModel extends Model
{
    use HasFactory;

    protected $table = 't_tipe_kamar';
    protected $primaryKey = 'id_tipe_kamar';

    public $timestamps = true;

    protected $fillable = [
        'jenis_tipe_kamar',
        'harga_perbulan',
        'fasilitas'
    ];

    // tipe kamar → kamar banyak
    public function kamar()
    {
        return $this->hasMany(KamarModel::class, 'id_tipe_kamar');
    }
}