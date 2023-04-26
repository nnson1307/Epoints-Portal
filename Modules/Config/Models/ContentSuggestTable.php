<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 16:26
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;

class ContentSuggestTable extends Model
{
    protected $table = "content_suggest";
    protected $primaryKey = "content_suggest_id";
    protected $fillable = [
        "content_suggest_id",
        "content_suggest",
        "rating_value",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy option tag
     *
     * @param $ratingValue
     * @return mixed
     */
    public function getOption($ratingValue)
    {
        return $this
            ->select(
                "content_suggest_id",
                "content_suggest",
                "rating_value"
            )
            ->where("rating_value", $ratingValue)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("is_actived", self::IS_ACTIVE)
            ->get();
    }

    /**
     * Lấy option cú pháp gợi ý
     *
     * @return mixed
     */
    public function getOptionSuggest()
    {
        return $this
            ->select(
                "content_suggest_id",
                "content_suggest",
                "rating_value"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("is_actived", self::IS_ACTIVE)
            ->get();
    }

    /**
     * Thêm cú pháp gợi ý
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->content_suggest_id;
    }

}