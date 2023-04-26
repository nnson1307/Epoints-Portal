<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PaymentTypeTable extends Model
{
    protected $table = "payment_type";
    protected $primaryKey = "payment_type_id";

    const IS_ACTIVE = 1;

    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "payment_type_id",
            "payment_type_name_$lang as payment_type_name"
        )->where("is_active", self::IS_ACTIVE);
        return $select->get();
    }
}