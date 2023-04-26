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

class ContractConfigStatusDefaultTable extends Model
{
    protected $table = 'contract_config_status_default';
    protected $primaryKey = 'contract_config_status_default_id';
    protected $fillable = [
        "contract_config_status_default_id",
        "status_name_vi",
        "status_name_en",
        "default_system",
    ];
}