<?php


namespace Modules\Delivery\Models;


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

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}