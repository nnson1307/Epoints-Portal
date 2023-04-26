<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/07/2022
 * Time: 10:50
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerContractTable extends Model
{
    protected $table = "customer_contract";
    protected $primaryKey = "customer_contract_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const PAY_SUCCESS = "paysuccess";
    const PAY_HALF = "pay-half";

    /**
     * Đếm số hợp đồng đang thực hiện trong thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getContractProcessing($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw('count(*) as total')
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy("{$this->table}.staff_id")
            ->first();
    }

    /**
     * Tính tổng giá trị hợp đồng đầu tiên trong thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalAmountContractFirst($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw("sum({$this->table}.total_amount) as total_amount"),
                DB::raw("sum({$this->table}.total) as total")
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereNull("or.contract_code_extend")
            ->groupBy("{$this->table}.staff_id")
            ->first();
    }

    /**
     * Tính tổng doanh thu hợp đồng trong thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalContract($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw("sum({$this->table}.total) as total")
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->groupBy("{$this->table}.staff_id")
            ->first();
    }

    /**
     * Lấy dữ liệu hợp đồng được tạo bởi nhân viên trong khoảng thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getDataContract($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->get();
    }

    /**
     * Tính tổng giá trị hợp đồng tái kí trong thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalAmountContractReNew($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw("sum({$this->table}.total) as total"),
                DB::raw("sum({$this->table}.total_amount) as total_amount")
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereNotNull("or.contract_code_extend")
            ->groupBy("{$this->table}.staff_id")
            ->first();
    }

    /**
     * Tính tổng giá trị hợp đồng của phòng ban trong thời gian áp dụng kpi
     *
     * @param $departmentId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalAmountContractByDepartment($departmentId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw("sum({$this->table}.total_amount) as total_amount"),
                DB::raw("sum({$this->table}.total_amount) as total")
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->join("staffs as sf", "sf.staff_id", "=", "{$this->table}.created_by")
            ->where("sf.department_id", $departmentId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->groupBy("sf.department_id")
            ->first();
    }

    /**
     * Lấy dữ liệu hợp đồng được tạo bởi nhân viên trong khoảng thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getContractNewGroupByCustomer($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereNull("or.contract_code_extend")
            ->groupBy("{$this->table}.customer_id")
            ->get();
    }

    /**
     * Lấy hợp đồng quá khứ của khách hàng
     *
     * @param $customerId
     * @param null $contractId
     * @param null $startDate
     * @return mixed
     */
    public function getContractPast($customerId, $contractId = null, $startDate = null)
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF]);

        if ($contractId != null) {
            $ds->where("{$this->table}.customer_contract_id" , "<>", $contractId);
        }

        if ($startDate != null) {
            $ds->where("{$this->table}.created_at", "<", $startDate);
        }

        return $ds->get();
    }

    /**
     * Lấy data hợp đồng đang thực hiện trong ngày của nhân viên
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDateContractProcessingByDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code"
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->get();
    }

    /**
     * Lấy dữ liệu hợp đồng được tạo bởi nhân viên trong ngày
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDataContractByDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->get();
    }

    /**
     * Tính tổng giá trị hợp đồng đầu tiên trong ngày
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDataContractFirstByDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereNull("or.contract_code_extend")
            ->get();
    }

    /**
     * Tính data hợp đồng tái kí trong ngày
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDataContractReNew($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereNotNull("or.contract_code_extend")
            ->get();
    }

    /**
     * Lấy data hợp đồng của phòng ban trong ngày
     *
     * @param $departmentId
     * @param $day
     * @return mixed
     */
    public function getDataContractByDepartment($departmentId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->join("staffs as sf", "sf.staff_id", "=", "{$this->table}.created_by")
            ->where("sf.department_id", $departmentId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->get();
    }

    /**
     * Lấy dữ liệu hợp đồng được tạo bởi nhân viên trong ngày
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getContractNewGroupByCustomerInDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_contract_id",
                "{$this->table}.customer_contract_code",
                "{$this->table}.order_id",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.bonus",
                "{$this->table}.total_amount",
                "{$this->table}.created_at"
            )
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereNull("or.contract_code_extend")
            ->groupBy("{$this->table}.customer_id")
            ->get();
    }
}