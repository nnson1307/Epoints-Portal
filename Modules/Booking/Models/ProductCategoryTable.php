<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 11:58 AM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryTable extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'product_category_id';

    protected $fillable = ['product_category_id', 'category_name', 'description', 'is_actived', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at', 'slug'];

    /*
     * get all product category
     */
    public function getAll()
    {
        return $this->select('product_category_id', 'category_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }
}