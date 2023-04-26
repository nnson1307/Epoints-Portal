<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 30/07/2021
 * Time: 16:57
 */

namespace Modules\OnCall\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";
    public $timestamps = false;
    protected $fillable
        = [
            'config_id', 'key', 'value', 'name', 'is_show', 'type'
        ];

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->where('key', $key)->first();
    }
}