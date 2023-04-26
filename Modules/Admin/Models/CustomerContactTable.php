<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 20/3/2019
 * Time: 15:25
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerContactTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_contacts';
    protected $primaryKey = 'customer_contact_id';
    protected $fillable = [
        'customer_contact_id', 'customer_id', 'province_id', 'district_id','ward_id','type_address',
        'postcode', 'address_default', 'contact_name', 'full_address', 'contact_phone',
        'contact_email', 'created_at', 'updated_at', 'customer_contact_code','is_deleted'
    ];

    const IS_DEFAULT = 1;

    public function add (array $data)
    {
        $add = $this->create($data);
        return $add->customer_contact_id;
    }

    public function _getList (&$filter = [])
    {
        $list = $this->select(
            'customer_contacts.*',
            $this->table.'.contact_name as customer_name',
            $this->table.'.contact_phone as customer_phone',
            $this->table.'.full_address as address',
            $this->table.'.type_address',
            $this->table.'.address_default as is_default',
            DB::raw('CONCAT(province.type," ",province.name) as province_name'),
            DB::raw('CONCAT(district.type," ",district.name) as district_name'),
            DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
        )
            ->leftJoin('province', 'province.provinceid', '=', 'customer_contacts.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customer_contacts.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where($this->table.'.customer_id', $filter['customer_id'])
            ->where($this->table.'.is_deleted', 0)
            ->orderBy('customer_contact_id', 'desc');

        unset($filter['customer_id_filter']);

        if (isset($filters['customer_id'])){
            $list = $list->where($this->table.'.customer_id',$filters['customer_id']);
            unset($filters['customer_id']);
        }

        return $list;
    }
    public function getDetailContact ($idCusContact)
    {
        return $this->select(
            'customer_contacts.*',
            'province.name as province_name',
            'district.name as district_name'
        )
            ->leftJoin('province', 'province.provinceid', '=', 'customer_contacts.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customer_contacts.district_id')
            ->where('customer_contact_id', $idCusContact)
            ->where('is_deleted', 0)->first();
    }
    public function edit (array $data, $idContact)
    {
        return $this->where('customer_contact_id', $idContact)
            ->update($data);
    }
    public function remove ($id)
    {
        return $this->where('customer_contact_id', $id)->update(['is_deleted' => 1]);
    }
    public function setDefault ($idCustomer, $idContact)
    {
        // set all address_default -> 0
        $temp = $this->where('customer_id', $idCustomer)->update(['address_default' => 0]);
        // set -> 1
        return $this->where('customer_contact_id', $idContact)->update(['address_default' => 1]);
    }


    /** lay dia chi mac dinh cua khach hang
     * @param $idCus
     */
    public function getContactDefault ($idCus)
    {
        return $this->select(
            'customer_contacts.*',
            'province.name as province_name',
            'district.name as district_name'
        )
            ->leftJoin('province', 'province.provinceid', '=', 'customer_contacts.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customer_contacts.district_id')
            ->where('customer_id', $idCus)->where('address_default', 1)
            ->where('is_deleted', 0)->first();
    }

    /**
     * Lấy thông tin địa chỉ giao hàng
     *
     * @param $contractCode
     * @return mixed
     */
    public function getInfoContract($contractCode)
    {
        return $this
            ->select(
                'customer_contacts.*',
                'province.name as province_name',
                'district.name as district_name'
            )
            ->leftJoin('province', 'province.provinceid', '=', 'customer_contacts.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customer_contacts.district_id')
            ->where("{$this->table}.customer_contact_code", $contractCode)
            ->where('is_deleted', 0)
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
                $this->table.'.customer_contact_id',
                $this->table.'.customer_id',
                $this->table.'.contact_name as customer_name',
                $this->table.'.contact_phone as customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.full_address as address',
                $this->table.'.type_address',
                $this->table.'.address_default as is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('customer_id',$customer_id)
            ->where('address_default',self::IS_DEFAULT)
            ->first();
    }

    /**
     * lấy chi tiết địa nhận hàng
     * @param $customer_contact_id
     * @return mixed
     */
    public function getDetail($customer_contact_id){
        return $this
            ->select(
                $this->table.'.customer_contact_id',
                $this->table.'.customer_contact_code',
                $this->table.'.customer_id',
                $this->table.'.contact_name as customer_name',
                $this->table.'.contact_phone as customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.full_address as address',
                $this->table.'.type_address',
                $this->table.'.address_default as is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('customer_contact_id',$customer_contact_id)
            ->first();
    }

    /**
     * lấy chi tiết địa nhận hàng theo khách hàng
     * @param $delivery_customer_address_id
     * @return mixed
     */
    public function getDetailCustomer($customer_id){
        return $this
            ->select(
                $this->table.'.customer_contact_id',
                $this->table.'.customer_id',
                $this->table.'.contact_name as customer_name',
                $this->table.'.contact_phone as customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.full_address as address',
                $this->table.'.type_address',
                $this->table.'.address_default as is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('customer_id',$customer_id)
            ->where('address_default',self::IS_DEFAULT)
            ->first();
    }

    /**
     * lấy chi tiết địa nhận hàng
     * @param $customer_contact_id
     * @return mixed
     */
    public function getDetailByCode($customer_contact_code){
        return $this
            ->select(
                $this->table.'.customer_contact_id',
                $this->table.'.customer_id',
                $this->table.'.contact_name as customer_name',
                $this->table.'.contact_phone as customer_phone',
                $this->table.'.province_id',
                $this->table.'.district_id',
                $this->table.'.ward_id',
                $this->table.'.full_address as address',
                $this->table.'.type_address',
                $this->table.'.address_default as is_default',
                DB::raw('CONCAT(province.type," ",province.name) as province_name'),
                DB::raw('CONCAT(district.type," ",district.name) as district_name'),
                DB::raw('CONCAT(ward.type," ",ward.name) as ward_name')
            )
            ->leftJoin('province','province.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where('customer_contact_code',$customer_contact_code)
            ->first();
    }

    /**
     * kiểm tra địa chỉ mặc định
     * @param $customerId
     */
    public function checkAddressDefault($customerId){
        return $this
            ->where('customer_id',$customerId)
            ->where('address_default',self::IS_DEFAULT)
            ->first();
    }

    /**
     * Chỉnh sửa địa chỉ theo khách hàng
     */
    public function updateAddressCustomer($data,$customer_id){
        return $this->where('customer_id',$customer_id)->update($data);
    }

    /**
     * Tổng địa chỉ theo khách hàng
     */
    public function countAddressCustomer($customer_id){
        return $this->where('customer_id',$customer_id)->count();
    }

    /**
     * Cập nhật địa chỉ
     * @param $customer_contact_id
     */
    public function updateAddress($data,$customer_contact_id){
        return $this->where('customer_contact_id',$customer_contact_id)->update($data);
    }

    /**
     * Tạo địa chỉ mới
     */
    public function addAddress($data){
        return $this->insertGetId($data);
    }
}