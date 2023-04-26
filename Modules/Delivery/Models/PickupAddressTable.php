<?php

namespace Modules\Delivery\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class PickupAddressTable extends Model
{
    use ListTableTrait;
    protected $table = "pickup_address";
    protected $primaryKey = "pickup_address_id";
    protected $fillable = [
        'pickup_address_id',
        'pickup_address_code',
        'address',
        'is_deleted',
        'is_actived',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const NOT_DELETED = 0;
    CONST IN_ACTIVE = 1;

    /**
     * Option dia chi lay hang
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "pickup_address_id",
                "pickup_address_code",
                "address"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("is_actived", self::IN_ACTIVE)
            ->get();
    }

    /**
     * Danh sách địa chỉ lấy hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select("*")
            ->where('is_deleted', self::NOT_DELETED)
//            ->where("is_actived", self::IN_ACTIVE)
            ->orderBy('pickup_address_id', 'desc');

        // filter địa chỉ lấy hàng
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('pickup_address.address', 'like', '%' . $search . '%')
                ->orWhere('pickup_address.pickup_address_code', 'like', '%' . $search . '%');
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
     * Luu dia chi lay hang
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->create($data)->pickup_address_id;
    }

    /**
     * Cap nhat dia chi lay hang
     *
     * @param array $data
     * @param array $id
     * @return bool
     */
    public function edit(array $data, $id)
    {
        return $this->where("pickup_address_id", $id)->update($data);
    }

    public function getDetail($pickupAddressId)
    {
        $detail = $this->select('*')
            ->where('pickup_address_id', $pickupAddressId)
            ->where('is_deleted', 0)
            ->first();
        return $detail;
    }
}