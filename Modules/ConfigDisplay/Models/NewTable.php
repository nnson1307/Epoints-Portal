<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 4:03 PM
 */

namespace Modules\ConfigDisplay\Models;

use Illuminate\Database\Eloquent\Model;

class NewTable extends Model
{
    protected $table = "news";
    protected $primaryKey = "new_id";
    protected $fillable = [
        "new_id",
        "title_vi",
        "title_en",
        "image",
        "description_vi",
        "description_en",
        "description_detail_vi",
        "description_detail_en",
        "product",
        "service",
        "is_actived",
        "created_at",
        "updated_at",
        "is_deleted",
        "created_by",
        "updated_by"
    ];

    const IS_ACTIVE = 1;
    const IS_DELETED = 0;

    public function getAll()
    {
        return $this->select("new_id", "title_vi")
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::IS_DELETED)
            ->get();
    }
}
