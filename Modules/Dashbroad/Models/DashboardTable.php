<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 9/29/2021
 * Time: 2:23 PM
 * @author nhandt
 */


namespace Modules\Dashbroad\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DashboardTable extends Model
{
    protected $table = 'dashboard';
    protected $primaryKey = 'dashboard_id';
    protected $fillable = [
        "dashboard_id",
        "name_vi",
        "name_en",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVED = 1;
    const IS_DELETED = 0;


    use ListTableTrait;

    protected function _getList(&$filter = [])
    {
        $lang = app()->getLocale();
        $ds = $this->select(
            "{$this->table}.dashboard_id",
            "{$this->table}.name_$lang as name",
            "{$this->table}.name_vi",
            "{$this->table}.name_en",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "staffs.full_name"
        )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'dashboard.created_by')
            ->where("{$this->table}.is_deleted", self::IS_DELETED);
        if(isset($filter['search']) && $filter['search'] != ''){
            $search = $filter['search'];
            $ds->where(function($query)use($search){
                $query->where("{$this->table}.name_vi", 'like', '%'. $search. '%')
                    ->orWhere("{$this->table}.name_en", 'like', '%'. $search. '%');
            });
        }
        unset($filter['search']);
        if(isset($filter['is_actived'])){
            $ds->where("{$this->table}.is_actived", $filter['is_actived']);
        }
        unset($filter['is_actived']);
        if (isset($filter['created_at']) && $filter['created_at'] != ''){
            $arr_explode = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_explode[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_explode[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter['created_at']);
        return $ds;
    }
    public function getItem($id)
    {
        return $this->where("dashboard_id", $id)->first();
    }
    /**
     * option để chọn bảo sao
     *
     * @return mixed
     */
    public function getOptionDashboard()
    {
        $lang = app()->getLocale();
        $option = $this->select(
            "dashboard_id",
            "name_$lang as name"
        )
            ->where("is_deleted", self::IS_DELETED);
        return $option->get();
    }
    public function getDashboard($idDashboard)
    {
        $lang = app()->getLocale();
        $item = $this->select(
            "dashboard_id",
            "name_$lang as name"
        )
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::IS_DELETED)
            ->where("dashboard_id", $idDashboard);
        return $item->first();
    }
    public function createData($data)
    {
        return $this->create($data)->dashboard_id;
    }
    public function updateData($data, $id)
    {
        return $this->where("dashboard_id", $id)->update($data);
    }
    public function updateActive($data)
    {
        return $this->whereNotNull("dashboard_id")->update($data);
    }
    public function getActiveDashboard()
    {
        return $this->where("is_actived", self::IS_ACTIVED)->first();
    }
}