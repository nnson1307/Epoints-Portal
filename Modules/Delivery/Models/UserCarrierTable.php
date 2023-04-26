<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/8/2020
 * Time: 9:44 AM
 */

namespace Modules\Delivery\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class UserCarrierTable extends Model
{
    use ListTableTrait;

    protected $table = "user_carrier";
    protected $primaryKey = "user_carrier_id";
    protected $fillable = [
        "user_carrier_id",
        "full_name",
        "birthday",
        "gender",
        "phone",
        "email",
        "address",
        "user_name",
        "password",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "avatar"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Ds nhân viên giao hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "user_carrier_id",
                "full_name",
                "birthday",
                "gender",
                "phone",
                "email",
                "address",
                "user_name",
                "password",
                "is_actived"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->orderBy("$this->primaryKey", "desc");

        // filter tên tên, sđt
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $ds;
    }

    /**
     * Thêm nv giao hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->primaryKey;
    }

    /**
     * Lấy thông tin nv giao hàng
     *
     * @param $userCarrierId
     * @return mixed
     */
    public function getInfo($userCarrierId)
    {
        return $this
            ->select(
                "user_carrier_id",
                "full_name",
                "birthday",
                "gender",
                "phone",
                "email",
                "address",
                "user_name",
                "is_actived",
                "avatar"
            )
            ->where("$this->primaryKey", $userCarrierId)
            ->first();
    }

    /**
     * Chỉnh sửa nv giao hàng
     *
     * @param array $data
     * @param $userCarrierId
     * @return mixed
     */
    public function edit(array $data, $userCarrierId)
    {
        return $this->where("$this->primaryKey", $userCarrierId)->update($data);
    }

    /**
     * Lấy option nv giao hàng
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "user_carrier_id",
                "full_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }
}