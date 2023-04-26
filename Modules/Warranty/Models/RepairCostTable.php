<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class RepairCostTable extends Model
{
    protected $table = "repair_costs";
    protected $primaryKey = "repair_cost_id";

    /**
     * Lấy chi phí phát sinh của phiếu bảo dưỡng
     *
     * @param $repairId
     * @return mixed
     */
    public function getCost($repairId)
    {
        return $this
            ->select(
                "repair_cost_id",
                "repair_id",
                "maintenance_cost_type",
                "cost"
            )
            ->where("repair_id", $repairId)
            ->get();
    }

    /**
     * Xóa tất cả chi phí phát sinh của phiếu bảo dưỡng
     *
     * @param $repairId
     * @return mixed
     */
    public function removeCost($repairId)
    {
        return $this->where("repair_id", $repairId)->delete();
    }
}