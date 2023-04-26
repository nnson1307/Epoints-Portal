<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryTable extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'product_category_id';

    public function getAll()
    {
        return $this->select('product_category_id', 'category_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }
}