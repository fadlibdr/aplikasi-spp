<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    protected $type;
    protected $from;
    protected $to;
    protected $data;

    public function __construct(string $type, string $from, string $to, $data)
    {
        $this->type = $type;
        $this->from = $from;
        $this->to = $to;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('laporan.export', [
            'type' => $this->type,
            'from' => $this->from,
            'to' => $this->to,
            'data' => $this->data,
        ]);
    }
}
