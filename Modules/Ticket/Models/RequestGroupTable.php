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

class RequestGroupTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_issue_group';
    protected $primaryKey = 'ticket_issue_group_id';

    protected $fillable = ['ticket_issue_group_id', 'name','type','description','created_by','updated_by','created_at', 'updated_at','is_active'];

    public function staff_created()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','created_by','staff_id');
    }
    public function staff_updated()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable','updated_by','staff_id');
    }
    public function issue_group()
    {
        return $this->hasMany('Modules\Ticket\Models\RequestTable','ticket_issue_group_id','ticket_issue_group_id')->where('ticket_issue.is_active',1);
    }
    protected function _getList($filters = [])
    {
         
        $query = $this->select('ticket_issue_group_id', 'name','type','description','created_by','updated_by','created_at', 'updated_at','is_active')
            ->orderBy($this->primaryKey, 'desc');
        // filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("name", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("created_by", $filters["created_by"]);
        }
        // filters NGAY TAO
         if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("created_at", ">=", $startTime);
            $query->whereDate("created_at", "<=", $endTime);
        }
         if (isset($filters["type"]) && $filters["type"] != "") {
            $query->where("type", $filters["type"]);
        }
         if (isset($filters["is_active"]) && $filters["is_active"] != "") {
            $query->where("is_active", $filters["is_active"]);
        }
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("ticket_issue_group_id","name")->where("is_active", 1)->get();
        return ($oSelect->pluck("name","ticket_issue_group_id")->toArray());
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_issue_group_id;
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

}