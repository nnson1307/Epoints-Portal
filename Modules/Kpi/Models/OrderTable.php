<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/07/2022
 * Time: 17:08
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    const CONFIRM = "confirmed";
    const CANCEL = "ordercancle";
    const NOT_DELETED = 0;
    const PAY_SUCCESS = "paysuccess";
    const PAY_HALF = "pay-half";

    /**
     * Đếm số đơn hàng đã được xác nhận của nhân viên
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getOrderConfirm($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw('count(*) as total')
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.process_status", self::CONFIRM)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy("{$this->table}.staff_id")
            ->first();
    }

    /**
     * Đếm số đơn hàng đã xoá của nhân viên
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getOrderCancel($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw('count(*) as total')
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.process_status", self::CANCEL)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy("{$this->table}.staff_id")
            ->first();
    }

    /**
     * Kiểm tra đơn hàng của khách hàng
     *
     * @param $customerId
     * @param $orderId
     * @param $createdAt
     * @return mixed
     */
    public function getOrderByCustomer($customerId, $orderId, $createdAt)
    {
        return $this
            ->where("process_status", self::CONFIRM)
            ->where("customer_id", $customerId)
            ->where("order_id", "<>", $orderId)
            ->where("created_at", "<=", $createdAt)
            ->get();
    }

    /**
     * Lấy thông tin đơn hàng thành công
     *
     * @param $customerId
     * @param $orderId
     * @param $date
     * @return mixed
     */
    public function getInfoOrderSuccess($customerId, $orderId, $date)
    {
        return $this
            ->where("customer_id", $customerId)
            ->where("order_id", $orderId)
            ->whereIn("{$this->table}.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereDate("{$this->table}.created_at", $date)
            ->first();
    }

    /**
     * Lấy đơn hàng thành công đầu tiên của KH trong giời gian áp dụng kpi
     *
     * @param $customerId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getFirstOrderSuccess($customerId, $startDate, $endDate)
    {
        return $this
            ->where("customer_id", $customerId)
            ->whereIn("{$this->table}.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("order_id", "asc")
            ->first();
    }

    /**
     * Lấy data đơn hàng đã được xác nhận của nhân viên
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDataOrderConfirmByDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.total",
                "{$this->table}.total_amount"
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.process_status", self::CONFIRM)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->get();
    }

    /**
     * Lấy data đơn hàng đã xoá của nhân viên
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDataOrderCancelByDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.total",
                "{$this->table}.total_amount"
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.process_status", self::CANCEL)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->get();
    }

    /**
     * Lấy data đơn hàng của nhân viên
     *
     * @param $teamId
     * @param $day
     * @return mixed
     */
    public function getDataOrderByTeamInDay($teamId, $day)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.total",
                "{$this->table}.total_amount",
                "{$this->table}.process_status"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("s.team_id", $teamId)
            ->where("{$this->table}.process_status", "<>", self::CANCEL)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereDate("{$this->table}.created_at", $day)
            ->get();
    }
}