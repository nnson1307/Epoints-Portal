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

class RequestTable extends Model
{
    use ListTableTrait;

    protected $table = 'ticket_issue';
    protected $primaryKey = 'ticket_issue_id';

    protected $fillable = ['ticket_issue_id', 'name','ticket_issue_group_id','level', 'process_time','description','created_by','updated_by','created_at', 'updated_at','is_active'];

    public function staff_created()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','created_by','staff_id');
    }
    public function staff_updated()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','updated_by','staff_id');
    }
    public function ticket_check()
    {
        return $this->belongsTo('Modules\Ticket\Models\TicketTable','ticket_issue_id','ticket_issue_id');
    }
    public function issue_group()
    {
        return $this->belongsTo('Modules\Ticket\Models\RequestGroupTable','ticket_issue_group_id','ticket_issue_group_id');
    }
    protected function _getList(&$filters = [])
    {
        $query = $this->select('ticket_issue_id', 'name','ticket_issue_group_id','level', 'process_time','description','created_by','updated_by','created_at', 'updated_at','is_active')
            ->orderBy($this->primaryKey, 'desc');
        // filters tên
        if (isset($filters["search"]) && $filters["search"]!= "") {
            $search = $filters["search"];
            $query->where("name", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("created_by", $filters["created_by"]);
        }
        // filters level
         if (isset($filters["level"]) && $filters["level"] != "") {
            $query->where("level", $filters["level"]);
        }
        // filters process_time
         if (isset($filters["search_keyword"]) && $filters["search_keyword"] != "") {
            /*
                1 => "Nhỏ hơn 1 giờ",
                2 => "1 giờ - 3 giờ",
                3 => "3 giờ - 5 giờ",
                4 => "Lớn hơn 5 giờ", 
                5 => "1 ngày",
                6 => "2 ngày",
                7 => "Lớn hơn 2 ngày"
            */
            if($filters["search_keyword"] == 1){
                $query->where("process_time","<", 1);
            }elseif($filters["search_keyword"] == 2){
                $query->where('process_time','>=',1);
                $query->where('process_time','<=',3);
            }elseif($filters["search_keyword"] == 3){
                $query->where('process_time', '>=', 3);
                $query->where('process_time','<=', 5);
            }elseif($filters["search_keyword"] == 4){
                $query->where("process_time",">", 5);
            }elseif($filters["search_keyword"] == 5){
                $query->where("process_time", 24);
            }elseif($filters["search_keyword"] == 6){
                $query->where("process_time", 48);
            }elseif($filters["search_keyword"] == 7){
                $query->where("process_time", '>' ,48);
            }
            unset($filters["search_keyword"]);
        }


        // filter ngày tạo
        if (isset($filters["created_at"]) != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("created_at", ">=", $startTime);
            $query->whereDate("created_at", "<=", $endTime);
        }
         if (isset($filters["is_active"]) != "") {
            $query->where("is_active", $filters["is_active"]);
        }
         if (isset($filters["ticket_issue_group_id"]) != "") {
            $query->where("ticket_issue_group_id", $filters["ticket_issue_group_id"]);
        }
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_issue_id","name")->where("is_active", 1)->get();
        return ($oSelect->pluck("name","ticket_issue_id")->toArray());
    }
    
    public function testCode($code, $id)
    {
        return $this->where('queue_name', $code)->where('ticket_issue_id', '<>', $id)->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_issue_id;
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
        ->where('ticket_issue_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_issue_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }
}