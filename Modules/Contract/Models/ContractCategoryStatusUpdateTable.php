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

class ContractCategoryStatusUpdateTable extends Model
{
    protected $table = 'contract_category_status_update';
    protected $primaryKey = 'contract_category_status_update_id';
    protected $fillable = [
        "contract_category_status_update_id",
        "status_code",
        "status_code_update",
    ];


    public function insertStatusUpdate($data)
    {
        return $this->insert($data);
    }

    public function deleteStatusCodeUpdate($statusCode)
    {
        return $this->where("status_code", $statusCode)
            ->delete();
    }

    /**
     * Lấy trạng thái được update
     *
     * @param $statusCode
     * @return mixed
     */
    public function getStatusUpdate($statusCode)
    {
        return $this
            ->select(
                "contract_category_status_update_id",
                "status_code",
                "status_code_update"
            )
            ->where("status_code", $statusCode)
            ->get();
    }
    public function getDetailStatusUpdate($statusCode)
    {
        $data = $this
            ->select(
                "{$this->table}.contract_category_status_update_id",
                "{$this->table}.status_code",
                "{$this->table}.status_code_update",
                "contract_category_status.status_name"
            )
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "{$this->table}.status_code_update")
            ->where("{$this->table}.status_code", $statusCode)
            ->get();
        return $data;
    }
}