<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";
    protected $fillable = ['service_id', 'service_name', 'service_category_id', 'service_code', 'is_sale', 'price_standard', 'service_type', 'time', 'have_material', 'description', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function getServiceName(){
        return self::select("service_id","service_name")->get();
    }
}
