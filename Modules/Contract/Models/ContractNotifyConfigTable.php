<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/19/2021
 * Time: 2:10 PM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractNotifyConfigTable extends Model
{
    protected $table = 'contract_notify_config';
    protected $primaryKey = "contract_notify_config_id";
    protected $fillable = [
      "contract_notify_config_id",
      "contract_notify_config_code",
      "contract_notify_config_name_vi",
      "contract_notify_config_name_en",
      "contract_notify_config_content",
      "is_created_by",
      "is_performer_by",
      "is_signer_by",
      "is_follow_by",
      "detail_action_name",
      "detail_action",
      "detail_action_params",
      "created_by",
      "updated_by",
      "created_at",
      "updated_at",
    ];

    public function getAllConfig()
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "{$this->table}.contract_notify_config_id",
            "{$this->table}.contract_notify_config_code",
            "{$this->table}.contract_notify_config_name_$lang as contract_notify_config_name",
            "{$this->table}.contract_notify_config_name_vi",
            "{$this->table}.contract_notify_config_name_en",
            "{$this->table}.contract_notify_config_content",
            "{$this->table}.is_created_by",
            "{$this->table}.is_performer_by",
            "{$this->table}.is_signer_by",
            "{$this->table}.is_follow_by",
            "{$this->table}.detail_action_name",
            "{$this->table}.detail_action",
            "{$this->table}.detail_action_params",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("GROUP_CONCAT(contract_notify_config_method_map.notify_method) as notify_method")
        )
            ->leftJoin("contract_notify_config_method_map", "contract_notify_config_method_map.contract_notify_config_id", "{$this->table}.contract_notify_config_id");
        $data->groupBy("{$this->table}.contract_notify_config_id");
        return $data->get();
    }
    public function updateData($data, $id)
    {
        return $this->where("{$this->table}.{$this->primaryKey}", $id)->update($data);
    }
    public function getItem($code)
    {
        $lang = app()->getLocale();
        $data = $this->select(
            "{$this->table}.contract_notify_config_id",
            "{$this->table}.contract_notify_config_code",
            "{$this->table}.contract_notify_config_name_$lang as contract_notify_config_name",
            "{$this->table}.contract_notify_config_name_vi",
            "{$this->table}.contract_notify_config_name_en",
            "{$this->table}.contract_notify_config_content",
            "{$this->table}.is_created_by",
            "{$this->table}.is_performer_by",
            "{$this->table}.is_signer_by",
            "{$this->table}.is_follow_by",
            "{$this->table}.detail_action_name",
            "{$this->table}.detail_action",
            "{$this->table}.detail_action_params",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("GROUP_CONCAT(contract_notify_config_method_map.notify_method) as notify_method")
        )
            ->leftJoin("contract_notify_config_method_map", "contract_notify_config_method_map.contract_notify_config_id", "{$this->table}.contract_notify_config_id")
            ->where("{$this->table}.contract_notify_config_code",$code);
        $data->groupBy("{$this->table}.contract_notify_config_id");
        return $data->first();
    }
}