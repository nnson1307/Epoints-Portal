<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 4:26 PM
 */

namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CpoCustomerLogTable extends Model
{
    protected $table = "cpo_customer_logs";
    protected $primaryKey = "cpo_customer_log_id";
    protected $fillable = [
        "cpo_customer_log_id",
        "title",
        "object_type",
        "object_id",
        "key_table",
        "value_old",
        "value_new",
        "day",
        "month",
        "year",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "is_deal_created",
    ];

    /**
     * Lưu log Customer tạo deal
     * @param $data
     */
    public function insertLog($data){
        return $this
            ->insertGetId($data);
    }

    public function insertArrData($data){
        return $this->insert($data);
    }

    //    Lấy log cuối cùng
    public function getLastLog($objectId,$type,$objectType = null){
        $oSelect = $this
            ->where('object_id',$objectId)
            ->where('type',$type);

        if ($objectType != null){
            $oSelect = $oSelect
                ->where('object_type',$objectType);
        } else {
            $oSelect = $oSelect
                ->where('object_type','<>','customer');
        }

        return $oSelect
            ->orderBy('cpo_customer_log_id','DESC')
            ->first();
    }

    /**
     * Xóa hành trình theo mã hành trình
     * @param $arrJourney
     * @param $customerLogId
     */
    public function deleteByArrayJourneyCode($arrJourney,$objectId,$type){
        return $this
            ->whereIn('value_new',$arrJourney)
            ->where('object_id',$objectId)
            ->where('type',$type)
            ->delete();
    }

    /**
     * Lấy danh sách log theo lead
     */
    public function getListLog($pipelineCode, $filter = []){
        $oSelect = $this
            ->join('cpo_customer_lead','cpo_customer_lead.customer_lead_id',$this->table.'.object_id')
            ->leftJoin('staffs','staffs.staff_id','cpo_customer_lead.sale_id')
            ->where($this->table.'.type','lead')
            ->where($this->table.'.object_type','customer_lead')
            ->where('cpo_customer_lead.pipeline_code',$pipelineCode)
            ->where('cpo_customer_lead.is_convert',0)
            ->where('cpo_customer_lead.is_deleted',0);

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_customer_lead.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['customer_source_id'])){
            $oSelect = $oSelect->where('cpo_customer_lead.customer_source',$filter['customer_source_id']);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where('staffs.department_id',$filter['department_id']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where('staffs.staff_id',$filter['staff_id']);
        }

        return $oSelect->get();

    }

    /**
     * Lấy danh sách log theo deal
     */
    public function getListLogDeal($pipelineCode){
        $oSelect = $this
            ->join('cpo_deals','cpo_deals.deal_id',$this->table.'.object_id')
            ->leftJoin('staffs','staffs.staff_id','cpo_deals.sale_id')
            ->where($this->table.'.type','deal')
            ->where($this->table.'.object_type','customer_deal')
            ->where('cpo_deals.pipeline_code',$pipelineCode)
//            ->where('cpo_deal.is_convert',0)
            ->where('cpo_deals.is_deleted',0);

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_deals.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where('staffs.department_id',$filter['department_id']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where('staffs.staff_id',$filter['staff_id']);
        }

        return $oSelect->get();
    }

    public function removeLogByType($object_type,$object_id){
        return $this
            ->where('object_type',$object_type)
            ->where('object_id',$object_id)
            ->delete();
    }

//    Xóa log theo mã hành trình
    public function removeLogByJourney($object_type,$object_id,$arrJourney){
        return $this
            ->where('object_type',$object_type)
            ->where('object_id',$object_id)
            ->whereIn('value_new',$arrJourney)
            ->delete();
    }
}