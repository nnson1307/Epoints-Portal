<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 2:31 PM
 */

namespace Modules\FNB\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class PromotionMasterTable extends Model
{
    use ListTableTrait;
    protected $table = "promotion_master";
    protected $primaryKey = "promotion_id";
    protected $fillable = [
        "promotion_id",
        "promotion_code",
        "promotion_name",
        "promotion_name_en",
        "start_date",
        "end_date",
        "is_actived",
        "is_display",
        "is_time_campaign",
        "time_type",
        "image",
        "image_en",
        "branch_apply",
        "is_feature",
        "position_feature",
        "promotion_type",
        "promotion_type_discount",
        "promotion_type_discount_value",
        "order_source",
        "quota",
        "promotion_apply_to",
        "description",
        "description_en",
        "description_detail",
        "description_detail_en",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "type_display_app"
    ];

    const NOT_DELETED = 0;
    const IN_ACTIVE = 1;

    /**
     * Danh sách CTKM
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "promotion_id",
                "promotion_code",
                "promotion_name",
                "promotion_name_en",
                "start_date",
                "end_date",
                "is_actived",
                "is_display",
                "is_time_campaign",
                "promotion_type",
                "image",
                "image_en",
                "is_feature",
                "created_at"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->orderBy("promotion_id", "desc");

        // filter tên CT, mã CT
        if (isset($filter["search"]) != "") {
            $search = $filter["search"];
            $ds->where(function ($query) use ($search) {
                $query->where("promotion_name", "like", "%" . $search . "%")
                    ->orWhere("promotion_code", "like", "%" . $search . "%");
            });
        }

        // filter ngày diễn ra CT
        if (isset($filter["time_promotion"]) != "") {
            $arr_filter = explode(" - ", $filter["time_promotion"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
            $ds->where(function ($query) use ($startTime) {
                $query->whereDate("start_date", ">=", $startTime)
                    ->orWhereDate("end_date", ">=", $startTime);
            })->where(function ($query) use ($endTime) {
                $query->whereDate("start_date", "<=", $endTime)
                    ->orWhereDate("end_date", "<=", $endTime);
            });
        }
        unset($filter["time_promotion"]);

        return $ds;
    }

    /**
     * Lấy thông tin CTKM
     *
     * @param $promotionId
     * @return mixed
     */
    public function getInfo($promotionId)
    {
        return $this
            ->select(
                "promotion_id",
                "promotion_code",
                "promotion_name",
                "promotion_name_en",
                "start_date",
                "end_date",
                "is_actived",
                "is_display",
                "is_time_campaign",
                "time_type",
                "image",
                "image_en",
                "is_feature",
                "position_feature",
                "promotion_type",
                "promotion_type_discount",
                "promotion_type_discount_value",
                "branch_apply",
                "order_source",
                "quota",
                "promotion_apply_to",
                "description",
                "description_en",
                "description_detail",
                "description_detail_en",
                "type_display_app"
            )
            ->where("promotion_id", $promotionId)
            ->first();
    }

    /**
     * Thêm CTKM
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->promotion_id;
    }

    /**
     * Chỉnh sửa CTKM
     *
     * @param array $data
     * @param $promotionId
     * @return mixed
     */
    public function edit(array $data, $promotionId)
    {
        return $this->where("promotion_id", $promotionId)->update($data);
    }

    /**
     * Lấy vị trí hiển thị nổi bật
     *
     * @param $position
     * @return mixed
     */
    public function getPosition($position)
    {
        return $this
            ->select(
                "promotion_id",
                "promotion_name"
            )
            ->where("position_feature", $position)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("end_date", ">=", Carbon::now())
            ->first();
    }
}