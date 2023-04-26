<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/06/2021
 * Time: 13:58
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class SmsLogTable extends Model
{
    protected $table = 'sms_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'brandname',
        'campaign_id',
        'phone',
        'customer_name',
        'message',
        'sms_status',
        'sms_type',
        'error_code',
        'error_description',
        'sms_guid',
        'created_at',
        'updated_at',
        'time_sent',
        'time_sent_done',
        'sent_by',
        'created_by',
        'object_id',
        'object_type'
    ];

    /**
     * Cập nhật sms_log
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}