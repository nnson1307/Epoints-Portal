<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetMarketingTable extends Model
{
    protected $table      = 'budget_marketing';
    protected $primaryKey = 'budget_marketing_id'; 
    protected $fillable = [
        'budget_marketing_id',
        'department_id',
        'team_id',
        'effect_time',
        'budget',
        'budget_type',
        'created_by',
        'created_at',
        'updated_at'
    ];

    const BY_MONTH    = 0;
    const BY_DAY      = 1;
    const NOT_DELETED = 0;


    public function list($param, $type)
    {
        $oSelect = $this->select(
                            "{$this->table}.budget_marketing_id",
                            "{$this->table}.department_id",
                            'd.department_name',
                            "{$this->table}.team_id",
                            't.team_name',
                            "{$this->table}.budget",
                            "{$this->table}.effect_time",
                            "{$this->table}.budget_type",
                            "{$this->table}.created_by",
                            "{$this->table}.created_at",
                            "{$this->table}.updated_at"
                        )
                        ->where("{$this->table}.is_deleted", self::NOT_DELETED)
                        ->leftJoin('departments as d', 'd.department_id', '=', "{$this->table}.department_id")
                        ->leftJoin('team as t', 't.team_id', '=', "{$this->table}.team_id");
        if ($type == 0) {
            $oSelect->where("{$this->table}.budget_type", self::BY_MONTH);
        } else {
            $oSelect->where("{$this->table}.budget_type", self::BY_DAY);
        }
        
        if (isset($param['department_id'])) {
            $oSelect->where("{$this->table}.department_id", $param['department_id']);
        }

        if (isset($param['effect_time'])) {
            $oSelect->where("{$this->table}.effect_time", $param['effect_time']);
        }

        return $oSelect->orderBy("{$this->table}.created_at", "DESC")->paginate(10);
    }

    public function add($data)
    {
        return $this->insert($data);
    }

    public function updateData($id, $data)
    {
        return $this->where("{$this->table}.budget_marketing_id", $id)
                    ->update($data);
    }

    public function remove($id)
    {
        return $this->where("{$this->table}.budget_marketing_id", $id)
                    ->update(['is_deleted' => 1]);
    }

    // Lấy danh sách bugget
    public function getAll($filter = []){
        $oSelect = $this
            ->where($this->table.'.is_deleted',0);

        if (isset($filter['start_month'])) {
            $oSelect = $oSelect->where($this->table.'.effect_time','>=',$filter['start_month']);
        }

        if (isset($filter['end_month'])) {
            $oSelect = $oSelect->where($this->table.'.effect_time','<=',$filter['end_month']);
        }

        if (isset($filter['budget_type'])) {
            $oSelect = $oSelect->where($this->table.'.budget_type',$filter['budget_type']);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where($this->table.'.department_id',$filter['department_id']);
        }

        return $oSelect->get();

    }
}
