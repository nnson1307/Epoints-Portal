<?php


namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReceiptTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";

    const IS_DELETED = 0;
    const IS_ACTIVE = 1;
    /**
     * Lấy dữ liệu doanh thu theo status
     * Filter theo branch id và customer group id
     *
     * @param $startTime
     * @param $endTime
     * @param array $receiptStatus
     * @param array $orderStatus
     * @param $branchId
     * @param $customerGroupId
     * @return mixed
     */
    public function getValueByStatus($startTime, $endTime, array $receiptStatus, array $orderStatus, $branchId, $customerGroupId)
    {
        // Filter customer group
        if ($customerGroupId != null && $customerGroupId != "") {
            $select = $this
                ->select(
                    "receipts.receipt_id",
                    "receipts.customer_id",
                    "receipts.order_id",
                    "receipts.total_money",
                    "receipts.status",
                    "receipts.amount",
                    "receipts.amount_paid",
                    "receipts.created_at",
                    "receipts.created_by",
                    DB::raw("SUM(receipts.amount_paid) as total_receipt"),
                    "orders.amount as order_amount",
                    "orders.branch_id"
                )
                ->join('orders', 'orders.order_id', 'receipts.order_id')
                ->join('customers', 'customers.customer_id', 'receipts.customer_id')
                ->where('receipts.object_type', '<>', 'debt')
                ->where('customers.customer_group_id', $customerGroupId)
                ->whereBetween('receipts.created_at',[$startTime . " 00:00:00", $endTime . " 23:59:59"])
                ->whereIn('orders.process_status', $orderStatus)
                ->where('orders.is_deleted', 0)
                ->groupBy('receipts.order_id');
        } else {
            $select = $this
                ->select(
                    "receipts.receipt_id",
                    "receipts.customer_id",
                    "receipts.order_id",
                    "receipts.total_money",
                    "receipts.status",
                    "receipts.amount",
                    "receipts.amount_paid",
                    "receipts.created_at",
                    "receipts.created_by",
                    DB::raw("SUM(receipts.amount_paid) as total_receipt"),
                    "orders.amount as order_amount",
                    "orders.branch_id"
                )
                ->join('orders', 'orders.order_id', 'receipts.order_id')
                ->where('receipts.object_type', '<>', 'debt')
                ->whereBetween('receipts.created_at',[$startTime . " 00:00:00", $endTime . " 23:59:59"])
                ->whereIn('orders.process_status', $orderStatus)
                ->where('orders.is_deleted', 0)
                ->groupBy('receipts.order_id');
        }

        if (count($receiptStatus) > 0) {
            $select->whereIn('receipts.status', $receiptStatus);
        }
        // Filter branch
        if ($branchId != null && $branchId != "") {
            $select->where('orders.branch_id', $branchId);
        }
        return $select->get();
    }

    /**
     * Lấy tất cả khách hàng trong bảng doanh thu theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getAllCustomer($startTime, $endTime, $branchId, $limit)
    {
        $select = $this->select(
            "{$this->table}.receipt_id",
            "{$this->table}.customer_id",
            "{$this->table}.order_id",
            "customers.full_name as customer_name",
            "customers.phone1 as customer_phone"
        )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("orders", "orders.order_id", "{$this->table}.order_id")
            ->whereBetween("{$this->table}.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("customers.is_actived", self::IS_ACTIVE)
            ->where("customers.is_deleted", self::IS_DELETED)
            ->groupBy("{$this->table}.customer_id");

        // Filter branch
        if ($branchId != null && $branchId != "") {
            $select->where('orders.branch_id', $branchId);
        }
        // Filter number customer
        if ($limit != null && $limit != "") {
            $select->limit($limit);
        }

        return $select->get();
    }

    /**
     * Lấy tất cả khách hàng trong bảng doanh thu theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getAllStaff($startTime, $endTime, $branchId, $limit)
    {
        $select = $this->select(
            "{$this->table}.receipt_id",
            "{$this->table}.customer_id",
            "{$this->table}.order_id",
            "staffs.full_name as staff_name",
            "staffs.staff_id"
        )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->join("orders", "orders.order_id", "{$this->table}.order_id")
            ->whereBetween("{$this->table}.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("staffs.is_actived", self::IS_ACTIVE)
            ->where("staffs.is_deleted", self::IS_DELETED)
            ->groupBy("{$this->table}.created_by");

        // Filter branch
        if ($branchId != null && $branchId != "") {
            $select->where('orders.branch_id', $branchId);
        }
        // Filter number staff
        if ($limit != null && $limit != "") {
            $select->limit($limit);
        }
        return $select->get();
    }
}