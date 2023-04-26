<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:54
 */

namespace Modules\ReportSale\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceiptsTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";

    const RECEIPTS_UNPAID = "unpaid";
    const RECEIPTS_PART_PAID = "part-paid";
    const RECEIPTS_PAID = "paid";
    const RECEIPTS_CANCEL = "cancel";
    const RECEIPTS_FAIL = "fail";
    const ORDER_DELETED = 0;
    protected $casts = [
        "amount" => 'float',
    ];

    public function getTotalReceipt($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('SUM(amount) as amount')
            )
            // ->join("orders", function ($join) {
            //     $join->on("{$this->table}.order_id", "=", "orders.order_id");
            // })
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::RECEIPTS_PAID)
                    ->orWhere("{$this->table}.status",  self::RECEIPTS_PART_PAID);
            })
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        return $ds->first();
    }

    public function getTotalReceiptByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('SUM(amount) as amount')
            )
            // ->join("orders", function ($join) {
            //     $join->on("{$this->table}.order_id", "=", "orders.order_id");
            // })
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::RECEIPTS_PAID)
                    ->orWhere("{$this->table}.status",  self::RECEIPTS_PART_PAID);
            })
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($staffId != null) {
            $ds->where("{$this->table}.staff_id", $staffId);
        }
        return $ds->first();
    }

    public function getTotalAmountOrderPayMehtod($branchId, $startDate, $endDate)
    {
        $ds = $this
            ->select(
                "payment_method.payment_method_name_vi as payment_method_name_vi",
                "payment_method.payment_method_code as payment_method_code",
                DB::raw('SUM(receipt_details.amount) as amount')
            )
            // ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("receipt_details", "receipt_details.receipt_id", "=", "{$this->table}.receipt_id")
            ->join("payment_method", "payment_method.payment_method_code", "=", "receipt_details.payment_method_code");
        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        $ds->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.status", "!=", self::RECEIPTS_UNPAID)
            ->where("{$this->table}.status", "!=", self::RECEIPTS_CANCEL)
            ->where("{$this->table}.status", "!=", self::RECEIPTS_FAIL)
            // ->whereIn("{$this->table}.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->groupBy("payment_method_name_vi", "payment_method.payment_method_code");

        return $ds->get();
    }

    public function getChartTotalReceipt($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount) as amount"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "branches.branch_name as branch_name"
            )

            // ->join("orders", function ($join) {
            //     $join->on("{$this->table}.order_id", "=", "orders.order_id");
            // })
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::RECEIPTS_PAID)
                    ->orWhere("{$this->table}.status",  self::RECEIPTS_PART_PAID);
            })
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        $ds->groupBy("date", "branch_name");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getChartTotalReceiptByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount) as amount"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "s.staff_id",
                "s.full_name as staff_name"
            )

            // ->join("orders", function ($join) {
            //     $join->on("{$this->table}.order_id", "=", "orders.order_id");
            // })
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::RECEIPTS_PAID)
                    ->orWhere("{$this->table}.status",  self::RECEIPTS_PART_PAID);
            })
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($staffId != null) {
            $ds->where("{$this->table}.staff_id", $staffId);
        }

        $ds->groupBy("date", "staff_id");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getTotalReceiptByCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount) as amount")
            );
        if ($customerGroupId != null) {
            $ds->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
                ->where("customer_groups.customer_group_id", $customerGroupId);
        }
        $ds->where(function ($select) {
            $select->where("{$this->table}.status",  self::RECEIPTS_PAID)
                ->orWhere("{$this->table}.status",  self::RECEIPTS_PART_PAID);
        })
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerId != null) {
            $ds->where("{$this->table}.customer_id", $customerId);
        }
        return $ds->first();
    }

    public function getChartTotalReceiptCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount) as amount"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "customer_groups.group_name as group_name"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::RECEIPTS_PAID)
                    ->orWhere("{$this->table}.status",  self::RECEIPTS_PART_PAID);
            })
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerGroupId != null) {
            $ds->where("customer_groups.customer_group_id", $customerGroupId);
        }
        $ds->groupBy("date", "group_name");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }
}