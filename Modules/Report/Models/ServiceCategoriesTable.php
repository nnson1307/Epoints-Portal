<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceCategoriesTable extends Model
{
    protected $table = "service_categories";
    protected $primaryKey = "service_category_id";

    const NOT_DELETE = 0;
    const IS_ACTIVED = 1;

    /**
     * Lấy danh sách dịch vụ
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select(
            "service_category_id",
            "name"
        )
            ->where("is_deleted", self::NOT_DELETE)
            ->where("is_actived", self::IS_ACTIVED);
        return $select->get();
    }

    /**
     * tuỳ chọn dịch vụ phụ thu
     *
     * @return mixed
     */
    public function getOptionSurchargeService()
    {
        $data = $this->select(
            "{$this->table}.service_category_id",
            "{$this->table}.name"
        )
            ->where("is_deleted", self::NOT_DELETE)
            ->where("is_actived", self::IS_ACTIVED);
        return $data->get()->toArray();
    }
}