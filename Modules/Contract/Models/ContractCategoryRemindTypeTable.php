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

class ContractCategoryRemindTypeTable extends Model
{
    protected $table = 'contract_category_remind_type';
    protected $primaryKey = 'contract_category_remind_type_id';
    protected $fillable = [
        "contract_category_remind_type_id",
        "remind_type_code",
        "remind_type_name_vi",
        "remind_type_name_en",
        "limit",
    ];

    /**
     * Ds remind (option select)
     *
     * @return mixed
     */
    public function getListRemindType()
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "contract_category_remind_type_id",
            "remind_type_code",
            "remind_type_name_$lang as remind_type_name",
            "limit");
        return $data->get()->toArray();
    }

    /**
     * Thông tin 1 remind type
     *
     * @param $typeCode
     * @return mixed
     */
    public function getItem($typeCode){
        $lang = app()->getLocale();
        $data = $this->select(
            "contract_category_remind_type_id",
            "remind_type_code",
            "remind_type_name_$lang as remind_type_name",
            "limit")->where('remind_type_code', $typeCode);
        return $data->first();
    }
}