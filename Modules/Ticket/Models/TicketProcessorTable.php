<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2021
 * Time: 15:42
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;

class TicketProcessorTable extends Model
{
    protected $table = "ticket_processor";
    protected $primaryKey = "ticket_processor_id";

    protected $fillable = ['ticket_processor_id', 'ticket_id','name', 'process_by', 'created_by','created_at', 'updated_at','updated_by'];

    /**
     * Lấy thông tin file của ticket
     *
     * @param $TicketId
     * @return mixed
     */
    public function getTicketProcessor($TicketId)
    {
        return $this
            ->where("ticket_id", $TicketId)
            ->get();
    }

    protected function _getList($filters = [])
    {
        return null;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_processor_id","path")->get();
        return ($oSelect->pluck("path","ticket_processor_id")->toArray());
    }
    
    public function testCode($code, $id)
    {
        return $this->where('path', $code)->where('ticket_processor_id', '<>', $id)->first();
    }

    public function add(array $data)
    {
        $oTicketProcessor = $this->create($data);
        return $oTicketProcessor->ticket_processor_id;
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
        ->where('ticket_processor_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_processor_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }

    /**
     * Xoá tất cả file của ticket
     *
     * @param $TicketId
     * @return mixed
     */
    public function removeFile($TicketId)
    {
        return $this->where("ticket_id", $TicketId)->delete();
    }

    public function getListProcessor($ticketId){
        $oSelect = $this
            ->select('process_by as staff_id')
            ->where('ticket_id',$ticketId)
            ->get();
        return $oSelect;
    }

    /**
     * Lấy nhân viên xử lý ticket
     *
     * @param $ticketId
     * @return mixed
     */
    public function getListProcessorByTicket($ticketId)
    {
        return $this
            ->select(
                "{$this->table}.process_by",
                "s.full_name as staff_name"
            )
            ->join("staffs as s", 's.staff_id', "=", "{$this->table}.process_by")
            ->where("{$this->table}.ticket_id", $ticketId)
            ->get();
    }
}