<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ZNS\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TriggerParamsTable extends Model
{
    use ListTableTrait;
    protected $table = 'zns_trigger_params';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'zns_trigger_config_id',
        'params_id',
    ];

    public function getParamsByTriggerConfig($zns_trigger_config_id)
    {
        return $this->select("params.value")
        ->leftjoin("params","params.params_id","zns_trigger_params.params_id")->where("{$this->table}.zns_trigger_config_id",$zns_trigger_config_id)->get();
    }
    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
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