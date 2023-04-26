<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:11 AM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractAnnexModel extends Model
{
    protected $table = "contract_annex";
    protected $primaryKey = "contract_annex_id";
    protected $fillable = [
        "contract_annex_id",
        "contract_id",
        "contract_annex_code",
        "sign_date",
        "effective_date",
        "expired_date",
        "adjustment_type",
        "content",
        "is_active",
        "is_deleted",
        "is_checked_recare",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const IS_DELETED = 0;

    use ListTableTrait;

    public function createData($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }
    public function updateData($data, $id)
    {
        return $this->where("contract_annex_id", $id)->update($data);
    }
    public function updateDataByCode($data, $code)
    {
        return $this->where("contract_annex_code", $code)->update($data);
    }
    public function getInfoByCode($code)
    {
        return $this->where("contract_annex_code", $code)->first();
    }
    public function getInfoContractByAnnex($contractAnnexId)
    {
        $ds = $this->select(
            "contracts.contract_category_id"
        )
            ->leftJoin("contracts", "contracts.contract_id", "{$this->table}.contract_id")
            ->where("{$this->table}.contract_annex_id", $contractAnnexId);
        return $ds->first();

    }
    public function getItem($id)
    {
        $item = $this->select(
            "{$this->table}.contract_annex_id",
            "{$this->table}.contract_id",
            "{$this->table}.contract_annex_code",
            "{$this->table}.sign_date",
            "{$this->table}.effective_date",
            "{$this->table}.expired_date",
            "{$this->table}.adjustment_type",
            "{$this->table}.content",
            "{$this->table}.is_active",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("group_concat(contract_annex_files.link) as list_link"),
            DB::raw("group_concat(contract_annex_files.name) as list_name")
        )
        ->leftJoin("contract_annex_files", "contract_annex_files.contract_annex_id", "{$this->table}.contract_annex_id")
        ->where("{$this->table}.contract_annex_id", $id);
        return $item->groupBy("{$this->table}.contract_annex_id")->first();
    }
    public function getItemFormatDate($id)
    {
        $item = $this->select(
            "{$this->table}.contract_annex_id",
            "{$this->table}.contract_id",
            "{$this->table}.contract_annex_code",
            DB::raw("DATE_FORMAT({$this->table}.sign_date,'%d/%m/%Y') as sign_date"),
            DB::raw("DATE_FORMAT({$this->table}.effective_date,'%d/%m/%Y') as effective_date"),
            DB::raw("DATE_FORMAT({$this->table}.expired_date,'%d/%m/%Y') as expired_date"),
            "{$this->table}.adjustment_type",
            "{$this->table}.content",
            "{$this->table}.is_active",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("group_concat(contract_annex_files.link) as list_link"),
            DB::raw("group_concat(contract_annex_files.name) as list_name")
        )
        ->leftJoin("contract_annex_files", "contract_annex_files.contract_annex_id", "{$this->table}.contract_annex_id")
        ->where("{$this->table}.contract_annex_id", $id);
        return $item->groupBy("{$this->table}.contract_annex_id")->first();
    }
    public function getItemFormatDateByCode($code)
    {
        $item = $this->select(
            "{$this->table}.contract_annex_id",
            "{$this->table}.contract_id",
            "{$this->table}.contract_annex_code",
            DB::raw("DATE_FORMAT({$this->table}.sign_date,'%d/%m/%Y') as sign_date"),
            DB::raw("DATE_FORMAT({$this->table}.effective_date,'%d/%m/%Y') as effective_date"),
            DB::raw("DATE_FORMAT({$this->table}.expired_date,'%d/%m/%Y') as expired_date"),
            "{$this->table}.adjustment_type",
            "{$this->table}.content",
            "{$this->table}.is_active",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("group_concat(contract_annex_files.link) as list_link"),
            DB::raw("group_concat(contract_annex_files.name) as list_name")
        )
        ->leftJoin("contract_annex_files", "contract_annex_files.contract_annex_id", "{$this->table}.contract_annex_id")
        ->where("{$this->table}.contract_annex_code", $code);
        return $item->groupBy("{$this->table}.contract_annex_id")->first();
    }
}