<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/11/2021
 * Time: 14:05
 */

namespace Modules\Config\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ConfigCustomerParameterTable extends Model
{
    use ListTableTrait;
    protected $table = "config_customer_parameter";
    protected $primaryKey = "config_customer_parameter_id";
    protected $fillable = [
        "config_customer_parameter_id",
        "parameter_name",
        "content",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách tham số
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "config_customer_parameter_id",
                "parameter_name",
                "content",
                "created_at"
            )
            ->where("is_deleted", self::NOT_DELETED);

        // filter tên tên, mã
        if (!empty($filter['search'])) {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("parameter_name", 'like', '%' . $search . '%')
                    ->orWhere("content", 'like', '%' . $search . '%');
            });

            unset($filter['search']);
        }

        return $ds;
    }

    /**
     * Thêm tham số
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->config_customer_parameter_id;
    }

    /**
     * Lấy thông tin tham số
     *
     * @param $customerParameterId
     * @return mixed
     */
    public function getInfo($customerParameterId)
    {
        return $this
            ->select(
                "config_customer_parameter_id",
                "parameter_name",
                "content",
                "created_at"
            )
            ->where("config_customer_parameter_id", $customerParameterId)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Chỉnh sửa tham số
     *
     * @param array $data
     * @param $customerParameterId
     * @return mixed
     */
    public function edit(array $data, $customerParameterId)
    {
        return $this->where("config_customer_parameter_id", $customerParameterId)->update($data);
    }
}