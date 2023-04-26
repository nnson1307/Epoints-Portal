<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/1/2019
 * Time: 11:57 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigPrintBillTable extends Model
{
    protected $table = "config_print_bill";
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'printed_sheet',
        'is_print_reply',
        'print_time',
        'is_show_logo',
        'is_show_unit',
        'is_show_address',
        'is_show_phone',
        'is_show_order_code',
        'is_show_cashier',
        'is_show_customer',
        'is_show_datetime',
        'is_show_footer',
        'updated_by',
        'created_at',
        'updated_at',
        'template',
        'symbol',
        'is_total_bill',
        'is_total_discount',
        'is_total_amount',
        'is_total_receipt',
        'is_amount_return',
        'is_amount_member',
        'is_qrcode_order',
        'is_payment_method',
        'note_footer'
    ];

    public function getItem($id)
    {
        return $this
            ->select(
                'printed_sheet',
                'is_print_reply',
                'print_time',
                'is_show_logo',
                'is_show_unit',
                'is_show_address',
                'is_show_phone',
                'is_show_order_code',
                'is_show_cashier',
                'is_show_customer',
                'is_show_datetime',
                'is_show_footer',
                'template',
                'symbol',
                'is_total_bill',
                'is_total_discount',
                'is_total_amount',
                'is_total_receipt',
                'is_amount_return',
                'is_amount_member',
                'is_qrcode_order',
                'is_payment_method',
                'is_customer_code',
                'is_order_code',
                'is_profile_code',
                'is_company_tax_code',
                'is_sign',
                'is_dept_customer',
                'note_footer'
            )
            ->where($this->primaryKey, $id)
            ->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}