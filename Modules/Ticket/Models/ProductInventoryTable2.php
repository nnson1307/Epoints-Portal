<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/8/2018
 * Time: 12:33 AM
 */

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ProductInventoryTable2 extends Model
{
    use ListTableTrait;
    protected $table = 'product_inventorys';
    protected $primaryKey = 'product_inventory_id';
    public $timestamps = true;

    protected $fillable = ['product_inventory_id', 'product_id', 'product_code', 'warehouse_id', 'import', 'export', 'quantity', 'created_at', 'updated_at', 'created_by', 'updated_by'];


    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
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

    public function plusQuantityProduct($product_code, $quantity)
    {
        return $this->where('product_code', $product_code)->update([
            'quantity' => DB::raw("quantity+{$quantity}"),
        ]);
    }
}