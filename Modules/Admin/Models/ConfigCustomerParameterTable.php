<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/11/2021
 * Time: 16:38
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigCustomerParameterTable extends Model
{
    protected $table = "config_customer_parameter";
    protected $primaryKey = "config_customer_parameter_id";

    const NOT_DELETED = 0;

    /**
     * Lấy tham số
     *
     * @return mixed
     */
    public function getParameter()
    {
        return $this
            ->select(
                "config_customer_parameter_id",
                "parameter_name",
                "content",
                "created_at"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}