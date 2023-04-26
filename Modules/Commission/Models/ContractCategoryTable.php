<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/10/2022
 * Time: 17:10
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;

class ContractCategoryTable extends Model
{
    protected $table = "contract_categories";
    protected $primaryKey = "contract_category_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const SELL = "sell";

    /**
     * Lấy option loại hợp đồng
     *
     * @return mixed
     */
    public function getOptionCategory()
    {
        return $this
            ->select(
                "contract_category_id",
                "contract_category_code",
                "contract_category_name"
            )
            ->where("type", self::SELL)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}