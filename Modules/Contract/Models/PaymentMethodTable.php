<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2021
 * Time: 16:47
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PaymentMethodTable extends Model
{
    protected $table = "payment_method";
    protected $primaryKey = "payment_method_id";
    protected $fillable = [
        'payment_method_id ',
        'payment_method_code',
        'payment_method_name_vi',
        'payment_method_name_en',
        'payment_method_type',
        'payment_method_image',
        'is_system',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const MEMBER_CARD = 'MEMBER_CARD';

    /**
     * Lấy các loại thanh toán để tạo sự lựa chọn (select option)
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');

        return $this
            ->select(
                "payment_method_id",
                "payment_method_code",
                "payment_method_name_$lang as payment_method_name"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_delete", self::NOT_DELETED)
            ->where("payment_method_code", "<>", self::MEMBER_CARD)
            ->get();
    }

    /**
     * Thêm phương thức thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->payment_method_id;
    }

    /**
     * Cập nhật phương thức thanh toán
     *
     * @param array $data
     * @param $paymentMethodId
     * @return mixed
     */
    public function edit(array $data, $paymentMethodId)
    {
        return $this->where("payment_method_id", $paymentMethodId)->update($data);
    }

    /**
     * Lấy thông tin phương thức thanh toán
     *
     * @param $paymentMethodId
     * @return mixed
     */
    public function getInfo($paymentMethodId)
    {
        $lang = Config::get('app.locale');

        return $this
            ->select(
                "payment_method_id",
                "payment_method_code",
                "payment_method_name_$lang as payment_method_name"
            )
            ->where("payment_method_id", $paymentMethodId)
            ->first();
    }
}