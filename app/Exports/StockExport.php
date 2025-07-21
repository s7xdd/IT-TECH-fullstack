<?php

namespace App\Exports;

use App\Models\Product; // Replace with your Order model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;

class StockExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $category_id;
    protected $keyword;
    
    
     public function __construct($category_id = null, $keyword = null)
    {
        $this->category_id = $category_id;
        $this->keyword = $keyword;
    }
    
    /**
     * Fetch order details for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $products = Product::orderBy('created_at', 'desc');
       
        if ($this->category_id && $this->category_id !== '0') {
            $childIds = [];
            $categoryfilter = $this->category_id;
            $childIds[] = array($this->category_id);
            
            if($categoryfilter != ''){
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $products = $products->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }

        if ($this->keyword != null) {
            $sort_search = $this->keyword;
            $products->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        
        return $products->get();   
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
            ['Sl No.', 'Product SKU', 'Product Name', 'Stock'],
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $totalColumns = 3;
        $sheet->mergeCells('A1:D1');
       
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(120);
        $sheet->getColumnDimension('D')->setWidth(20);

        // $sheet->getStyle('A1:A' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // $sheet->getStyle('C1:C' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [
            // Optional: Add specific styles for the header row
            1 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            2 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]
        ];
    }
    
    public function map($products): array
    {
        // Combine product details into a single field
        static $count = 0;
        $count++;

        return [
            $count,
            $products->sku,
            $products->name,
            (string)$products->stocks[0]->qty ?? 0
        ];
    }
}
