<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 10:15 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class WarehouseTable extends Model
{
    use ListTableTrait;
    protected $table = "warehouses";
    protected $primaryKey = "warehouse_id";

    protected $fillable = [
        'warehouse_id', 'name', 'branch_id', 'address', 'description', 'created_by','phone',
        'updated_by', 'created_at', 'updated_at', 'is_deleted', 'province_id', 'district_id','ward_id', 'is_retail', 'slug','ghn_shop_id'
    ];

    //function lay danh sach
    protected function _getList(&$filter = [])
    {
        $ds = $this->leftJoin('branches', 'branches.branch_id', '=', 'warehouses.branch_id')
            ->select('warehouses.*', 'warehouses.warehouse_id as id',
                'warehouses.name as name',
                'warehouses.address as address',
                'warehouses.description as description',
                'warehouses.created_by as created_by',
                'warehouses.updated_by as updated_by ',
                'warehouses.created_at as created_at',
                'warehouses.updated_at as updated_at ',
                'warehouses.is_deleted as is_deleted',
                'branches.branch_name as branch_name')
            ->where('warehouses.is_deleted', 0)
            ->orderBy('warehouses.warehouse_id', 'desc');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where('name', 'like', '%' . $search . '%')
                ->orWhere('warehouses.address', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('branch_name', 'like', '%' . $search . '%')
                ->where('warehouses.is_deleted', 0);
        }
        unset($filter['search']);
        return $ds;
    }

    //function add warehouse
    public function add(array $data)
    {
        $ware = $this->create($data);
        return $ware->id;
    }
//    public function getBranch()
//    {
//        return $this->select('branch_id','branch_name','address','phone')->get()->toArray();
//    }
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    public function getItem($id)
    {
        $ds = $this

            ->select(
                'warehouses.*',
                'warehouse_id',
                'name',
                'branches.branch_name as branch_name',
                'warehouses.address as address',
                'warehouses.description as description',
                'warehouses.created_by as created_by',
                'warehouses.updated_by as updated_by ',
                'warehouses.created_at as created_at',
                'warehouses.updated_at as updated_at ',
                'warehouses.is_deleted as is_deleted',
                'warehouses.is_retail as is_retail'
            )
            ->leftJoin('branches', 'branches.branch_id', '=', 'warehouses.warehouse_id')
            ->where($this->primaryKey, $id)->first();
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function testName($name, $id)
    {
        return $this->where('slug', $name)->where('warehouse_id', '<>', $id)->where('is_deleted', 0)->first();
    }

    public function getWareHouseOption()
    {
        $select = $this->select('warehouse_id', 'name')
            ->where('is_deleted', 0);
        if (Auth::user()->is_admin != 1) {
            $select->where('branch_id', Auth::user()->branch_id);
        }
        return $select->get();
    }
//    public function getWareHouseOption(){
//        return $this->select('warehouse_id', 'name')
//            ->where('is_deleted', 0)->get();
//    }
    /*
     * get warehouse not id parameter
     */
    public function getWarehouseNotId($id)
    {
        return $this->select('warehouse_id', 'name')
            ->where('warehouse_id', '<>', $id)
            ->where('is_deleted', 0)->get();
    }

    //search where in warehouse.
    public function searchWhereIn(array $warehouse)
    {
        return $this->whereIn('warehouse_id', $warehouse)->get();
    }

    public function checkIsRetail($branchId, $id)
    {
        $select = $this->leftJoin('branches', 'branches.branch_id', '=', 'warehouses.branch_id')
            ->where('branches.branch_id', $branchId)
            ->where('warehouses.is_retail', 1)
            ->where('warehouses.warehouse_id', '<>', $id)
            ->get();
        return $select;
    }

    public function getWarehouseByBranch($branchId)
    {
        $select = $this->where('branch_id', $branchId)->get();
        return $select;
    }

    public function changeIsRetailAction($branchId)
    {
        $select = $this->where('branch_id', $branchId)->update(['is_retail' => 0]);
        return $select;
    }

    public function checkIsFirstWarehouse($branchId)
    {
        $select = $this->where('branch_id', $branchId)->first();
        return $select;
    }

    /**
     * Lấy danh sách kho chưa tạo cửa hàng trên GHN
     * @return mixed
     */
    public function getListWareHouseNoStore(){
        $select = $this->whereNull('ghn_shop_id')->get();
        return $select;
    }
}