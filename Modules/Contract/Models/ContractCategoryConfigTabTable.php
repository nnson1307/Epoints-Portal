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

class ContractCategoryConfigTabTable extends Model
{
    protected $table = 'contract_category_config_tab';
    protected $primaryKey = 'contract_category_config_tab_id';
    protected $fillable = [
        "contract_category_config_tab_id",
        "contract_category_id",
        "tab",
        "key",
        "type",
        "key_name",
        "is_default",
        "is_show",
        "is_validate",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    const IS_SHOW = 1;

    /**
     * Lấy cấu hình trường dữ liệu theo category
     *
     * @param $categoryId
     * @return mixed
     */
    public function getConfigTabByCategory($categoryId)
    {
        return $this
            ->select(
                "contract_category_config_tab_id",
                "contract_category_id",
                "tab",
                "key",
                "type",
                "key_name",
                "is_default",
                "is_show",
                "is_validate",
                "number_col"
            )
            ->where("contract_category_id", $categoryId)
            ->where("is_show", self::IS_SHOW)
            ->get();
    }
    public function saveConfigTab($data)
    {
        return $this->insert($data);
    }
    public function deleteConfigTab($contractCategoryId, $tab)
    {
        return $this->where("contract_category_id", $contractCategoryId)
            ->where("tab", $tab)->delete();
    }

    /**
     * Ds các field (key) theo data type, tab, category
     *
     * @param $categoryId
     * @param $tab
     * @param $type
     * @return mixed
     */
    public function getKeyNameByTypeData($categoryId, $tab, $type)
    {
        $data = $this->select(
            "tab",
            "key",
            "type",
            "key_name"
        )->where("tab", $tab)->where("contract_category_id", $categoryId)->where("type", $type);
        return $data->get()->toArray();
    }

    /**
     * Ds các field (key) theo key (like %), tba, category
     *
     * @param $categoryId
     * @param $tab
     * @param $key
     * @return mixed
     */
    public function getKeyNameByKey($categoryId, $tab, $key)
    {
        $data = $this->select(
            "tab",
            "key",
            "type",
            "key_name"
        )->where("tab", $tab)->where("contract_category_id", $categoryId)
            ->where("key", 'like', '%' . $key);
        return $data->get()->toArray();
    }
    public function getListKeyByTabAndCategory($categoryId, $tab)
    {
        $data = $this->select(
            "tab",
            "key",
            "type",
            "key_name",
            "is_default",
            "is_show",
            "is_validate"
        )->where("tab", $tab)->where("contract_category_id", $categoryId);
        return $data->get()->toArray();
    }
}