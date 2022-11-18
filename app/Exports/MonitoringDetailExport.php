<?php

namespace App\Exports;

use App\Models\Kategori;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringDetailExport implements FromView, WithStyles
{
	use Exportable;

    /**
     * Create a new message instance.
     *
     * @param  object $monitoring
     * @return void
     */
    public function __construct($monitoring)
    {
        $this->monitoring = $monitoring;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true]],
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        // Get kategori
        $kategori = Kategori::has('jenis')->orderBy('jenis_id','asc')->get();

    	// View
    	return view('admin/monitoring/excel-category', [
    		'monitoring' => $this->monitoring,
            'kategori' => $kategori
    	]);
    }
}