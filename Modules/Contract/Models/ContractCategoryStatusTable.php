<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 8/23/2021
 * Time: 10:11 AM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractCategoryStatusTable extends Model
{
    protected $table = 'contract_category_status';
    protected $primaryKey = 'contract_category_status_id';
    protected $fillable = [
        "contract_category_status_id",
        "contract_category_id",
        "status_name",
        "status_code",
        "default_system",
        "is_approve",
        "approve_by",
        "is_edit_contract",
        "is_deleted_contract",
        "is_reason",
        "is_show",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_DELETED = 0;
    const IS_SHOW = 1;
    const DRAFT = "draft";

    /**
     * Lấy option trạng thái hợp đồng
     *
     * @param $categoryId
     * @return mixed
     */
    public function getOptionByCategory($categoryId)
    {
        return $this
            ->select(
                "contract_category_status_id",
                "contract_category_id",
                "status_name",
                "status_code",
                "default_system",
                "is_approve",
                "approve_by",
                "is_edit_contract",
                "is_deleted_contract",
                "is_reason",
                "is_show"
            )
            ->where("is_show", self::IS_SHOW)
            ->where("contract_category_id", $categoryId)
            ->get();
    }

    /**
     * Lấy trạng thái nháp của HĐ
     *
     * @param $categoryId
     * @return mixed
     */
    public function getStatusDraft($categoryId)
    {
        return $this
            ->select(
                "contract_category_status_id",
                "contract_category_id",
                "status_name",
                "status_code",
                "default_system",
                "is_approve",
                "approve_by",
                "is_edit_contract",
                "is_deleted_contract",
                "is_reason",
                "is_show"
            )
            ->where("contract_category_id", $categoryId)
            ->where("default_system", self::DRAFT)
            ->first();
    }

    /**
     * Lấy option trạng thái hợp đồng được chỉnh sửa
     *
     * @param $categoryId
     * @param $arrayStatusCode
     * @return mixed
     */
    public function getOptionByCategoryEdit($categoryId, $arrayStatusCode)
    {
        return $this
            ->select(
                "contract_category_status_id",
                "contract_category_id",
                "status_name",
                "status_code",
                "default_system",
                "is_approve",
                "approve_by",
                "is_edit_contract",
                "is_deleted_contract",
                "is_reason",
                "is_show"
            )
            ->where("is_show", self::IS_SHOW)
            ->where("contract_category_id", $categoryId)
            ->whereIn("status_code", $arrayStatusCode)
            ->get();
    }

    public function createStatusTab($data)
    {
        return $this->create($data)->contract_category_status_id;
    }
    public function updateStatusTab($data, $id)
    {
        return $this->where("contract_category_status_id", $id)->update($data);
    }

    /**
     * update status tab by status code
     *
     * @param $data
     * @param $code
     * @return mixed
     */
    public function updateStatusTabByStatusCode($data, $code)
    {
        return $this->where("status_code", $code)->update($data);
    }
    public function insertStatusTab($data)
    {
        return $this->insert($data);
    }

    public function deleteStatusTab($contractCategoryId)
    {
        return $this->where("contract_category_id", $contractCategoryId)
            ->delete();
    }
    public function getStatusNameByCode($code)
    {
        return $this->where("status_code", $code)->first();
    }
}