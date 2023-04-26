<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/18/2019
 * Time: 2:13 PM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;

class SmsConfigTable extends Model
{
    protected $table = 'sms_config';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'key', 'value', 'time_sent', 'name', 'content', 'is_active', 'created_by', 'updated_by', 'created_at', 'updated_at', 'actived_by', 'datetime_actived'];

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function activeConfig($param, $data)
    {
        return $this->where('key', $param)->update($data);
    }

    //Lấy tất cả loại tin nhắn.
    public function getAllKey()
    {
        $select = $this->select('key', 'value', 'name', 'content', 'is_active', 'time_sent')->get()->toArray();
        return $select;
    }

    public function getItemByType($type)
    {
        return $this->where('key', $type)->first();
    }
}