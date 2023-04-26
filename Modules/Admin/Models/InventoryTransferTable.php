<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 4:12 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class InventoryTransferTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_tranfers';
    protected $primaryKey = 'inventory_tranfer_id';

    protected $fillable = ['inventory_tranfer_id', 'warehouse_to', 'warehouse_from', 'transfer_code', 'created_by', 'updated_by', 'approved_by', 'created_at', 'updated_at', 'approved_at', 'transfer_at', 'status', 'note'];

    /**
     * Insert inventory transfer to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_tranfer_id;
    }

    protected function _getList(&$filter = [])
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_tranfers.created_by')
            ->leftJoin('warehouses as w1', 'w1.warehouse_id', '=', 'inventory_tranfers.warehouse_to')
            ->leftJoin('warehouses as w2', 'w2.warehouse_id', '=', 'inventory_tranfers.warehouse_from')
            ->select(
                'inventory_tranfers.inventory_tranfer_id as id',
                'inventory_tranfers.transfer_code as transferCode',
                'inventory_tranfers.status as status',
                'staffs.full_name as user',
                'inventory_tranfers.created_at as createdAt',
                'w1.name as warehouseTo',
                'w2.name as warehouseFrom'
            )->orderBy($this->primaryKey, 'desc');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween('inventory_tranfers.created_at', [$startTime, $endTime]);
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
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
     * detail inventory transfer
     */
    public function detail($id)
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_tranfers.created_by')
            ->leftJoin('warehouses as w1', 'w1.warehouse_id', '=', 'inventory_tranfers.warehouse_to')
            ->leftJoin('warehouses as w2', 'w2.warehouse_id', '=', 'inventory_tranfers.warehouse_from')->select(
                'inventory_tranfers.transfer_code as transferCode',
                'inventory_tranfers.status as status',
                'inventory_tranfers.note as note',
                'inventory_tranfers.transfer_at as transferAt',
                'inventory_tranfers.approved_at as approvedAt',
                'staffs.full_name as user',
                'w1.name as warehouseTo',
                'w2.name as warehouseFrom'
            )->where('inventory_tranfers.inventory_tranfer_id', $id);
            if (Auth::user()->is_admin != 1) {
                $select->where('w2.branch_id', Auth::user()->branch_id);
            }
        return $select->first();
    }

    /*
     * get inventory transfer edit
     */
    public function getInventoryTransferEdit($id)
    {
        $select = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_tranfers.created_by')
            ->leftJoin('warehouses as w1', 'w1.warehouse_id', '=', 'inventory_tranfers.warehouse_to')
            ->leftJoin('warehouses as w2', 'w2.warehouse_id', '=', 'inventory_tranfers.warehouse_from')->select(
                'inventory_tranfers.transfer_code as transferCode',
                'inventory_tranfers.status as status',
                'inventory_tranfers.note as note',
                'staffs.full_name as user',
                'inventory_tranfers.warehouse_to as warehouseTo',
                'inventory_tranfers.warehouse_from as warehouseFrom',
                'inventory_tranfers.transfer_at as transferAt',
                'inventory_tranfers.approved_at as approvedAt'
            )->where('inventory_tranfers.inventory_tranfer_id', $id)->first();
        return $select;
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
            $select->whereIn('inventory_tranfers.warehouse_from', $warehouse);
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
            $select->where('w2.branch_id', Auth::user()->branch_id);
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
            $select->whereIn('inventory_tranfers.warehouse_from', $filter);
            unset($filter);
            return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        }
    }
}
//