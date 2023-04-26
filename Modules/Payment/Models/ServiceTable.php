<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 10/01/2022
 * Time: 19:47
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";

    /**
     * Lấy thông tin dịch vụ
     *
     * @param $serviceId
     * @return mixed
     */
    public function getInfo($serviceId)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "service_category_id",
                "service_code",
                "is_sale",
                "price_standard",
                "service_type",
                "time",
                "have_material",
                "description",
                "detail_description",
                "is_actived",
                "service_avatar",
                "is_sale",
                "type_refer_commission",
                "refer_commission_value",
                "type_staff_commission",
                "staff_commission_value",
                "type_deal_commission",
                "deal_commission_value",
                "is_surcharge",
                "is_remind",
                "remind_value"
            )
            ->where("service_id", $serviceId)
            ->first();
    }

    /**
     * Lấy thông tin dịch vụ khuyến mãi
     *
     * @param $serviceCode
     * @return mixed
     */
    public function getServicePromotion($serviceCode)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "service_code",
                "price_standard as new_price"
            )
            ->where("service_code", $serviceCode)
            ->first();
    }
}