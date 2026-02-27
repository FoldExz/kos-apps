<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransBarangJasaModel extends Model
{
    // Arahkan model ini khusus ke koneksi PostgreSQL
    protected $connection = 'pgsql'; 
    
    protected $table = 'trans_barang_jasa';
    protected $primaryKey = 'kd_barang';
    public $incrementing = false; // Karena PK berupa string (kd_barang)
    protected $keyType = 'string';

    // ... sisa konfigurasi model
}