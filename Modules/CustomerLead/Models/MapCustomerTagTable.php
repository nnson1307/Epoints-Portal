<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:07 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class MapCustomerTagTable extends Model
{
    protected $table = "cpo_map_customer_tag";
    protected $primaryKey = "map_customer_tag_id";
    protected $fillable = [
        "map_customer_tag_id",
        "customer_lead_code",
        "tag_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm map customer tag
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->map_customer_tag_id;
    }

    /**
     * Lấy thông tin map customer tag
     *
     * @param $customerLeadCode
     * @return mixed
     */
    public function getMapByCustomer($customerLeadCode)
    {
        return $this
            ->select(
                "map_customer_tag_id",
                "customer_lead_code",
                "tag_id"
            )
            ->where("customer_lead_code", $customerLeadCode)
            ->get();
    }
    public function getArrayMapByCustomer($customerLeadCode)
    {
        return $this
            ->select(
                "tag_id"
            )
            ->where("customer_lead_code", $customerLeadCode)
            ->get()->toArray();
    }

    /**
     * Xóa map customer tag
     *
     * @param $customerLeadCode
     * @return mixed
     */
    public function remove($customerLeadCode)
    {
        return $this->where("customer_lead_code", $customerLeadCode)->delete();
    }

    /**
     * Lấy danh sách customer lead theo tag id
     *
     * @param $tagId
     * @return mixed
     */
    public function getListLeadByTagId($tagId)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_code"
            )
            ->leftJoin("cpo_tag", "cpo_tag.tag_id", "=", "cpo_map_customer_tag.tag_id")
            ->where("{$this->table}.tag_id", $tagId)
            ->get();
    }
}