<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:00 AM
 */

namespace Modules\Payment\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\Config;

class PaymentUnitTable extends Model
{
    use ListTableTrait;
    protected $table = "payment_units";
    protected $primaryKey = "payment_unit_id";

    protected $fillable = [
        'payment_unit_id',
        'name',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_DELETE = 0;

    /**
     * Danh sách đơn vị thanh toán, filter, paging
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this->select(
            "{$this->table}.payment_unit_id",
            "{$this->table}.name",
            "{$this->table}.is_actived")
            ->where("is_deleted", self::IS_DELETE);
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.name", 'like', '%' . $search . '%');
            });
        }
        return $ds->orderBy('payment_unit_id', 'desc');
    }

    /**
     * Thêm đơn vị thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->payment_unit_id;
    }

    /**
     * Lấy thông tin 1 đơn vị thanh toán
     *
     * @param $paymentMethodId
     * @return mixed
     */
    public function getInfo($paymentUnitId)
    {
        return $this
            ->select(
                "{$this->table}.payment_unit_id",
                "{$this->table}.name",
                "{$this->table}.is_actived"
            )
            ->where("{$this->table}.payment_unit_id", $paymentUnitId)
            ->first();
    }

    /**
     * Chỉnh sửa đơn vị thanh toán
     *
     * @param array $data
     * @param $paymentUnitId
     * @return mixed
     */
    public function edit(array $data, $paymentUnitId)
    {
        return $this->where("payment_unit_id", $paymentUnitId)->update($data);
    }

    /**
     * Xoá 1 PTTT
     *
     * @param $paymentUnitId
     * @return mixed
     */
    public function deleteType($paymentUnitId)
    {
        return $this->where($this->primaryKey, $paymentUnitId)->update(['is_deleted' => 1]);
    }

    /**
     * Lấy các option đơn vị thanh toán
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "{$this->table}.payment_unit_id",
            "{$this->table}.name"
        )
            ->where("is_active", self::IS_ACTIVE);
        return $select->get();
    }
}