<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/19/2019
 * Time: 3:38 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SmsLogTable extends Model
{
    protected $table = 'sms_log';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'brandname', 'campaign_id','type_customer', 'phone', 'customer_name', 'message', 'sms_status', 'sms_type', 'error_code', 'error_description', 'sms_guid', 'created_at', 'updated_at', 'time_sent', 'time_sent_done', 'sent_by', 'created_by', 'object_id', 'object_type'];

    const NEW = "new";

    public function add(array $data)
    {
        $data = $this->create($data);
        return $data->id;
    }

    public function getLogCampaign($id)
    {
        $select = $this->where('campaign_id', $id)->get();
        return $select;
    }

    public function remove($id)
    {
        return $this->where('id', $id)->delete();
    }

    /**
     * Import Excel
     */
//    public function importExcel($fileName)
//    {
//        $reader = ReaderFactory::create(Type::XLSX);
//        $reader->open($fileName);
//        foreach ($reader->getSheetIterator() as $sheet) {
//            foreach ($sheet->getRowIterator() as $key => $row) {
//                if ($key == 1) {
//
//                } elseif ($key != 1 && $row[0] != '') {
//                    DB::table($this->table)
//                        ->insert([
//                            'service_name' => $row[0],
//                            'service_code' => $row[1],
//                            'service_time_id' => $row[2],
//                            'description' => $row[3],
//                            'detail' => $row[4],
//                            'is_active' => $row[5]
//                        ]);
//                }
//            }
//        }
//        $reader->close();
//    }

    //Cancel sms log
    public function cancelLog($type)
    {
//        $datetime = date('Y-m-d');
        $select = $this->where('sms_type', $type)->where('sms_status', 'new')
//            ->whereBetween('time_sent', [$datetime . ' 00:00:00', $datetime . ' 23:59:59'])
            ->update(['sms_status' => 'cancel']);
        return $select;
    }

    public function getAll()
    {
        return $this->get();
    }

    public function getAllLogNew($timeSent)
    {
        $select = $this->where('sms_status', 'new')->where('time_sent', $timeSent)->get();
        return $select;
    }

    public function getAllLogNewNoTimeSend($timeSent)
    {
        $select = $this->where('sms_status', 'new');

        if ($timeSent != null) {
            $select->where(function ($query) use ($timeSent) {
                $query->where('time_sent', '<', $timeSent)
                    ->orWhere('time_sent', '=', $timeSent)
                    ->orWhere('time_sent', null)
                    ->orWhere('time_sent', '');
            });
        } else {
            $select->where(function ($query) use ($timeSent) {
                $query->orWhere('time_sent', null)
                    ->orWhere('time_sent', '');
            });
        }

        return $select->get();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getLogDetailCampaign($id, array $filter = [])
    {
        $select = $this->select(
            'sms_log.customer_name as customer',
            'sms_log.phone as phone',
            'sms_log.message as message',
            'staffs1.user_name as created_by',
            'staffs2.user_name as sent_by',
            'sms_log.sms_status as sms_status',
            'sms_log.time_sent as time_sent',
            'sms_log.time_sent_done as time_sent_done',
            'sms_log.error_code as error_code',
            'sms_log.created_at as created_at'
        )
            ->leftJoin('sms_campaign', 'sms_campaign.campaign_id', '=', 'sms_log.campaign_id')
            ->leftJoin('staffs as staffs1', 'staffs1.staff_id', '=', 'sms_log.created_by')
            ->leftJoin('staffs as staffs2', 'staffs2.staff_id', '=', 'sms_log.sent_by')
            ->where('sms_campaign.campaign_id', $id);
        return $select->get();
    }

    public function cancelLogCampaign($id)
    {
        $select = $this->where('campaign_id', $id)->update(['sms_status' => 'cancel']);
        return $select;
    }

    public function getSmsSend($timeSend)
    {
        $select = $this->where('sms_status', 'new')
            ->where(function ($query) use ($timeSend) {
                $query->whereDate('time_sent', $timeSend)
                    ->orWhere('time_sent', null);
            })->get();
        return $select;
    }

    /**
     * Kiểm tra sms_log đã tồn tại trong chưa
     *
     * @param $smsType
     * @param $objectType
     * @param $objectId
     * @return mixed
     */
    public function checkLogExist($smsType, $objectType, $objectId)
    {
        return $this
            ->where("sms_type", $smsType)
            ->where("object_type", $objectType)
            ->where("object_id", $objectId)
            ->whereDate("created_at", Carbon::now()->format('Y-m-d'))
            ->first();
    }

    /**
     * Lấy log sms chăm sóc khách hàng
     *
     * @param $timeSent
     * @return mixed
     */
    public function getLogLoyalty($timeSent)
    {
        return $this
            ->where('sms_status', self::NEW)
            ->where(function ($query) use ($timeSent) {
                $query->where('time_sent', '<', $timeSent)
                    ->orWhere('time_sent', '=', $timeSent)
                    ->orWhere('time_sent', null)
                    ->orWhere('time_sent', '');
            })
            ->whereNull("campaign_id")
            ->get();
    }

    /**
     * Lấy log sms theo chương trình marketing
     *
     * @param $campaignId
     * @return mixed
     */
    public function getLogMarketing($campaignId)
    {
        return $this
            ->where('sms_status', self::NEW)
            ->where("campaign_id", $campaignId)
            ->get();
    }
    public function getCustomerApproach($filter)
    {
        $data = $this->select(
            DB::raw("SUM(IF({$this->table}.type_customer = 'lead', 1, 0)) as sum_lead"),
            DB::raw("SUM(IF({$this->table}.type_customer = 'customer', 1, 0)) as sum_customer"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_id) as sum_lead_convert")
        )
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_id", "{$this->table}.object_id")
                    ->where("{$this->table}.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->whereNotNull("{$this->table}.type_customer")
        ->whereNotNull("{$this->table}.campaign_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if(isset($filter['option_sms']) != ''){
            $data->where("{$this->table}.campaign_id", $filter['option_sms']);
        }
        return $data->get()->toArray();
    }
    public function getCustomerApproachPerformance($filter)
    {
        $data = $this->select(
            DB::raw("SUM(IF({$this->table}.type_customer = 'lead', 1, 0)) as sum_lead"),
            DB::raw("SUM(IF({$this->table}.type_customer = 'customer', 1, 0)) as sum_customer"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_id) as sum_lead_convert")
        )
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_id", "{$this->table}.object_id")
                    ->where("{$this->table}.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereNotNull("{$this->table}.type_customer")
        ->whereNotNull("{$this->table}.campaign_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->first();
    }
}