<?php

/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:26 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerGroupFilterTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_group_filter';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
    = [
        'id',
        'name',
        'is_active',
        'filter_group_type',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'filter_condition_rule_A',
        'filter_condition_rule_B',
        'is_deleted',
        'is_survey'
    ];

    public function add(array $data)
    {
        $oSelect = $this->create($data);
        return $oSelect->id;
    }

    public function _getList(&$filters = [])
    {
        $select = $this->select($this->fillable)
            ->where('is_deleted', 0)
            ->where('is_survey' , null)
            ->orderBy('id', 'desc');
        if (isset($filters['group_type']) != '') {
            $select->where("filter_group_type", $filters['group_type']);
            unset($filters['group_type']);
        }
        if (isset($filters['name']) != '') {
            $select->where("name", 'like', '%' . $filters['name'] . '%');
            unset($filters['name']);
        }
        return $select;
    }

    public function getItem($id)
    {
        $result = $this->where('id', $id);

        return $result->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function getCustomerGroupDefine()
    {
        $select = $this->select('id', 'name')
            ->where('is_deleted', 0)
            ->where('filter_group_type', 'user_define')
            ->orderBy('id', 'desc')
            ->get();
        return $select;
    }
    public function getOptionByType($type)
    {
        $select = $this->select(
            "id",
            "name"
        )->where("filter_group_type", "=", $type)
            ->where("is_active", "=", "1");
        return $select->get()->toArray();
    }

    /**
     * Xoá nhóm KH tự động
     *
     * @param $id
     * @return mixed
     */
    public function deleteGroup($id)
    {
        return $this->where("id", $id)->delete();
    }

    /**
     * Lấy danh sách nhóm khách hàng phân trang 
     */

    public function getAllGroups($filters = [])
    {

        $select =  $this->where('is_deleted', 0)
            ->orderBy('id', 'desc');
        if (isset($filters['where_in'])) {
            $select->whereIn('id', $filters['where_in']);
            unset($filters['where_in']);
        }
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filters['perpage']);
        unset($filters['page']);
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
