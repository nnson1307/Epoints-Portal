<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 10:25 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class OrderSourceTable extends Model
{
    use ListTableTrait;
    protected $table = 'order_sources';
    protected $primaryKey = 'order_source_id';

    protected $fillable = ['order_source_id', 'order_source_name', 'is_actived',
        'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at','slug'];

    protected function _getList()
    {
        return $this->select('order_source_id', 'order_source_name', 'is_actived',
            'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at')
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->customer_source_id;
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

    public function getName()
    {
        $oSelect = self::select("order_source_id", "order_source_name")->where('is_deleted', 0)->get();
        return (["" => "Tất cả"]) + ($oSelect->pluck("order_source_name", "order_source_id")->toArray());
    }

    /*
     * check oder source
     */
    public function check($name)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', 0)->first();
    }

    /*
     * check oder source edit
     */
    public function checkEdit($id, $name)
    {
        return $this->where('order_source_id', '<>', $id)->where('order_source_name', $name)->where('is_deleted', 0)->first();
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
        return $this->where('order_source_name', $name)->update(['is_deleted' => 0]);
    }

    public function getOption()
    {
        return $this->select('order_source_id', 'order_source_name')->where('is_deleted', 0)->get()->toArray();
    }
}