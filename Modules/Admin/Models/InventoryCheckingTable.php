<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:39 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class InventoryCheckingTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_checkings';
    protected $primaryKey = 'inventory_checking_id';

    protected $fillable = ['inventory_checking_id', 'warehouse_id', 'checking_code', 'created_by', 'updated_by', 'approved_by', 'created_at', 'updated_at', 'status', 'reason'];

    /**
     * Insert inventory input to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_checking_id;
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
        return $this->where($this->primaryKey, $id)->first();
    }

    protected function _getList(&$filter = [])
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_checkings.created_by')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_checkings.warehouse_id')
            ->select(
                'inventory_checkings.inventory_checking_id as id',
                'inventory_checkings.checking_code as code',
                'warehouses.name as warehouseName',
                'inventory_checkings.status as status',
                'staffs.full_name as user',
                'inventory_checkings.created_at as createdAt'
            )->orderBy($this->primaryKey, 'desc');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween('inventory_checkings.created_at', [$startTime, $endTime]);
        }
        unset($filter["created_at"]);

//        if (isset($filter['search']) != '') {
//            $search = $filter['search'];
//            $select->
//            $select->where('inventory_checkings.checking_code', 'like', '%' . $search . '%');
//        }
        unset($filter['search']);
        return $select;
    }

    /*
     * Detail inventory checking.
     */
    public function detail($id)
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_checkings.created_by')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_checkings.warehouse_id')
            ->select(
                'inventory_checkings.inventory_checking_id as id',
                'inventory_checkings.checking_code as code',
                'warehouses.name as warehouseName',
                'inventory_checkings.status as status',
                'staffs.full_name as user',
                'inventory_checkings.created_at as createdAt',
                'inventory_checkings.reason as reason',
                'inventory_checkings.warehouse_id as warehouse_id'
            )->where('inventory_checkings.inventory_checking_id', $id);
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        }

        return $select->first();
    }

    /*
    * get data edit
    */
    public function getDataEdit($id)
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_checkings.created_by')
            ->select(
                'inventory_checkings.inventory_checking_id as id',
                'inventory_checkings.checking_code as code',
                'inventory_checkings.warehouse_id as warehouseId',
                'inventory_checkings.status as status',
                'staffs.full_name as user',
                'inventory_checkings.created_at as createdAt',
                'inventory_checkings.reason as reason'
            )->where('inventory_checkings.inventory_checking_id', $id)->first();
        return $select;
    }

    public function getList1($filter)
    {
        if ($filter != null) {
            $select = $this->_getList($filter);
            $page = (int)($filter['page'] ?? 1);
            $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
            // search term
            $select->whereIn('inventory_checkings.warehouse_id', $filter);
            unset($filter);
            return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        }
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
            $select->whereIn('inventory_checkings.warehouse_id', $warehouse);
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
}
//