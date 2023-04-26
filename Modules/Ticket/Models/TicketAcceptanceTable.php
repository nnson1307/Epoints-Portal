<?php


namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketAcceptanceTable extends Model
{
    protected $table = "ticket_acceptance";
    protected $primaryKey = "ticket_acceptance_id";

//    Tạo biên bản nghiệm thu
    public function createdTicketAcceptance($data){
        return $this->insertGetId($data);
    }

//    Chỉnh sửa biên bản nghiệm thu
    public function editTicketAcceptance($data,$id){
        return $this->where('ticket_acceptance_id',$id)->update($data);
    }

    public function getAcceptanceNew($code){
        $oSelect = $this
            ->where('ticket_acceptance_code','like','%'.$code.'%')
            ->orderBy('ticket_acceptance_id','DESC')
            ->first();

        return $oSelect != null ? $oSelect['ticket_acceptance_code'] : null;
    }

//    Lấy danh sách biên bản nghiệm thu
    public function getListAcceptance($ticketId){
        return $this
            ->select(
                $this->table.'.ticket_acceptance_id',
                $this->table.'.ticket_acceptance_code',
                $this->table.'.ticket_id',
                'ticket.ticket_code',
                $this->table.'.title',
                $this->table.'.sign_by',
                $this->table.'.sign_date',
                $this->table.'.status',
                'customers.full_name as customer_name',
                'staffs.full_name as created_name'
            )
            ->join('ticket','ticket.ticket_id',$this->table.'.ticket_id')
            ->join('customers','customers.customer_id',$this->table.'.customer_id')
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.ticket_id',$ticketId)
            ->get();
    }
    
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}