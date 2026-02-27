<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengelolaModel extends Model
{
    protected $table = 't_pengelola';
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'jenis_kelamin'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id_pengelola', 'id_pemilik');
    }
}