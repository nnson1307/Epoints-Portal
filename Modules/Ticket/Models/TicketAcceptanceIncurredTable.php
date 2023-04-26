<?php


namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketAcceptanceIncurredTable extends Model
{
    protected $table = "ticket_acceptance_incurred";
    protected $primaryKey = "ticket_acceptance_incurred_id";

//    Tạo biên bản nghiệm thu
    public function createdTicketAcceptance($data){
        return $this->insert($data);
    }

//    Chỉnh sửa biên bản nghiệm thu
    public function editTicketAcceptance($data,$id){
        return $this->where('ticket_acceptance_id',$id)->update($data);
    }

//    Xoá vật tư phát sinh
    public function deleteTicketAcceptance($ticket_acceptance_id){
        return $this->where('ticket_acceptance_id',$ticket_acceptance_id)->delete();
    }

    public function getAcceptanceNew($code){
        $oSelect = $this
            ->where('ticket_acceptance_code','like','%'.$code.'%')
            ->orderBy('ticket_acceptance_id','DESC')
            ->first();

        return $oSelect != null ? $oSelect['ticket_acceptance_code'] : null;
    }

//    lấy danh sách vật tư phát sinh
    public function getListProduct($ticket_acceptance_id){
        return $this
            ->select(
                'ticket_acceptance_incurred_id',
                'ticket_acceptance_id',
                'product_id',
                'product_code',
                'product_name',
                'quantity',
                'unit_name',
                'money',
                'status'
            )
            ->where('ticket_acceptance_id',$ticket_acceptance_id)
            ->get();

    }
//    lấy danh sách vật tư phát sinh by ticket id
    public function listIncurredByTicketId($ticket_id){
        return $this
            ->select(
                "{$this->table}.ticket_acceptance_incurred_id",
                "{$this->table}.ticket_acceptance_id",
                "{$this->table}.product_id",
                "{$this->table}.product_code",
                "{$this->table}.product_name",
                "{$this->table}.quantity",
                "{$this->table}.unit_name",
                "{$this->table}.money",
                "{$this->table}.status"
            )
            ->leftjoin("ticket_acceptance as p1","p1.ticket_acceptance_id","{$this->table}.ticket_acceptance_id")
            ->where('p1.ticket_id',$ticket_id)
            ->get();

    }
//    lấy danh sách vật tư phát sinh hoàn ứng
    public function getListTicketAcceptanceIncurred($ticket_id){
        return $this
            ->select(
                "{$this->table}.ticket_acceptance_incurred_id",
                "{$this->table}.product_name",
                "{$this->table}.quantity",
                "{$this->table}.unit_name",
                "{$this->table}.money",
                "p1.ticket_id"
            )
            ->join("ticket_acceptance as p1","p1.ticket_acceptance_id","{$this->table}.ticket_acceptance_id")
            ->where('p1.ticket_id',$ticket_id)
            ->get();
    }
    
    public function getResultRefund($array_ticket){
        return $this
            ->select(
                "{$this->table}.ticket_acceptance_incurred_id",
                "{$this->table}.product_name",
                "{$this->table}.quantity",
                "{$this->table}.unit_name",
                "{$this->table}.money",
                "p1.ticket_id",
                'ticket_refund_item.quantity as quantity_refund',
                'ticket_refund_item.money as money_refund',
            )
            ->leftJoin("ticket_acceptance as p1","p1.ticket_acceptance_id","{$this->table}.ticket_acceptance_id")
            ->leftJoin('ticket_refund_item','ticket_refund_item.obj_id',$this->table.'.ticket_acceptance_incurred_id')
            ->whereIn('p1.ticket_id',$array_ticket)
            ->where("ticket_refund_item.money",">",0)
            ->get();

    }

}