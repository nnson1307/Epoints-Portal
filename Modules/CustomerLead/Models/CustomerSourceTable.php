<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/31/2020
 * Time: 4:57 PM
 */

namespace Modules\CustomerLead\Models;


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
    public function getOptionByFilter($filter)
    {
        $data = $this
            ->select(
                "customer_source_id",
                "customer_source_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);
        if(isset($filter['customer_source_id']) != ''){
            $data->where("customer_source_id", $filter['customer_source_id']);
            unset($filter['customer_source_id']);
        }
        return $data->get();
    }

    /**
     * Lấy id theo tên nguồn khách hàng
     *
     * @param $name
     * @return mixed
     */
    public function getIdByName($name)
    {
        return $this->select(
            "customer_source_id",
            "customer_source_name"
        )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("customer_source_name", $name)
            ->first();
    }

    /**
     * Thêm nguồn khách hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_source_id;
    }

    public function getInfo($customerSourceId)
    {
        $item = $this->select(
            "customer_source_id",
            "customer_source_name"
        )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("customer_source_id", $customerSourceId)
            ->first();
        return $item;

    }

    public function getSourceByCode($source_code)
    {
        return $this->select(
            "customer_source_id",
            "customer_source_name"
        )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("customer_source_name", $source_code)
            ->first();
    }

    public function _getList(&$filter = [])
    {
        $oSelect = $this
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);

        if (isset($filter['customer_source_id'])) {
            $oSelect = $oSelect->where('customer_source_id', $filter['customer_source_id']);
        }

        unset($filter['time']);
        unset($filter['pipeline']);
        unset($filter['department_id']);
        unset($filter['customer_source_id']);
        unset($filter['staff_id']);
        unset($filter['pipeline_code']);

        return $oSelect;
    }

}