<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/07/2022
 * Time: 11:54
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CpoLeadTable extends Model
{
    protected $table = "cpo_customer_lead";
    protected $primaryKey = "customer_lead_id";

    const NOT_DELETED = 0;
    const IS_CONVERT = 1;
    const CONVERT_TYPE_DEAL = "deal";
    const NOT_CONVERT = 0;

    /**
     * Lấy thông tin lead được sale chăm sóc trong khoảng thời gian áp dụng kpi
     *
     * @param $saleId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getLeadBySale($saleId, $startDate, $endDate)
    {
        return $this
            ->where("sale_id", $saleId)
            ->where("is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.allocation_date", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();
    }

    /**
     * Đếm số lượng lead chuyển đổi thành deal trong thời gian áp dụng kpi
     *
     * @param $saleId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getLeadConvertIntoDealBySale($saleId, $startDate, $endDate)
    {
        return $this
            ->select(
                DB::raw('count(*) as total')
            )
            ->where("sale_id", $saleId)
            ->where("is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.allocation_date", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("is_convert", self::IS_CONVERT)
            ->where("convert_object_type", self::CONVERT_TYPE_DEAL)
            ->groupBy("sale_id")
            ->first();
    }

    /**
     * Lấy data lead chuyển đổi thành deal trong thời gian áp dụng kpi
     *
     * @param $saleId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getDataLeadConvertIntoDealBySale($saleId, $startDate, $endDate)
    {
        return $this
            ->where("sale_id", $saleId)
            ->where("is_deleted", self::NOT_DELETED)
            ->whereBetween("{$this->table}.allocation_date", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("is_convert", self::IS_CONVERT)
            ->where("convert_object_type", self::CONVERT_TYPE_DEAL)
            ->get();
    }

    /**
     * Lấy data lead được phân bổ cho team trong ngày
     *
     * @param $teamId
     * @param $day
     * @return mixed
     */
    public function getLeadCreateInDayByTeam($teamId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.sale_id",
                "{$this->table}.allocation_date",
                "s.team_id"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.sale_id")
            ->whereDate("{$this->table}.allocation_date", $day)
            ->where("s.team_id", $teamId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy data lead chuyển đổi thành deal của team trong ngày
     *
     * @param $teamId
     * @param $day

     * @return mixed
     */
    public function getDataLeadConvertIntoDealByTeam($teamId, $day)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.sale_id",
                "{$this->table}.allocation_date",
                "s.team_id"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.sale_id")
            ->join("cpo_deals as d", "d.deal_code", "=", "{$this->table}.convert_object_code")
            ->where("s.team_id", $teamId)
            ->whereDate("d.created_at", $day)
            ->where("{$this->table}.is_convert", self::IS_CONVERT)
            ->where("convert_object_type", self::CONVERT_TYPE_DEAL)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy data lead được phân bổ cho team trong khoảng thời gian
     *
     * @param $teamId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getDataLeadByTeamRangeTime($teamId, $startDate, $endDate)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.sale_id",
                "{$this->table}.allocation_date",
                "{$this->table}.is_convert",
                "{$this->table}.convert_object_type",
                "{$this->table}.convert_object_code",
                "{$this->table}.allocation_date",
                "d.deal_id",
                "d.deal_code",
                "d.customer_id",
                "d.order_id"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.sale_id")
            ->leftJoin("cpo_deals as d", "d.deal_code", "=", "{$this->table}.convert_object_code")
            ->where("s.team_id", $teamId)
            ->whereBetween("{$this->table}.allocation_date", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }
}