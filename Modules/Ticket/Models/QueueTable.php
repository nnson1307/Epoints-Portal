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

class QueueTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_queue';
    protected $primaryKey = 'ticket_queue_id';

    protected $fillable = ['ticket_queue_id', 'queue_name','department_id','email', 'description', 'created_by',
        'updated_by', 'created_at', 'updated_at','is_actived'];

    public function staff_created()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','created_by','staff_id');
    }
    
    public function staff_updated()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','updated_by','staff_id');
    }
    public function department()
    {
        return $this->belongsTo('Modules\Ticket\Models\DepartmentTable','department_id','department_id');
    }

    protected function _getList($filters = [])
    {
         
        $query = $this->select('ticket_queue_id', 'department_id' ,'queue_name','email', 'description', 'created_by',
            'updated_by', 'created_at', 'updated_at','is_actived')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("queue_name", "like", "%" . $search . "%")
                    ->orWhere("description", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("created_by", $filters["created_by"]);
        }
        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("created_at", ">=", $startTime);
            $query->whereDate("created_at", "<=", $endTime);
        }
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_queue_id","queue_name")->where("is_actived", 1)->orderBy('queue_name', 'ASC')->get();
        return ($oSelect->pluck("queue_name","ticket_queue_id")->toArray());
    }

    public function testCode($code, $id)
    {
        return $this->where('queue_name', $code)->where('ticket_queue_id', '<>', $id)->first();
    }

    public function add(array $data)
    {
        $exists = $this->where('department_id', '=', $data['department_id'])
        ->where('queue_name', '=' , $data['queue_name'])
        ->first();
        if($exists){
            return -2;
        }
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_queue_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        $exists = $this->where('department_id', '=', $data['department_id'])
        ->where('queue_name', '=' , $data['queue_name'])
        ->where('ticket_queue_id','<>', $id)->first();
        if($exists){
            return 2;
        }
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
        ->where('ticket_queue_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_queue_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }
}