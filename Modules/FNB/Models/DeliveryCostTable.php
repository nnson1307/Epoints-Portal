<?php

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryCostTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_costs";
    protected $primaryKey = "delivery_cost_id";
    protected $fillable = [
        "delivery_cost_id",
        "delivery_cost_code",
        "delivery_cost_name",
        "delivery_cost",
        "is_actived",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
        "is_system",
        "is_delivery_fast",
        "delivery_fast_cost"
    ];

    /**
     * Danh sách chi phí giao hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select("*")
            ->orderBy('delivery_cost_id', 'desc');

        // filter tên chi phí giao hàng
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('delivery_costs.delivery_cost_name', 'like', '%' . $search . '%')
                    ->orWhere('delivery_costs.delivery_cost_code', 'like', '%' . $search . '%');
            });
        }
        // filter ngày tạo
//        if (isset($filter["created_at"]) != "") {
//            $arr_filter = explode(" - ", $filter["created_at"]);
//            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
//            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
//            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
//        }

        return $ds;
    }

    /**
     * Them moi chi phi giao hang
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->create($data)->delivery_cost_id;
    }

    /**
     * Cap nhat chi phi giao hang
     *
     * @param array $data
     * @param array $id
     * @return bool
     */
    public function edit(array $data, $id)
    {
        return $this->where("delivery_cost_id", $id)->update($data);
    }

    /**
     * Chi tiet chi phi giao hang
     *
     * @param $deliveryCostId
     * @return mixed
     */
    public function getDetail($deliveryCostId)
    {
        return $this->select
        (
            "delivery_cost_id",
            "delivery_cost_code",
            "delivery_cost_name",
            "is_actived",
            "delivery_cost",
            "is_system",
            "is_delivery_fast",
            "delivery_fast_cost"
        )
        ->where("delivery_cost_id", $deliveryCostId)
        ->first();
    }

    /**
     * Xoa chi phi giao hang
     *
     * @param $id
     * @return mixed
     */
    public function delDeliveryCost($id)
    {
        return $this->where('delivery_cost_id', $id)->delete();
    }

    /**
     * cai dat chi phi van chuyen mac dinh
     *
     * @param $data
     * @return mixed
     */
    public function updateAll($data)
    {
        return $this->where('is_system', 1)->update($data);
    }

    /**
     * Lấy địa chỉ mặc định
     * @param $postcode
     * @param $districtId
     * @param $costCode
     * @return mixed
     */
    public function checkAddressDefault() {
        return $this->select(
            'delivery_costs.delivery_cost',
            'delivery_costs.delivery_cost_id',
            'delivery_costs.is_delivery_fast',
            'delivery_costs.delivery_fast_cost'
        )
            ->where('delivery_costs.is_system',1)
            ->first();
    }
}