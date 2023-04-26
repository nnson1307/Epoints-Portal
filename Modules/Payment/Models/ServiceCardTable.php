<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 10/01/2022
 * Time: 19:47
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    /**
     * Lấy thông tin thẻ dịch vụ
     *
     * @param $code
     * @return mixed
     */
    public function getInfo($code)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "service_is_all",
                "service_id",
                "service_card_type",
                "date_using",
                "number_using",
                "price",
                "money",
                "is_actived",
                "code",
                "type_refer_commission",
                "refer_commission_value",
                "type_staff_commission",
                "staff_commission_value"
            )
            ->where("code", $code)
            ->first();
    }

    /**
     * Lấy thông tin thẻ dịch vụ khuyến mãi
     *
     * @param $serviceCardCode
     * @return mixed
     */
    public function getServiceCardPromotion($serviceCardCode)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "code",
                "price as new_price"
            )
            ->where("code", $serviceCardCode)
            ->first();
    }
}