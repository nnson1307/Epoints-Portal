<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 14:09
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigReviewTable extends Model
{
    protected $table = "config_reviews";
    protected $primaryKey = "config_review_id";
    protected $fillable = [
        "config_review_id",
        "type",
        "is_browse",
        "is_buy",
        "expired_review",
        "is_edit",
        "is_deleted",
        "is_review_image",
        "limit_number_image",
        "limit_capacity_image",
        "is_review_video",
        "limit_number_video",
        "limit_capacity_video",
        "is_auto_reply",
        "is_suggest",
        "is_review_google",
        "rating_value_google",
        "max_length_content",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy cấu hình đánh giá
     *
     * @param $type
     * @return mixed
     */
    public function getConfigReview($type)
    {
        return $this->where("type", $type)->first();
    }

    /**
     * Chỉnh sửa cấu hình đánh giá
     *
     * @param array $data
     * @param $configReviewId
     * @return mixed
     */
    public function edit(array $data, $configReviewId)
    {
        return $this->where("config_review_id", $configReviewId)->update($data);
    }
}