<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerConfigTabDetailTable extends Model
{
    protected $table = "customer_config_tab_detail";
    protected $primaryKey = "customer_config_tab_detail_id";

    const IS_SHOW = 1;

    /**
     * Lấy cấu hình tab trong chi tiết KH
     *
     * @return mixed
     */
    public function getConfigTabDetail()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "customer_config_tab_detail_id",
                "code",
                "tab_name_{$lang} as tab_name"
            )
            ->where("is_show", self::IS_SHOW)
            ->get();
    }
}