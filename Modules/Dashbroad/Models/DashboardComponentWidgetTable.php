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

class DashboardComponentWidgetTable extends Model
{
    protected $table = 'dashboard_component_widgets';
    protected $primaryKey = 'dashboard_component_widget_id';
    protected $fillable = [
        "dashboard_component_widget_id",
        "dashboard_component_id",
        "dashboard_widget_id",
        "widget_display_name",
        "widget_position",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    public function getWidgetOfComponent($componentId)
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "{$this->table}.dashboard_component_widget_id",
            "{$this->table}.dashboard_component_id",
            "{$this->table}.dashboard_widget_id",
            "{$this->table}.widget_display_name",
            "{$this->table}.widget_position",
            "dashboard_widgets.widget_name_$lang as widget_name",
            "dashboard_widgets.widget_name_vi",
            "dashboard_widgets.widget_name_en",
            "dashboard_widgets.widget_code",
            "dashboard_widgets.size_column",
            "dashboard_widgets.widget_type",
            "dashboard_widgets.icon",
            "dashboard_widgets.image"
        )
            ->leftJoin("dashboard_widgets", "{$this->table}.dashboard_widget_id", "dashboard_widgets.dashboard_widget_id")
            ->where("{$this->table}.dashboard_component_id", $componentId);
        return $data->get();
    }
    public function createData($data)
    {
        return $this->create($data)->dashboard_component_widget_id;
    }
    public function insertData($data)
    {
        return $this->insert($data);
    }
    public function removeData($dashboardComponentId)
    {
        return $this->where("dashboard_component_id", $dashboardComponentId)->delete();
    }
}