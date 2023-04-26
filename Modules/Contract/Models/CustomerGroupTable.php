<?php

namespace Modules\Contract\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroupTable extends Model
{
    protected $table = "customer_group";
    protected $primaryKey = "customer_group_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhóm KH
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "customer_group_id",
                "group_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}