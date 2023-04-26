<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/1/2019
 * Time: 3:49 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class PrintBillLogTable extends Model
{
    protected $table = 'print_log';
    protected $primaryKey = 'id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = [
        'id',
        'branch_id',
        'order_code',
        'staff_print_reply_by',
        'staff_print_by',
        'total_money',
        'created_at',
        'updated_at',
        'debt_code'
        ];

    public function add(array $data)
    {
        $insert = $this->create($data);
        return $insert->id;
    }

    //Kiểm tra đơn hàng được in
    public function checkPrintBillOrder($orderId)
    {
        $select = $this->select(
            'branch_id', 'order_code',
            'staff_print_reply_by', 'staff_print_by'
        )->where('order_code', $orderId)->get();
        return $select;
    }

    //get biggest id
    public function getBiggestId()
    {
        $select = $this->select('id')->whereRaw('id = (select max(`id`) from print_log)')->first();
        return $select;
    }

    /**
     * @param $debt_code
     * @return mixed
     */
    public function checkPrintBillDebt($debt_code)
    {
        $select = $this->select(
            'branch_id', 'order_code',
            'staff_print_reply_by', 'staff_print_by'
        )->where('debt_code', $debt_code)->get();
        return $select;
    }
}