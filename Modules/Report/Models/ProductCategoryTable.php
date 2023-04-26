<?php


namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;

class ProductCategoryTable extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'product_category_id';

    /**
     * Lấy các option danh mục sản phẩm
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this->select('product_category_id', 'category_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }
}