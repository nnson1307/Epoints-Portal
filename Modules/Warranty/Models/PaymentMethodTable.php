<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PaymentMethodTable extends Model
{
    protected $table = 'payment_method';
    protected $primaryKey = 'payment_method_id';

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const MEMBER_CARD = 'MEMBER_CARD';

    /**
     * Lấy các otion phương thức thanh toán
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "payment_method_id",
            "payment_method_code",
            "payment_method_name_$lang as payment_method_name"
        )
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_delete", self::NOT_DELETE)
            ->where("payment_method_code", "<>", self::MEMBER_CARD);
        return $select->get();
    }

    /**
     * Lấy thông tin phương thức thanh toán bằng code
     *
     * @param $paymentMethodCode
     * @return mixed
     */
    public function getInfoByCode($paymentMethodCode)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "payment_method_id",
                "payment_method_name_$lang as payment_method_name",
                "payment_method_code",
                "note",
                "payment_method_type"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("payment_method_code", $paymentMethodCode)
            ->first();
    }
}