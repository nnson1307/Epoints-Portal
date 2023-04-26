<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryOutputDetailSerialTable extends Model
{
    protected $table = 'inventory_output_detail_serial';
    protected $primaryKey = 'inventory_output_detail_serial_id';
    protected $fillable = [
        'inventory_output_detail_serial_id','inventory_output_detail_id','product_code',
        'serial' ,'barcode','created_at','updated_at'
    ];

    /**
     * Lưu cấu hình sản phẩm theo serial
     * @param $data
     */
    public function insertListSerial($data){
        return $this->insert($data);
    }

    /**
     * Kiểm tra số serial đã tồn tại theo sản phẩm
     * @param $product_code
     * @param $serial
     */
    public function checkSerial($product_code,$serial,$idInventoryOutputDetail = null){
        $oSelect = $this
            ->where('product_code',$product_code)
            ->where('serial',$serial);

        if ($idInventoryOutputDetail != null){
            $oSelect = $oSelect->where('inventory_output_detail_id',$idInventoryOutputDetail);
        }

        return $oSelect->first();
    }

    /**
     * Xoá danh sác serial theo input detail
     * @param $inventory_output_detail_id
     */
    public function deleteSerialInput($inventory_output_detail_id){
        return $this->where('inventory_output_detail_id',$inventory_output_detail_id)->delete();
    }

    /**
     * Lấy danh sách serial theo detail
     */
    public function getListSerialByDetailLimit($arr_inventory_output_detail_id){
        return $this
            ->select(
                'inventory_output_detail_serial_id',
                'inventory_output_detail_id',
                'product_code',
                'serial',
                'barcode'
            )
            ->whereIn('inventory_output_detail_id',$arr_inventory_output_detail_id)
            ->orderBy('inventory_output_detail_serial_id','DESC')
            ->get();
    }

    /**
     * Lấy danh sách serial theo detail
     */
    public function getListSerialByDetail($arr_inventory_output_detail_id){
        return $this
            ->select(
                'inventory_output_detail_serial_id',
                'inventory_output_detail_id',
                'product_code',
                'serial',
                'barcode'
            )
            ->whereIn('inventory_output_detail_id',$arr_inventory_output_detail_id)
            ->orderBy('inventory_output_detail_serial_id','DESC')
            ->get();
    }

    /**
     * lấy danh sách serial có phân trang
     * @param $filter
     * @return mixed
     */
    public function getListSerialPaging($filter){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? 100);

        $oSelect = $this
            ->select(
                'inventory_output_detail_serial_id',
                'inventory_output_detail_id',
                'product_code',
                'serial',
                'barcode'
            )
            ->where('inventory_output_detail_id',$filter['inventory_output_detail_id'])
            ->orderBy('inventory_output_detail_serial_id','DESC');

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where('serial','like','%'.$filter['serial'].'%');
        }

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Xoá serial theo id
     * @param $inventory_output_detail_serial_id
     */
    public function deleteSerialById($inventory_output_detail_serial_id){
        return $this->where('inventory_output_detail_serial_id',$inventory_output_detail_serial_id)->delete();
    }

    /**
     * Xoá tất cả số serial theo chi tiết phiếu
     * @param $inventory_output_id
     */
    public function removeSerialByDetail($inventory_output_id){
        return $this
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->where('inventory_output_details.inventory_output_id',$inventory_output_id)
            ->delete();
    }

    /**
     * Lấy danh sách serial của phiếu xuất
     */
    public function getListSerial($inventory_output_id){
        return $this
            ->select(
                $this->table.'.product_code',
                $this->table.'.serial',
                DB::raw("CONCAT({$this->table}.product_code,'-',{$this->table}.serial) as key_group")
            )
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->where('inventory_output_details.inventory_output_id',$inventory_output_id)
            ->get();
    }

    /**
     * Xoá serial theo kiểm kho
     */
    public function removeProductByChecking($productCode, $idChecking){
        return $this
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->join('inventory_outputs','inventory_outputs.inventory_output_id','inventory_output_details.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$idChecking)
            ->where($this->table.'.product_code',$productCode)
            ->where('inventory_outputs.status','<>','success')
            ->delete();
    }

    /**
     * Xoá serial theo kiểm kho
     */
    public function removeSerialByChecking($productCode, $idChecking, $serial){
        return $this
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->join('inventory_outputs','inventory_outputs.inventory_output_id','inventory_output_details.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$idChecking)
            ->where($this->table.'.product_code',$productCode)
            ->where($this->table.'.serial',$serial)
            ->where('inventory_outputs.status','<>','success')
            ->delete();
    }

    /**
     * Xoá số serial theo code
     */
    public function removeSerialByCode($arrCode){
        return $this
            ->where('product_code',$arrCode)
            ->delete();
    }

    /**
     * Lấy danh sách số serial theo input id
     */
    public function getListSerialByOutputId($inputId){
        return $this
            ->select(
                $this->table.'.product_code',
                $this->table.'.serial'
            )
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->where('inventory_output_details.inventory_output_id',$inputId)
            ->get();
    }

    public function getTotalSerialOutput($inventory_output_id,$product_code){
        return $this
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->where('inventory_output_details.inventory_output_id',$inventory_output_id)
            ->where($this->table.'.product_code',$product_code)
            ->count();
    }

    /**
     * Lấy danh sách serial by checking
     */
    public function getListSerialOutputByChecking($inventoryCheckingId){
        return $this
            ->select(
                $this->table.'.inventory_output_detail_serial_id',
                $this->table.'.inventory_output_detail_id',
                $this->table.'.product_code',
                $this->table.'.serial'
            )
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->join('inventory_outputs','inventory_outputs.inventory_output_id','inventory_output_details.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$inventoryCheckingId)
            ->get();
    }

    /**
     * Lấy danh sách serial có phân trang
     * @param array $filter
     */
    public function getListSerialExport($filter=[]){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.inventory_output_detail_serial_id',
                $this->table.'.inventory_output_detail_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                'inventory_checking_status.name as inventory_checking_status_name'
            )
            ->join('product_inventory_serial','product_inventory_serial.serial',$this->table.'.serial')
            ->join('inventory_checking_status','inventory_checking_status.inventory_checking_status_id','product_inventory_serial.inventory_checking_status_id')
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->join('inventory_outputs','inventory_outputs.inventory_output_id','inventory_output_details.inventory_output_id');

        if (isset($filter['inventory_checking_id'])){
            $oSelect = $oSelect->where('inventory_outputs.inventory_checking_id',$filter['inventory_checking_id']);
        }

        if (isset($filter['checking_status'])){
            $oSelect = $oSelect->where('product_inventory_serial.inventory_checking_status_id',$filter['checking_status']);
        }

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where($this->table.'.serial','like','%'.$filter['serial'].'%');
        }

        return $oSelect->orderBy($this->table.'.inventory_output_detail_serial_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

//    Lấy tổng số serial export
    public function getTotalSerialExport($inventory_checking_id,$productCode = null){
        $oSelect = $this
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->join('inventory_outputs','inventory_outputs.inventory_output_id','inventory_output_details.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$inventory_checking_id);

        if ($productCode != null){
            $oSelect = $oSelect->where($this->table.'.product_code',$productCode);
        }

        return $oSelect->count();
    }

    /**
     * Xóa danh sách serial theo id kiểm kho
     * @param $inventory_checking_id
     * @return mixed
     */
    public function removeDetailSerialByCheckingId($inventory_checking_id){
        return $this
            ->join('inventory_output_details','inventory_output_details.inventory_output_detail_id',$this->table.'.inventory_output_detail_id')
            ->join('inventory_outputs','inventory_outputs.inventory_output_id','inventory_output_details.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$inventory_checking_id)
            ->delete();
    }

}