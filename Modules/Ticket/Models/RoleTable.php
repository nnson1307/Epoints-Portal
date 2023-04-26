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

class RoleTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_role';
    protected $primaryKey = 'ticket_role_id';

    protected $fillable = ['ticket_role_id', 'role_name','role_group_id','description','is_approve_refund','ticket_action_role','created_by','created_at','updated_at','updated_by'];

    public function staff_created()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','created_by','staff_id');
    }
    public function staff_updated()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','updated_by','staff_id');
    }
    public function ticketRoleActionMap()
    {
        return $this->hasOne('Modules\Ticket\Models\TicketRoleActionMapTable','ticket_role_id','ticket_role_id');
    }
    public function TicketRoleStatusMap()
    {
        return $this->hasMany('Modules\Ticket\Models\TicketRoleStatusMapTable','ticket_role_id','ticket_role_id');
    }
    protected function _getList($filters = [])
    {
        $query = $this->select($this->table.'.ticket_role_id', $this->table.'.role_name',$this->table.'.description', $this->table.'.created_by',$this->table.'.created_at',$this->table.'.updated_at',$this->table.'.updated_by',$this->table.'.role_group_id','role_group.name as name',$this->table.'.is_approve_refund')
            ->orderBy($this->primaryKey, 'desc');
        // filters tên
        if (isset($filters["search"]) != "") {
            $search = $filters["search"];
            $query->where("role_group.name", "like", "%" . $search . "%")
            ->orwhere("description", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) != "") {
            $query->where("created_by", $filters["created_by"]);
        }

        // filter ngày tạo
        if (isset($filters["created_at"]) != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate( $this->table.".created_at", ">=", $startTime);
            $query->whereDate( $this->table.".created_at", "<=", $endTime);
        }
        $query->leftJoin('role_group','role_group.id','=',$this->table.'.role_group_id');
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_role_id","role_name")->get();
        return ($oSelect->pluck("role_name","ticket_role_id")->toArray());
    }
    public function getRoleGroupId(){
        $oSelect= self::select("role_group_id")->groupBy('role_group_id')->where('role_group_id','<>','null')->get();
        return ($oSelect->pluck("role_group_id")->toArray());
    }

    public function add(array $data)
    {
        $item = $this->where("role_group_id",'=',$data['role_group_id'])->first();
        if(!isset($item->ticket_role_id)){
            $oCustomerGroup = $this->create($data);
            return $oCustomerGroup->ticket_role_id;
        }else{
            return false;
        }
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
        return $this->select($this->table.'.*','role_group.name as name')->leftJoin('role_group','role_group.id','=',$this->table.'.role_group_id')
        ->where($this->primaryKey, $id)->first();
    }


}