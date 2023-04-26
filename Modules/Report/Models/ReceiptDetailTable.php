<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReceiptDetailTable extends Model
{
    protected $table = 'receipt_details';
    protected $primaryKey = 'receipt_detail_id';
    const NOT_DELETE = 0;

    /**
     * data phương thức thanh toán
     * group by phương thức thanh toán
     *
     * @return mixed
     */
    public function getSumMoneyByReceiptType()
    {
        return $this->select(
            'receipt_details.receipt_id',
            'receipt_details.receipt_type',
            'receipt_details.receipt_detail_id',
            'receipt_details.amount',
            DB::raw("SUM(receipt_details.amount) as sum_type")
        )
            ->groupBy('receipt_details.receipt_type')
            ->get();
    }

    /**
     * Lấy doanh thu theo phương thức thanh toán
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $customerGroupId
     * @return mixed
     */
    public function getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branchId, $customerGroupId)
    {
        $res = $this->select(
            'receipt_details.receipt_id',
            'receipt_details.receipt_type',
            'receipt_details.receipt_detail_id',
            'receipts.order_id',
            'receipt_details.amount',
            DB::raw("SUM(receipt_details.amount) as sum_type")
        )   ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->join("orders", "orders.order_id", "=", "receipts.order_id")
            ->join("customers", "customers.customer_id", "=", "receipts.customer_id")
            ->whereIn("orders.process_status", ['paysuccess', 'pay-half'])
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->whereBetween('orders.created_at',[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->groupBy('receipt_details.receipt_type');

        if ($branchId != null) {
            $res->where("orders.branch_id", $branchId);
        }
        if ($customerGroupId != null) {
            $res->where("customers.customer_group_id", $customerGroupId);
        }
        return $res->get();
    }
}