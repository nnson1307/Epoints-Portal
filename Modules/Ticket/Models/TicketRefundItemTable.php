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

class TicketRefundItemTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_refund_item';
    protected $primaryKey = 'ticket_refund_item_id';

    protected $fillable = ['ticket_refund_item_id', 'ticket_refund_map_id','ticket_id','type','obj_id','note','quantity','money','created_at','updated_by', 'created_by', 'updated_at'];

    public function add(array $data)
    {
        return $this->create($data);
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function removeByTicketReFundMapId($id)
    {
        return $this->where('ticket_refund_map_id', $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function removeByRefundId($refund_id)
    {
        return $this->where('ticket_refund_id', $refund_id)->delete();
    }
    public function getRefundItemByRefundId($refund_id)
    {
        return $this->select(
            "{$this->table}.*",
            'product_childs.product_code as product_code',
            'product_childs.unit_id as unit_id'
        )
        ->join("ticket_refund_map as p1","p1.ticket_refund_map_id","{$this->table}.ticket_refund_map_id")
        ->leftJoin("ticket_request_material_detail as p3","p3.ticket_request_material_detail_id","{$this->table}.obj_id")
        ->leftjoin('product_childs','product_childs.product_id','p3.product_id')
        ->leftjoin('units','units.unit_id','product_childs.unit_id')
        ->where('p1.ticket_refund_id', $refund_id)->get()->toArray();
    }

    //    Lấy danh sách vật tư theo ticket refund
    public function getListMaterialRefund($ticketId){
        $oSelect = $this
            ->select(
                "{$this->table}.ticket_refund_item_id",
                "ticket_request_material_detail.product_id",
                "ticket_request_material_detail.ticket_request_material_detail_id as ticket_request_material_detail_id",
                "ticket_request_material_detail.quantity as quantity",
                "ticket_request_material_detail.quantity_approve as quantity_approve",
                "ticket_request_material_detail.quantity_return as quantity_return",
                "ticket_request_material_detail.quantity_reality as quantity_reality",
                "units.name as unit_name",
                "product_childs.product_child_name as product_name",
                "product_childs.product_code",
                "product_childs.price as price",
                "{$this->table}.ticket_id",
                "{$this->table}.note",
                "{$this->table}.quantity as quantity_refund"
            )
            ->leftjoin("ticket_request_material_detail","ticket_request_material_detail.ticket_request_material_detail_id","{$this->table}.obj_id")
            ->leftjoin("product_childs","product_childs.product_child_id","ticket_request_material_detail.product_id")
            ->leftjoin("units","units.unit_id","product_childs.unit_id")
            ->where("{$this->table}.ticket_id", $ticketId)
            ->where("{$this->table}.type", "A")
            ->where("ticket_request_material_detail.quantity_return",">",0)
            ->where("ticket_request_material_detail.status","approve")
            ->groupBy("{$this->table}.ticket_refund_item_id")
            ->get();

        return $oSelect;

    }

    //    lấy danh sách vật tư phát sinh hoàn ứng
    public function getListTicketAcceptanceIncurred($ticket_id){
        return $this
            ->select(
                "{$this->table}.ticket_refund_item_id",
                "p2.ticket_acceptance_incurred_id",
                "p2.product_name",
                "p2.quantity",
                "p2.unit_name",
                "p2.money",
                "{$this->table}.ticket_id",
                "{$this->table}.note",
                "{$this->table}.quantity as quantity_refund",
                "{$this->table}.money as money_refund"
            )
            ->leftjoin("ticket_acceptance_incurred as p2","p2.ticket_acceptance_incurred_id","{$this->table}.obj_id")
            ->leftjoin("ticket_refund_map as p3","p3.ticket_refund_map_id","{$this->table}.ticket_refund_map_id")
            ->leftjoin("ticket_refund as p4","p4.ticket_refund_id","p3.ticket_refund_id")
            ->where("{$this->table}.ticket_id",$ticket_id)
            ->where("{$this->table}.type", "I")
            ->where("p4.status","!=", "R")
            ->groupBy("{$this->table}.ticket_refund_item_id")
            ->get();
    }

    public function getResultRefundMaterial($ticket_refund_id)
    {
        $oSelect = $this
            ->select(
                "{$this->table}.ticket_refund_item_id",
                "ticket_request_material_detail.product_id",
                "ticket_request_material_detail.ticket_request_material_detail_id",
                "ticket_request_material_detail.quantity",
                "ticket_request_material_detail.quantity_approve",
                "ticket_request_material_detail.quantity_return",
                "ticket_request_material_detail.quantity_reality",
                "units.name as unit_name",
                "product_childs.product_child_name as product_name",
                "product_childs.product_code",
                "{$this->table}.ticket_id",
                "product_childs.unit_id",
                "{$this->table}.quantity as quantity_refund",
                \DB::raw("SUM({$this->table}.quantity) as sum_quantity_refund"),
                "{$this->table}.money as money_refund"
            )
            ->leftJoin("ticket_request_material_detail","ticket_request_material_detail.ticket_request_material_detail_id","{$this->table}.obj_id")
            ->leftJoin('product_childs','product_childs.product_child_id',"ticket_request_material_detail.product_id")
            ->leftJoin('units','units.unit_id','product_childs.unit_id')
            ->leftJoin('ticket_refund_map','ticket_refund_map.ticket_refund_map_id',"{$this->table}.ticket_refund_map_id")
            ->where("{$this->table}.type", "A")
            ->where('ticket_refund_map.ticket_refund_id',$ticket_refund_id)
            ->groupBy("{$this->table}.obj_id")
            ->orderBy($this->primaryKey, 'desc')
            ->get();
        return $oSelect;
    }

    public function getResultRefundIncurred($ticket_refund_id){
        return $this
            ->select(
                "{$this->table}.ticket_refund_item_id",
                "{$this->table}.obj_id as ticket_acceptance_incurred_id",
                "ticket_acceptance_incurred.product_name",
                "ticket_acceptance_incurred.quantity",
                "ticket_acceptance_incurred.unit_name",
                "ticket_acceptance_incurred.money",
                "{$this->table}.ticket_id",
                "{$this->table}.quantity as quantity_refund",
                "{$this->table}.money as money_refund"
            )
            ->leftJoin("ticket_acceptance_incurred",'ticket_acceptance_incurred.ticket_acceptance_incurred_id',"{$this->table}.obj_id")
            ->leftJoin('ticket_refund_map','ticket_refund_map.ticket_refund_map_id',"{$this->table}.ticket_refund_map_id")
            ->where('ticket_refund_map.ticket_refund_id',$ticket_refund_id)
            ->where("{$this->table}.type", "I")
            ->orderBy($this->primaryKey, 'desc')
            ->get();
    }

}