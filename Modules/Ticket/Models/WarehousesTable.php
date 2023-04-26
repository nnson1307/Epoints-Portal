<?php


namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;

class WarehousesTable extends Model
{
    protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_id';

    protected $fillable = ['warehouse_id', 'name', 'slug', 'branch_id','province_id','district_id','address','description', 'created_by', 'updated_by', 'created_at', 'updated_at','is_deleted','is_retail'];

    public function getOption()
    {
        $oSelect= self::select("warehouse_id","name")->where("is_deleted", 0)->get();
        return ($oSelect->pluck("name","warehouse_id")->toArray());
    }

    public function getWarehouseList()
    {
        return self::select()->where("is_deleted", 0)->get();
    }
}