<?php


namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryCostDetailTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_cost_detail";
    protected $primaryKey = "delivery_cost_detail_id";
    protected $fillable = [
        "delivery_cost_detail_id",
        "delivery_cost_code",
        "province_id",
        "postcode",
        "district_id",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Thêm mới chi tiết chi phí giao hàng
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->create($data)->delivery_cost_detail_id;
    }

    /**
     * Cập nhật chi tiết chi phí giao hàng
     *
     * @param array $data
     * @param array $id
     * @return bool
     */
    public function edit(array $data, $id)
    {
        return $this->where("delivery_cost_detail_id", $id)->update($data);
    }

    /**
     * Chi tiết chi phí vận chuyển theo id
     *
     * @param $code
     * @return mixed
     */
    public function getDetailByCode($code)
    {
        return $this->select
        (
            "delivery_cost_detail_id",
            "delivery_cost_code",
            "province_id",
            "postcode",
            "district_id",
            "created_at"
        )
            ->where("delivery_cost_code", $code)
            ->get();
    }

    /**
     * Xoá chi tiết chi phí vận chuyển theo code
     *
     * @param $deliveryCostCode
     * @return mixed
     */
    public function remove($deliveryCostCode)
    {
        return $this->where("delivery_cost_code", $deliveryCostCode)->delete();
    }

    public function checkPostcode($postcode, $districtId, $costCode) {
        return $this->select(
            "delivery_cost_detail_id",
            "delivery_cost_code",
            "province_id",
            "postcode",
            "district_id",
            "created_at"
        )
            ->where('postcode', $postcode)
            ->where('district_id', $districtId)
            ->where("delivery_cost_code", "<>", $costCode)
            ->first();
    }

    /**
     * Kiểm tra địa chỉ
     * @param $postcode
     * @param $districtId
     * @param $costCode
     * @return mixed
     */
    public function checkAddress($provinceId, $districtId) {
        return $this->select(
            $this->table.'.delivery_cost_detail_id',
            $this->table.'.delivery_cost_code',
            $this->table.'.province_id',
            $this->table.'.postcode',
            $this->table.'.district_id',
            $this->table.'.created_at',
            'delivery_costs.delivery_cost',
            'delivery_costs.delivery_cost_id',
            'delivery_costs.is_delivery_fast',
            'delivery_costs.delivery_fast_cost'
        )
            ->Join('delivery_costs','delivery_costs.delivery_cost_code',$this->table.'.delivery_cost_code')
            ->where('province_id', $provinceId)
            ->where('district_id', $districtId)
            ->first();
    }
}