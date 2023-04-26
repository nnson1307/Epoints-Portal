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

class OrdersTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    const ORDER_CANCEL = "ordercancle";
    const ORDER_DELETED = 0;
    const ORDER_NOT_CALL = "not_call";
    const ORDER_CONFIRMED = "confirmed";
    const ORDER_COMPLETED = "ordercomplete";
    const ORDER_PAYSUCCESS = "paysuccess";
    const ORDER_PAYFAIL = "payfail";
    const ORDER_NEW = "new";
    const ORDER_PAYHALF = "pay-half";

    protected $casts = [
        "amount" => 'float',
    ];

    /**
     * Lấy tổng đơn hàng từ ngày -> ngày
     *
     * @param $branchId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalOrder($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                // DB::raw('COUNT(orders.order_id) as number_order'),
                DB::raw('SUM(orders.amount) as amount')
            );
        $ds->where(function ($select) {
            $select->where("{$this->table}.process_status", '!=',  self::ORDER_CANCEL)
                ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
        })
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        return $ds->first();
    }

    public function getTotalOrderByBranch($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('COUNT(orders.order_id) as number_order'),
                "branches.branch_name as branch_name"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        $ds->groupBy("branch_name");
        return $ds->get();
    }

    public function getTotalOrderByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
            // DB::raw('COUNT(orders.order_id) as number_order'),
                DB::raw('SUM(orders.amount) as amount')
            );
        $ds->where(function ($select) {
            $select->where("{$this->table}.process_status", '!=',  self::ORDER_CANCEL)
                ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
        })
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($staffId != null) {
            $ds->where("{$this->table}.created_by", $staffId);
        }
        return $ds->first();
    }

    public function getTotalOrderByStaffGet($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('COUNT(orders.order_id) as number_order'),
                "s.full_name as staff_name"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.created_by")
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($staffId != null) {
            $ds->where("{$this->table}.created_by", $staffId);
        }
        $ds->groupBy("staff_name");
        return $ds->get();
    }


    /**
     * Lấy tổng đơn hàng từ ngày -> ngày
     *
     * @param $branchId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalNumberOrder($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('COUNT(orders.order_id) as number_order'),
                "{$this->table}.process_status"
            )
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        $ds->groupBy("{$this->table}.process_status");
        return $ds->get();
    }

    public function getTotalNumberOrderByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('COUNT(orders.order_id) as number_order'),
                "{$this->table}.process_status"
            )
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($staffId != null) {
            $ds->where("{$this->table}.created_by", $staffId);
        }
        $ds->groupBy("{$this->table}.process_status");
        return $ds->get();
    }



    public function getChartTotalOrder($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('SUM(orders.amount) as amount'),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "branches.branch_name as branch_name"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where(function ($select) {
                $select->where("{$this->table}.process_status", '!=',  self::ORDER_CANCEL)
                    ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
            })
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        $ds->groupBy("date", "branch_name");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getChartTotalOrderByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('SUM(orders.amount) as amount'),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "s.staff_id",
                "s.full_name as staff_name"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.created_by")
            ->where(function ($select) {
                $select->where("{$this->table}.process_status", '!=',  self::ORDER_CANCEL)
                    ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
            })
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($staffId != null) {
            $ds->where("{$this->table}.created_by", $staffId);
        }
        $ds->groupBy("date", "staff_id");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getChartTotalCountOrder($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("COUNT({$this->table}.order_id) as number_order"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "branches.branch_name as branch_name"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
        $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        $ds->groupBy("date", "branch_name");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    public function getChartTotalCountOrderByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("COUNT({$this->table}.order_id) as number_order"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "s.staff_id",
                "s.full_name as staff_name"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.created_by")
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
        $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($staffId != null) {
            $ds->where("{$this->table}.created_by", $staffId);
        }
        $ds->groupBy("date", "staff_id");
        $ds->orderBy("date", "ASC");
        return $ds->get();
    }

    //Report by customer
    public function getTotalOrderByCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                // DB::raw('COUNT(orders.order_id) as number_order'),
                DB::raw('SUM(orders.amount) as amount')
            );
        if ($customerGroupId != null) {
            $ds->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
                ->where("customer_groups.customer_group_id", $customerGroupId);
        }
        $ds->where(function ($select) {
            $select->where("{$this->table}.process_status", '!=',  self::ORDER_CANCEL)
                ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
        })
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerId != null) {
            $ds->where("{$this->table}.customer_id", $customerId);
        }
        return $ds->first();
    }

    public function getChartTotalOrderByCustomer($time, $branchId, $customerGroupId, $customerId)
    {

        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('SUM(orders.amount) as amount'),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "customer_groups.group_name as group_name"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
            ->where(function ($select) {
                $select->where("{$this->table}.process_status", '!=',  self::ORDER_CANCEL)
                    ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
            })
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

    public function getChartTotalCountOrderByCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("COUNT({$this->table}.order_id) as number_order"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%Y-%m-%d') as date"),
                "customer_groups.group_name as group_name"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
        $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
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

    public function getTotalOrderByCustomerGroup($time, $branchId, $customerGroupId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('COUNT(orders.order_id) as number_order'),
                "customer_groups.group_name as group_name"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerGroupId != null) {
            $ds->where("customer_groups.customer_group_id", $customerGroupId);
        }
        $ds->groupBy("group_name");
        return $ds->get();
    }

    public function getTotalNumberOrderByCustomer($time, $branchId, $customerGroupId, $customerId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw('COUNT(orders.order_id) as number_order'),
                "{$this->table}.process_status"
            )
            ->where("{$this->table}.is_deleted",  self::ORDER_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($customerGroupId != null) {
            $ds->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
                ->where("customer_groups.customer_group_id", $customerGroupId);
        }
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerId != null) {
            $ds->where("{$this->table}.customer_id", $customerId);
        }
        $ds->groupBy("{$this->table}.process_status");
        return $ds->get();
    }

    public function getList($filter = [])
    {
        $time = $filter['time'];
        $branch = $filter['branch'];
        $orderType = $filter['order_type'];
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                "{$this->table}.order_id as order_id",
                "{$this->table}.order_code as order_code",
                "{$this->table}.total as total",
                "{$this->table}.discount as discount",
                "{$this->table}.amount as amount",
                "{$this->table}.tranport_charge as tranport_charge",
                "{$this->table}.process_status as process_status",
                "customers.full_name as full_name_cus",
                "{$this->table}.created_at as created_at",
                "staffs.full_name as full_name",
                "branches.branch_name as branch_name",
                "branches.branch_id as branch_id",
                "{$this->table}.order_description",
                "{$this->table}.order_source_id",
                "{$this->table}.is_apply",
                "{$this->table}.tranport_charge",
                "{$this->table}.customer_id",
                "{$this->table}.receive_at_counter",
                DB::raw("SUM(receipts.amount) as amount_paid")
            )
             ->leftJoin("receipts", function ($join) {
                $join->on("receipts.order_id", "=", "{$this->table}.order_id");
            })
            ->leftJoin('customers', 'customers.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('staffs', 'staffs.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('branches', 'branches.branch_id', '=', "{$this->table}.branch_id");
        if (Auth::user()->is_admin != 1) {
            $ds->where("{$this->table}.branch_id", Auth::user()->branch_id);
        }
        switch ($orderType) {
            case 'paid':
                $ds->where("{$this->table}.process_status", self::ORDER_PAYSUCCESS);
                break;
            case 'paid-half':
                $ds->where("{$this->table}.process_status", self::ORDER_PAYHALF);
                break;
            case 'not-paid':
                $ds->where(function ($query) {
                    $query->where("{$this->table}.process_status", self::ORDER_NEW)
                            ->orWhere("{$this->table}.process_status", self::ORDER_CONFIRMED)
                            ->orWhere("{$this->table}.process_status", self::ORDER_PAYFAIL);
                });
                break;
            case 'cancel':
                    $ds->where("{$this->table}.process_status", self::ORDER_CANCEL);
                    break;
            default:
                $ds->where("{$this->table}.is_deleted",  self::ORDER_DELETED);
                break;
        }
        if($branch != null){
            $ds->where("{$this->table}.branch_id", $branch);
        }
        $ds->whereBetween('orders.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('order_code', 'like', '%' . $search . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $search . '%');
            });
        }
        $ds->orderBy("{$this->table}.created_at", 'desc');
        $ds->groupBy("{$this->table}.order_id");
        $page = (int)($filter["page"] ?? 1);
        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}