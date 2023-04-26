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

class ContractCategoryRemindMapReceiverTable extends Model
{
    protected $table = 'contract_category_remind_map_receiver';
    protected $primaryKey = 'contract_category_remind_map_receiver_id';
    protected $fillable = [
        "contract_category_remind_map_receiver_id",
        "contract_category_remind_id",
        "receiver_by",
    ];


    public function insertMapReceiver($data)
    {
        return $this->insert($data);
    }

    public function deleteMapReceiverByRemindId($remindId)
    {
        return $this->where("contract_category_remind_id", $remindId)
            ->delete();
    }

    /**
     * get list receiver of 1 remind
     *
     * @param $remindId
     * @return mixed
     */
    public function getReceiver($remindId)
    {
        $data = $this->select("receiver_by")
            ->where("contract_category_remind_id", $remindId);
        return $data->get();
    }
    public function getReceiverHaveName($remindId)
    {
        $data = $this->select(
            DB::raw("GROUP_CONCAT({$this->table}.receiver_by) as receiver_by"),
            DB::raw("GROUP_CONCAT(contract_category_config_tab.key_name) as receiver_name")
        )
            ->leftJoin("contract_category_remind", "contract_category_remind.contract_category_remind_id", "{$this->table}.contract_category_remind_id")
            ->leftJoin("contract_category_config_tab", function($join)use($remindId){
                $join->on("{$this->table}.receiver_by", "=", "contract_category_config_tab.key")
                    ->on("contract_category_remind.contract_category_id", "=", "contract_category_config_tab.contract_category_id")
                    ->where("contract_category_config_tab.tab", "=", DB::raw("'general'"));
            })
            ->where("{$this->table}.contract_category_remind_id", $remindId)
            ->groupBy("{$this->table}.contract_category_remind_id");
        return $data->first();
    }
}