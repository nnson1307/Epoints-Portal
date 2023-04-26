<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/11/2021
 * Time: 14:20
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class RatingLogSuggestTable extends Model
{
    protected $table = "rating_log_suggest";
    protected $primaryKey = "rating_log_suggest_id";

    /**
     * Lấy log cú pháp đánh giá
     *
     * @param $ratingLogId
     * @return mixed
     */
    public function getLogSuggest($ratingLogId)
    {
        return $this
            ->select(
                "rating_log_id",
                "content_suggest"
            )
            ->where("rating_log_id", $ratingLogId)
            ->get();
    }
}