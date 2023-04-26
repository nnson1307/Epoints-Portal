<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * class KpiCriteriaTable
 * @author HaoNMN
 * @since Jun 2022
 */
class KpiCriteriaTable extends Model
{
    protected $table      = 'kpi_criteria';
    protected $primaryKey = 'kpi_criteria_id';
    protected $fillable = [
        'kpi_criteria_id',
        'kpi_criteria_name',
        'description',
        'kpi_criteria_unit_id',
        'kpi_criteria_trend',
        'is_blocked',
        'kpi_criteria_type',
        'status',
        'is_lead',
        'is_customize',
        'created_by',
        'is_deleted',
        'created_at',
        'updated_at'
    ];

    /**
     * Lấy danh sách tiêu chí kpi
     */
    public function getList($param)
    {
        $select = $this->where("{$this->table}.is_deleted", 0);

        if (isset($param['kpi_criteria_name'])) {
            $select->where("{$this->table}.kpi_criteria_name", 'like', '%' .$param['kpi_criteria_name']. '%');
        }

        if (isset($param['kpi_criteria_trend'])) {
            $select->where("{$this->table}.kpi_criteria_trend", $param['kpi_criteria_trend']);
        }

        if (isset($param['status'])) {
            $select->where("{$this->table}.status", $param['status']);
        }

        if (isset($param['kpi_note_type'])) {
            $select->where(function ($cond) use ($param) {
                $cond->where("{$this->table}.kpi_criteria_type", $param['kpi_note_type'])
                     ->orWhere("{$this->table}.is_customize", 1);
            });
        }

        if (isset($param['query'])) {
            return $select->get()->toArray();
        }

        return $select->paginate(10);
    }

    public function add($data) 
    {
        return $this->insert($data);
    }

    /**
     * Cập nhật dữ liệu tiêu chí kpi
     */
    public function updateCriteria($id, $data)
    {
        return $this->where("{$this->table}.kpi_criteria_id", $id)
                    ->update($data);
    }

    /**
     * Xóa tiêu chí kpi
     */
    public function remove($id) 
    {
        return $this->where("{$this->table}.kpi_criteria_id", $id)
                    ->update(["{$this->table}.is_deleted" => 1]);
    }

    // Lấy danh sách tiêu chí
    public function getAll($filter = []){
        $oSelect = $this
            ->where($this->table.'.is_deleted',0);

        if (isset($filter['arrCriteriaId'])){
            $oSelect = $oSelect->whereIn($this->table.'.kpi_criteria_id',$filter['arrCriteriaId']);
        }

        return $oSelect->get();
    }
}
