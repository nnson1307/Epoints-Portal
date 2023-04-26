<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 8/23/2021
 * Time: 10:11 AM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractNotifyConfigMethodMapTable extends Model
{
    protected $table = 'contract_notify_config_method_map';
    protected $primaryKey = 'contract_notify_config_method_map_id';
    protected $fillable = [
        "contract_notify_config_method_map_id",
        "contract_notify_config_id",
        "notify_method",
    ];
    public $timestamps = false;

    public function createData($data)
    {
        return $this->create($data);
    }

    public function deleteMap($id)
    {
        return $this->where("contract_notify_config_id", $id)
            ->delete();
    }
}