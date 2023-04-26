<?php

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class DiscountCauseTable extends Model
{
    protected $table = "discount_causes";
    protected $primaryKey = "discount_cause_id";

    /**
     * Lấy option lý do giảm giá
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');
        return $this->select(
            "discount_causes_id",
            "discount_causes_name_$lang as discount_causes_name"
        )
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->get();
    }
}