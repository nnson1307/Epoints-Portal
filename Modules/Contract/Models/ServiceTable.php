<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/09/2021
 * Time: 11:01
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";

    const SURCHARGE = 0;

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
                "service_code",
                "service_name",
                "price_standard as price"
            )
            ->where("service_id", $serviceId)
            ->first();
    }
}