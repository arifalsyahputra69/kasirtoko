<?php

namespace App\Exports;

use App\Models\TransactionItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function collection()
    {
        $items = TransactionItem::with([
            'transaction.user',
            'productSerial.productVariant.product.category'
        ])
        ->whereHas('transaction', function ($q) {
            $q->whereBetween('created_at', [
                $this->start.' 00:00:00',
                $this->end.' 23:59:59'
            ])
            ->whereDoesntHave('retur');
        })
        ->get();

        return $items->map(function ($item) {

            $serial  = $item->productSerial;
            $variant = $serial?->productVariant;
            $product = $variant?->product;
            $trx     = $item->transaction;

            // tentukan size/variant text
            $sizeText = '-';
            if($variant){
                if($variant->size)
                    $sizeText = $variant->size;
                elseif($variant->peci_number)
                    $sizeText = 'Peci '.$variant->peci_number.' ('.$variant->peci_height.')';
                else
                    $sizeText = 'Simple';
            }

            return [
                'Tanggal'    => $trx?->created_at?->format('d/m/Y H:i') ?? '-',
                'Karyawan'   => $trx?->user?->name                       ?? '-',
                'Kategori'   => $product?->category?->name               ?? '-',
                'Brand'      => $product?->brand                         ?? '-',
                'Type'       => $product?->type                          ?? '-',
                'Warna'      => $product?->color                         ?? '-',
                'Size'       => $sizeText,
                'Qty'        => 1,
                'Modal'      => $item->cost_price    ?? 0,
                'Harga Jual' => $item->selling_price ?? 0,
                'Profit'     => $item->profit        ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Karyawan',
            'Kategori',
            'Brand',
            'Type',
            'Warna',
            'Size',
            'Qty',
            'Modal',
            'Harga Jual',
            'Profit',
        ];
    }
}