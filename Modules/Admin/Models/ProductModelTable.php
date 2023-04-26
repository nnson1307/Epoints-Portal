<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/28/2018
 * Time: 4:50 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductModelTable extends Model
{
    use ListTableTrait;
    /*
    * table service_package
    */
    protected $table = 'product_model';
    protected $primaryKey = 'product_model_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = ['product_model_id', 'product_model_name', 'product_model_note', 'created_at', 'updated_at', 'is_deleted','slug'];

    /*
     * Get list
     */
    protected function _getList()
    {
        return $this->select('product_model_id', 'product_model_name', 'product_model_note', 'created_at', 'updated_at')
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
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
        return $oCustomerGroup->product_model_id;
    }

    /*
     * function add
     */
    public function createBrand(array $data)
    {
        return $this->create($data);
    }

    /*
     * function getItem
     */
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
    * function get all
    */
    public function getAll()
    {
        return $this->select('product_model_id', 'product_model_name')
            ->where('is_deleted', 0)
            ->get();
    }

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->where($this->primaryKey, '<>', $id)->where('is_deleted', 0)->get();
    }

    //Kiểm tra tồn tại của nhãn sp.
    public function check($name, $isDelete)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', $isDelete)->first();
    }

    /*
    * Cập nhật với tên nhãn
    */
    public function editByName($name)
    {
        return $this->where('slug', str_slug($name))->update(['is_deleted' => 0]);
    }

    /*
   * check unique.
   */
    public function checkEdit($id, $name){
        return $this->where('product_model_id','<>',$id)->where('product_model_name',$name)->where('is_deleted', 0)->first();
    }

    public function getProductModelByName($name){
        return $this->select()
            ->where("product_model_name", $name)
            ->first();
    }
}