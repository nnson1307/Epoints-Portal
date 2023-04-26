<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 09:44
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogGeneralTable extends Model
{
    protected $table = "contract_log_general";
    protected $primaryKey = "contract_log_general_id";
    protected $fillable = [
        "contract_log_general_id",
        "contract_log_id",
        "contract_code",
        "contract_name",
        "sign_date",
        "sign_by",
        "tag",
        "performer_by",
        "effective_date",
        "expired_date",
        "follow_by",
        "is_renew",
        "number_day_renew",
        "is_created_ticket",
        "status_code_created_ticket",
        "warranty_start_date",
        "warranty_end_date",
        "content",
        "note",
        "status_code",
        "is_value_goods",
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
        "contract_name_new",
        "contract_code_new",
        "sign_date_new",
        "sign_by_new",
        "tag_new",
        "performer_by_new",
        "effective_date_new",
        "expired_date_new",
        "follow_by_new",
        "is_renew_new",
        "number_day_renew_new",
        "is_created_ticket_new",
        "status_code_created_ticket_new",
        "warranty_start_date_new",
        "warranty_end_date_new",
        "content_new",
        "note_new",
        "status_code_new",
        "is_value_goods_new",
        "custom_1_new",
        "custom_2_new",
        "custom_3_new",
        "custom_4_new",
        "custom_5_new",
        "custom_6_new",
        "custom_7_new",
        "custom_8_new",
        "custom_9_new",
        "custom_10_new",
        "custom_11_new",
        "custom_12_new",
        "custom_13_new",
        "custom_14_new",
        "custom_15_new",
        "custom_16_new",
        "custom_17_new",
        "custom_18_new",
        "custom_19_new",
        "custom_20_new",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log thông tin chung
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}