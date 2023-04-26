<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 9/29/2021
 * Time: 2:23 PM
 * @author nhandt
 */


namespace Modules\Dashbroad\Models;


use Illuminate\Database\Eloquent\Model;

class DashboardComponentTable extends Model
{
    protected $table = 'dashboard_components';
    protected $primaryKey = 'dashboard_component_id';
    protected $fillable = [
        "dashboard_component_id",
        "dashboard_id",
        "component_type",
        "component_position",
        "is_default",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_DEFAULT = 1;

    public function getComponentDefault()
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "dashboard_component_id",
            "component_type",
            "component_position"
        )
            ->where("is_default", self::IS_DEFAULT);
        return $data->get();
    }
    public function getComponent($idDashboard)
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "dashboard_component_id",
            "component_type",
            "component_position"
        )
            ->where("dashboard_id", $idDashboard);
        $data->orderBy("component_position");
        return $data->get();
    }
    public function createData($data)
    {
        return $this->create($data)->dashboard_component_id;
    }
    public function insertData($data)
    {
        return $this->insert($data);
    }
    public function removeData($dashboardId)
    {
        return $this->where("dashboard_id", $dashboardId)->delete();
    }
}