<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseTable extends Model
{
    protected $table = "warehouses";
    protected $primaryKey = "warehouse_id";
    protected $fillable = [
        'warehouse_id', 'name', 'branch_id', 'address', 'description', 'created_by',
        'updated_by', 'created_at', 'updated_at', 'is_deleted', 'province_id', 'district_id', 'is_retail', 'slug'
    ];

    /**
     * Lấy tất cả kho theo chi nhánh
     *
     * @param $branchId
     * @return mixed
     */
    public function getWarehouseByBranch($branchId)
    {
        $select = $this->where('branch_id', $branchId);
        return $select->get();
    }
}