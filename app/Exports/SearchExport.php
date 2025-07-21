<?php

namespace App\Exports;

use App\Models\Search; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;

class SearchExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $from_date;
    protected $to_date;

    public function __construct( $from_date = null, $to_date = null)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }
    
    /**
     * Fetch order details for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Search::latest();

        if ($this->from_date != null && $this->to_date != NULL) {
            $query->whereDate('created_at', '>=', $this->from_date)->whereDate('created_at', '<=', $this->to_date);
        }

        return $query->get();   
    }

    /**
     * Define headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            ['Exported on: ' . now()->format('d-m-Y h:i A')],
            ['Sl No.', 'Search Key', 'User', 'IP Address','Date'],
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
       
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(30);
        return [
            // Optional: Add specific styles for the header row
            1 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            2 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]
        ];
    }
    
    public function map($searche): array
    {
        // Combine product details into a single field
        static $count = 0;
        $count++;

        return [
            $count,
            $searche->query ,
            $searche->user->name ?? 'GUEST',
            $searche->ip_address,
            $searche->created_at->format('d-m-Y h:i A')
        ];
    }
}
