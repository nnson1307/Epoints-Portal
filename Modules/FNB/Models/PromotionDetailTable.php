<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/14/2020
 * Time: 3:58 PM
 */

namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionDetailTable extends Model
{
    const OBJECT_TYPE_SERVICE_CARD = 'service_card';
    const OBJECT_TYPE_SERVICE = 'service';
    const OBJECT_TYPE_PRODUCT = 'product';

    const TIME_TYPE_WEEK = 'W';
    const TIME_TYPE_MONTH = 'M';
    const TIME_TYPE_DATE_TIME = 'R';

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
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $promotionType
     * @param $currentDate
     * @return mixed
     */
    public function getPromotionDetail($objectType, $objectCode, $promotionType = null, $currentDate = null)
    {
        $ds =  $this
            ->select(
                "promotion_master.promotion_id",
                "promotion_master.promotion_code",
                "promotion_master.start_date",
                "promotion_master.end_date",
                "promotion_master.is_time_campaign",
                "promotion_master.time_type",
                "promotion_master.branch_apply",
                "promotion_master.promotion_type",
                "promotion_master.promotion_type_discount",
                "promotion_master.promotion_type_discount_value",
                "promotion_master.order_source",
                "promotion_master.quota",
                "promotion_master.quota_use",
                "promotion_master.promotion_apply_to",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_code",
                "{$this->table}.base_price",
                "{$this->table}.promotion_price",
                "{$this->table}.quantity_buy",
                "{$this->table}.quantity_gift",
                "{$this->table}.gift_object_type",
                "{$this->table}.gift_object_id",
                "{$this->table}.gift_object_name",
                "{$this->table}.gift_object_code"
            )
            ->join("promotion_master", "promotion_master.promotion_code", "=", "{$this->table}.promotion_code")
            ->where("{$this->table}.object_type", $objectType)
            ->where("{$this->table}.object_code", $objectCode)
            ->where(function ($query) use ($currentDate) {
                $query->where("promotion_master.start_date", "<=", $currentDate)
                    ->where("promotion_master.end_date", ">=", $currentDate);
            })
            ->where("promotion_master.is_actived", self::IS_ACTIVE)
            ->where("promotion_master.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE);

        if ($promotionType != null) {
            $ds->where("promotion_master.promotion_type", $promotionType);
        }

        return $ds->get();
    }


    /**
     * Lấy giá khuyến mãi của các dịch vụ, dùng cho calendar
     *
     * Có 2 loại điều kiện thời gian:
     * D (table promotion_daily_time)
     * W (table promotion_weekly_time)
     * M (table promotion_monthly_time)
     * R (table promotion_date_time)
     * => Riêng promotion_date_time sẽ có nhiều dòng cho 1 chương trình
     *
     * @param $arrServiceId ID của các dịch vụ
     * @param $minDate Ngày bắt đầu xem trên calendar. Format Y-m-d
     * @param $maxDate Ngày kết thúc xem trên calendar. Format Y-m-d
     * @return mixed
     */
    public function getServicesPromotionPrice($arrServiceId, $minDate, $maxDate)
    {
        return $this->select(
                        'pm.promotion_id',
                        'sv.service_id',
                        "{$this->table}.promotion_price",
                        'pm.start_date',
                        'pm.end_date',
                        'pm.is_time_campaign',
                        'pm.time_type',

                        'w.is_monday',
                        'w.is_tuesday',
                        'w.is_wednesday',
                        'w.is_thursday',
                        'w.is_friday',
                        'w.is_saturday',
                        'w.is_sunday',
                        'm.run_date'
                    )
                    ->join('services as sv', function ($join) use ($arrServiceId) {
                        $join->on('sv.service_id', "{$this->table}.object_id")
                             ->where('object_type', self::OBJECT_TYPE_SERVICE)
                             ->whereIn('sv.service_id', $arrServiceId);
                    })
                    ->join('promotion_master as pm', function ($join) {
                        $join->on('pm.promotion_id', "{$this->table}.promotion_id")
                             ->where('pm.promotion_type', PromotionMasterTable::TYPE_DISCOUNT);
                    })
                    ->leftJoin('promotion_weekly_time as w', function ($join) {
                        $join->on('w.promotion_id', 'pm.promotion_id')
                             ->where('pm.time_type', self::TIME_TYPE_WEEK);
                    })
                    ->leftJoin('promotion_monthly_time as m', function ($join) {
                        $join->on('m.promotion_id', 'pm.promotion_id')
                            ->where('pm.time_type', self::TIME_TYPE_MONTH);
                    })
                    ->where('pm.is_actived', 1)
                    ->where('pm.is_display', 1)
                    ->where('pm.is_deleted', 0)
                    ->where("{$this->table}.is_actived", 1)
                    ->where('pm.start_date', '<=', $maxDate . ' 23:59:59') // Lọc ra chương trình có thời gian hợp lệ
                    ->where('pm.end_date', '>=', $minDate . ' 00:00:00')
                    ->get();
    }
}