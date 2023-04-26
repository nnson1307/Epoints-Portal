<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 2:33 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryDetailTable extends Model
{
    public $timestamps = false;
    protected $table = "delivery_detail";
    protected $primaryKey = "delivery_detail_id";
    protected $fillable = [
        "delivery_detail_id",
        "delivery_history_id",
        "object_id",
        "quantity",
        "note",
        "sku",
        "object_type",
        "price"
    ];

    /**
     * Thêm chi tiết phiếu giao hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy thông tin chi tiết giao hàng
     *
     * @param $historyId
     * @return mixed
     */
    public function getInfo($historyId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_detail_id",
                "{$this->table}.delivery_history_id",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.quantity",
                "{$this->table}.note",
                "{$this->table}.price",
//                "pr.product_code",
//                "pr.product_child_name as product_name",
                "{$this->table}.sku",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'product' THEN product_childs.product_code
                    WHEN  {$this->table}.object_type = 'service' THEN services.service_code
                    WHEN  {$this->table}.object_type = 'service_card' THEN service_cards.code

                    WHEN  {$this->table}.object_type = 'product_gift' THEN product_childs.product_code
                    WHEN  {$this->table}.object_type = 'service_gift' THEN services.service_code
                    WHEN  {$this->table}.object_type = 'service_card_gift' THEN service_cards.code
                    END
                ) as product_code"),
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'product' THEN product_childs.product_child_name
                    WHEN  {$this->table}.object_type = 'service' THEN services.service_name
                    WHEN  {$this->table}.object_type = 'service_card' THEN service_cards.name

                    WHEN  {$this->table}.object_type = 'product_gift' THEN product_childs.product_child_name
                    WHEN  {$this->table}.object_type = 'service_gift' THEN services.service_name
                    WHEN  {$this->table}.object_type = 'service_card_gift' THEN service_cards.name
                    END
                ) as product_name")
            )
            ->leftJoin("product_childs", "product_childs.product_child_id", "=", "{$this->table}.object_id")
            ->leftJoin("services", "services.service_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "{$this->table}.object_id")

            ->where("{$this->table}.delivery_history_id", $historyId)
            ->get();
    }

    /**
     * Lấy số lượng sản phẩm đã được giao
     *
     * @param $historyId
     * @return mixed
     */
    public function getQuantityDetail($historyId)
    {
        return $this
            ->select(
                "delivery_detail_id",
                DB::raw("SUM(quantity)")
            )
            ->groupBy("delivery_history_id")
            ->where("delivery_history_id", $historyId)
            ->get();
    }

    /**
     * Lấy số lượng sản phẩm đã được giao
     *
     * @param $historyId
     * @return mixed
     */
    public function getProductDelivered($historyId)
    {
        return $this
            ->select(
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                DB::raw("SUM(quantity) as quantity")
            )
            ->join("delivery_history", "delivery_history.delivery_history_id", "=", "{$this->table}.delivery_history_id")
            ->groupBy("object_type","object_id")
            ->where("delivery_history.delivery_id", $historyId)
            ->whereIn("delivery_history.status", ["success", "confirm"])
            ->get();
    }

    public function checkSKU($sku)
    {
        $oSelect = $this->where('sku',$sku)->count();
        return $oSelect;
    }

    /**
     * Cập nhật chi tiết phiếu giao hàng
     * @param $data
     * @param $delivery_detail_id
     * @return mixed
     */
    public function updateHistory($data,$delivery_detail_id){
        return $this
            ->where('delivery_detail_id',$delivery_detail_id)
            ->update($data);
    }
}