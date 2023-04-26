<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 2:26 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryPartnerTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_partner";
    protected $primaryKey = "delivery_partner_id";
    protected $fillable = [
        "delivery_partner_id",
        "delivery_partner_name",
        "delivery_partner_avatar",
        "is_active",
        "is_connect",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"

    ];

    const IS_ACTIVE = 1;
    const IS_CONNECT = 1;

    /**
     * lấy danh sách đối tác
     * @return mixed
     */
    public function getListPartner(){
        return $this
            ->select(
                'delivery_partner_id',
                'delivery_partner_name'
            )
            ->where('is_active',self::IS_ACTIVE)
            ->where('is_connect',self::IS_CONNECT)
            ->orderBy('delivery_partner_id','DESC')
            ->get();
    }
}