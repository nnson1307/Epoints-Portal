<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\Ticket\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffQueueMapTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_staff_queue_map';
    protected $primaryKey = 'ticket_staff_queue_map_id';

    protected $fillable = ['ticket_staff_queue_map_id', 'ticket_staff_queue_id','ticket_queue_id', 'created_at', 'created_by',
        'updated_by', 'updated_at'];

    protected function _getList($filters = [])
    {
        return null;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }
    public function getName(){
        $oSelect= self::select("ticket_staff_queue_map_id","queue_name")->get();
        return ($oSelect->pluck("queue_name","ticket_staff_queue_map_id")->toArray());
    }
    public function testCode($code, $id)
    {
        return $this->where('queue_name', $code)->where('ticket_staff_queue_map_id', '<>', $id)->first();
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_staff_queue_map_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
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
    public function checkExistEmail($email = '', $id = '')
    {
        $select = $this->where('email', $email)
        ->where('ticket_staff_queue_map_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_staff_queue_map_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }
    /**
     * Xoá tất cả queue phân công
     *
     * @param $ticket_staff_queue_id
     * @return mixed
     */
    public function removeBy($staffQueueId)
    {
        return $this->where("ticket_staff_queue_id", $staffQueueId)->delete();
    }

    /**
     * Lấy queue đã phân công
     *
     * @param $staffQueueId
     * @return mixed
     */
    public function getQueueMap($staffQueueId)
    {
        return $this->where("ticket_staff_queue_id", $staffQueueId)->get();
    }

    /**
     * Lấy queue đã được phân công cho nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getQueueByStaff($staffId)
    {
        return $this
            ->select(
                "{$this->table}.ticket_queue_id",
                "sq.staff_id"
            )
            ->join("ticket_staff_queue as sq", "sq.ticket_staff_queue_id", "=", "{$this->table}.ticket_staff_queue_id")
            ->where("sq.staff_id", $staffId)
            ->get();
    }
}