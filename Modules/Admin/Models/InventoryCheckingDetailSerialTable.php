<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryCheckingDetailSerialTable extends Model
{
    protected $table = 'inventory_checking_detail_serial';
    protected $primaryKey = 'inventory_input_detail_serial_id';
    protected $fillable = [
        'inventory_input_detail_serial_id','inventory_input_detail_id','product_code',
        'serial' ,'barcode','created_at','updated_at','is_new'
    ];


    /**
     * Lấy danh sách serial theo detail
     */
    public function getListSerialByDetailLimit($arr_inventory_checking_detail_id){
        return $this
            ->select(
                $this->table.'.inventory_checking_detail_serial_id',
                $this->table.'.inventory_checking_detail_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                $this->table.'.is_new',
                'inventory_checking_status.is_default',
                'inventory_checking_status.name as inventory_checking_status_name',
                $this->table.'.inventory_checking_status_id'
            )
            ->join('inventory_checking_status','inventory_checking_status.inventory_checking_status_id',$this->table.'.inventory_checking_status_id')
            ->whereIn('inventory_checking_detail_id',$arr_inventory_checking_detail_id)
            ->orderBy('inventory_checking_detail_serial_id','DESC')
            ->get();
    }

    /**
     * lấy danh sách serial có phân trang
     * @param $filter
     * @return mixed
     */
    public function getListSerialPaging($filter){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.inventory_checking_detail_serial_id',
                $this->table.'.inventory_checking_detail_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                $this->table.'.barcode',
                $this->table.'.inventory_checking_status_id',
                $this->table.'.is_new',
                'inventory_checking_status.name',
                'inventory_checking_details.type_resolve',
                'product_inventory_serial.product_inventory_serial_id'
            )
            ->leftJoin('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->leftJoin('inventory_checkings','inventory_checkings.inventory_checking_id','inventory_checking_details.inventory_checking_id')
            ->leftJoin('inventory_checking_status','inventory_checking_status.inventory_checking_status_id',$this->table.'.inventory_checking_status_id')
            ->leftJoin('product_inventory_serial',function ($sql){
                $sql
                    ->on('product_inventory_serial.product_code',$this->table.'.product_code')
//                    ->on('product_inventory_serial.serial',$this->table.'.serial')
                    ->where('product_inventory_serial.status','new');
            })
            ->orderBy($this->table.'.inventory_checking_detail_serial_id','DESC');

        if(isset($filter['inventory_checking_detail_id'])){
            $oSelect = $oSelect->where($this->table.'.inventory_checking_detail_id',$filter['inventory_checking_detail_id']);
        }

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where($this->table.'.serial','like','%'.$filter['serial'].'%');
        }

        if (isset($filter['type_resolve'])){
            $oSelect = $oSelect
                ->where('inventory_checking_details.type_resolve','like','%'.$filter['type_resolve'].'%');
        }

        if (isset($filter['is_new'])){
            if ($filter['is_new'] == 1){
                $oSelect = $oSelect->where($this->table.'.is_new',$filter['is_new']);
            }else if ($filter['is_new'] == 0) {
                $oSelect = $oSelect
                    ->where($this->table.'.is_new',$filter['is_new'])
                    ->whereNull('product_inventory_serial.product_inventory_serial_id');
            } else {
                $oSelect = $oSelect
                    ->where($this->table.'.is_new',0)
                    ->whereNotNull('product_inventory_serial.product_inventory_serial_id');
            }
        }

        if (isset($filter['checking_status'])){
            $oSelect = $oSelect->where($this->table.'.inventory_checking_status_id','like','%'.$filter['checking_status'].'%');
        }

        return $oSelect->groupBy($this->table.'.serial')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Thêm serial
     * @param $data
     */
    public function addSerial($data){
        return $this->insert($data);
    }

    /**
     * Kiểm tra số serial đã tồn tại theo sản phẩm
     * @param $product_code
     * @param $serial
     */
    public function checkSerial($product_code,$serial,$idInventoryCheckingDetail = null){
        $oSelect = $this
            ->where('product_code',$product_code)
            ->where('serial',$serial);

        if ($idInventoryCheckingDetail != null){
            $oSelect = $oSelect->where('inventory_checking_detail_id',$idInventoryCheckingDetail);
        }

        return $oSelect->first();
    }

    /**
     * Thêm số serial
     * @param $data
     */
    public function insertListSerial($data){
        return $this->insert($data);
    }

    /**
     * Thêm số serial
     * @param $data
     */
    public function insertSerial($data){
        return $this->insertGetId($data);
    }

    /**
     * Xoá số serial
     */
    public function deleteSerialById($serialId){
        return $this->where('inventory_checking_detail_serial_id',$serialId)->delete();
    }

    /**
     * Lấy tổng số lượng serial
     */
    public function getTotalSerial($inventory_checking_id,$productCode){
        return $this
            ->join('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->where('inventory_checking_details.inventory_checking_id',$inventory_checking_id)
            ->where($this->table.'.product_code',$productCode)
            ->count();
    }

    public function removeSerialByDetail($inventory_checking_detail_id){
        return $this
            ->where($this->table.'.inventory_checking_detail_id',$inventory_checking_detail_id)
            ->delete();
    }

    /**
     * Xoá số serial by code
     * @param $arrCode
     */
    public function removeSerialByCode($arrCode){
        return $this
            ->where('product_code',$arrCode)
            ->delete();
    }

    /**
     * Lấy danh sách số serial
     */
    public function getListSerial($inventory_checking_id){
        return $this
            ->select(
                $this->table.'.product_code',
                $this->table.'.serial',
                $this->table.'.is_new',
                'inventory_checking_details.type_resolve'
            )
            ->join('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->where('inventory_checking_details.inventory_checking_id',$inventory_checking_id)
            ->where('inventory_checking_details.type_resolve','<>','not')
            ->where($this->table.'.is_new',1)
            ->get();
    }

    /**
     * Lấy danh sách serial nhập kho
     */
    public function getListSerialImport($filter = []){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.inventory_checking_detail_serial_id',
                $this->table.'.inventory_checking_detail_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                'inventory_checking_details.type_resolve',
                'inventory_checking_status.name as inventory_checking_status_name'
            )
            ->leftJoin('inventory_checking_status','inventory_checking_status.inventory_checking_status_id',$this->table.'.inventory_checking_status_id')
            ->join('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->where($this->table.'.inventory_checking_detail_id',$filter['inventory_checking_detail_id'])
            ->where($this->table.'.is_new',1);

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where($this->table.'.serial','like','%'.$filter['serial'].'%');
        }

        if (isset($filter['checking_status'])){
            $oSelect = $oSelect->where($this->table.'.inventory_checking_status_id','like','%'.$filter['checking_status'].'%');
        }

        return $oSelect->orderBy($this->table.'.inventory_checking_detail_serial_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy danh sách serial nhập kho
     */
    public function getListSerialExport($inventory_checking_detail_id){
        return $this
            ->select(
                $this->table.'.product_code',
                $this->table.'.serial',
                'inventory_checking_details.type_resolve'
            )
            ->join('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->where($this->table.'.inventory_checking_detail_id',$inventory_checking_detail_id)
            ->where($this->table.'.is_new',0)
            ->get();
    }

    /**
     * Lấy danh sách serial theo chi tiết sản phẩm kiểm kho
     */
    public function getListSerialByCodeDetail($inventory_checking_id,$product_code){
        return $this
            ->select($this->table.'.serial')
            ->join('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->where('inventory_checking_details.inventory_checking_id',$inventory_checking_id)
            ->where($this->table.'.product_code',$product_code)
            ->where($this->table.'.is_new',0)
            ->get();
    }

    /**
     * Lấy danh sách serial checking
     * @param $inventory_checking_id
     * @return mixed
     */
    public function getListSerialChecking($inventory_checking_id,$product_code){
        return $this
            ->join('inventory_checking_details','inventory_checking_details.inventory_checking_detail_id',$this->table.'.inventory_checking_detail_id')
            ->where('inventory_checking_details.inventory_checking_id',$inventory_checking_id)
            ->where($this->table.'.product_code',$product_code)
            ->get();
    }
}