<?php

namespace Modules\Dashbroad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReceiptTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";

    const NOT_DELETE = 0;
    const ORDER_DIRECT = 1;

    /**
     *  Tính doanh thu trong ngày theo chi nhánh
     *
     * @param $branchId
     * @return mixed
     */
    public function getRevenueInDayByBranchId($branchId)
    {
        $now = date('Y-m-d');
        $select = $this->select(
            DB::raw("SUM({$this->table}.amount_paid) as total_receipt")
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where("orders.branch_id", $branchId)
//            ->where("{$this->table}.status", 'paid')
            ->whereIn("orders.process_status", ['paysuccess', 'pay-half'])
//            ->where("orders.order_source_id", self::ORDER_DIRECT)
            ->whereBetween("{$this->table}.created_at", [$now . ' 00:00:00', $now . ' 23:59:59'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("orders.is_deleted", self::NOT_DELETE);
        return $select->first();
    }
}