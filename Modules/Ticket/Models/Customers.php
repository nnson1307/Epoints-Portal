<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";
    protected $fillable = [];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    public function getName(){
        $oSelect= self::select("customer_id","full_name")->where('is_actived',1)->where('is_deleted',0)->get();
        return ($oSelect->pluck("full_name","customer_id")->toArray());
    }
    public function getAdress(){
        $oSelect= self::select("customer_id","address")->where('is_actived',1)->where('is_deleted',0)->get();
        return ($oSelect->pluck("address","customer_id")->toArray());
    }
    public function getFullOption($fillable = []){
        $oSelect= self::select("customer_id",
        \DB::raw('CONCAT(full_name,"_",phone1) as full_name')
        )->where('is_actived',1)->where('is_deleted',0)->get();
        return ($oSelect->pluck("full_name","customer_id")->toArray());
    }

    /**
     * Lấy thông tin khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getItem($customerId)
    {
        return $this
            ->select(
                'customer_id',
                'full_name',
                'branch_id',
                'customer_group_id',
                'phone1',
                'email',
                'customer_code'
            )
            ->where('customer_id', $customerId)
            ->where('is_actived', self::IS_ACTIVE)
            ->where('is_deleted', self::NOT_DELETE)
            ->first();
    }
}
