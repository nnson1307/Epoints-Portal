<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";
    protected $fillable = [];

    public function getName(){
        $oSelect= self::select("customer_id","full_name")->where('is_deleted',0)->get();
        return ([""=>"Tất cả"]) + ($oSelect->pluck("full_name","customer_id")->toArray());
    }
}
