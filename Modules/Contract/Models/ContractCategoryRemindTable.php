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

class ContractCategoryRemindTable extends Model
{
    protected $table = 'contract_category_remind';
    protected $primaryKey = 'contract_category_remind_id';
    protected $fillable = [
        "contract_category_remind_id",
        "contract_category_id",
        "remind_type",
        "title",
        "content",
        "recipe",
        "unit",
        "unit_value",
        "compare_unit",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVED = 1;
    const IS_DELETED = 0;
    public function createRemind($data)
    {
        return $this->create($data)->contract_category_remind_id;
    }

    /**
     * Lấy option nội dung nhắc nhở theo loại HĐ
     *
     * @param $categoryId
     * @param $type
     * @return mixed
     */
    public function getOptionByType($categoryId, $type)
    {
        return $this
            ->select(
                "contract_category_remind_id",
                "contract_category_id",
                "remind_type",
                "title",
                "content",
                "recipe",
                "unit",
                "unit_value",
                "compare_unit"
            )
            ->where("contract_category_id", $categoryId)
            ->where("remind_type", $type)
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::IS_DELETED)
            ->get();
    }

    /**
     * get 1 remind by category id and remind_type to check limit remind type
     *
     * @param $categoryId
     * @param $type
     * @param $remindId
     * @return mixed
     */
    public function getRemindByCategoryAndType($categoryId, $type, $remindId = null)
    {
        $data = $this->select(
            "contract_category_remind_id",
            "contract_category_id",
            "remind_type",
            "title",
            "content",
            "recipe",
            "unit",
            "unit_value",
            "compare_unit",
            "is_actived",
            "is_deleted",
            "created_by",
            "updated_by",
            "created_at",
            "updated_at")
            ->where("contract_category_id", $categoryId)
            ->where("is_deleted", self::IS_DELETED)
            ->where("remind_type", $type);
        if($remindId != null){
            $data->where("contract_category_remind_id", "!=", $remindId);
        }
        return $data->first();
    }

    public function getRemindByCategory($categoryId)
    {

        $lang = app()->getLocale();
        $data = $this->select(
            "{$this->table}.contract_category_remind_id",
            "{$this->table}.contract_category_id",
            "{$this->table}.remind_type",
            "contract_category_remind_type.remind_type_name_$lang as remind_type_text",
            "{$this->table}.title",
            "{$this->table}.content",
            "{$this->table}.recipe",
            "{$this->table}.unit",
            "{$this->table}.unit_value",
            "{$this->table}.compare_unit",
            "contract_category_config_tab.key_name as compare_unit_text",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at")
            ->leftJoin("contract_category_config_tab", function($join)use($categoryId){
                $join->on("{$this->table}.compare_unit", "=", "contract_category_config_tab.key")
                    ->on("{$this->table}.contract_category_id", "=", "contract_category_config_tab.contract_category_id")
                    ->where("contract_category_config_tab.tab", "=", DB::raw("'general'"));
            })
            ->leftJoin("contract_category_remind_type", "contract_category_remind_type.remind_type_code", "{$this->table}.remind_type")
            ->where("{$this->table}.contract_category_id", $categoryId)
            ->where("{$this->table}.is_deleted", self::IS_DELETED);
        return $data->get();
    }
    public function getItem($remindId)
    {
        $data = $this->select(
            "contract_category_remind_id",
            "contract_category_id",
            "remind_type",
            "title",
            "content",
            "recipe",
            "unit",
            "unit_value",
            "compare_unit",
            "is_actived",
            "is_deleted",
            "created_by",
            "updated_by",
            "created_at",
            "updated_at")
            ->where("contract_category_remind_id", $remindId)
            ->where("is_deleted", self::IS_DELETED);
        return $data->first();
    }

    /**
     * remove remind
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function removeRemind($data, $id)
    {
        return $this->where("contract_category_remind_id", $id)->update($data);
    }

    /**
     * update 1 remind
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateRemind($data, $id)
    {
        return $this->where("contract_category_remind_id", $id)->update($data);
    }
}