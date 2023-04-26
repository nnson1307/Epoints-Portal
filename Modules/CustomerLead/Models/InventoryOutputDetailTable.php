<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryOutputDetailTable extends Model
{
    protected $table = 'inventory_output_details';
    protected $primaryKey = 'inventory_output_detail_id';

    protected $fillable = ['inventory_output_detail_id', 'inventory_output_id', 'product_code', 'unit_id', 'quantity', 'current_price', 'total', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    /**
     * Insert inventory output detail to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_output_detail_id;
    }
}