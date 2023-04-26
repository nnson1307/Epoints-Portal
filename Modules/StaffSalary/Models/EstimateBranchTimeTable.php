<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;

class EstimateBranchTimeTable extends Model
{
    use ListTableTrait;
    protected $table = "estimate_branch_time";
    protected $primaryKey = "estimate_branch_time_id";

    const TYPE_WEEK = "W";
    const TYPE_MONTH = "M";
    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách ngân sách theo chi nhánh
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.estimate_branch_time_id",
                "{$this->table}.estimate_time",
                "{$this->table}.estimate_money",
                "br.branch_name",
                "br.branch_id"
            )
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->where("br.is_actived", self::IS_ACTIVED)
            ->where("br.is_deleted", self::NOT_DELETED);

        //Filter theo loại (tuần/ tháng)
        if (isset($filter['date_type']) && $filter['date_type'] != null
            && isset($filter['date_object']) && $filter['date_object'] != null) {

            switch ($filter['date_type']) {
                case 'by_week':
                    $ds->where("{$this->table}.type", self::TYPE_WEEK)
                        ->where("{$this->table}.week", $filter['date_object'])
                        ->where("{$this->table}.year", Carbon::now()->format('Y'));
                    break;
                case 'by_month':
                    $ds->where("{$this->table}.type", self::TYPE_MONTH)
                        ->where("{$this->table}.month", $filter['date_object'])
                        ->where("{$this->table}.year", Carbon::now()->format('Y'));
                    break;
            }
        }

        //Filter chi nhánh
        if (isset($filter['branch_id']) && $filter['branch_id'] != null) {
            $ds->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        unset($filter['date_type'], $filter['date_object'], $filter['branch_id']);

        return $ds;
    }

    public function getEstimateByBranch($branchId, $type, $typeValue)
    {

        $ds = $this
            ->select(
               "{$this->table}.estimate_money",
               "{$this->table}.estimate_time"
            )

            ->where("{$this->table}.branch_id", $branchId);
        //Filter theo loại (tuần/ tháng)
        switch ($type) {
            case 'by_week':
                $ds->where("{$this->table}.type", self::TYPE_WEEK)
                    ->where("{$this->table}.week", $typeValue)
                    ->where("{$this->table}.year", Carbon::now()->format('Y'));
                break;
            case 'by_month':
                $ds->where("{$this->table}.type", self::TYPE_MONTH)
                    ->where("{$this->table}.month", $typeValue)
                    ->where("{$this->table}.year", Carbon::now()->format('Y'));
                break;
        }
        return $ds->first();
    }
}