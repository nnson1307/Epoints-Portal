<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            [
                '1',
                '100431',
                '8410113005250',
                'Chile',
                'Vang đỏ',
                'Torres',
                'Miguel Torres, Santa Digna Reserva Cabernet Sauvignon, Central Valley, 18.7Cl',
                'Miguel Torres, Santa Digna Reserva Cabernet Sauvignon, Central Valley, 18.7Cl',
                '18.7CL',
                '141,000',
                '',
                'https://domain/product-image.png',
            ],

        ]);
    }

    public function headings(): array
    {
        $export = [
            __('STT'),
            __('Code'),
            __('MÃ VẠCH'),
            __('XUẤT XỨ'),
            __('CHỦNG LOẠI'),
            __('THƯƠNG HIỆU'),
            __('TÊN SẢN PHẨM'),
            __('TÊN SẢN PHẨM TIẾNG VIỆT'),
            __('DUNG TÍCH'),
            __('GIÁ'),
            __('CHI TIẾT'),
            __('HÌNH'),
        ];
        return $export;
    }
}
