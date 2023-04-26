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
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class QueueStaffTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_staff_queue';
    protected $primaryKey = 'ticket_staff_queue_id';

    protected $fillable = ['ticket_staff_queue_id', 'staff_id', 'ticket_queue_id', 'role_id', 'ticket_role_queue_id', 'created_at', 'updated_at'];

    public function staff()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable', 'staff_id', 'staff_id');
    }

    public function queue()
    {
        return $this->belongsTo('Modules\Ticket\Models\QueueTable', 'ticket_queue_id', 'ticket_queue_id');
    }

    public function staffQueueMap()
    {
        return $this->hasMany('Modules\Ticket\Models\StaffQueueMapTable', 'ticket_staff_queue_id', 'ticket_staff_queue_id');
    }

    protected function _getList(&$filters = [])
    {
        $query = $this
            ->select(
                "{$this->table}.ticket_staff_queue_id",
                "{$this->table}.staff_id",
                "{$this->table}.ticket_queue_id",
                "{$this->table}.ticket_role_queue_id",
                "{$this->table}.role_id",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                DB::raw("(GROUP_CONCAT(q.queue_name)) as queue_name")
            )
            ->leftJoin("ticket_staff_queue_map as m", "m.ticket_staff_queue_id", "=", "{$this->table}.ticket_staff_queue_id")
            ->leftJoin("ticket_queue as q", "q.ticket_queue_id", "=", "m.ticket_queue_id")
            ->groupBy("{$this->table}.ticket_staff_queue_id")
            ->orderBy($this->primaryKey, 'desc');

        // filters nhân viên
        if (isset($filters["staff_id"]) != "") {
            $query->where("{$this->table}.staff_id", $filters["staff_id"]);
        }
        // filters queue trực thuộc
        if (isset($filters["ticket_queue_id"]) != "") {
            $query->where("q.ticket_queue_id", $filters["ticket_queue_id"]);
        }
        // filters role
        if (isset($filters["ticket_role_queue_id"]) != "") {
            $query->where("{$this->table}.ticket_role_queue_id", $filters["ticket_role_queue_id"]);
        }

        unset($filters["ticket_queue_id"]);

        return $query;
    }

    // lấy tất cả
    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    //  lấy nv đã phân công
    public function getStaff()
    {
        $oSelect = self::select("staff_id")->get();
        return ($oSelect->pluck("staff_id")->toArray());
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_staff_queue_id;
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

    public function getQueueOption($ticket_queue_id, $ticket_role_queue_id)
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "s.full_name"
            )
            ->join("ticket_staff_queue_map as m", "m.ticket_staff_queue_id", "=", "{$this->table}.ticket_staff_queue_id")
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.ticket_role_queue_id", $ticket_role_queue_id)
            ->where('s.is_deleted', 0)
            ->where('s.is_actived', 1)
            ->where("m.ticket_queue_id", $ticket_queue_id)
            ->groupBy("{$this->table}.ticket_staff_queue_id")
            ->get();
    }

    public function getTicketQueueIdByStaffId($staff_id)
    {
        return $this
            ->select(
                'p1.queue_name',
                "{$this->table}.staff_id",
                "{$this->table}.ticket_role_queue_id"
            )
            ->where('staff_id', $staff_id)
            ->leftJoin("ticket_queue as p1", "p1.ticket_queue_id", "{$this->table}.ticket_queue_id")
            ->first();
    }

}