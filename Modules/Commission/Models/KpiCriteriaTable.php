<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/10/2022
 * Time: 16:07
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;

class KpiCriteriaTable extends Model
{
    protected $table = "kpi_criteria";
    protected $primaryKey = "kpi_criteria_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;


    /**
     * Lấy option tiêu chí kpi theo loại
     *
     * @param $kpiCriteriaType
     * @return mixed
     */
    public function getOptionCriteria($kpiCriteriaType)
    {
        $ds = $this
            ->select(
                "kpi_criteria_id",
                "kpi_criteria_code",
                "kpi_criteria_name"
            )
            ->where("status", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED);

        if ($kpiCriteriaType != null) {
            $ds->where(function ($query) use ($kpiCriteriaType) {
                $query->where("kpi_criteria_type", $kpiCriteriaType)
                    ->orWhere("kpi_criteria_type", "A");
            });
        }

        return $ds->get();
    }


}