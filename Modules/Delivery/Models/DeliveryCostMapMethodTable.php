<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 12:09 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryCostMapMethodTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_cost_map_method";
    protected $primaryKey = "delivery_cost_map_method_id";
    protected $fillable = [
        "delivery_cost_map_method_id",
        "delivery_cost_id",
        "delivery_method_config_id",
        "delivery_cost",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    /**
     * Tạo thông tin giao hàng
     */
    public function insertMethod($data){
        return $this->insert($data);
    }

    /**
     * Lấy danh sách cấu hình method
     * @param $deliveryCostId
     */
    public function getListByCostId($deliveryCostId){
        return $this
            ->select(
                $this->table.'.delivery_cost_map_method_id',
                $this->table.'.delivery_cost_id',
                $this->table.'.delivery_method_config_id',
                $this->table.'.delivery_cost',
                'delivery_method_config.delivery_method_name',
                'delivery_method_config.delivery_method_code'
            )
            ->join('delivery_method_config','delivery_method_config.delivery_method_config_id',$this->table.'.delivery_method_config_id')
            ->where('delivery_cost_id',$deliveryCostId)
            ->get();
    }

    /**
     * Xoá danh sách phương thức giao hàng
     * @param $deliveryCostId
     */
    public function deleteByCostId($deliveryCostId){
        return $this
            ->where('delivery_cost_id',$deliveryCostId)
            ->delete();
    }
}