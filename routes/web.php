<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\TipeKamarController;
use App\Http\Controllers\TransaksiPembayaranController;
use App\Http\Controllers\TransaksiSewaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Route untuk proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function(){

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::prefix('penyewa')->name('penyewa.')->group(function () {
        Route::post('/list', [PenyewaController::class, 'list']);
        Route::get('/', [PenyewaController::class, 'index']);
        Route::get('/create', [PenyewaController::class, 'create']);
        Route::post('/store', [PenyewaController::class, 'store']);
        Route::get('/{id}/show', [PenyewaController::class, 'show']);
        Route::get('/{id}/edit', [PenyewaController::class, 'edit']);
        Route::put('/{id}/update', [PenyewaController::class, 'update']);
        Route::get('/{id}/confirm', [PenyewaController::class, 'confirm']);
        Route::delete('/{id}/delete', [PenyewaController::class, 'delete']);
    });
        
    Route::prefix('pengelola')->name('pengelola.')->group(function () {
        Route::post('/list', [PengelolaController::class, 'list']);
        Route::get('/', [PengelolaController::class, 'index']);
        Route::get('/create', [PengelolaController::class, 'create']);
        Route::post('/store', [PengelolaController::class, 'store']);
        Route::get('/{id}/show', [PengelolaController::class, 'show']);
        Route::get('/{id}/edit', [PengelolaController::class, 'edit']);
        Route::put('/{id}/update', [PengelolaController::class, 'update']);
        Route::get('/{id}/confirm', [PengelolaController::class, 'confirm']);
        Route::delete('/{id}/delete', [PengelolaController::class, 'delete']);
    });

        Route::prefix('tipe_kamar')->name('tipe_kamar.')->group(function () {
        Route::post('/list', [TipeKamarController::class, 'list']);
        Route::get('/', [TipeKamarController::class, 'index']);
        Route::get('/create', [TipeKamarController::class, 'create']);
        Route::post('/store', [TipeKamarController::class, 'store']);
        Route::get('/{id}/show', [TipeKamarController::class, 'show']);
        Route::get('/{id}/edit', [TipeKamarController::class, 'edit']);
        Route::put('/{id}/update', [TipeKamarController::class, 'update']);
        Route::get('/{id}/confirm', [TipeKamarController::class, 'confirm']);
        Route::delete('/{id}/delete', [TipeKamarController::class, 'delete']);
    });

        Route::prefix('kamar')->name('kamar.')->group(function () {
        Route::post('/list', [KamarController::class, 'list']);
        Route::get('/', [KamarController::class, 'index']);
        Route::get('/create', [KamarController::class, 'create']);
        Route::post('/store', [KamarController::class, 'store']);
        Route::get('/{id}/show', [KamarController::class, 'show']);
        Route::get('/{id}/edit', [KamarController::class, 'edit']);
        Route::put('/{id}/update', [KamarController::class, 'update']);
        Route::get('/{id}/confirm', [KamarController::class, 'confirm']);
        Route::delete('/{id}/delete', [KamarController::class, 'delete']);
    });

        Route::prefix('transaksi_sewa')->name('transaksi_sewa.')->group(function () {
        Route::post('/list', [TransaksiSewaController::class, 'list']);
        Route::get('/', [TransaksiSewaController::class, 'index']);
        Route::get('/create', [TransaksiSewaController::class, 'create']);
        Route::post('/store', [TransaksiSewaController::class, 'store']);
        Route::get('/{id}/show', [TransaksiSewaController::class, 'show']);
        Route::get('/{id}/edit', [TransaksiSewaController::class, 'edit']);
        Route::put('/{id}/update', [TransaksiSewaController::class, 'update']);
        Route::get('/{id}/confirm', [TransaksiSewaController::class, 'confirm']);
        Route::delete('/{id}/delete', [TransaksiSewaController::class, 'delete']);
        Route::get('/{id}/generateDocx', [TransaksiSewaController::class, 'generateDocx']);


    });

        Route::prefix('transaksi_pembayaran')->name('transaksi_pembayaran.')->group(function () {
        Route::post('/list', [TransaksiPembayaranController::class, 'list']);
        Route::get('/', [TransaksiPembayaranController::class, 'index']);
        Route::get('/create', [TransaksiPembayaranController::class, 'create']);
        Route::post('/store', [TransaksiPembayaranController::class, 'store']);
        Route::get('/{id}/show', [TransaksiPembayaranController::class, 'show']);
        Route::get('/{id}/edit', [TransaksiPembayaranController::class, 'edit']);
        Route::put('/{id}/update', [TransaksiPembayaranController::class, 'update']);
        Route::get('/{id}/confirm', [TransaksiPembayaranController::class, 'confirm']);
        Route::delete('/{id}/delete', [TransaksiPembayaranController::class, 'delete']);
        Route::get('/{id}/generatePembayaran', [TransaksiPembayaranController::class, 'generatePembayaran']);
    });

    
        Route::prefix('user')->name('user.')->group(function () {

            Route::post('/list', [UserController::class, 'list']);
            Route::get('/', [UserController::class, 'index']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/store', [UserController::class, 'store']);
            Route::get('/{id}/show', [UserController::class, 'show']);
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::put('/{id}/update', [UserController::class, 'update']);
            Route::get('/{id}/confirm', [UserController::class, 'confirm']);
            Route::delete('/{id}/delete', [UserController::class, 'delete']);
            
        });

});      