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

class ContractCategoryRemindMapMethodTable extends Model
{
    protected $table = 'contract_category_remind_map_method';
    protected $primaryKey = 'contract_category_remind_map_method_id';
    protected $fillable = [
        "contract_category_remind_map_method_id",
        "contract_category_remind_id",
        "remind_method",
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
     * get list method of 1 remind
     *
     * @param $remindId
     * @return mixed
     */
    public function getMethod($remindId)
    {
        $data = $this->select("remind_method")
            ->where("contract_category_remind_id", $remindId);
        return $data->get();
    }
    public function getMethodGroupConcat($remindId)
    {
        $data = $this->select(
            DB::raw("GROUP_CONCAT({$this->table}.remind_method) as remind_method")
        )
            ->where("contract_category_remind_id", $remindId);
        return $data->first();
    }
}