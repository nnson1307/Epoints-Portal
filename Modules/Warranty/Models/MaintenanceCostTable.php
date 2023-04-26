<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 10:34 AM
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class MaintenanceCostTable extends Model
{
    protected $table = "maintenance_cost";
    protected $primaryKey = "maintenance_cost_id";

    /**
     * Lấy chi phí phát sinh của phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function getCost($maintenanceId)
    {
        return $this
            ->select(
                "maintenance_cost_id",
                "maintenance_id",
                "maintenance_cost_type",
                "cost"
            )
            ->where("maintenance_id", $maintenanceId)
            ->get();
    }

    /**
     * Xóa tất cả chi phí phát sinh của phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function removeCost($maintenanceId)
    {
        return $this->where("maintenance_id", $maintenanceId)->delete();
    }
}