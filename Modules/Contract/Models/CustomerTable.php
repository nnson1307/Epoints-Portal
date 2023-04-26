<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2021
 * Time: 13:59
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    protected $fillable = [
        'customer_id',
        'branch_id',
        'customer_group_id',
        'type_customer',
        'customer_type',
        'tax_code',
        'representative',
        'hotline',
        'full_name',
        'birthday',
        'gender',
        'phone1',
        'phone2',
        'email',
        'facebook',
        'address',
        'customer_source_id',
        'customer_refer_id',
        'customer_avatar',
        'note',
        'date_last_visit',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'zalo',
        'account_money',
        'customer_code',
        'province_id',
        'district_id',
        'custom_1',
        'custom_2',
        'custom_3',
        'custom_4',
        'custom_5',
        'custom_6',
        'custom_7',
        'custom_8',
        'custom_9',
        'custom_10'
    ];
    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    public function testPhone($phone, $id)
    {
        return $this->where(function ($query) use ($phone) {
            $query->where('phone1', '=', $phone)
                ->orWhere('phone2', '=', $phone);
        })->where('customer_id', '<>', $id)
            ->where('is_deleted', 0)->first();
    }
    /**
     * Lấy thông tin KH theo loại (cá nhân/ doanh nghiệp)
     *
     * @param $customerType
     * @return mixed
     */
    public function getCustomer($customerType)
    {
        return $this
            ->select(
                "customer_id as id",
                "full_name as name",
                "phone1 as phone",
                "customer_type"
            )
            ->where("customer_type", $customerType)
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("customer_id", "<>", 1)
            ->get();
    }

    /**
     * Lấy thông tin khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getInfoById($customerId)
    {
        return $this
            ->select(
                "{$this->table}.customer_id as id",
                "{$this->table}.full_name",
                "{$this->table}.customer_code",
                "{$this->table}.gender",
                "{$this->table}.phone1 as phone",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.address as address",
                "{$this->table}.email",
                "{$this->table}.birthday",
                "{$this->table}.customer_avatar",
                "{$this->table}.point",
                "{$this->table}.zalo",
                "{$this->table}.facebook"
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->first();
    }
    public function createData($data)
    {
        return $this->create($data)->customer_id;
    }
    public function updateData($data, $id)
    {
        return $this->where("customer_id", $id)->update($data);
    }

    /**
     * Lấy thông tin khách hàng bằng sđt
     *
     * @param $phone
     * @return mixed
     */
    public function getCustomerByPhone($phone)
    {
        return $this
            ->select(
                "customer_id as id",
                "full_name as name",
                "phone1 as phone",
                "customer_type",
                "{$this->table}.address",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("phone1", $phone)
            ->where("customer_id", "<>", 1)
            ->first();
    }

    public function getCustomerOption()
    {
        return $this->select('full_name', 'customer_code', 'customer_id', 'phone1', 'customer_type')
            ->where('is_actived', 1)
            ->where('is_deleted', 0)
            ->where('customer_id', '!=', 1)->get()->toArray();
    }

    /**
     * Thêm KH
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_id;
    }
}