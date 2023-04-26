<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/05/2021
 * Time: 15:58
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerTable extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_id',
        'branch_id',
        'customer_group_id',
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
    const IS_ACTIVE = 1;
    const IS_DELETE = 0;
    /**
     * Cập nhật thông tin khách hàng
     *
     * @param array $data
     * @param $customerId
     * @return mixed
     */
    public function edit(array $data, $customerId)
    {
        return $this->where("customer_id", $customerId)->update($data);
    }

    /**
     * Lấy danh sách khách hàng 
     * @param array $filters
     * @return mixed
     */
    public function getListCore($filters)
    {
        $select = $this->select(
            "{$this->table}.customer_id",
            "{$this->table}.customer_group_id",
            "{$this->table}.full_name",
            "{$this->table}.customer_code",
            "{$this->table}.phone1",
            "{$this->table}.phone2",
            "{$this->table}.address",
            "{$this->table}.customer_type",
            "{$this->table}.is_deleted",
            "{$this->table}.is_actived",
            "customer_groups.group_name as nameGroup"
        );
        $select->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_actived", 1);
        if (isset($filters['not_in'])) {
            $select->whereNotIn('customer_id', $filters['not_in']);
            unset($filters['not_in']);
        }
        if (isset($filters['where_in'])) {
            $select->whereIn('customer_id', $filters['where_in']);
            unset($filters['where_in']);
        }

        if (!empty($filters['nameOrCode'])) {

            $nameOrCode = $filters['nameOrCode'];
            $select->where(function ($query) use ($nameOrCode) {
                $query->where("full_name", 'like', '%' . $nameOrCode . '%');
                $query->orWhere("customer_code", $nameOrCode);
            });
            unset($filters['nameOrCode']);
        }
        $select->leftJoin("customer_groups", function ($join) {
            $join->on("{$this->table}.customer_group_id", "=", "customer_groups.customer_group_id");
        });

        return $select;
    }
    /**
     * Lấy danh sách khách hàng và phân trang tránh conflict với mycore
     * @param $filters
     * @return mixed
     * 
     */
    public function getListNew(array $filters = [])
    {
        $select = $this->getListCore($filters);
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filters['perpage']);
        unset($filters['page']);
        if (isset($filters['nameOrCode'])) unset($filters['nameOrCode']);
        if (isset($filters['status'])) unset($filters['status']);
        if ($filters) {
            // filter list
            foreach ($filters as $key => $val) {
                if (is_array($val) || trim($val) == '' || trim($val) == null) {
                    continue;
                }
                if (strpos($key, 'keyword_') !== false) {
                    $select->where(str_replace('$', '.', str_replace('keyword_', '', $key)), 'like', '%' . $val . '%');
                } elseif (strpos($key, 'sort_') !== false) {
                    $select->orderBy(str_replace('$', '.', str_replace('sort_', '', $key)), $val);
                } else {
                    $select->where(str_replace('$', '.', $key), $val);
                }
            }
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}
