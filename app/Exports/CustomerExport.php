<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            [
                '1',
                '17/09/2019',
                'NGUYỄN VĂN A',
                '1',
                'Khách cũ',
                'Trực tiếp',
                '0791234567',
                'nguyenvana@gmail.com',
                '01/01/1980',
                'Hồ CHí Minh',
                '7',
                'Tân Thuận Đông',
                '72 Trần Trọng Cung',
                '001-TTH',
                'THÊM BẰNG TAY',
            ],
            [
                '2',
                '17/09/2019',
                'NGUYỄN VĂN B',
                '0',
                'Khách cũ',
                'Trực tiếp',
                '0312345678',
                'nguyenvanb@gmail.com',
                '01/01/1980',
                'Hồ CHí Minh',
                '7',
                'Tân Thuận Tây',
                '72 Trần Trọng Cung',
                '002-TTC',
                'THÊM BẰNG IMPORT'
            ],

        ]);
    }

    public function headings(): array
    {
        $export = [
            __('STT'),
            __('NGÀY SD DỊCH VỤ'),
            __('TÊN KHÁCH HÀNG'),
            __('GIỚI TÍNH'),
            __('NHÓM KHÁCH HÀNG'),
            __('NGUỒN KHÁCH HÀNG'),
            __('SỐ ĐIỆN THOẠI'),
            __('EMAIL'),
            __('NGÀY SINH'),
            __('TỈNH/THÀNH'),
            __('PHƯỜNG/XÃ'),
            __('QUẬN/HUYỆN'),
            __('ĐỊA CHỈ'),
            __('MÃ HỒ SƠ'),
            __('GHI CHÚ')
        ];
        return $export;
    }
}
