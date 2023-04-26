<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerGroupExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
        ]);
    }

    public function headings(): array
    {
        $export = [
            'STT',
            'SỐ ĐIỆN THOẠI'
        ];
        return $export;
    }
}
