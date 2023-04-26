<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 5:49 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductAttributeTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_attributes';
    protected $primaryKey = 'product_attribute_id';

    protected $fillable = ['product_attribute_id', 'product_attribute_group_id',
        'product_attribute_label', 'product_attribute_code', 'created_by', 'updated_by',
        'created_at', 'updated_at', 'is_deleted', 'is_actived','slug', 'type'];

    protected function _getList()
    {
        $select = $this->leftJoin('product_attribute_groups', 'product_attribute_groups.product_attribute_group_id', '=', 'product_attributes.product_attribute_group_id')
            ->select('product_attributes.product_attribute_id as proAttId',
                'product_attributes.product_attribute_group_id as proAttrGr',
                'product_attributes.product_attribute_label as proAttrLabel',
                'product_attributes.product_attribute_code as proAttrCode',
                'product_attributes.created_by as createBy', 'product_attributes.updated_by as updateBy',
                'product_attributes.created_at as createdAt', 'product_attributes.updated_at',
                'product_attributes.is_deleted', 'product_attributes.is_actived as isActive',
                'product_attribute_groups.product_attribute_group_name as proAttrGrName')
            ->where('product_attributes.is_deleted', 0)->orderBy($this->primaryKey, 'desc');
        return $select;
    }

    public function getList(array $filter = [])
    {
        $select = $this->leftJoin('product_attribute_groups', 'product_attribute_groups.product_attribute_group_id', '=', 'product_attributes.product_attribute_group_id')
            ->select('product_attributes.product_attribute_id as proAttId',
                'product_attributes.product_attribute_group_id as proAttrGr',
                'product_attributes.product_attribute_label as proAttrLabel',
                'product_attributes.product_attribute_code as proAttrCode',
                'product_attributes.created_by as createBy', 'product_attributes.updated_by as updateBy',
                'product_attributes.created_at as createdAt', 'product_attributes.updated_at',
                'product_attributes.is_deleted', 'product_attributes.is_actived as isActive',
                'product_attribute_groups.product_attribute_group_name as proAttrGrName')
            ->where('product_attributes.is_deleted', 0)->orderBy($this->primaryKey, 'desc');
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (isset($filter['search_keyword'])) {
            $select->where('product_attributes.product_attribute_label', 'like', '%' . $filter['search_keyword'] . '%');
            $select->orWhere('product_attributes.product_attribute_code', 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_keyword'], $filter['page'], $filter['display'], $filter['label']);
        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->product_attribute_id;
    }

    public function createAttribute(array $data)
    {
        return $this->create($data);
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

    // test unique product_attribute_code
    public function testCode($code, $id)
    {
        $select = $this->where('product_attribute_code', $code)
            ->where('product_attribute_id', '<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }

    // test unique product attribute label
    public function testLabel($label, $id)
    {
        $select = $this->where('product_attribute_label', $label)
            ->where('product_attribute_id', '<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }

    // get option
    public function getOption()
    {
        return $this->select('product_attribute_id', 'product_attribute_label', 'product_attribute_group_id')->where('is_deleted', 0)->get();
    }

    public function getProductAttributeByGroup($idGroup)
    {
        return $this->select('product_attribute_id', 'product_attribute_label')->where('product_attribute_group_id', $idGroup)->where('is_deleted', 0)->get()->toArray();
    }

    public function getProductAttributeGroup($attributeId)
    {
        return $this->select('product_attribute_group_id', 'product_attribute_id')->where('product_attribute_id', $attributeId)->first();
    }

    /*
     * Get product attribute where not in
     */
    public function getProductAttributeWhereNotIn(array $data)
    {
        $select = $this->select('product_attribute_id', 'product_attribute_label', 'product_attribute_group_id')->whereNotIn('product_attribute_id', $data)->get();
        return $select;
    }

    /*
     * check exist
     */
    public function checkExist($group, $label, $isDelete)
    {
        $select = $this->where('product_attribute_group_id', $group)
            ->where('slug', str_slug($label))
            ->where('is_deleted', $isDelete)->first();
        return $select;
    }

    // Kiểm tra thuộc tính sản phẩm theo id,  id nhóm và thuộc tính (is_deleted=0)
    public function testEdit($id, $groupId, $label)
    {
        $select = $this->where('product_attribute_id', '<>', $id)
            ->where('product_attribute_group_id', $groupId)
            ->where('slug', str_slug($label))
            ->where('is_deleted', 0)->first();
        return $select;
    }

    public function getListByArrId($arrId){
        return $this
            ->whereIn('product_attribute_id',$arrId)
            ->get();
    }

    public function getAttributeByCode($code){

        return $this
            ->where('product_attribute_code',$code)
            ->first();
    }
}