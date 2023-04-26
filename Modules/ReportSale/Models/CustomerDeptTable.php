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

class CustomerDeptTable extends Model
{
    protected $table = "customer_debt";
    protected $primaryKey = "customer_debt_id";

    const PAID = "paid";
    const PART_PAID = "part-paid";
    const UN_PAID = "unpaid";
    const CANCEL_PAID = "cancel";
    const FAIL_PAID = "fail";
    protected $casts = [
        "amount" => 'float',
    ];


    /**
     * Lấy công nợ
     *
     * @param $branchId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getCustomerDept($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount - {$this->table}.amount_paid) as amount")
            )
            // ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.is_deleted", 0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::PART_PAID)
                    ->orWhere("{$this->table}.status",  self::UN_PAID);
            });
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        return $ds->first();
    }

    public function getCustomerDeptByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount - {$this->table}.amount_paid) as amount")
            )
            // ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.is_deleted", 0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::PART_PAID)
                    ->orWhere("{$this->table}.status",  self::UN_PAID);
            });
        if ($staffId != null) {
            $ds->where("{$this->table}.staff_id", $staffId);
        }
        return $ds->first();
    }

    public function getTotalCustomerDept($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount - {$this->table}.amount_paid) as amount"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "branches.branch_name as branch_name"
            )
            // ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_deleted",  0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::PART_PAID)
                    ->orWhere("{$this->table}.status",  self::UN_PAID);
            });
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        $ds->groupBy("date", "branch_name");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getTotalCustomerDeptByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount - {$this->table}.amount_paid) as amount"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "s.staff_id",
                "s.full_name as staff_name"
            )
            // ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.is_deleted",  0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::PART_PAID)
                    ->orWhere("{$this->table}.status",  self::UN_PAID);
            });
        if ($staffId != null) {
            $ds->where("{$this->table}.staff_id", $staffId);
        }
        $ds->groupBy("date", "staff_id");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getCustomerDeptByCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount - {$this->table}.amount_paid) as amount")
            );
        if ($customerGroupId != null) {
            $ds->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
                ->where("customer_groups.customer_group_id", $customerGroupId);
        }
        $ds->where("{$this->table}.is_deleted", 0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::PART_PAID)
                    ->orWhere("{$this->table}.status",  self::UN_PAID);
            });
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerId != null) {
            $ds->where("{$this->table}.customer_id", $customerId);
        }
        return $ds->first();
    }

    public function getTotalCustomerDeptByCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.amount - {$this->table}.amount_paid) as amount"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "customer_groups.group_name as group_name"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
            ->where("{$this->table}.is_deleted",  0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where(function ($select) {
                $select->where("{$this->table}.status",  self::PART_PAID)
                    ->orWhere("{$this->table}.status",  self::UN_PAID);
            });
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerGroupId != null) {
            $ds->where("customers.customer_group_id", $customerGroupId);
        }
        $ds->groupBy("date", "group_name");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }
}