<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceTable extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";

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
            "service_id",
            "service_name"
        )
            ->where("is_surcharge", 0)
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
            "{$this->table}.service_id",
            "{$this->table}.service_name"
        )
            ->where("is_surcharge", 1)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("is_actived", self::IS_ACTIVED);
        return $data->get()->toArray();
    }
}