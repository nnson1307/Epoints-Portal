<?php

namespace Modules\Commission\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class StaffCommissionEveryDayTable extends Model
{
    use ListTableTrait;
    protected $table = "staff_commission_every_day";
    protected $primaryKey = "staff_commission_every_day_id";


    /**
     * Danh sách hoa hồng đã nhận của nhân viên
     *
     * @param $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_commission_every_day_id",
                "{$this->table}.commission_id",
                "{$this->table}.number_value",
                "{$this->table}.commission_money",
                "{$this->table}.coefficient",
                "{$this->table}.date",
                "{$this->table}.day",
                "{$this->table}.month",
                "{$this->table}.year",
                "c.commission_name",
                "c.commission_type"
            )
            ->join("commission as c", "c.commission_id", "=", "{$this->table}.commission_id");

        //Filter nhân viên
        if (isset($filter['staff_id']) && $filter['staff_id'] != null) {
            $ds->where("{$this->table}.staff_id", $filter['staff_id']);
        }

        //Filter theo tháng
        if (isset($filter['month']) && $filter['month'] != null) {
            $ds->where("{$this->table}.month", $filter['month']);
        }

        //Filter theo loại hoa hôồng
        if (isset($filter['commission_type']) && $filter['commission_type'] != null) {
            $ds->where("c.commission_type", $filter['commission_type']);
        }

        return $ds;
    }


    /**
     * Lấy hoa hồng của nhân viên hàng ngày
     *
     * @param $staffId
     * @param $commissionDay
     * @return mixed
     */
    public function getCommissionByStaff($staffId, $commissionDay)
    {
        $ds = $this
            ->select(
                DB::raw('sum(commission_money) as total_commission_money')
            )
            ->where("staff_id", $staffId)
            ->groupBy("staff_id");

        //Filter ngày nhận hoa hồng
        if (isset($commissionDay) && $commissionDay != null) {
            $arr_filter = explode(" - ", $commissionDay);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');

            $ds->whereBetween("date", [$startTime, $endTime]);
        }

        return $ds->first();
    }
}