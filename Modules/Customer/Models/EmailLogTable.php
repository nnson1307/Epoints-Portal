<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/06/2021
 * Time: 13:58
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class EmailLogTable extends Model
{
    protected $table = 'email_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'campaign_id', 'email', 'customer_name', 'email_status', 'email_type', 'content_sent',
        'created_at', 'updated_at', 'time_sent', 'time_sent_done', 'provider', 'sent_by',
        'created_by', 'updated_by','object_id','object_type'
    ];

    /**
     * Cập nhật email_log
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }
}