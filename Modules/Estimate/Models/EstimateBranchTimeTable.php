<?php

namespace Modules\Estimate\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * Class EstimateBranchTimeTable
 * @author HaoNMN
 * @since May 2022
 */
class EstimateBranchTimeTable extends Model
{
    use ListTableTrait;
    protected $table = 'estimate_branch_time';
    protected $primaryKey = 'estimate_branch_time_id';
    protected $fillable = [
        'branch_id',
        'type',
        'week',
        'month',
        'year',
        'estimate_time',
        'estimate_money'
    ];


    /**
     * Lấy danh sách cấu hình theo tuần hoặc theo tháng
     * @param $type, $year
     * @return mixed
     */
    public function getEstimateList($type, $year, $branchId)
    {
        $oSelect = $this->select(
            'estimate_branch_time_id',
            'branch_id',
            'type',
            'week',
            'month',
            'year',
            'estimate_time',
            'estimate_money'
        )
        ->where("{$this->table}.type", $type)
        ->where("{$this->table}.branch_id", (int)$branchId);

        if ($year) {
            $oSelect->where("{$this->table}.year", $year);
        }

        // Nếu type là W (tuần) thì sắp xếp theo col week và ngược lại
        if ($type == "W") {
            $oSelect->orderBy("{$this->table}.week", 'asc');
        } else {
            $oSelect->orderBy("{$this->table}.month", 'asc');
        }
       
        return $oSelect->get();
    }

    /**
     * Lấy danh sách năm
     * @param $branchId
     * @return mixed
     */
    public function getYearsEstimate($branchId)
    {
        $oSelect = $this->select(
            'year'
        )
        ->where("{$this->table}.branch_id", (int)$branchId)
        ->groupBy("{$this->table}.year");
                    
        return $oSelect->pluck('year')->toArray();
    }

    /**
     * chỉnh sửa cấu hình
     * @param $branchId
     * @return mixed
     */
    public function editEstimate($id, $data)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}