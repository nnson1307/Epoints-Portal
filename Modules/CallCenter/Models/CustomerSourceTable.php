<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/31/2020
 * Time: 4:57 PM
 */

namespace Modules\CallCenter\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerSourceTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_sources";
    protected $primaryKey = "customer_source_id";
    protected $fillable = [
        "customer_source_id",
        "customer_source_name",
        "customer_source_type",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "slug"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy option nguồn KH
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "customer_source_id",
                "customer_source_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }
}