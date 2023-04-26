<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 1:05 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class SupplierTable extends Model
{
    use ListTableTrait;
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    protected $fillable = ['supplier_id', 'supplier_name', 'description', 'is_deleted', 'updated_at', 'created_at', 'address', 'contact_name', 'contact_title', 'contact_phone','slug'];

    protected function _getList()
    {
        return $this->select('supplier_id', 'supplier_name', 'description', 'is_deleted', 'updated_at', 'created_at', 'address', 'contact_name', 'contact_title', 'contact_phone')
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->supplier_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

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
   * function get all
   */
    public function getAll()
    {
        return $this->select('supplier_id', 'supplier_name')
            ->where('is_deleted', 0)->get();
    }

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->where($this->primaryKey, '<>', $id)->get();
    }

    /*
     * check supplier
     */
    public function check($id, $name)
    {
        $res = $this->where('supplier_id', '<>', $id)->where('slug', str_slug($name))
            ->where('is_deleted', 0)->first();
        return $res;
    }
    /*
    * check exist
    */
    public function checkExist($name,$isDelete)
    {
        $select = $this->where('slug', str_slug($name))
            ->where('is_deleted', $isDelete)->first();
        return $select;
    }

    /**
     * Lấy thông tin nhà cung cấp - dùng làm history
     *
     * @param $supplierId
     * @return mixed
     */
    public function getInfo($supplierId)
    {
        return $this
            ->select(
                'supplier_id',
                'supplier_name',
                'contact_name',
                'contact_title',
                'contact_phone'
            )
            ->where('supplier_id', $supplierId)
            ->first();;
    }
}