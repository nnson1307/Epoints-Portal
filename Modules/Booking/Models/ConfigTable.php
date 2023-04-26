<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/22/2019
 * Time: 11:21 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";
    protected $fillable
        = [
            'config_id', 'key', 'value', 'updated_at'
        ];

    /**
     * Edit
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->where('config_id', $id)->first();
    }
}