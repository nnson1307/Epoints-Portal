<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 17:19
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigReviewContentSuggestTable extends Model
{
    protected $table = "config_review_content_suggest";
    protected $primaryKey = "config_review_content_suggest_id";

    /**
     * Lấy cú pháp gợi ý đánh giá
     *
     * @param $configReviewId
     * @return mixed
     */
    public function getContentSuggest($configReviewId)
    {
        return $this
            ->select(
                "config_review_content_suggest_id",
                "config_review_id",
                "rating_value",
                "content_suggest_id"
            )
            ->where("config_review_id", $configReviewId)
            ->get();
    }

    /**
     * Xoá tất cả gợi ý đánh giá
     *
     * @param $configReviewId
     * @return mixed
     */
    public function removeSuggestByReviewId($configReviewId)
    {
        return $this->where("config_review_id", $configReviewId)->delete();
    }
}