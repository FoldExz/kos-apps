<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterOpdModel extends Model
{
    // Arahkan model ini khusus ke koneksi PostgreSQL
    protected $connection = 'pgsql'; 
    
    protected $table = 'master_opd_skpd';
    protected $primaryKey = 'kd_skpd';
    
    // Karena Primary Key-nya string (kode SKPD), bukan angka auto-increment
    public $incrementing = false; 
    protected $keyType = 'string';
    
    // Matikan timestamps karena di tabel master kita tadi gak ada created_at & updated_at
    public $timestamps = false; 
}