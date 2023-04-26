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

class MaterialTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_request_material';
    protected $primaryKey = 'ticket_request_material_id';

    protected $fillable = ['ticket_request_material_id', 'ticket_request_material_code','ticket_id', 'proposer_by', 'proposer_date',
        'approved_by', 'approved_date', 'description','status','created_at','created_by','updated_at','updated_by'];

    public function ticketCode()
    {
        return $this->belongsTo('Modules\Ticket\Models\TicketTable','ticket_id','ticket_id');
    }
    public function proposer()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','proposer_by','staff_id');
    }
    public function approved()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','approved_by','staff_id');
    }

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.ticket_request_material_id",
            "{$this->table}.ticket_request_material_code",
            "{$this->table}.ticket_id",
            "{$this->table}.proposer_by",
            "{$this->table}.proposer_date",
            "{$this->table}.approved_by",
            "{$this->table}.approved_date",
            "{$this->table}.description",
            "{$this->table}.status",
            "{$this->table}.created_at",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "{$this->table}.updated_by",
            "ticket.ticket_code"
            )
            ->leftJoin("ticket as ticket", "ticket.ticket_id", '=', "{$this->table}.ticket_id")
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("{$this->table}.ticket_request_material_code", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.description", "like", "%" . $search . "%")
                    ->orWhere("ticket.ticket_code", "like", "%" . $search . "%");
        }
        // filters nguoi đề xuất
         if (isset($filters["proposer_by"]) && $filters["proposer_by"] != "") {
            $query->where("{$this->table}.proposer_by", $filters["proposer_by"]);
        }
        // filters nguoi duyệt
         if (isset($filters["approved_by"]) && $filters["approved_by"] != "") {
            $query->where("{$this->table}.approved_by", $filters["approved_by"]);
        }
        // filters status
         if (isset($filters["status"]) && $filters["status"] != "") {
            $query->where("{$this->table}.status",'like', $filters["status"]);
        }

        // filter ngày đề xuất
        if (isset($filters["proposer_date"]) && $filters["proposer_date"] != "") {
            $arr_filter = explode(" - ", $filters["proposer_date"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.proposer_date", ">=", $startTime);
            $query->whereDate("{$this->table}.proposer_date", "<=", $endTime);
        }
        return $query;
    }

    public function listMatrerial($filters = [])
    {
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select(
            "{$this->table}.ticket_request_material_id",
            "{$this->table}.ticket_request_material_code",
            "{$this->table}.ticket_id",
            "{$this->table}.proposer_by",
            "{$this->table}.proposer_date",
            "{$this->table}.approved_by",
            "{$this->table}.approved_date",
            "{$this->table}.description",
            "{$this->table}.status",
            "{$this->table}.created_at",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "{$this->table}.updated_by",
            "ticket.ticket_code"
        )
        ->leftJoin("ticket as ticket", "ticket.ticket_id", '=', "{$this->table}.ticket_id")
        ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("ticket_request_material_code", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.description", "like", "%" . $search . "%")
                    ->orWhere("ticket.ticket_code", "like", "%" . $search . "%");
        }
        // filters nguoi đề xuất
         if (isset($filters["proposer_by"]) && $filters["proposer_by"] != "") {
            $query->where("{$this->table}.proposer_by", $filters["proposer_by"]);
        }
        // filters nguoi duyệt
         if (isset($filters["approved_by"]) && $filters["approved_by"] != "") {
            $query->where("{$this->table}.approved_by", $filters["approved_by"]);
        }
        // filters status
         if (isset($filters["status"]) && $filters["status"] != "") {
            $query->where("{$this->table}.status", $filters["status"]);
        }

        // filter ngày đề xuất
        if (isset($filters["proposer_date"]) && $filters["proposer_date"] != "") {
            $arr_filter = explode(" - ", $filters["proposer_date"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.proposer_date", ">=", $startTime);
            $query->whereDate("{$this->table}.proposer_date", "<=", $endTime);
        }
        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_request_material_id","ticket_request_material_code")->get();
        return ($oSelect->pluck("ticket_request_material_code","ticket_request_material_id")->toArray());
    }

    public function testCode($code, $id)
    {
        return $this->where('ticket_request_material_code', $code)->where('ticket_request_material_id', '<>', $id)->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_request_material_id;
    }

    public function remove($id)
    {
        $deleted = $this->where($this->primaryKey, $id)->delete();
        return $deleted;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
    public function getItemByTicketId($ticket_id)
    {
        return $this->where('ticket_id', $ticket_id)->get();
    }
    public function getMaterialDetailByTicketId($ticket_request_material_id)
    {
        return $this->select(
            "material_detail.ticket_request_material_detail_id",
            "material_detail.quantity_approve",
            "material_detail.quantity_return",
            "material_detail.quantity_reality",
            "material_detail.status",
            "material_detail.quantity",
            "material_detail.product_id",
            "pi.quantity as quantity_max",
            "units.name as unitName",
            "units.unit_id as unitId",
            "pc.product_child_name as product_name",
            "pc.product_code as product_code"
        )
        ->join("ticket_request_material_detail as material_detail", "material_detail.ticket_request_material_id", "=", "{$this->table}.ticket_request_material_id")
        ->join("product_inventorys as pi", "pi.product_id", "=", "material_detail.product_id")
        ->join("product_childs as pc", "pc.product_id", "=", "material_detail.product_id")
        ->join("units", "units.unit_id", "=", "pc.unit_id")
        // ->join("ticket as ticket", "ticket.ticket_id", "=", "{$this->table}.ticket_id")
        // ->where("ticket.ticket_id", $ticket_id)
        ->where("{$this->table}.ticket_request_material_id", $ticket_request_material_id)
        ->get();
    }

}