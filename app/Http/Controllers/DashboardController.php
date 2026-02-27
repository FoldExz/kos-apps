<?php

namespace App\Http\Controllers;

use App\Models\KamarModel;
use App\Models\PenyewaModel;
use App\Models\PengelolaModel;
use App\Models\TransaksiPembayaranModel;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalKamar'          => KamarModel::count(),
            'totalKamarTersedia'  => KamarModel::where('status', 'tersedia')->count(),
            'totalKamarDisewa'    => KamarModel::where('status', 'disewa')->count(),
            'totalPenyewa'        => PenyewaModel::count(),
            'totalPengelola'      => PengelolaModel::count(),
            'totalTransaksi'      => TransaksiPembayaranModel::whereMonth('created_at', now()->month)->count()
        ]);
    }
}