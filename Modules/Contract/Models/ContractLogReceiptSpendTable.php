<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/08/2021
 * Time: 14:43
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogReceiptSpendTable extends Model
{
    protected $table = "contract_log_receipt_spend";
    protected $primaryKey = "contract_log_receipt_spend_id";
    protected $fillable = [
        "contract_log_receipt_spend_id",
        "contract_log_id",
        "object_type",
        "object_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Lưu log thu - chi khi có thay đổi
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}