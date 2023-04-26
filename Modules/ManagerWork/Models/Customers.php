<?php

namespace Modules\ManagerWork\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customers extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";
    protected $fillable = [];

    public function getName()
    {
        $oSelect = self::select("customer_id", "full_name")->where('is_deleted', 0)->get();
        return ($oSelect->pluck("full_name", "customer_id")->toArray());
    }
    public function getFullOption($fillable = [])
    {
        $personal = __('Cá nhân');
        $bussiness = __('Doanh nghiệp');

        $oSelect = self::select(
            "customer_id",
            DB::raw("CONCAT((CASE WHEN customer_type = 'business' THEN '{$bussiness}' ELSE '{$personal}' END),'_',IFNULL(full_name,''),'_',IFNULL(phone1,'')) as full_name")
        )
            ->where('is_deleted', 0)
            ->where("{$this->table}.customer_id", "!=", 1)
            ->get();
        return ($oSelect->pluck("full_name", "customer_id")->toArray());
    }

    /**
     * lấy danh sách khách hàng
     * @return mixed
     */
    public function getAll()
    {
        $personal = __('Cá nhân');
        $bussiness = __('Doanh nghiệp');

        return $this
            ->select(
                'customer_id',
                'full_name',
                DB::raw("CONCAT((CASE WHEN customers.customer_type = 'business' THEN '{$bussiness}' ELSE '{$personal}' END),'_',IFNULL(customers.full_name,''),'_',IFNULL(customers.phone1,'')) as customer_name")
            )
            ->where("{$this->table}.customer_id", "!=", 1)
            ->where('is_actived', 1)
            ->get();
    }

    /**
     * lấy danh sách khách hàng theo loại
     * @param $type
     * @return mixed
     */

    public function getAllByType($type)
    {
        return $this
            ->select(
                'customer_id',
                'full_name'
            )
            ->where("{$this->table}.customer_id", "!=", 1)
            ->where('is_actived', 1)
            ->where("customer_type", $type)
            ->get();
    }

    public function getDetail($customerId){
        return $this->where('customer_id',$customerId)->first();
    }
}
