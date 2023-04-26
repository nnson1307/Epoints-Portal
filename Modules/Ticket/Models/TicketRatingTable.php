<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2021
 * Time: 15:42
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;

class TicketRatingTable extends Model
{
    protected $table = "ticket_rating";
    protected $primaryKey = "ticket_rating_id";

    protected $fillable = ['ticket_rating_id', 'ticket_id','point','description', 'created_at', 'created_by','updated_at','updated_by'];

    /**
     * Lấy thông tin file của ticket
     *
     * @param $TicketId
     * @return mixed
     */
    public function getTicketQueueMapTable($TicketId)
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
        $oSelect= self::select("ticket_rating_id","point")->get();
        return ($oSelect->pluck("point","ticket_rating_id")->toArray());
    }
    
    public function testCode($code, $id)
    {
        return $this->where('path', $code)->where('ticket_rating_id', '<>', $id)->first();
    }

    public function add(array $data)
    {
        $oticketRating = $this->create($data);
        return $oticketRating->ticket_rating_id;
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
        ->where('ticket_rating_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_rating_id','<>', $id)
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