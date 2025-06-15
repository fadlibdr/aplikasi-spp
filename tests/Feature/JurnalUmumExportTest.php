<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Exports\JurnalUmumExport;

class JurnalUmumExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role and user
        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Disable role middleware during test
        $this->withoutMiddleware([
            RoleMiddleware::class,
        ]);
    }

    public function test_export_excel_returns_download_response(): void
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)
            ->get('/jurnal-umum/export-excel');

        $response->assertOk();
        Excel::assertDownloaded('jurnal-umum.xlsx', function (JurnalUmumExport $export) {
            return $export instanceof JurnalUmumExport;
        });
    }

    public function test_export_pdf_returns_pdf_download_with_correct_filename(): void
    {
        Pdf::fake();

        $response = $this->actingAs($this->admin)
            ->get('/jurnal-umum/cetak-pdf');

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename="jurnal-umum.pdf"');
        Pdf::assertDownloaded('jurnal-umum.pdf');
    }
}
