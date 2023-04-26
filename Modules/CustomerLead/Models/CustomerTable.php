<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 2:34 PM
 */

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class CustomerTable extends Model
{
    use ListTableTrait;
    protected $table = "customers";
    protected $primaryKey = "customer_id";
    protected $fillable = [
        "customer_id", "branch_id", "customer_group_id", "full_name", "birthday",
        "gender", "phone1", "phone2", "email", "facebook", "province_id",
        "district_id", "address", "customer_source_id", "customer_refer_id", "customer_avatar",
        "note", "date_last_visit", "is_actived", "is_deleted", "created_by", "updated_by",
        "created_at", "zalo", "updated_at", "account_money", "customer_code",
        "point", "member_level_id", "password", "phone_verified", 'customer_type',
        'tax_code',
        'representative',
        'hotline',
    ];

    /**
     * Lấy thông tin khách hàng
     *
     * @param $phone
     * @return mixed
     */
    public function getCustomerByPhone($phone)
    {
        return $this->where("phone1", $phone)->where("customer_id", "<>", 1)->first();
    }

    /**
     * Thêm khách hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_id;
    }

    /**
     * Search trong danh sách customer
     *
     * @param $data
     * @return mixed
     */
    public function getCustomerSearch($data)
    {
        $select = $this
            ->select(
                'customers.customer_id',
                'customers.customer_group_id',
                'customers.full_name',
                'customers.phone1',
                'customers.customer_avatar',
                'customers.account_money',
                'customers.address',
                'customers.postcode',
                'customers.customer_code',
                'group.group_name as group_name'
            )
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin("customer_branch as cb", "cb.customer_id", "=", "{$this->table}.customer_id")
            ->where(function ($query) use ($data) {
                $query->where("{$this->table}.full_name", 'like', '%' . $data . '%')
                    ->orWhere("{$this->table}.phone1", 'like', '%' . $data . '%');
            })
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->groupBy("{$this->table}.customer_id");

        //Phân quyền xem khách hàng theo chi nhánh
        if (Auth::user()->is_admin != 1 && in_array('permission-customer-branch',session('routeList'))) {
            $select->where("cb.branch_id", Auth::user()->branch_id);
        }

        return $select->paginate(6);
    }

    /**
     * lay thong tin khach hang theo ma khach hang
     *
     * @param $customerCode
     * @return mixed
     */
    public function getCustomerByCode($customerCode)
    {
        $select = $this->select(
            "customer_id",
            "branch_id",
            "customer_group_id",
            "full_name",
            "birthday",
            "gender",
            "phone1",
            "province_id",
            "district_id",
            "address",
            "customer_source_id",
            "account_money",
            "customer_code",
            "customer_type"
        )
            ->where('customer_code', $customerCode)
            ->where('is_actived', 1)
            ->where('is_deleted', 0);
        return $select->first();
    }

    /**
     * lay cac option khach hang
     *
     * @return mixed
     */
    public function getOption()
    {
        $ds = $this
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.customer_code",
                "{$this->table}.customer_id",
                "{$this->table}.phone1"
            )
            ->leftJoin("customer_branch as cb", "cb.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.customer_id", '!=', 1)
            ->groupBy("{$this->table}.customer_id");

        //Phân quyền xem khách hàng theo chi nhánh
        if (Auth::user()->is_admin != 1 && in_array('permission-customer-branch',session('routeList'))) {
            $ds->where("cb.branch_id", Auth::user()->branch_id);
        }

        return $ds->get()->toArray();
    }

    /**
     * lay thong tin khach hang
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $select = $this
            ->select('customers.customer_group_id as customer_group_id',
                'group.group_name as group_name',
                'customers.full_name as full_name',
                'customers.branch_id as branch_id',
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
                'customers.account_money as account_money',
                'customers.province_id as province_id',
                'customers.district_id as district_id',
                'customers.point as point',
                'customers.member_level_id as member_level_id',
                'member_levels.name as member_level_name',
                'customers.point as point',
                'member_levels.discount as member_level_discount',
                "{$this->table}.postcode"
            )
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('customer_sources as source', 'source.customer_source_id', '=', 'customers.customer_source_id')
            ->leftJoin('province', 'province.provinceid', '=', 'customers.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customers.district_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->where('customers.customer_id', $id);
        return $select->first();
    }
    public function edit(array $data, $id)
    {
        return $this->where('customer_id', $id)->update($data);
    }
    public function getCustomerByCustomerCode($customerCode)
    {
        $select = $this
            ->select(
                'group.group_name as group_name',
                'customers.full_name as full_name',
                'customers.phone1 as phone',
                'customers.email as email',
                'customers.gender as gender',
                'customers.address as address',
                'source.customer_source_name',
                'customers.facebook as fanpage',
                'customers.zalo as zalo',
                'customers.customer_avatar as avatar'
            )
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('customer_sources as source', 'source.customer_source_id', '=', 'customers.customer_source_id')
            ->where('customers.customer_code', $customerCode);
        return $select->first();
    }
}