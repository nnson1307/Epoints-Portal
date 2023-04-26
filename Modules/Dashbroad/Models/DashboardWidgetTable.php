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

class DashboardWidgetTable extends Model
{
    protected $table = 'dashboard_widgets';
    protected $primaryKey = 'dashboard_widget_id';
    protected $fillable = [
        "dashboard_widget_id",
        "widget_name_vi",
        "widget_name_en",
        "widget_code",
        "size_column",
        "widget_type",
        "icon",
        "image",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    public function getListWidget($filter = [])
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "dashboard_widget_id",
            "widget_name_$lang as widget_name",
            "widget_name_vi",
            "widget_name_en",
            "widget_code",
            "size_column",
            "widget_type",
            "icon",
            "image"
        );
        if(isset($filter['search']) && $filter['search'] != '')
        {
            $search = $filter['search'];
            $data->where(function ($query) use($search){
                $query->where("widget_name_vi", 'like', '%' . $search . '%')
                    ->orWhere("widget_name_en", 'like', '%' . $search . '%');
            });
        }
        if(isset($filter['widget_type']) && $filter['widget_type'] != ''){
            $data->where("widget_type", $filter['widget_type']);
        }
        return $data->get();
    }
    public function createData($data)
    {
        return $this->create($data)->dashboard_widget_id;
    }
    public function insertData($data)
    {
        return $this->insert($data);
    }
}