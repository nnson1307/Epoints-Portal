<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/22/2021
 * Time: 3:44 PM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractAnnexLogTable extends Model
{
    protected $table = "contract_annex_logs";
    protected $primaryKey = "contract_annex_log_id";
    protected $fillable = [
      "contract_annex_log_id",
      "object_type",
      "contract_annex_id",
      "key_table",
      "key",
      "key_name",
      "value_old",
      "value_new",
      "created_by",
      "updated_by",
      "created_at",
      "updated_at",
    ];

    public  function createData($data)
    {
        return $this->create($data);
    }
    public  function insertData($data)
    {
        return $this->insert($data);
    }
    public function getLogContractAnnex($categoryId, $objectType, $contractAnnexId, $keyTable = '')
    {
        $lang = app()->getLocale();
        $ds = $this->select(
            "{$this->table}.contract_annex_log_id",
            "{$this->table}.object_type",
            "{$this->table}.contract_annex_id",
            "{$this->table}.key_table",
            "{$this->table}.key",
            "{$this->table}.value_old",
            "{$this->table}.value_new",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "contract_category_config_tab.key_name",
            "contract_category_config_tab.type"
        )
            ->leftJoin('contract_category_config_tab',function($join)use($categoryId,$keyTable) {
                $join->on("contract_category_config_tab.key", "=", "{$this->table}.key")
                    ->where("contract_category_config_tab.contract_category_id", $categoryId)
                    ->where("contract_category_config_tab.tab", substr($keyTable, strripos($keyTable, '_') + 1, strlen($keyTable)));
                })
            ->where("{$this->table}.contract_annex_id", $contractAnnexId)
            ->where("{$this->table}.object_type", $objectType);
        if($keyTable != ''){
            $ds->where("{$this->table}.key_table", $keyTable);
        }
        return $ds->get()->toArray();
    }
    public function getLogContractAnnexCommon($objectType, $contractAnnexId, $keyTable = '')
    {
        $lang = app()->getLocale();
        $ds = $this->select(
            "{$this->table}.contract_annex_log_id",
            "{$this->table}.object_type",
            "{$this->table}.contract_annex_id",
            "{$this->table}.key_table",
            "{$this->table}.key",
            "{$this->table}.value_old",
            "{$this->table}.value_new",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "contract_category_config_tab.key_name",
            "contract_category_config_tab.type"
        )
            ->leftJoin('contract_category_config_tab', "contract_category_config_tab.key", "{$this->table}.key")
            ->where("{$this->table}.contract_annex_id", $contractAnnexId)
            ->where("{$this->table}.object_type", $objectType);
        if($keyTable != ''){
            $ds->where("{$this->table}.key_table", $keyTable);
        }
        return $ds->get()->toArray();
    }
}