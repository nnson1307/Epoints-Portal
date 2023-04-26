<?php

/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 13/03/2018
 * Time: 1:10 CH
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;


class CustomerSourceTable extends Model
{
    use ListTableTrait;

    /*
     * table customer_sources
     */
    protected $table = 'customer_sources';
    protected $primaryKey = 'customer_source_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = [
        'customer_source_id', 'customer_source_name',
        'customer_source_type', 'is_actived', 'is_deleted', 'created_by',
        'updated_by', 'created_at', 'updated_at', 'slug'
    ];

    /*
     * Build query table
     * @author doan thi huynh nhu
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList($filter = [])
    {
        $ds = $this
            ->select(
                'customer_source_id',
                'customer_source_name',
                'customer_source_type',
                'is_actived',
                'is_deleted',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at'
            )
            ->where('is_deleted', 0)
            ->orderBy($this->primaryKey, 'desc');

        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('customer_source_name', 'like', '%' . $search . '%');
            });
        }

        unset($filter['search']);

        return $ds;
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
     * function add
     */
    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->customer_source_id;
    }

    /*
     * function getItem
     */
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }


    public function getOptionCustomerSource()
    {
        return $this->select('customer_source_id', 'customer_source_name')->where('is_deleted', 0)->get();
    }

    /*
     * test customer source add
     */
    public function testCustomerSourceName($customerSourceName)
    {
        return $this->where('slug', str_slug($customerSourceName))->where('is_deleted', 0)->first();
    }

    /*
     * test customer source edit
     */
    public function testCustomerSourceNameEdit($id, $customerSourceName)
    {
        return $this->where('customer_source_id', '<>', $id)->where('customer_source_name', $customerSourceName)->where('is_deleted', 0)->first();
    }

    /*
     * add update customer source
     */
    public function testIsDeleted($customerSourceName)
    {
        return $this->where('slug', str_slug($customerSourceName))->where('is_deleted', 1)->first();
    }

    /*
     * edit by customer source name
     */
    public function editByName($customerSourceName)
    {
        return $this->where('customer_source_name', $customerSourceName)->update(['is_deleted' => 0]);
    }
}
//