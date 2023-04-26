<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 11:58 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductCategoryTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_categories';
    protected $primaryKey = 'product_category_id';

    protected $fillable = [
        'product_category_id',
        'category_name',
        'category_code',
        'description',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'slug',
        'icon_image'
    ];

    protected function _getList()
    {
        return $this
            ->select(
                'product_category_id',
                'category_name',
                'description',
                'is_actived',
                'is_deleted',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at'
            )
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
    }

    //Add product category
    public function add(array $data)
    {
        $productCategory = $this->create($data);
        return $productCategory->product_category_id;
    }

    //Add product category
    public function createCategory(array $data)
    {
        return $this->create($data);
    }

    /*
     * Delete product category
     */
    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    /*
     * Edit product category
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /*
     * get item
     */

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
     * get all product category
     */
    public function getAll()
    {
        return $this->select('product_category_id', 'category_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }

    /*
     * test product category name
     */
    public function testProductCategoryName($id, $name)
    {
        return $this->where('slug', str_slug($name))->where('product_category_id', '<>', $id)->where('is_deleted', 0)->first();
    }

    /*
     * test product category name
     */
    public function checkProductCategoryCode($id, $code)
    {
        return $this->where('category_code', $code)->where('product_category_id', '<>', $id)->where('is_deleted', 0)->first();
    }

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->where($this->primaryKey, '<>', $id)->where('is_deleted', 0)->get();
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
    public function editByName($name)
    {
        return $this->where('slug', str_slug($name))->update(['is_deleted' => 0]);
    }

    public function getCategoryByName($name){
        return $this->select()
                    ->where("category_name", $name)
                    ->first();
    }
}