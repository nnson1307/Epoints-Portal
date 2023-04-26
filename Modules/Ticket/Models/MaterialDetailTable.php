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

class MaterialDetailTable extends Model
{
    use ListTableTrait;

    protected $table = 'ticket_request_material_detail';
    protected $primaryKey = 'ticket_request_material_detail_id';

    protected $fillable = ['ticket_request_material_detail_id', 'ticket_request_material_id', 'product_id', 'quantity', 'quantity_approve',
        'quantity_return', 'quantity_reality', 'warehouse_id', 'product_inventory_id', 'status', 'updated_by', 'created_at', 'updated_at'];

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_request_material_detail_id;
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

    public function getItemByMaterialId($id)
    {
        return $this->select(
            "{$this->table}.ticket_request_material_detail_id",
            "{$this->table}.quantity_approve",
            "{$this->table}.quantity_return",
            "{$this->table}.quantity_reality",
            "{$this->table}.status",
            "{$this->table}.quantity",
            "{$this->table}.product_id",
            "{$this->table}.warehouse_id",
            \DB::raw("IFNULL(pi.quantity,0) as quantity_max"),
            "units.name as unitName",
            "units.unit_id as unitId",
            "pc.product_child_name as product_name",
            "pc.product_code",
        )
            ->leftjoin("product_inventorys as pi", "pi.product_inventory_id", "=", "{$this->table}.product_inventory_id")
            ->leftjoin("product_childs as pc", "pc.product_id", "=", "{$this->table}.product_id")
            ->leftjoin("units", "units.unit_id", "=", "pc.unit_id")
            // ->where("{$this->table}.warehouse_id", 'pi.warehouse_id')
            ->where("{$this->table}.ticket_request_material_id", $id)
            // ->orderBy($this->primaryKey, 'ASC')
            ->get();
    }

    public function getListMaterialByTicketId($ticketId)
    {
        $oSelect = $this
            ->select(
                "{$this->table}.ticket_request_material_detail_id",
//                \DB::raw("SUM({$this->table}.quantity_approve) as quantity_approve"),
//                \DB::raw("(CASE WHEN {$this->table}.status = 'approve' THEN SUM({$this->table}.quantity_approve) ELSE SUM({$this->table}.quantity_approve) END) as quantity_approve"),
                 "TotalCatches.sum_quantity_approve as quantity_approve",
                \DB::raw("SUM({$this->table}.quantity_return) as quantity_return"),
                \DB::raw("SUM({$this->table}.quantity_reality) as quantity_reality"),
                \DB::raw("SUM({$this->table}.quantity) as quantity"),
                \DB::raw("IFNULL(pi.quantity,0) as quantity_max"),
                "{$this->table}.product_id",
                "{$this->table}.warehouse_id",
                "{$this->table}.status",
                "units.name as unitName",
                "units.unit_id as unitId",
                "pc.product_child_name as product_name",
                "pc.product_code"
            )
            ->leftJoin('ticket_request_material', 'ticket_request_material.ticket_request_material_id', $this->table . '.ticket_request_material_id')
            ->leftjoin("product_inventorys as pi", "pi.product_inventory_id", "=", "{$this->table}.product_inventory_id")
            ->leftjoin("product_childs as pc", "pc.product_id", "=", "{$this->table}.product_id")
            ->leftjoin("units", "units.unit_id", "=", "pc.unit_id")
            ->leftjoin(\DB::raw("(SELECT SUM(ticket_request_material_detail.quantity_approve) as sum_quantity_approve ,product_id ,warehouse_id ,product_inventory_id
               FROM `ticket_request_material_detail` 
               LEFT JOIN ticket_request_material on ticket_request_material.ticket_request_material_id = ticket_request_material_detail.ticket_request_material_id
               WHERE ticket_request_material_detail.status = 'approve' and ticket_request_material.ticket_id = {$ticketId}
               GROUP BY product_id,warehouse_id,product_inventory_id)
               TotalCatches"),
                function ($join) {
                    $join->on("{$this->table}.product_id", "=", "TotalCatches.product_id");
                })
            ->where('ticket_request_material.ticket_id', $ticketId)
//            ->where("{$this->table}.status","approve")
            ->groupBy("{$this->table}.product_id")
//            ->where($this->table . '.quantity_approve', '<>', 0)
            ->get();
        return $oSelect;

    }

    public function removeByMaterialId($id)
    {
        return $this->where('ticket_request_material_id', $id)->delete();
    }

//    Lấy danh sách vật tư theo ticket
    public function getListMaterial($ticketId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.product_id',
                $this->table . '.ticket_request_material_detail_id',
                $this->table . '.quantity',
                $this->table . '.quantity_approve',
                $this->table . '.quantity_return',
                $this->table . '.quantity_reality',
                'units.name as unit_name',
                'product_childs.product_child_name as product_name',
                'product_childs.product_code'
            )
            ->join('ticket_request_material', 'ticket_request_material.ticket_request_material_id', $this->table . '.ticket_request_material_id')
            ->leftJoin('ticket', 'ticket.ticket_request_material_id', 'ticket_request_material.ticket_request_material_id')
            ->join('product_childs', 'product_childs.product_child_id', $this->table . '.product_id')
            ->join('units', 'units.unit_id', 'product_childs.unit_id')
            ->where('ticket_request_material.ticket_id', $ticketId)
            ->get();

        return $oSelect;

    }

//    Lấy danh sách vật tư theo ticket refund
    public function getListMaterialRefund($ticketId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.product_id',
                $this->table . '.ticket_request_material_detail_id',
                $this->table . '.quantity',
                $this->table . '.quantity_approve',
                $this->table . '.quantity_return',
                $this->table . '.quantity_reality',
                'units.name as unit_name',
                'product_childs.product_child_name as product_name',
                'product_childs.product_code',
                'product_childs.price as price',
                'ticket_request_material.ticket_id'
            )
            ->leftjoin('ticket_request_material', 'ticket_request_material.ticket_request_material_id', $this->table . '.ticket_request_material_id')
            ->leftjoin('product_childs', 'product_childs.product_child_id', $this->table . '.product_id')
            ->leftjoin('units', 'units.unit_id', 'product_childs.unit_id')
            ->where('ticket_request_material.ticket_id', $ticketId)
            ->where($this->table . '.quantity_return', '>', 0)
            ->get();

        return $oSelect;

    }

    public function getResultRefund($array_ticket)
    {
        $oSelect = $this
            ->select(
                $this->table . '.product_id',
                $this->table . '.ticket_request_material_detail_id',
                $this->table . '.quantity',
                $this->table . '.quantity_approve',
                $this->table . '.quantity_return',
                $this->table . '.quantity_reality',
                'units.name as unit_name',
                'product_childs.product_child_name as product_name',
                'product_childs.product_code',
                'ticket_refund_item.ticket_id',
                'product_childs.unit_id',
                'ticket_refund_item.quantity as quantity_refund',
                \DB::raw("SUM(ticket_refund_item.quantity) as sum_quantity_refund"),
                'ticket_refund_item.money as money_refund',
            // \DB::raw("COUNT({$this->table}.product_id) as sum_price")
            )
            ->leftjoin('ticket_request_material', 'ticket_request_material.ticket_request_material_id', $this->table . '.ticket_request_material_id')
            ->leftjoin('product_childs', 'product_childs.product_child_id', $this->table . '.product_id')
            ->leftjoin('units', 'units.unit_id', 'product_childs.unit_id')
            ->join('ticket_refund_item', 'ticket_refund_item.obj_id', $this->table . '.ticket_request_material_detail_id')
            ->whereIn('ticket_request_material.ticket_id', $array_ticket)
            ->whereIn('ticket_refund_item.ticket_id', $array_ticket)
            ->where($this->table . '.quantity_return', '>', 0)
            // ->where('ticket_refund_item.obj_id',$this->table.'.product_id')
            // ->where('ticket_refund_item.ticket_id','ticket_request_material.ticket_id')
            ->groupBy('ticket_refund_item.obj_id')
            ->get();
        return $oSelect;
    }

    public function getListMaterialAcceptance($ticketId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.product_id',
                $this->table . '.ticket_request_material_detail_id',
                $this->table . '.quantity',
                $this->table . '.quantity_approve',
                $this->table . '.quantity_return',
                $this->table . '.quantity_reality',
                'units.name as unit_name',
                'product_childs.product_child_name as product_name',
                'product_childs.product_code',
            )
            ->join('ticket_request_material', 'ticket_request_material.ticket_request_material_id', $this->table . '.ticket_request_material_id')
            ->join('product_childs', 'product_childs.product_child_id', $this->table . '.product_id')
            ->join('units', 'units.unit_id', 'product_childs.unit_id')
            ->where('ticket_request_material.ticket_id', $ticketId)
            ->where('ticket_request_material.status', 'approve')
            ->where($this->table . '.quantity_approve', '<>', 0)
            ->get();

        return $oSelect;

    }

}