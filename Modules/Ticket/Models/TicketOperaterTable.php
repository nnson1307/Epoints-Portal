<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2021
 * Time: 15:42
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;

class TicketOperaterTable extends Model
{
    protected $table = "ticket_operater";
    protected $primaryKey = "ticket_operater_id";

    protected $fillable = ['ticket_operater_id', 'ticket_id','name', 'operate_by', 'created_by','created_at', 'updated_at','updated_by'];

    /**
     * Lấy thông tin file của ticket
     *
     * @param $TicketId
     * @return mixed
     */
    public function getTicketOperater($TicketId)
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
        $oSelect= self::select("ticket_operater_id","path")->get();
        return ($oSelect->pluck("path","ticket_operater_id")->toArray());
    }
    
    public function testCode($code, $id)
    {
        return $this->where('path', $code)->where('ticket_operater_id', '<>', $id)->first();
    }

    public function add(array $data)
    {
        $oTicketOperater = $this->create($data);
        return $oTicketOperater->ticket_operater_id;
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
        ->where('ticket_operater_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_operater_id','<>', $id)
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
}