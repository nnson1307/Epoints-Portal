<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/30/2021
 * Time: 5:04 PM
 * @author nhandt
 */


namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLogTable extends Model
{
    protected $table = "customer_logs";
    protected $primaryKey = "customer_log_id";
    protected $fillable = [
        "customer_log_id",
        "title",
        "object_type",
        "object_id",
        "key_table",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    use ListTableTrait;

    protected function _getList($filter = []){
        $type = $filter['object_type'];
        $ds = $this->select(
            "{$this->table}.customer_log_id",
            "{$this->table}.title",
            "{$this->table}.object_type",
            "{$this->table}.object_id",
            "{$this->table}.key_table",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "staffs.full_name as staff_name",
            DB::raw("(CASE WHEN {$this->table}.object_type = 'customer_lead' THEN cpo_customer_lead.full_name
             ELSE customers.full_name END) 
             as name")
        )
            ->leftJoin("cpo_customer_lead", function($join)use($type){
                $join->on("cpo_customer_lead.customer_lead_id", "=", "{$this->table}.object_id")
                    ->where("{$this->table}.object_type", $type);
            })
            ->leftJoin("customers", function($join)use($type){
                $join->on("customers.customer_id", "=", "{$this->table}.object_id")
                    ->where("{$this->table}.object_type", $type);
            })
            ->leftJoin("staffs","staffs.staff_id", "{$this->table}.created_by");

        if(isset($filter['object_id']) && $filter['object_id'] != ''){
            $ds->where("{$this->table}.object_id", $filter['object_id']);
        }
        unset($filter['object_id']);

        if(isset($filter['search']) && $filter['search'] != ''){
            $ds->where("staffs.full_name",'like', '%'. $filter['search'] . '%');
        }
        unset($filter['search']);

        if(isset($filter['created_at']) && $filter['created_at'] != ''){
            $time = explode(' - ', $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $ds->whereBetween("{$this->table}.created_at", [$startTime, $endTime]);
        }
        unset($filter['created_at']);


        unset($filter['object_type']);
        return $ds->orderBy("{$this->table}.created_at", "DESC");
    }

    public function createLog($data)
    {
        return $this->create($data)->customer_log_id;
    }

    public function getListLogById($customerLogId, $type = 'customer')
    {
        $ds = $this->select(
            "customer_log_update.key",
            "customer_log_update.value_old",
            "customer_log_update.value_new"
        )
            ->leftJoin("customer_log_update", "customer_log_update.customer_log_id", "{$this->table}.customer_log_id")
            ->where("{$this->table}.customer_log_id", $customerLogId)
            ->where("{$this->table}.object_type", $type);
        return $ds->get()->toArray();
    }

}