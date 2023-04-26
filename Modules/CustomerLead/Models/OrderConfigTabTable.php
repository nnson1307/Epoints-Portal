<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class OrderConfigTabTable extends Model
{
    protected $table = "order_config_tab";
    protected $primaryKey = "order_config_tab_id";
    protected $fillable = [
        "order_config_tab_id",
        "code",
        "tab_name_vi",
        "tab_name_en",
        "is_show",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_SHOW = 1;

    /**
     * Lấy cấu hình tab trong đơn hàng
     *
     * @return mixed
     */
    public function getConfigTab()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "order_config_tab_id",
                "code",
                "tab_name_{$lang} as tab_name"
            )
            ->where("is_show", self::IS_SHOW)
            ->get();
    }
}