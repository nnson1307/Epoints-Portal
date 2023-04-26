<?php

/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:10 CH
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * ServiceGroupTable
 * @author thachleviet
 * @since March 13, 2018
 */
class CustomerGroupTable extends Model
{
    use ListTableTrait;

    /*
     * table service_package
     */
    protected $table = 'customer_groups';
    protected $primaryKey = 'customer_group_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = [
        'customer_group_id',
        'group_name',
        'created_at',
        'updated_at',
        'is_deleted',
        'created_by',
        'updated_by',
        'is_actived',
        'slug',
        'group_uuid'
    ];

    /*
     * get list
     */
    protected function _getList($filter = [])
    {
        return $this
            ->select(
                'customer_group_id',
                'group_name',
                'created_at',
                'is_actived')
            ->where('is_deleted', 0)
            ->orderBy($this->primaryKey, 'desc');
    }

    // function remove item
    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    /*
     * function edit
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
    /*
     * function save
     */

    /*
     * function add
     */
    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->customer_group_id;
    }

    /*
     * function getItem
     */
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
    public function getOption()
    {
        return $this
            ->select(
                'customer_group_id',
                'group_name'
            )
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get()
            ->toArray();
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->where('slug', str_slug($name))->where('customer_group_id', '<>', $id)->where('is_deleted', 0)->first();
    }
    /*
     * test group name
     */
    public function testGroupName($name)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', 0)->first();
    }
    /*
     * test is delete.
     */
    public function testIsDeleted($name)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', 1)->first();
    }
    /*
     * edit by name
     */
    public function editByName($name)
    {
        return $this->where('group_name', $name)->update(['is_deleted' => 0]);
    }
    /*
     * delete by name
     */
    public function deleteByName($name)
    {
        return $this->where('group_name', $name)->delete();
    }
}
