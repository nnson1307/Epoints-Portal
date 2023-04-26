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

class TicketRefundTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_refund';
    protected $primaryKey = 'ticket_refund_id';

    protected $fillable = ['ticket_refund_id', 'code','staff_id', 'approve_id', 'status',
        'created_at','created_by','updated_at','updated_by'];

    public function listRefund($filters = [])
    {
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select(
            "{$this->table}.ticket_refund_id",
            "{$this->table}.code",
            "{$this->table}.staff_id",
            "{$this->table}.approve_id",
            "{$this->table}.status",
            "{$this->table}.created_at",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "{$this->table}.updated_by",
            "p1.full_name as refund_by_full_name",
            "p2.full_name as approve_by_full_name",
            "p3.full_name as created_by_full_name",
            "p4.full_name as updated_by_full_name",
            )
            ->leftJoin("staffs as p1","p1.staff_id","{$this->table}.staff_id")
            ->leftJoin("staffs as p2","p2.staff_id","{$this->table}.approve_id")
            ->leftJoin("staffs as p3","p3.staff_id","{$this->table}.created_by")
            ->leftJoin("staffs as p4","p4.staff_id","{$this->table}.updated_by");
        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("{$this->table}.code", "like", "%" . $filters["search"] . "%");
        }
        // filters nhân viên hoàn ứng
         if (isset($filters["staff_id"]) && $filters["staff_id"] != "") {
            $query->where("{$this->table}.staff_id", $filters["staff_id"]);
        }
        // filters người tạo
         if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("{$this->table}.created_by", $filters["created_by"]);
        }
        // filters nguoi duyệt
         if (isset($filters["approve_id"]) && $filters["approve_id"] != "") {
            $query->where("{$this->table}.approve_id", $filters["approve_id"]);
        }
        // filters status
         if (isset($filters["status"]) && $filters["status"] != "") {
            $query->where("{$this->table}.status",'=', $filters["status"]);
        }
        // filter ngày đề xuất
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("{$this->table}.created_at", ">=", $startTime);
            $query->whereDate("{$this->table}.created_at", "<=", $endTime);
        }
        return $query->orderBy($this->primaryKey, 'desc')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getName(){
        $oSelect= self::select("ticket_refund_id","code")->get();
        return ($oSelect->pluck("code","ticket_refund_id")->toArray());
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->ticket_refund_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)
        ->where("{$this->table}.status", 'D')->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->select(
            "{$this->table}.*",
            "p1.full_name as refund_by_full_name",
            "p1.phone1 as refund_by_phone",
            "p1.email as refund_by_email",
            "p7.queue_name as refund_by_queue_name",
            "p2.full_name as approve_by_full_name",
            "p3.full_name as created_by_full_name",
            "p4.full_name as updated_by_full_name",
            "p5.department_name as approve_by_department",
            )
        ->leftJoin("staffs as p1","p1.staff_id","{$this->table}.staff_id")
        ->leftJoin("staffs as p2","p2.staff_id","{$this->table}.approve_id")
        ->leftJoin("staffs as p3","p3.staff_id","{$this->table}.created_by")
        ->leftJoin("staffs as p4","p4.staff_id","{$this->table}.updated_by")
        ->leftJoin("departments as p5","p5.department_id","p2.department_id")
        ->leftJoin("ticket_staff_queue as p6","p6.staff_id","p1.staff_id")
        ->leftJoin("ticket_queue as p7","p7.ticket_queue_id","p6.ticket_queue_id")
        ->where($this->primaryKey, $id)
        ->first();
    }
    public function getItemByTicketId($staff_id)
    {
        return $this->where('staff_id', $staff_id)->get();
    }

    public function getTicketRefundMap($id)
    {
        $query = $this->select('ticket_id')
        ->join("ticket_refund_map as p1","p1.ticket_refund_id","{$this->table}.ticket_refund_id")
        ->where("{$this->table}.ticket_refund_id", $id)->get();
        return ($query->pluck("ticket_id","ticket_id")->toArray());
    }

}