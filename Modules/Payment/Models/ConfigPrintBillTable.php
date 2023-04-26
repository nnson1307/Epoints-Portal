<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigPrintBillTable extends Model
{
    protected $table = "config_print_bill";
    protected $primaryKey = "id";

    /**
     * Lấy thông tin config in bill theo id
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $select = $this->select(
            'printed_sheet', 'is_print_reply',
            'print_time', 'is_show_logo',
            'is_show_unit', 'is_show_address',
            'is_show_phone', 'is_show_order_code',
            'is_show_cashier', 'is_show_customer',
            'is_show_datetime', 'is_show_footer', 'template', 'symbol'
        )
            ->where($this->primaryKey, $id);
        return $select->first();
    }
}