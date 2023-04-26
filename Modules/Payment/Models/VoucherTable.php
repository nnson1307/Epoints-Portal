<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/01/2022
 * Time: 13:48
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class VoucherTable extends Model
{
    protected $table = "vouchers";
    protected $primaryKey = "voucher_id";

    /**
     * Lấy thông voucher bằng code
     *
     * @param $code
     * @return mixed
     */
    public function getInfoByCode($code)
    {
        return $this
            ->select(
                "voucher_id",
                "voucher_type",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "quota",
                "total_use",
                "is_actived",
                "branch_id",
                "total_use",
                "using_by_guest",
                "number_of_using"
            )
            ->where("code", $code)
            ->where("is_deleted", 0)
            ->first();
    }

    /**
     * Chỉnh sửa voucher bằng code
     *
     * @param $data
     * @param $code
     * @return mixed
     */
    public function editVoucherByCode($data, $code)
    {
        return $this->where("code", $code)->update($data);
    }
}