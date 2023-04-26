<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-03
 * Time: 5:48 PM
 * @author SonDepTrai
 */

namespace Modules\CallCenter\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractsTable extends Model
{
    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';
    protected $fillable = [
        "contract_id",
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

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    
   
    public function getListContractByCustomer($customerId)
    {
        $ds = $this->select(
            "{$this->table}.contract_id",
            "{$this->table}.contract_code",
            "{$this->table}.contract_name",
            "{$this->table}.contract_no",
            "contract_categories.contract_category_name",
            "contract_categories.type",
            "contract_category_status.status_name",
            "contract_payment.last_total_amount",
            "contract_partner.partner_object_type"
        )
            ->join("contract_partner",function($join){
                $join->on("contract_partner.contract_id",'=', "{$this->table}.contract_id")
                    ->where("contract_partner.partner_object_type", "<>", "supplier");
            })
            ->join("contract_payment", "contract_payment.contract_id", "{$this->table}.contract_id")
            ->join("contract_categories", "contract_categories.contract_category_id", "{$this->table}.contract_category_id")
            ->join("contract_category_status", "contract_category_status.status_code", "{$this->table}.status_code")
            ->where("{$this->table}.is_deleted", 0)
            ->where("contract_partner.partner_object_id", $customerId);
        return $ds->get()->toArray();

    }
}
