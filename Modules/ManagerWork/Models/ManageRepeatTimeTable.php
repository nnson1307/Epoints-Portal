<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ManageRepeatTimeTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_repeat_time';
    protected $primaryKey = 'manage_repeat_time_id';

    protected $fillable = ['manage_repeat_time_id', 'manage_work_id', 'time','created_by',
        'updated_by', 'created_at', 'updated_at'];

    /**
     * Thêm thời gian
     * @param $data
     * @return mixed
     */
    public function createRepeatTime($data){
        return $this->insertGetId($data);
    }

    public function insertTime($data){
        return $this->insert($data);
    }

    /**
     * Xoá thời gian theo công việc
     * @param $manage_work_id
     */
    public function removeRepeatTime($manage_work_id){
        return $this->where('manage_work_id',$manage_work_id)->delete();
    }

    /**
     * Lấy thời gian theo công việc
     */
    public function listTimeWork($manage_work_id){
        $oSelect = $this
            ->select('manage_repeat_time_id','manage_work_id','time')
            ->where('manage_work_id',$manage_work_id)
            ->get();

        if (count($oSelect) != 0){
            $oSelect = collect($oSelect)->pluck('time')->toArray();
        }

        return $oSelect;
    }

    protected function _getList($filters = [])
    {
        $query = $this->select('manage_repeat_time_id', 'manage_work_id', 'time','created_by',
            'updated_by', 'created_at', 'updated_at')
            ->orderBy($this->primaryKey, 'desc');
        return $query;
    }

    public function getName(){
        $oSelect= self::select("manage_repeat_time_id","manage_work_id")->get();
        return ($oSelect->pluck("manage_work_id","manage_repeat_time_id")->toArray());
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_repeat_time_id;
    }

    public function remove($manageWorkId)
    {
        return $this->where('manage_work_id', $manageWorkId)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where('manage_work_id', $id)->first();
    }

    /**
     * Lấy thời gian theo công việc
     */
    public function listTimeWorkProject($manage_work_id){
        $oSelect = $this
            ->select('manage_repeat_time_id','manage_work_id','time')
            ->where('manage_work_id',$manage_work_id)
            ->get();

        if (count($oSelect) != 0){
            $oSelect = collect($oSelect)->pluck('time', 'time')->toArray();
        }

        return $oSelect;
    }

}
