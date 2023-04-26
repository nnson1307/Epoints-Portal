<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerLeadExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                '1',
                'NGUYỄN VĂN A',
                '0791234522',
                '0987789788,0987456123',
                '01/01/1999',
                '1',
                'abc@gmail.com',
                'test01@gmail.com,test02@gmail.com,test03@gmail.com',
                'Hồ Chí Minh',
                '7',
                '72 Trần Trọng Cung',
                'personal',
                'testttt',
                'Nguồn ABC',
                'Doanh nghiep A',
                'https://www.facebook.com/abcdef/',
                '',
                'https://chat.zalo.me/',
                'tag2,tag3',
                'Hannah',
                '',
                '',
                ''
            ],
            [
                '2',
                'NGUYỄN THỊ B',
                '0791234511',
                '0987789711,0987444123',
                '01/02/1990',
                '0',
                'nguyenthib@gmail.com',
                'test04@gmail.com,test05@gmail.com',
                'Kon Tum',
                'Đắk Hà',
                '72 Trần Trọng Cung',
                'business',
                'testttt',
                'Facebook',
                'Doanh nghiep B',
                'https://www.facebook.com/abcdef/',
                '',
                'https://chat.zalo.me/',
                'tag2,tag3',
                'Hannah',
                'THUE0000001',
                'Nguyễn Văn A',
                '0988119999'
            ],

        ]);
    }

    public function headings(): array
    {
        $export = [
            'STT',
            'TÊN KHÁCH HÀNG',
            'SỐ ĐIỆN THOẠI',
            'SDT KÈM THEO',
            'NGÀY SINH',
            'GIỚI TÍNH',
            'EMAIL',
            'EMAIL KÈM THEO',
            'TỈNH/ THÀNH',
            'QUẬN/ HUYỆN',
            'ĐỊA CHỈ',
            'LOẠI KHÁCH HÀNG',
            'PIPELINE',
            'NGUỒN KHÁCH HÀNG',
            'ĐẦU MỐI DOANH NGHIỆP',
            'FANPAGE',
            'FANPAGE KÈM THEO',
            'ZALO',
            'TAG',
            'NGƯỜI ĐƯỢC PHÂN BỔ',
            'MÃ SỐ THUẾ',
            'NGƯỜI ĐẠI DIỆN',
            'HOTLINE',
        ];
        return $export;
    }
}