<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\Ticket\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class InventoryInputsTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_inputs';
    protected $primaryKey = 'inventory_input_id';

    protected $fillable = [
        'inventory_input_id',
        'warehouse_id',
        'supplier_id',
        'pi_code',
        'approved_by',
        'approved_at',
        'status',
        'type',
        'note',
        'user_recived',
        'date_recived',
        'object_id',
        'created_at',
        'updated_by',
        'created_by',
        'updated_at'
    ];

    public function add(array $data)
    {
        $odata = $this->create($data);
        return $odata->inventory_input_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function count()
    {
        return $this->select('*')->count();
    }
    
    public function getItemByRefundID($id)
    {
        return $this->select("{$this->table}.*",
        "p1.full_name as created_by_full_name",
        \DB::raw("SUM(p2.quantity) as sum_quantity")
        )
        ->leftJoin("staffs as p1","p1.staff_id","{$this->table}.created_by")
        ->join("inventory_input_details as p2","p2.inventory_input_id","{$this->table}.inventory_input_id")
        ->where("{$this->table}.warehouse_id", 0)
        ->where("{$this->table}.type", 'normal')
        ->where("{$this->table}.object_id", $id)
        ->first();
    }
}