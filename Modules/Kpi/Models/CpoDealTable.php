<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/07/2022
 * Time: 09:41
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CpoDealTable extends Model
{
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";

    const NOT_DELETED = 0;
    const PAY_SUCCESS = "paysuccess";
    const PAY_HALF = "pay-half";

    /**
     * Lấy thông tin deal được chăm sóc bởi sale trong thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getDealBySale($staffId, $startDate, $endDate)
    {
        return $this
            ->where("{$this->table}.sale_id", $staffId)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin deal bằng code
     *
     * @param $dealCode
     * @return mixed
     */
    public function getInfoByCode($dealCode)
    {
        return $this->where("deal_code", $dealCode)->first();
    }

    /**
     * Đếm số deal thành công trong thời gian áp dụng kpi
     *
     * @param $staffId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getDealWin($staffId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw('count(*) as total')
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.sale_id", $staffId)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->groupBy("{$this->table}.sale_id")
            ->first();
    }

    /**
     * Lấy thông tin deal được chăm sóc bởi sale trong ngày
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDealBySaleInDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.customer_id",
                "{$this->table}.order_id"
            )
            ->where("{$this->table}.sale_id", $staffId)
            ->whereDate("{$this->table}.created_at", $day)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy data deal thành công trong ngày
     *
     * @param $staffId
     * @param $day
     * @return mixed
     */
    public function getDealWinInDay($staffId, $day)
    {
        return $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.customer_id",
                "{$this->table}.order_id"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->join("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.sale_id", $staffId)
            ->whereDate("{$this->table}.created_at", $day)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->whereIn("or.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->get();
    }
}