<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventoryTable extends Model
{
    protected $table = 'product_inventorys';
    protected $primaryKey = 'product_inventory_id';
    protected $fillable = ['product_inventory_id', 'product_id', 'product_code', 'warehouse_id', 'import', 'export', 'quantity', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     * Insert product inventory to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oInsert = $this->create($data);

        return $oInsert->product_inventory_id;
    }

    /**
     * Edit product inventory in database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    /**
     * Check product inventory in database
     *
     * @param $productCode
     * @param $warehouseId
     * @return array
     */
    public function checkProductInventory($productCode, $warehouseId)
    {
        return $this->where('product_code', $productCode)->where('warehouse_id', $warehouseId)->first();
    }
}