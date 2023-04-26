<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
//                [
//                    'Nguyễn Ngọc Sơn','abc@gmail.com'
//                ]
            ],

        ]);
    }

    public function headings(): array
    {
        $export = [
            'Tên khách hàng',
            'Email'
        ];
        return $export;
    }
}
