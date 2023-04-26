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

class ContractConfigTabDefaultTable extends Model
{
    protected $table = 'contract_config_tab_default';
    protected $primaryKey = 'contract_config_tab_default_id';
    protected $fillable = [
        "contract_config_tab_default_id",
        "tab",
        "key",
        "type",
        "key_name_vi",
        "key_name_en",
        "key_name_en",
        "is_validate",
    ];
}