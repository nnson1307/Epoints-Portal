<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryCustomerAddressTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_customer_address";
    protected $primaryKey = "delivery_customer_address_id";

    //function fillable
    protected $fillable = [
        'delivery_customer_address_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'type_address',
        'is_default',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const IS_DEFAULT = 1;

    public function _getList(&$filters = [])
    {
        $oSelect = $this
            ->select(
                $this->table.'.delivery_customer_address_id',
                $this->table.'.customer_id',
                $this->table.'.customer_name',
                $this->table.'.customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.address',
                $this->table.'.type_address',
                $this->table.'.is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
//                'province.name as province_name',
//                'district.name as district_name'
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('customer_id',$filters['customer_id'])
            ->orderBy($this->table.'.is_default','DESC')
            ->orderBy($this->table.'.delivery_customer_address_id','DESC');

        return $oSelect;
    }

    /**
     * Tạo địa chỉ mới
     */
    public function addAddress($data){
        return $this->insertGetId($data);
    }

    /**
     * Chỉnh sửa địa chỉ theo khách hàng
     */
    public function updateAddressCustomer($data,$customer_id){
        return $this->where('customer_id',$customer_id)->update($data);
    }

    /**
     * Cập nhật địa chỉ
     * @param $delivery_customer_address_id
     */
    public function updateAddress($data,$delivery_customer_address_id){
        return $this->where('delivery_customer_address_id',$delivery_customer_address_id)->update($data);
    }

    /**
     * Tổng địa chỉ theo khách hàng
     */
    public function countAddressCustomer($customer_id){
        return $this->where('customer_id',$customer_id)->count();
    }

    /**
     * Xoá địa chỉ nhận hàng
     */
    public function deleteAddressCustomer($delivery_customer_address_id){
        return $this->where('delivery_customer_address_id',$delivery_customer_address_id)->delete();
    }

    /**
     * lấy chi tiết địa nhận hàng
     * @param $delivery_customer_address_id
     * @return mixed
     */
    public function getDetail($delivery_customer_address_id){
        return $this
            ->select(
                $this->table.'.delivery_customer_address_id',
                $this->table.'.customer_id',
                $this->table.'.customer_name',
                $this->table.'.customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.address',
                $this->table.'.type_address',
                $this->table.'.is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('delivery_customer_address_id',$delivery_customer_address_id)
            ->first();
    }

    /**
     * lấy chi tiết địa nhận hàng theo khách hàng
     * @param $delivery_customer_address_id
     * @return mixed
     */
    public function getDetailByCustomer($customer_id){
        return $this
            ->select(
                $this->table.'.delivery_customer_address_id',
                $this->table.'.customer_id',
                $this->table.'.customer_name',
                $this->table.'.customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.address',
                $this->table.'.type_address',
                $this->table.'.is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('customer_id',$customer_id)
            ->where('is_default',self::IS_DEFAULT)
            ->first();
    }

    /**
     * kiểm tra địa chỉ mặc định
     * @param $customerId
     */
    public function checkAddressDefault($customerId){
        return $this
            ->where('customer_id',$customerId)
            ->where('is_default',self::IS_DEFAULT)
            ->first();
    }
}