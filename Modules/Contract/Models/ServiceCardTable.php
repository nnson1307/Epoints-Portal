<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/09/2021
 * Time: 11:01
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    const SURCHARGE = 0;

    /**
     * Lấy thông tin thẻ dịch vụ
     *
     * @param $serviceCardId
     * @return mixed
     */
    public function getInfo($serviceCardId)
    {
        return $this
            ->select(
                "service_card_id",
                "code",
                "name",
                "price"
            )
            ->where("service_card_id", $serviceCardId)
            ->first();
    }
}