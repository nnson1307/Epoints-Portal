<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 5:41 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class InventoryOutputTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_outputs';
    protected $primaryKey = 'inventory_output_id';

    protected $fillable = ['inventory_output_id', 'warehouse_id', 'po_code', 'created_by', 'updated_by', 'approved_by', 'created_at', 'updated_at', 'approved_at', 'status', 'type', 'object_id', 'note','inventory_checking_id'];

    /**
     * Insert inventory output to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_output_id;
    }

    protected function _getList(&$filter = [])
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_outputs.created_by')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_outputs.warehouse_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'inventory_outputs.object_id')
            ->select(
                'inventory_outputs.inventory_output_id as id',
                'inventory_outputs.po_code as code',
                'inventory_outputs.type as type',
                'warehouses.name as warehouseName',
                'inventory_outputs.status as status',
                'staffs.full_name as user',
                'inventory_outputs.created_at as createdAt',
                'inventory_outputs.object_id',
                'orders.order_code',
                DB::raw('(SELECT SUM(inventory_output_details.total) as total_detail FROM inventory_output_details where inventory_output_details.inventory_output_id = inventory_outputs.inventory_output_id) as total_money_product')
            )->orderBy($this->primaryKey, 'desc');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween('inventory_outputs.created_at', [$startTime, $endTime]);
        }
        unset($filter["created_at"]);
        return $select;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['status' => 'cancel']);
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        $select = $this->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_outputs.warehouse_id')
            ->select('inventory_outputs.*')
            ->where($this->primaryKey, $id);
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        };
        return $select->first();
    }

    /*
     * detail inventory input
     */
    public function detail($id)
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_outputs.created_by')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_outputs.warehouse_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'inventory_outputs.object_id')
            ->select(
                'inventory_outputs.inventory_output_id as id',
                'inventory_outputs.po_code as code',
                'inventory_outputs.type as type',
                'warehouses.name as warehouseName',
                'inventory_outputs.status as status',
                'staffs.full_name as user',
                'inventory_outputs.created_at as createdAt',
                'inventory_outputs.note as note',
                'inventory_outputs.object_id',
                'orders.order_code',
                DB::raw("(SELECT SUM(inventory_output_details.quantity) FROM inventory_output_details where inventory_output_details.inventory_output_id = inventory_outputs.inventory_output_id) as total_quantity"),
                DB::raw("(SELECT SUM(inventory_output_details.total) FROM inventory_output_details where inventory_output_details.inventory_output_id = inventory_outputs.inventory_output_id) as total_money")
            )->where('inventory_outputs.inventory_output_id', $id);
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        }
        return $select->first();
    }

    public function getList2($filter)
    {
        $select = $this->_getList($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display']);
        if (isset($filter['warehouses']) && $filter['warehouses'] != null) {
            $warehouse = explode(',', $filter['warehouses']);
            $select->whereIn('inventory_outputs.warehouse_id', $warehouse);
            unset($filter['warehouses']);
        }
        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getList1($filter)
    {
        if ($filter != null) {
            $select = $this->_getList($filter);
            $page = (int)($filter['page'] ?? 1);
            $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
            // search term
            $select->whereIn('inventory_outputs.warehouse_id', $filter);
            unset($filter);
            return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        }
    }

    /**
     * Lấy warehouse_id từ phiếu xuất kho theo order_id
     *
     * @param $orderId
     * @param $type
     * @return mixed
     */
    public function getInfoByOrderId($orderId, $type)
    {
        $select = $this->where('object_id', $orderId)
            ->where('type', $type);
        return $select->first();
    }

    /**
     * Cập nhật theo id kiểm kho
     */
    public function editByChecking($data,$inventoryCheckingId){
        return $this->where('inventory_checking_id',$inventoryCheckingId)->update($data);
    }

    /**
     * Lấy cho tiết phiếu theo id checking
     */
    public function getDetailByIdChecking($inventoryCheckingId){
        return $this->where('inventory_checking_id',$inventoryCheckingId)->first();
    }

    /**
     * Xóa phiếu nhập kho
     */
    public function removeByCheckingId($inventoryCheckingId){
        return $this->where('inventory_checking_id',$inventoryCheckingId)->delete();
    }
}
//