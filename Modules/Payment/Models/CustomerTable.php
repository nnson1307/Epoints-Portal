<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

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
                'customer_id as accounting_id',
                'full_name as accounting_name',
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
                'customers.customer_group_id as customer_group_id',
                'group.group_name as group_name',
                'customers.full_name as full_name',
                'customers.customer_code as customer_code',
                'customers.gender as gender',
                'customers.phone1 as phone1',
                'province.name as province_name',
                'province.type as province_type',
                'district.name as district_name',
                'district.type as district_type',
                'customers.address as address',
                'customers.email as email',
                'customers.customer_source_id as customer_source_id',
                'customers.birthday as birthday',
                'source.customer_source_name',
                'customers.customer_refer_id',
                'customers.facebook as facebook',
                'customers.zalo as zalo',
                'customers.note as note',
                'customers.customer_id as customer_id',
                'customers.is_actived as is_actived',
                'customers.phone2 as phone2',
                'customers.customer_avatar as customer_avatar',
                'customers.created_at as created_at',
                'customers.account_money',
                'customers.province_id as province_id',
                'customers.district_id as district_id',
                'customers.point as point',
                'customers.member_level_id as member_level_id',
                'member_levels.name as member_level_name',
                'customers.point as point',
                'member_levels.discount as member_level_discount',
                "{$this->table}.postcode",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10",
                "{$this->table}.total_commission"
            )
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('customer_sources as source', 'source.customer_source_id', '=', 'customers.customer_source_id')
            ->leftJoin('province', 'province.provinceid', '=', 'customers.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customers.district_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->where('customers.customer_id', $customerId)
//            ->where('customers.is_actived', self::IS_ACTIVE)
//            ->where('customers.is_deleted', self::NOT_DELETE)
            ->first();
    }

    /**
     * Chỉnh sửa khách hàng
     *
     * @param array $data
     * @param $customerId
     * @return mixed
     */
    public function edit(array $data, $customerId)
    {
        return $this->where("customer_id", $customerId)->update($data);
    }
}