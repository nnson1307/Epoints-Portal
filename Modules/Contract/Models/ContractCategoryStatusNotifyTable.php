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

class ContractCategoryStatusNotifyTable extends Model
{
    protected $table = 'contract_category_status_notify';
    protected $primaryKey = 'contract_category_status_notify_id';
    protected $fillable = [
        "contract_category_status_notify_id",
        "contract_category_id",
        "status_code",
        "content",
        "is_created_by",
        "is_performer_by",
        "is_signer_by",
        "is_follow_by",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    public function insertNotifyTab($data)
    {
        return $this->insert($data);
    }

    public function deleteNotifyTab($contractCategoryId)
    {
        return $this->where("contract_category_id", $contractCategoryId)
            ->delete();
    }
    public function getListNotifyByCategory($categoryId)
    {
        $data = $this->select(
            "{$this->table}.contract_category_status_notify_id",
            "{$this->table}.contract_category_id",
            "{$this->table}.status_code",
            "{$this->table}.content",
            "{$this->table}.is_created_by",
            "{$this->table}.is_performer_by",
            "{$this->table}.is_signer_by",
            "{$this->table}.is_follow_by",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "contract_category_status.status_name"
        )
            ->leftJoin("contract_category_status", "contract_category_status.status_code","{$this->table}.status_code")
            ->where("{$this->table}.contract_category_id", $categoryId);
        return $data->get();
    }
}