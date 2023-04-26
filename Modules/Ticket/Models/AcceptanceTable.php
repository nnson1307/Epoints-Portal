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

class AcceptanceTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_acceptance';
    protected $primaryKey = 'ticket_acceptance_id';

    protected $fillable = ['ticket_acceptance_id', 'ticket_acceptance_code','ticket_id', 'title', 'sign_by',
        'sign_date', 'sign_date_request', 'customer_id','status','created_at','created_by','updated_at','updated_by'];

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
        $query = $this->select('ticket_acceptance_id', 'ticket_acceptance_code','ticket_id', 'title', 'sign_by',
        'sign_date', 'sign_date_request', 'customer_id','status','created_at','created_by','updated_at','updated_by')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("ticket_request_material_code", "like", "%" . $search . "%")
                    ->orWhere("description", "like", "%" . $search . "%");
        }
        // filters nguoi đề xuất
         if (isset($filters["proposer_by"]) && $filters["proposer_by"] != "" && $filters["proposer_by"] != 0) {
            $query->where("proposer_by", $filters["proposer_by"]);
        }
        // filters nguoi duyệt
         if (isset($filters["approved_by"]) && $filters["approved_by"] != "" && $filters["approved_by"] != 0) {
            $query->where("approved_by", $filters["approved_by"]);
        }
        // filters status
         if (isset($filters["status"]) && $filters["status"] != "") {
            $query->where("status",'like', $filters["status"]);
        }

        // filter ngày đề xuất
        if (isset($filters["proposer_date"]) && $filters["proposer_date"] != "") {
            $arr_filter = explode(" - ", $filters["proposer_date"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("proposer_date", ">=", $startTime);
            $query->whereDate("proposer_date", "<=", $endTime);
        }
        return $query;
    }

    public function listAcceptance($filters = [])
    {
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select(
            $this->table.'.ticket_acceptance_id',
            $this->table.'.ticket_acceptance_code',
            $this->table.'.ticket_id',
            $this->table.'.title',
            $this->table.'.sign_by',
            $this->table.'.sign_date',
            $this->table.'.sign_date_request',
            $this->table.'.customer_id',
            'customers.full_name as customer_name',
            $this->table.'.status',
            $this->table.'.created_at',
            $this->table.'.created_by',
            $this->table.'.updated_at',
            $this->table.'.updated_by',
            'ticket.ticket_code',
            'staffs.full_name as created_name'
        )
            ->join('ticket','ticket.ticket_id',$this->table.'.ticket_id')
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->join('customers','customers.customer_id',$this->table.'.customer_id')
            ->orderBy($this->primaryKey, 'desc');
        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where($this->table.".ticket_acceptance_code", "like", "%" . $search . "%")
                    ->orWhere($this->table.".title", "like", "%" . $search . "%");
        }
        // filters nguoi đề xuất
         if (isset($filters["customer_id"]) && $filters["customer_id"] != "" && $filters["customer_id"] != 0) {
            $query->where($this->table.".customer_id", $filters["customer_id"]);
        }
        // filters nguoi duyệt
         if (isset($filters["sign_date_request"]) && $filters["sign_date_request"] != "") {
            $query->where($this->table.".sign_date_request", $filters["sign_date_request"]);
         }

        // filters người tạo
        if (isset($filters["created_by"]) && $filters["created_by"] != "" && $filters["created_by"] != 0) {
            $query->where($this->table.".created_by", $filters["created_by"]);
        }

        // filters người ký
        if (isset($filters["sign_by"]) && $filters["sign_by"] != "") {
            $query->where($this->table.".sign_by", "like", "%" . $filters["sign_by"] . "%");
        }

        if (isset($filters["ticket_code"]) && $filters["ticket_code"] != "") {
            $query->where("ticket.ticket_code", "like", "%" . $filters["ticket_code"] . "%");
        }


        // filter ngày đề xuất
        if (isset($filters["sign_date"]) && $filters["sign_date"] != "") {
            $arr_filter = explode(" - ", $filters["sign_date"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate($this->table.".sign_date", ">=", $startTime);
            $query->whereDate($this->table.".sign_date", "<=", $endTime);
        }

        // filter ngày đề xuất
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate($this->table.".created_at", ">=", $startTime);
            $query->whereDate($this->table.".created_at", "<=", $endTime);
        }

        // filters status
        if (isset($filters["status"]) && $filters["status"] != "" && $filters["status"] != "0") {
            $query->where($this->table.".status", $filters["status"]);
        }

        return $query->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_acceptance_id","ticket_acceptance_code")->get();
        return ($oSelect->pluck("ticket_acceptance_code","ticket_acceptance_id")->toArray());
    }

    public function testCode($code, $id)
    {
        return $this->where('ticket_acceptance_code', $code)->where('ticket_acceptance_id', '<>', $id)->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_acceptance_id;
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
        return $this
            ->select(
                $this->table.'.ticket_acceptance_id',
                $this->table.'.ticket_acceptance_code',
                $this->table.'.ticket_id',
                $this->table.'.title',
                $this->table.'.sign_by',
                $this->table.'.sign_date',
                $this->table.'.sign_date_request',
                $this->table.'.customer_id',
                $this->table.'.status',
                $this->table.'.created_at',
                $this->table.'.created_by',
                $this->table.'.updated_at',
                $this->table.'.updated_by',
                'ticket.date_issue'
            )
            ->join('ticket','ticket.ticket_id',$this->table.'.ticket_id')
            ->where($this->primaryKey, $id)
            ->first();
    }

}