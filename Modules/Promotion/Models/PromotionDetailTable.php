<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 2:31 PM
 */

namespace Modules\Promotion\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class PromotionDetailTable extends Model
{
    protected $table = "promotion_details";
    protected $primaryKey = "promotion_detail_id";
    protected $fillable = [
        "promotion_detail_id",
        "promotion_id",
        "promotion_code",
        "object_type",
        "object_id",
        "object_name",
        "object_code",
        "base_price",
        "promotion_price",
        "quantity_buy",
        "quantity_gift",
        "gift_object_type",
        "gift_object_id",
        "gift_object_name",
        "gift_object_code",
        "is_actived",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Thêm chi tiết CTKM
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy chi tiết CTKM by promotion_id
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getDetail($promotionCode)
    {
        return $this
            ->select(
                "promotion_detail_id",
                "promotion_id",
                "promotion_code",
                "object_type",
                "object_id",
                "object_name",
                "object_code",
                "base_price",
                "promotion_price",
                "quantity_buy",
                "quantity_gift",
                "gift_object_type",
                "gift_object_id",
                "gift_object_name",
                "gift_object_code",
                "is_actived"
            )
            ->where("promotion_code", $promotionCode)
            ->get();
    }

    /**
     * Remove promotion code
     *
     * @param $promotionCode
     * @return mixed
     */
    public function removeDetail($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->delete();
    }

    /**
     * Lấy thông tin sp, dv, thẻ dv đã sử dụng ở chương trình khác
     *
     * @param $promotionType
     * @param $promotionCode
     * @param $startDate
     * @param $endDate
     * @param $objectType
     * @param $objectCode
     * @return mixed
     */
    public function checkDetailUsing($promotionType, $promotionCode, $startDate, $endDate, $objectType, $objectCode)
    {
        return $this
            ->select(
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_code"
            )
            ->join("promotion_master", "promotion_master.promotion_code", "=", "{$this->table}.promotion_code")
            ->where("{$this->table}.object_type", $objectType)
            ->where("{$this->table}.object_code", $objectCode)
            ->where("promotion_master.is_actived", self::IS_ACTIVE)
            ->where("promotion_master.is_deleted", self::NOT_DELETE)
            ->where("promotion_master.promotion_code", "<>", $promotionCode)
            ->where("promotion_master.promotion_type", $promotionType)
            ->where(function ($query) use ($startDate) {
                $startTime = Carbon::createFromFormat('d/m/Y H:i', $startDate)->format('Y-m-d H:i');
                $query->where('start_date', '>=', $startTime)
                    ->orWhere('end_date', '>=', $startTime);
            })->where(function ($query) use ($endDate) {
                $endTime = Carbon::createFromFormat('d/m/Y H:i', $endDate)->format('Y-m-d H:i');
                $query->where('start_date', '<=', $endTime)
                    ->orWhere('end_date', '<=', $endTime);
            })
            ->first();
    }
}