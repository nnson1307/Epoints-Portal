<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 14:37
 */

namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ContractAnnexGeneralTable extends Model
{
    protected $table = "contract_annex_general";
    protected $primaryKey = "contract_annex_general_id";
    protected $fillable = [
        "contract_annex_general_id",
        "contract_annex_id",
        "contract_category_id",
        "contract_name",
        "contract_code",
        "contract_no",
        "sign_date",
        "performer_by",
        "effective_date",
        "expired_date",
        "warranty_start_date",
        "warranty_end_date",
        "content",
        "note",
        "status_code",
        "is_value_goods",
        "is_renew",
        "number_day_renew",
        "is_created_ticket",
        "status_code_created_ticket",
        "is_deleted",
        "custom_1",
        "custom_2",
        "custom_3",
        "custom_4",
        "custom_5",
        "custom_6",
        "custom_7",
        "custom_8",
        "custom_9",
        "custom_10",
        "custom_11",
        "custom_12",
        "custom_13",
        "custom_14",
        "custom_15",
        "custom_16",
        "custom_17",
        "custom_18",
        "custom_19",
        "custom_20",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Thêm HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_annex_general_id;
    }

    /**
     * Cập nhật HĐ
     *
     * @param array $data
     * @param $contractAnnexId
     * @return mixed
     */
    public function edit(array $data, $contractAnnexId)
    {
        return $this->where("contract_annex_id", $contractAnnexId)->update($data);
    }

    /**
     * Lấy thông tin HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function getInfo($contractAnnexId)
    {
        return $this
            ->select(
                "{$this->table}.contract_annex_id",
                "{$this->table}.contract_category_id",
                "{$this->table}.contract_name",
                "{$this->table}.contract_code",
                "{$this->table}.contract_no",
                "{$this->table}.sign_date",
                "{$this->table}.performer_by",
                "{$this->table}.effective_date",
                "{$this->table}.expired_date",
                "{$this->table}.warranty_start_date",
                "{$this->table}.warranty_end_date",
                "{$this->table}.content",
                "{$this->table}.note",
                "{$this->table}.status_code",
                "{$this->table}.is_value_goods",
                "{$this->table}.is_renew",
                "{$this->table}.number_day_renew",
                "{$this->table}.is_created_ticket",
                "{$this->table}.status_code_created_ticket",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10",
                "{$this->table}.custom_11",
                "{$this->table}.custom_12",
                "{$this->table}.custom_13",
                "{$this->table}.custom_14",
                "{$this->table}.custom_15",
                "{$this->table}.custom_16",
                "{$this->table}.custom_17",
                "{$this->table}.custom_18",
                "{$this->table}.custom_19",
                "{$this->table}.custom_20"
            )
            ->where("{$this->table}.contract_annex_id", $contractAnnexId)
            ->first();
    }

}