<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use App\Exports\LaporanExport;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            RoleMiddleware::class,
            PermissionMiddleware::class,
        ]);

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->seedMinimalData();
    }

    protected function seedMinimalData(): void
    {
        $ta = TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => 'Ganjil',
            'aktif' => true,
        ]);

        $kelas = Kelas::create([
            'nama' => 'X IPA',
            'kapasitas' => 30,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $siswa = Siswa::create([
            'nis' => '001',
            'nisn' => null,
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'email' => 'test@example.com',
            'kelas_id' => $kelas->id,
        ]);

        $jenis = JenisPembayaran::create([
            'kode' => 'SPP',
            'nama' => 'SPP',
            'nominal' => 100000,
            'frekuensi' => 'Bulanan',
        ]);

        $iuran = Iuran::create([
            'siswa_id' => $siswa->id,
            'jenis_pembayaran_id' => $jenis->id,
            'bulan' => 1,
            'status' => 'lunas',
        ]);

        Pembayaran::create([
            'iuran_id' => $iuran->id,
            'order_id' => 'ORDER-1',
            'jumlah' => 100000,
            'metode' => 'manual',
            'midtrans_id' => null,
            'tgl_bayar' => Carbon::today(),
            'status' => 'settlement',
        ]);

        Penerimaan::create([
            'pembayaran_id' => null,
            'sumber' => 'Sumbangan',
            'jumlah' => 50000,
            'keterangan' => 'Donasi',
            'tanggal' => Carbon::today(),
        ]);

        Pengeluaran::create([
            'kategori' => 'Biaya',
            'jumlah' => 30000,
            'keterangan' => 'Pembelian',
            'tanggal' => Carbon::today(),
        ]);
    }

    public function test_generate_renders_result_view(): void
    {
        $from = Carbon::today()->format('Y-m-d');
        $to = Carbon::today()->format('Y-m-d');

        $response = $this->actingAs($this->admin)->post('/laporan', [
            'type' => 'pembayaran',
            'date_from' => $from,
            'date_to' => $to,
        ]);

        $response->assertStatus(200)
            ->assertViewIs('laporan.result')
            ->assertSee('Data Pembayaran');
    }

    public function test_export_excel_returns_download(): void
    {
        Excel::fake();

        $from = Carbon::today()->format('Y-m-d');
        $to = Carbon::today()->format('Y-m-d');

        $this->actingAs($this->admin)
            ->get("/laporan/export-excel?type=pembayaran&date_from={$from}&date_to={$to}");

        Excel::assertDownloaded(
            "laporan-pembayaran_{$from}_to_{$to}.xlsx",
            function ($export) {
                return $export instanceof LaporanExport;
            }
        );
    }

    public function test_export_pdf_returns_download(): void
    {
        Pdf::fake();

        $from = Carbon::today()->format('Y-m-d');
        $to = Carbon::today()->format('Y-m-d');

        $response = $this->actingAs($this->admin)
            ->get("/laporan/cetak-pdf?type=pembayaran&date_from={$from}&date_to={$to}");

        $response->assertDownload("laporan-pembayaran_{$from}_to_{$to}.pdf");
    }
}
