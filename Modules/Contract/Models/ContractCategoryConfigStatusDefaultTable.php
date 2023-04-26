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

class ContractCategoryConfigStatusDefaultTable extends Model
{
    protected $table = 'contract_category_config_status_default';
    protected $primaryKey = 'contract_category_config_status_default_id';
    protected $fillable = [
        "contract_category_config_status_default_id",
        "status_name_vi",
        "status_name_en",
        "default_system",
    ];

    public function getList()
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "contract_category_config_status_default_id",
            "status_name_$lang as status_name",
            "default_system"
        );
        return $data->get()->toArray();
    }
}