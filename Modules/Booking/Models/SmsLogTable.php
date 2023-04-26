<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/19/2019
 * Time: 3:38 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLogTable extends Model
{
    protected $table = 'sms_log';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'brandname', 'campaign_id', 'phone', 'customer_name', 'message', 'sms_status', 'sms_type', 'error_code', 'error_description', 'sms_guid', 'created_at', 'updated_at', 'time_sent', 'time_sent_done', 'sent_by', 'created_by', 'object_id', 'object_type'];

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
        if ($timeSent!=null){
            $select = $this->where('sms_status', 'new')
                ->where(function ($query) use ($timeSent) {
                    $query
                        ->where('time_sent', '<', $timeSent)
                        ->orWhere('time_sent', '=', $timeSent)
                        ->orWhere('time_sent', null)
                        ->orWhere('time_sent', '');
                })
                ->get();
            return $select;
        }else{
            $select = $this->where('sms_status', 'new')
                ->where(function ($query) use ($timeSent) {
                    $query
                        ->orWhere('time_sent', null)
                        ->orWhere('time_sent', '');
                })
                ->get();
            return $select;
        }

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
            'sms_log.error_code as error_code'
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
}