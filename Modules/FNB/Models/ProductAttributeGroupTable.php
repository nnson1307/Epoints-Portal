<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 3:50 PM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductAttributeGroupTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_attribute_groups';
    protected $primaryKey = 'product_attribute_group_id';

    protected $fillable = ['product_attribute_group_id', 'product_attribute_group_name', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_deleted', 'is_actived','slug'];

    protected function _getList()
    {
        return $this->select('product_attribute_group_id', 'product_attribute_group_name', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_deleted', 'is_actived')
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->product_attribute_group_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
    // get option
    public function getOption()
    {
        return $this->select('product_attribute_group_id', 'product_attribute_group_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get()->toArray();
    }

    public function testProductAttGroupName($name, $id)
    {
        return $this->where('slug', str_slug($name))
            ->where('product_attribute_group_id', '<>', $id)
            ->where('is_deleted', 0)->first();
    }
    public function getOptionAttributeGroup(array $productAttributeGroupId)
    {
        return $this->select('product_attribute_group_id', 'product_attribute_group_name')
            ->where('is_deleted', 0)->whereNotIn('product_attribute_group_id',$productAttributeGroupId)->get()->toArray();
    }
    /*
     * test is deleted
     */
    public function testIsDeleted($name)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', 1)->first();
    }
    /*
     * edit by name
     */
    public function editByName($name){
        return $this->where('product_attribute_group_name', $name)->update(['is_deleted' => 0]);
    }
}