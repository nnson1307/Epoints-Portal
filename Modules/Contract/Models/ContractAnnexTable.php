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
use MyCore\Models\Traits\ListTableTrait;

class ContractAnnexTable extends Model
{
    protected $table = 'contract_annex';
    protected $primaryKey = 'contract_annex_id';
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
    const IS_ACTIVED = 1;
    const IS_DELETED = 0;

    use ListTableTrait;
    protected function _getList(&$filter = [])
    {
        $ds = $this->select(
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
            "staffs.full_name as staff_created_by")
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->where("{$this->table}.is_deleted", self::IS_DELETED);
        if(isset($filter['contract_id']) && $filter['contract_id'] != '')
        {
            $ds->where("{$this->table}.contract_id", $filter['contract_id']);
        }
        unset($filter['contract_id']);
        return $ds->orderBy("{$this->table}.updated_at",'desc');
    }

    /**
     * ds phụ lục gia hạn chưa được đánh dấu thực hiện gia hạn
     *
     * @param $contractId
     * @return mixed
     */
    public function getContractAnnexRecare($contractId)
    {
        $ds = $this->select(
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
            "{$this->table}.is_checked_recare",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at"
        )
            ->where('contract_id', $contractId)
            ->where('is_active', self::IS_ACTIVED)
            ->where('is_deleted', self::IS_DELETED)
            ->where('adjustment_type', 'renew_contract') // wrong enum data grammarly (right: recare_contract)
            ->where('is_checked_recare', 0);
        return $ds->get();
    }
    public function updateCheckedRecare($data, $id)
    {
        return $this->where("contract_annex_id", $id)->update($data);
    }
}