<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * Product Label Model
 *
 * @author ledangsinh
 * @since march 13, 2018
 */

class ProductLabelTable extends Model
{
    use ListTableTrait;

    protected $table='product_label';
    protected $primaryKey='product_label_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=['product_label_id', 'product_label_name', 'product_label_code', 'product_label_description', 'is_active', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     * Build query table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    protected function _getList()
    {
        return $this->select('product_label_id', 'product_label_name', 'product_label_code', 'is_active', 'created_at', 'updated_at', 'product_label_description')->where('is_delete',0);
    }

    /**
     * Insert product label to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {

        $oProduct = $this->create($data);
        return $oProduct->id;
    }

    /**
     * Edit product label to database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Remove product label to database
     *
     * @param number $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_delete'=>1]);
    }

    /**
     * Get item
     * @param $id
     * @return mixed
     */

    public function getItem($id)
    {
            return $this->where($this->primaryKey,$id)->first();
    }
}