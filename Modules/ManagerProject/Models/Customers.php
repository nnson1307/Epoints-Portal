<?php

namespace Modules\ManagerProject\Models;

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
    public  function getCustomerAll($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.customer_id",
                "{$this->table}.full_name as customer_name",
                "{$this->table}.customer_avatar as customer_avatar",
                "{$this->table}.gender",
                "{$this->table}.phone1 as phone",
                "{$this->table}.email",
                "{$this->table}.customer_type"
            );
        if(isset($filter['arrIdCustomer']) && $filter['arrIdCustomer'] != '' && $filter['arrIdCustomer']!= null ){
            $mSelect = $mSelect->whereIn("{$this->table}.customer_id",$filter['arrIdCustomer']);
        }
        if(isset($filter['customer_id']) && $filter['customer_id'] != '' && $filter['customer_id']!= null ){
            $mSelect = $mSelect->where("{$this->table}.customer_id",$filter['customer_id']);
        }
        return $mSelect->get()->toArray();
    }
    /**
     * Lấy các option khach hang
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select(
            "customer_id as accounting_id",
            "full_name as accounting_name"
        )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);
        return $select->get();
    }
}