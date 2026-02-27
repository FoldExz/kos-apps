<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyewaModel extends Model
{
    use HasFactory;

    protected $table = 't_penyewa';
    protected $primaryKey = 'id_penyewa';

    public $timestamps = true;

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'foto_ktp',
        'pekerjaan'
    ];

    public function transaksi()
{
    return $this->hasMany(TransaksiSewaModel::class, 'id_penyewa');
}

    public function user()
    {
        return $this->hasOne(UserModel::class, 'id_penyewa', 'id_penyewa');
    }
}