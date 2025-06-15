<?php

namespace App\Exports;

use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KeuanganExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Penerimaan' => new class implements FromCollection {
            public function collection()
            {
                return Penerimaan::all();
            }
            },
            'Pengeluaran' => new class implements FromCollection {
            public function collection()
            {
                return Pengeluaran::all();
            }
            },
        ];
    }
}
