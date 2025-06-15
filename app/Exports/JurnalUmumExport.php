<?php

namespace App\Exports;

use App\Models\JurnalUmum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JurnalUmumExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return JurnalUmum::select([
            'id',
            'tanggal',
            'keterangan',
            'debit',
            'kredit',
            'created_at',
            'updated_at'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Keterangan',
            'Debit',
            'Kredit',
            'Dibuat Pada',
            'Diubah Pada',
        ];
    }
}
