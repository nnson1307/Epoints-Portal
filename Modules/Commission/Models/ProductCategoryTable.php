<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryTable extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'product_category_id';

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;


    /**
     * Lấy ds nhóm SP
     *
     * @return mixed
     */
    public function getListCategory()
    {
        return $this->select(
                        "{$this->table}.product_category_id",
                        "{$this->table}.category_name"
                    )
                    ->where("{$this->table}.is_actived", self::IS_ACTIVE)
                    ->where("{$this->table}.is_deleted", self::NOT_DELETED)
                    ->get();
    }

    /**
     * Lấy thông tin nhóm SP
     *
     * @param $idCategory
     * @return mixed
     */
    public function getInfoCategory($idCategory)
    {
        return $this
            ->select(
                "{$this->table}.product_category_id",
                "{$this->table}.category_name"
            )
            ->where("{$this->table}.product_category_id", $idCategory)
            ->first();
    }
}
