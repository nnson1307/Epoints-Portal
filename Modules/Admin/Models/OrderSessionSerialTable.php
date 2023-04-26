<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class OrderSessionSerialTable extends Model
{
    use ListTableTrait;

    protected $table = "order_session_serial_log";
    protected $primaryKey = "order_session_serial_log_id";
    protected $fillable = [
        "order_session_serial_log_id",
        "session",
        "session",
        "product_code",
        "serial",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    /**
     * Kiểm tra số serial theo session
     */
    public function checkSerial($session,$productCode,$serial){
        return $this
            ->where('session',$session)
            ->where('product_code',$productCode)
            ->where('serial',$serial)
            ->get();
    }

    /**
     * Thêm từng record
     * @param $data
     * @return mixed
     */
    public function addSerialLog($data){
        return $this
            ->insertGetId($data);
    }

    /**
     * Thêm nhiều record
     * @param $data
     * @return mixed
     */
    public function addListSerialLog($data){
        return $this
            ->insert($data);
    }

    /**
     * Kiểm tra số serial theo session
     */
    public function getListSerialLimit($session,$position,$productCode){
        return $this
            ->where('session',$session)
            ->where('position',$position)
            ->where('product_code',$productCode)
            ->limit(5)
            ->orderBy('order_session_serial_log_id','DESC')
            ->get();
    }

    /**
     * Kiểm tra số serial theo session
     */
    public function getListSerialNoLimit($session,$position,$productCode){
        return $this
            ->where('session',$session)
            ->where('position',$position)
            ->where('product_code',$productCode)
            ->orderBy('order_session_serial_log_id','DESC')
            ->get();
    }

    /**
     * Xoá số serial
     */
    public function removeSerial($session,$position,$productCode,$serial){
        return $this
            ->where('session',$session)
            ->where('position',$position)
            ->where('product_code',$productCode)
            ->where('serial',$serial)
            ->delete();
    }

    /**
     * lấy danh sách serial phân trang
     */
    public function getListSerialPage($filter=[]){

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? 100);

        $oSelect = $this;

        if (isset($filter['session'])){
            $oSelect = $oSelect->where('session',$filter['session']);
        }

        if (isset($filter['numberRow'])){
            $oSelect = $oSelect->where('position',$filter['numberRow']);
        }

        if (isset($filter['product_code'])){
            $oSelect = $oSelect->where('product_code',$filter['product_code']);
        }

        if (isset($filter['serial_search'])){
            $oSelect = $oSelect->where('serial','like','%'.$filter['serial_search'].'%');
        }

        return $oSelect->orderBy('order_session_serial_log_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);

    }

    /**
     * lấy tổng số serial theo từng sản phẩm
     */
    public function totalSerial($session,$position,$productCode){
        return $this
            ->where('session',$session)
            ->where('position',$position)
            ->where('product_code',$productCode)
            ->count();
    }

    public function getListProductOrder($filter = []){
        $oSelect = $this;

        if (isset($filter['session'])){
            $oSelect = $oSelect->where('session',$filter['session']);
        }

        if (isset($filter['productCode'])){
            $oSelect = $oSelect->where('product_code',$filter['productCode']);
        }

        return $oSelect->get();
    }
}
