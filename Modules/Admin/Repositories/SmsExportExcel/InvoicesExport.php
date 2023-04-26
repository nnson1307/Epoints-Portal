<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 3/14/2019
 * Time: 9:00 AM
 */

namespace Modules\Admin\Repositories\SmsExportExcel;

use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{
    public function collection()
    {
        return Invoice::all();
    }
}