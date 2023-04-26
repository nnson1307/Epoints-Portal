<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 13/03/2018
 * Time: 13:32
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductUnitTable extends Model
{
    use ListTableTrait;
    protected $table ='product_unit';
    protected $primaryKey  = "product_unit_id";

    protected $fillable = ['product_unit_id', 'product_unit_name', 'product_unit_description', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'];
    protected function _getList()
    {
        return $this->select('product_unit_id', 'product_unit_name', 'product_unit_description', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by' )->where('is_delete','=',0)->orderBy('product_unit_id', 'desc');
    }


    /**
     * Xoa ProductUnitTable
     *
     * @param number $id'
     */
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_delete'=>1]);
    }


    /**
     * Insert user to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oProduct = $this->create($data);


        return $oProduct->product_unit_id;
    }

    /**
     * sửa product-unit
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);

    }

    public function getItem($id){
        return  $this->where($this->primaryKey,$id)->first();
    }
}