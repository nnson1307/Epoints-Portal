<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/6/2021
 * Time: 12:25 PM
 */

namespace Modules\ZNS\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarrantyCardTable extends Model
{
    protected $table = "warranty_card";
    protected $primaryKey = "warranty_card_id";

    const ACTIVE = 'actived';
    const NEW = 'new';
    const LIMIT_HOME = 6;
    const FINISH = "finish";

    /**
     * Lấy thông tin phiếu bảo hành
     *
     * @param $warrantyCode
     * @return mixed
     */
    public function getInfo($warranty_card_id)
    {
        $urlProduct = 'http://' . request()->getHttpHost() . '/static/images/product.png';
        $urlService = 'http://' . request()->getHttpHost() . '/static/images/service.png';
        $urlServiceCard = 'http://' . request()->getHttpHost() . '/static/images/service-card.png';

        return $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'product' && products.avatar IS NOT NULL THEN products.avatar
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NOT NULL THEN services.service_avatar
                    WHEN  {$this->table}.object_type = 'service_card' && service_cards.image IS NOT NULL THEN service_cards.image
                    
                    WHEN  {$this->table}.object_type = 'product' && products.avatar IS NULL THEN '{$urlProduct}'
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NULL THEN '{$urlService}'
                    WHEN  {$this->table}.object_type = 'service_card' && service_cards.image IS NULL THEN '{$urlServiceCard}'
                    
                    END
                ) as object_image"),
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'product' THEN product_childs.product_child_name
                    WHEN  {$this->table}.object_type = 'service' THEN services.service_name
                    WHEN  {$this->table}.object_type = 'service_card' THEN service_cards.name
             
                    END
                ) as object_name"),
                "{$this->table}.status",
                "{$this->table}.quota"
            )
            ->leftJoin("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
            ->leftJoin("products", "products.product_id", "=", "product_childs.product_id")
            ->leftJoin("services", "services.service_code", "=", "{$this->table}.object_code")
            ->leftJoin("service_cards", "service_cards.code", "=", "{$this->table}.object_code")
            ->where("{$this->table}.warranty_card_id", $warranty_card_id)
            ->first();
    }
}