<?php

namespace Modules\Contract\Models;

use Illuminate\Database\Eloquent\Model;

class ContractConfigTabTable extends Model
{
    protected $table = "contract_config_tab";
    protected $primaryKey = "contract_config_tab_id";
    protected $fillable = [
        "contract_config_tab_id",
        "contract_id",
        "tab",
        "key",
        "type",
        "key_name",
        "is_default",
        "is_show",
        "is_validate",
        "number_col",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_SHOW = 1;

    /**
     * Lấy cấu hình trường dữ liệu của HĐ
     *
     * @param $categoryId
     * @return mixed
     */
    public function getConfigTabByContract($contractId)
    {
        return $this
            ->select(
                "contract_config_tab_id",
                "contract_id",
                "tab",
                "key",
                "type",
                "key_name",
                "is_default",
                "is_show",
                "is_validate",
                "number_col"
            )
            ->where("contract_id", $contractId)
            ->where("is_show", self::IS_SHOW)
            ->get();
    }

    /**
     * Xoá cấu hình trường dữ liệu của HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function removeConfigTabByContract($contractId)
    {
        return $this->where("contract_id", $contractId)->delete();
    }
}