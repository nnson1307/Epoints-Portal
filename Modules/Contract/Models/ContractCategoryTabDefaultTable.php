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

class ContractCategoryTabDefaultTable extends Model
{
    protected $table = 'contract_category_tab_default';
    protected $primaryKey = 'contract_category_config_tab_id';
    protected $fillable = [
        "contract_category_config_tab_id",
        "tab",
        "key",
        "type",
        "key_name_vi",
        "key_name_en",
        "is_show",
        "is_validate",
        "number_col"
    ];

    public function getTabDefaultByType($tab)
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "tab",
            "key",
            "type",
            "key_name_$lang as key_name",
            "is_show",
            "is_validate",
            "number_col"
        )->where("tab", $tab);
        return $data->get()->toArray();
    }
}