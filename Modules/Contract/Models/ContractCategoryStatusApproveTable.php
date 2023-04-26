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

class ContractCategoryStatusApproveTable extends Model
{
    protected $table = 'contract_category_status_approve';
    protected $primaryKey = 'contract_category_status_approve_id';
    protected $fillable = [
        "contract_category_status_approve_id",
        "status_code",
        "approve_by",
    ];

    public function insertStatusApprove($data)
    {
        return $this->insert($data);
    }

    public function deleteStatusCodeApprove($statusCode)
    {
        return $this->where("status_code", $statusCode)
            ->delete();
    }
    public function getDetailStatusApprove($statusCode)
    {
        $data = $this
            ->select(
                "{$this->table}.contract_category_status_approve_id",
                "{$this->table}.status_code",
                "{$this->table}.approve_by",
                "role_group.name"
            )
            ->leftJoin("role_group", "role_group.id", "{$this->table}.approve_by")
            ->where("{$this->table}.status_code", $statusCode)
            ->get();
        return $data;
    }
}