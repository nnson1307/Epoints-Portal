<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ShiftTable extends Model
{
    use ListTableTrait;
    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';

    protected $fillable = ['shift_id', 'shift_code', 'start_time', 'end_time', 'created_by',
        'updated_by', 'created_at', 'updated_at', 'is_actived', 'is_deleted'];

    protected function _getList()
    {
        return $this->select('shift_id', 'shift_code', 'start_time', 'end_time', 'created_by',
            'updated_by', 'created_at', 'updated_at', 'is_actived')
            ->where('is_deleted', 0)
            ->orderBy($this->primaryKey, 'desc');
    }

    public function testCode($code, $id)
    {
        return $this->where('shift_code', $code)->where('shift_id', '<>', $id)->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->shift_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted'=>1]);
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
    * check exist
    */
    public function checkExist($startTime, $endTime,$isDelete)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('is_deleted', $isDelete)->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('shift_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }
}