<?php

namespace Modules\ZNS\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->where('key', $key)->first();
    }
}