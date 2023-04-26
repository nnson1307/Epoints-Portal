<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 17:22
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigReviewContentHintTable extends Model
{
    protected $table = "config_review_content_hint";
    protected $primaryKey = "config_review_content_hint_id";

    /**
     * Lấy nội dung gợi ý đánh giá
     *
     * @param $configReviewId
     * @return mixed
     */
    public function getContentHint($configReviewId)
    {
        return $this
            ->select(
                "config_review_content_hint_id",
                "config_review_id",
                "rating_value",
                "content_hint"
            )
            ->where("config_review_id", $configReviewId)
            ->get();
    }

    /**
     * Xoá tất cả nội dung gợi ý đánh giá
     *
     * @param $configReviewId
     * @return mixed
     */
    public function removeHintByReviewId($configReviewId)
    {
        return $this->where("config_review_id", $configReviewId)->delete();
    }
}