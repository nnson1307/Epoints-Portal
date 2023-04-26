<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/11/2021
 * Time: 14:20
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class RatingLogImageTable extends Model
{
    protected $table = "rating_log_image";
    protected $primaryKey = "rating_log_image_id";

    /**
     * Lấy ảnh/vide đánh giá
     *
     * @param $ratingLogId
     * @return mixed
     */
    public function getRatingFile($ratingLogId)
    {
        return $this
            ->select(
                "type",
                "link"
            )
            ->where("rating_log_id", $ratingLogId)
            ->get();
    }
}