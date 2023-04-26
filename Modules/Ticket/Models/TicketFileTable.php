<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2021
 * Time: 15:42
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;

class TicketFileTable extends Model
{
    protected $table = "ticket_file";
    protected $primaryKey = "ticket_file_id";

    protected $fillable = ['ticket_file_id', 'ticket_id','type', 'path','group','created_by','created_at', 'note'];

    /**
     * Lấy thông tin file của ticket
     *
     * @param $TicketId
     * @return mixed
     */
    public function getTicketFile($TicketId,$group)
    {
        return $this
            ->where("ticket_id", $TicketId)
            ->where("group", $group)
            ->get();
    }

    protected function _getList($filters = [])
    {
        return null;
    }

    public function getAll($group)
    {
        return $this
            ->where('group',$group)
            ->orderBy($this->primaryKey, 'desc')
            ->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_file_id","path")->get();
        return ($oSelect->pluck("path","ticket_file_id")->toArray());
    }
    
    public function testCode($code, $id)
    {
        return $this->where('path', $code)->where('ticket_file_id', '<>', $id)->first();
    }

    public function add(array $data)
    {
        $oTicketFile = $this->create($data);
        return $oTicketFile->ticket_file_id;
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
        ->where('ticket_file_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_file_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }

    /**
     * Xoá tất cả file của ticket
     *
     * @param $TicketId
     * @return mixed
     */
    public function removeFile($TicketId,$group = 'file')
    {
        return $this->where("ticket_id", $TicketId)->where("group", $group)->delete();
    }

    public function removeFileAcceptance($TicketId,$group = 'image')
    {
        return $this->where("ticket_id", $TicketId)->where("group", $group)->delete();
    }

    public function createFile($data){
        return $this->insert($data);
    }
    public function getFileRefundByTicketId($id)
    {
        return $this->select("path as path_file")
        ->orWhere("ticket_id", $id)
        ->where("group", 'acceptance')
        ->get()->toArray();
    }
}