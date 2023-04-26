<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/12/2018
 * Time: 9:33 AM
 */

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class InventoryInputTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_inputs';
    protected $primaryKey = 'inventory_input_id';

    protected $fillable = ['inventory_input_id', 'warehouse_id', 'supplier_id', 'pi_code', 'created_by', 'updated_by', 'approved_by', 'created_at', 'updated_at', 'approved_at', 'status', 'user_recived', 'date_recived', 'object_id', 'type', 'note','inventory_checking_id'];

    protected function _getList(&$filter = [])
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_inputs.created_by')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_inputs.warehouse_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'inventory_inputs.supplier_id')
            ->select(
                'inventory_inputs.inventory_input_id as id',
                'inventory_inputs.pi_code as code',
                'inventory_inputs.type as type',
                'warehouses.name as warehouseName',
                'inventory_inputs.status as status',
                'suppliers.supplier_name as supplierName',
                'staffs.full_name as user',
                'inventory_inputs.created_at as createdAt',
                DB::raw('(SELECT SUM(inventory_input_details.total) as total_detail FROM inventory_input_details where inventory_input_details.inventory_input_id = inventory_inputs.inventory_input_id) as total_money_product')
            )->orderBy($this->primaryKey, 'desc');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            if ($startTime == $endTime) {
                $select->whereBetween('inventory_inputs.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            } else {
                $select->whereBetween('inventory_inputs.created_at', [$startTime, $endTime]);
            }
        }
        unset($filter["created_at"]);
        return $select;
    }

    /**
     * Insert inventory input to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_input_id;
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
        $select = $this ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_inputs.warehouse_id')
            ->where($this->primaryKey, $id)
            ->select(
                'inventory_inputs.inventory_input_id as inventory_input_id',
                'inventory_inputs.warehouse_id as warehouse_id',
                'inventory_inputs.supplier_id as supplier_id',
                'inventory_inputs.pi_code as pi_code',
                'inventory_inputs.created_by as created_by',
                'inventory_inputs.updated_by as updated_by',
                'inventory_inputs.approved_by as approved_by',
                'inventory_inputs.created_at as created_at',
                'inventory_inputs.updated_at as updated_at',
                'inventory_inputs.approved_at as approved_at',
                'inventory_inputs.status as status',
                'inventory_inputs.user_recived as user_recived',
                'inventory_inputs.date_recived as date_recived',
                'inventory_inputs.object_id as object_id',
                'inventory_inputs.type as type',
                'inventory_inputs.note as note'
            );
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
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_inputs.created_by')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_inputs.warehouse_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'inventory_inputs.supplier_id')
            ->select(
                'inventory_inputs.inventory_input_id as id',
                'inventory_inputs.pi_code as code',
                'inventory_inputs.type as type',
                'warehouses.name as warehouseName',
                'inventory_inputs.status as status',
                'suppliers.supplier_name as supplierName',
                'staffs.full_name as user',
                'inventory_inputs.created_at as createdAt',
                'inventory_inputs.note as note',
                DB::raw("(SELECT SUM(inventory_input_details.quantity) FROM inventory_input_details where inventory_input_details.inventory_input_id = inventory_inputs.inventory_input_id) as total_quantity"),
                DB::raw("(SELECT SUM(inventory_input_details.total) FROM inventory_input_details where inventory_input_details.inventory_input_id = inventory_inputs.inventory_input_id) as total_money")
            )->where('inventory_inputs.inventory_input_id', $id);
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        };
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
            $select->whereIn('inventory_inputs.warehouse_id', $warehouse);
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
            $select->whereIn('inventory_inputs.warehouse_id', $filter);
            unset($filter);
            return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        }
    }

    /**
     * Cập nhật theo id kiểm kho
     * @param $data
     * @param $inventoryCheckingId
     */
    public function editByCheckingId($data,$inventoryCheckingId){
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