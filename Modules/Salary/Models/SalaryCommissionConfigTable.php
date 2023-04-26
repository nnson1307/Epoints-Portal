<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class SalaryCommissionConfigTable extends BaseModel
{
    use ListTableTrait;
    protected $table = "salary_commission_config";
    protected $primaryKey = "salary_commission_config_id";
    protected $fillable = [
        "salary_commission_config_id",
        "department_id",
        "type_view",
        "internal_new",
        "internal_renew",
        "external_new",
        "external_renew",
        "partner_new",
        "partner_renew",
        "installation_commission",
        "kpi_probationers",
        "kpi_staff",
        "is_actived",
        "created_at",
        "updated_by",
        "created_by",
        "updated_at"
    ];


    public function getDataList($filters = [])
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select(
            "{$this->table}.salary_commission_config_id",
            "{$this->table}.department_id",
            "{$this->table}.type_view",
            "{$this->table}.internal_new",
            "{$this->table}.internal_renew",
            "{$this->table}.external_new",
            "{$this->table}.external_renew",
            "{$this->table}.partner_new",
            "{$this->table}.partner_renew",
            "{$this->table}.installation_commission",
            "{$this->table}.kpi_probationers",
            "{$this->table}.kpi_staff",
            "{$this->table}.is_actived",
            "{$this->table}.created_at",
            "{$this->table}.updated_by",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "p1.full_name as created_by_full_name",
            "p3.full_name as updated_by_full_name",
            "p2.department_name as department_name"
        )
        ->leftJoin("staffs as p1","p1.staff_id","{$this->table}.created_by")
        ->leftJoin("staffs as p3","p3.staff_id","{$this->table}.updated_by")
        ->leftJoin("departments as p2","p2.department_id","{$this->table}.department_id")
            ->orderBy($this->primaryKey, 'DESC');
            if (isset($filters["department_id"]) && $filters["department_id"] != "") {
                $query->where("{$this->table}.department_id", $filters["department_id"]);
            }
            return $query->orderBy($this->primaryKey, 'desc')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->salary_commission_config_id;
    }

    public function getDepmartentId()
    {
        $oSelect= self::select("department_id")->groupBy('department_id')->where('department_id','<>','null')->get();
        return ($oSelect->pluck("department_id")->toArray());
    }
    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }


    public function getAllForInsert($id){
        return $this->select('*', DB::raw("{$id} as salary_id"))->where('is_actived', 1)->get()->toArray();
    }

    public function getByDepartment($depId){
        $oSelect = $this->where('department_id', $depId)->first();

        return $this->returntToArray($oSelect);
    }
}