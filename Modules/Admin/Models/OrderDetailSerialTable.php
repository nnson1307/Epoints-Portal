<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/21/2019
 * Time: 11:43 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailSerialTable extends Model
{
    protected $table = "order_detail_serial";
    protected $primaryKey = "order_detail_serial_id";
    public $timestamps = false;
    protected $fillable
        = [
            'order_detail_serial_id','order_id','order_detail_id', 'product_code', 'serial', 'created_at', 'created_by','updated_at','updated_by'
        ];

    /**
     * Lưu thông tin serial
     * @param $data
     */
    public function insertSerial($data){
        return $this->insert($data);
    }

    public function getListSerialByOrderId($order_id){
        return $this
            ->select('order_detail_serial_id','order_id','order_detail_id', 'product_code', 'serial')
            ->where('order_id',$order_id)
            ->orderBy('order_detail_serial_id','DESC')
            ->get();
    }

    /**
     * Lấy danh sách không orderBy
     * @param $order_id
     * @return mixed
     */
    public function getListSerialByOrderIdNotOrderBy($order_id){
        return $this
            ->select('order_detail_serial_id','order_id','order_detail_id', 'product_code', 'serial')
            ->where('order_id',$order_id)
            ->get();
    }

    /**
     * lấy danh sách serial phân trang
     */
    public function getListSerialPage($filter=[]){

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? 100);

        $oSelect = $this;

        if (isset($filter['order_detail_id'])){
            $oSelect = $oSelect->where('order_detail_id',$filter['order_detail_id']);
        }

        if (isset($filter['serial_search'])){
            $oSelect = $oSelect->where('serial','like','%'.$filter['serial_search'].'%');
        }

        return $oSelect->orderBy('order_detail_serial_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);

    }

    /**
     * Lấy danh sách serial theo id đơn hàng
     */
    public function getListSerialByOrder($orderId,$productCode){
        return $this
            ->where('order_id',$orderId)
            ->where('product_code',$productCode)
            ->get();
    }

    /**
     * Xoá serial theo đơn hàng
     */
    public function removeSerial($orderId){
        return $this
            ->where('order_id',$orderId)
            ->delete();
    }

}