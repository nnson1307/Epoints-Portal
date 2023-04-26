<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/07/2021
 * Time: 10:29
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerLeadCustomDefineTable extends Model
{
    protected $table = "cpo_customer_lead_custom_define";

    /**
     * Lấy thông tin define
     *
     * @return mixed
     */
    public function getDefine()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "key",
                "title_$lang as title",
                "type"
            )
            ->get();
    }

}