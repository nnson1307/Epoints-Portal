<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryOutputTable extends Model
{
    protected $table = 'inventory_outputs';
    protected $primaryKey = 'inventory_output_id';
    protected $fillable = ['inventory_output_id', 'warehouse_id', 'po_code', 'created_by', 'updated_by', 'approved_by', 'created_at', 'updated_at', 'approved_at', 'status', 'type', 'object_id', 'note'];

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

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}