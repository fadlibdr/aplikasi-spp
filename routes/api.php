<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\JurnalUmumController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


Route::post('midtrans/callback', [PembayaranController::class, 'callback'])
    ->name('midtrans.callback');

Route::get('keuangan', [KeuanganController::class, 'apiData'])->name('api.keuangan');
Route::get('jurnal-umum', [JurnalUmumController::class, 'apiData'])->name('api.jurnal-umum');
Route::get('laporan', [LaporanController::class, 'apiData'])->name('api.laporan');
