<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 2:59 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionDateTimeTable extends Model
{
    protected $table = "promotion_date_time";
    protected $primaryKey = "promotion_date_id";
    protected $fillable = [
        "promotion_date_id",
        "promotion_id",
        "promotion_code",
        "form_date",
        "to_date",
        "start_time",
        "end_time",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm promotion date time
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Get date time
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getDateTimeByPromotion($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->first();
    }

    /**
     * Remove date time
     *
     * @param $promotionCode
     * @return mixed
     */
    public function removeDateTime($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->delete();
    }

    /**
     * Lấy cấu hình khuyến mãi
     *
     * @param array $arrPromoId
     * @return mixed
     */
    public function getPromotionsConfig(array $arrPromoId)
    {
        return $this->select(
                        'promotion_id',
                        'form_date',
                        'to_date'
                    )
                    ->whereIn('promotion_id', $arrPromoId)
                    ->get();
    }
}