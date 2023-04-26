<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/18/2019
 * Time: 9:16 AM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class SmsProviderTable extends Model
{
    protected $table = 'sms_setting_brandname';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'provider', 'value', 'type', 'account', 'password', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_actived'];

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}