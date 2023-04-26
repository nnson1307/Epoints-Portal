<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 3/14/2019
 * Time: 9:01 AM
 */

namespace Modules\Admin\Repositories\SmsExportExcel;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CollectionExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            []
        ]);
    }

    public function headings(): array
    {
        return [
            'Tên khách hàng',
            'SĐT'
        ];
    }
}