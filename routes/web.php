<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\Modul6Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CekPembayaranController;
use App\Http\Controllers\RiwayatTransaksiController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\JurnalUmumController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfilSekolahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;






Route::get('/', function () {
    return view('welcome');
});


Route::resource('profil', ProfilSekolahController::class);

Route::post('midtrans/callback', [PembayaranController::class, 'callback'])
    ->name('midtrans.callback');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('tahun-ajaran', TahunAjaranController::class);
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('kelas', KelasController::class);
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('siswa', SiswaController::class)->except(['show']);
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
});


Route::group(['middleware' => ['auth', 'role:admin']], function () {
    // Jenis Pembayaran
    Route::get('jenis-pembayaran', [Modul6Controller::class, 'indexJenis'])->name('jenis.index');
    Route::get('jenis-pembayaran/create', [Modul6Controller::class, 'createJenis'])->name('jenis.create');
    Route::post('jenis-pembayaran', [Modul6Controller::class, 'storeJenis'])->name('jenis.store');
    Route::get('jenis-pembayaran/{jenis}/edit', [Modul6Controller::class, 'editJenis'])->name('jenis.edit');
    Route::put('jenis-pembayaran/{jenis}', [Modul6Controller::class, 'updateJenis'])->name('jenis.update');
    Route::delete('jenis-pembayaran/{jenis}', [Modul6Controller::class, 'destroyJenis'])->name('jenis.destroy');

    // Iuran
    Route::get('iuran', [Modul6Controller::class, 'indexIuran'])->name('iuran.index');
    Route::get('iuran/create', [Modul6Controller::class, 'createIuran'])->name('iuran.create');
    Route::post('iuran', [Modul6Controller::class, 'storeIuran'])->name('iuran.store');
    Route::get('iuran/{iuran}/edit', [Modul6Controller::class, 'editIuran'])->name('iuran.edit');
    Route::put('iuran/{iuran}', [Modul6Controller::class, 'updateIuran'])->name('iuran.update');
    Route::delete('iuran/{iuran}', [Modul6Controller::class, 'destroyIuran'])->name('iuran.destroy');
});


Route::middleware(['auth', 'role:admin|operator'])->group(function () {
    Route::get('jurnal-umum', [JurnalUmumController::class, 'index'])
        ->name('jurnal-umum.index');
    Route::get('jurnal-umum/create', [JurnalUmumController::class, 'create'])
        ->name('jurnal-umum.create');
    Route::post('jurnal-umum', [JurnalUmumController::class, 'store'])
        ->name('jurnal-umum.store');

    Route::get('jurnal-umum/export-excel', [JurnalUmumController::class, 'exportExcel'])
        ->name('jurnal-umum.export-excel');
    Route::get('jurnal-umum/cetak-pdf', [JurnalUmumController::class, 'exportPdf'])
        ->name('jurnal-umum.cetak-pdf');
});




Route::middleware(['auth', 'role:admin|operator'])->group(function () {
    // 1) Tampilkan daftar iuran pending
    Route::get('/pembayaran', [PembayaranController::class, 'form'])->name('pembayaran.index');

    // 2) Halaman bayar untuk satu iuran
    Route::get('/pembayaran/bayar/{iuran}', [PembayaranController::class, 'bayar'])->name('pembayaran.bayar');
});



// Admin-only dashboard
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');
});

// Siswa-only dashboard
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard-siswa', [DashboardController::class, 'student'])
        ->name('dashboard.student');
});


Route::get('/cek-pembayaran', [CekPembayaranController::class, 'index'])->name('cek-pembayaran.index');
Route::post('/cek-pembayaran', [CekPembayaranController::class, 'show'])->name('cek-pembayaran.show');


Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat-transaksi/{id}', [RiwayatTransaksiController::class, 'show'])->name('riwayat.show');
});


Route::middleware(['auth', 'role:admin|operator'])->group(function () {
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/keuangan/tambah', [KeuanganController::class, 'create'])->name('keuangan.create');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
});

Route::get('/keuangan/export-excel', [KeuanganController::class, 'exportExcel'])->name('keuangan.export-excel');
Route::get('/keuangan/cetak-pdf', [KeuanganController::class, 'exportPdf'])->name('keuangan.export-pdf');


Route::middleware(['auth', 'role:admin|operator'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
    Route::post('/laporan', [LaporanController::class, 'generate'])
        ->name('laporan.generate');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])
        ->name('laporan.export-excel');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan.cetak-pdf');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    // Tampilkan halaman settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');

    // Simpan perubahan settings (.env)
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Trigger backup database
    Route::post('settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');

    // Restore database dari file backup (zip)
    Route::post('settings/restore', [SettingsController::class, 'restore'])->name('settings.restore');

    Route::get('settings/backup/download/{file}', [SettingsController::class, 'downloadBackup'])
        ->where('file', '.*')                     // ← allow slashes
        ->name('settings.backup.download');
    // Restore dari file upload
    Route::post('settings/restore-upload', [SettingsController::class, 'restoreUpload'])->name('settings.restore.upload');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
});





require __DIR__ . '/auth.php';
