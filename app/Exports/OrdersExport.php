<?php

namespace App\Exports;

use App\Models\Order; // Replace with your Order model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;
use DB;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $keyword;
    protected $from_date;
    protected $to_date;
    protected $delivery_status;
    
    
     public function __construct($keyword = null, $from_date = null, $to_date = null, $delivery_status = null)
    {
        $this->keyword = $keyword;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->delivery_status = $delivery_status;
    }
    
    /**
     * Fetch order details for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        
        $orders = Order::orderBy('id', 'desc');
       
        if ($this->keyword) {
            $sort_search = $this->keyword;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
       
        if ($this->delivery_status != null) {
            $orders = $orders->where('delivery_status', $this->delivery_status);
        }
        if ($this->from_date != null && $this->to_date != null) {
            $orders = $orders->whereDate('created_at', '>=', $this->from_date)->whereDate('created_at', '<=', $this->to_date);
        }
        
        
        return $orders->get();
            
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
            [
                'Customer Name',
                'Customer Mobile Number',
                'Customer Email',
                'Order ID',
                'Order Date',
                'Order Time',
                'Order Status',
                'Invoice Date',
                'Invoice Time',
                'Products (SKU, Name, Quantity)',
                'Selling_Price',
                'Discount',
                'Delivery charge',
                'Taxable value',
                'VAT',
                'Payment method',
                'Shipping method',
                'Shipping status'
            ],
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:I1');
        $totalColumns = 20;
        foreach (range('A', chr(64 + $totalColumns)) as $column) {
            if ($column !== 'K' && $column !== 'A' && $column !== 'C' && $column !== 'D') {
                $sheet->getColumnDimension($column)->setWidth(20); // Set fixed width for other columns
            }
        }
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(100);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true); // Enable wrap text for column B
        return [
            // Optional: Add specific styles for the header row
            1 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            2 => ['font' => ['bold' => true, 'size' => '14px']], // Row 1 styling
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'C' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'E' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'F' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'G' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'H' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'I' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'J' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'K' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'L' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'M' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'N' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'O' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'P' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'Q' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'R' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }
    
    public function map($order): array
    {
        // Combine product details into a single field
        $productDetails = $order->orderDetails->map(function ($orderDeta)  {
            
            return "SKU: {$orderDeta->product->sku} - Name: {$orderDeta->product->name} -  Qty: {$orderDeta->quantity}";
        })->implode("\n"); // Separate products with a semicolon

        $username = json_decode($order->shipping_address)->name;
        $userphone = json_decode($order->shipping_address)->phone;
        $useremail = json_decode($order->shipping_address)->email;
        $totalDiscount = $order->coupon_discount + $order->offer_discount;
        $withoutTax = $order->sub_total - $order->tax;
       
        return [
            $username,
            $userphone,
            $useremail,
            $order->code,
            $order->created_at->format('Y-m-d'),
            $order->created_at->format('H:i A'),
            ucfirst(str_replace('_', ' ', $order->delivery_status)),
            $order->created_at->format('Y-m-d'),
            $order->created_at->format('H:i A'),
            $productDetails,
            $order->grand_total,
            ($totalDiscount != 0) ? $totalDiscount : '0.00',
            ($order->shipping_cost == 0)  ? '0.00' : $order->shipping_cost,
            ($withoutTax != 0) ? $withoutTax : '0.00',
            ($order->tax != 0) ? $order->tax : '0.00',
            ucfirst(str_replace('_', ' ', $order->payment_type)),
            ucfirst(str_replace('_', ' ', $order->shipping_type)),
            ucfirst(str_replace('_', ' ', $order->delivery_status))
            
        ];
    }
}
