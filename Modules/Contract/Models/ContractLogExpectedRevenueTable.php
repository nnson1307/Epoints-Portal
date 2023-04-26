<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 11:03
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogExpectedRevenueTable extends Model
{
    protected $table = "contract_log_expected_revenue";
    protected $primaryKey = "contract_log_expected_revenue_id";
    protected $fillable = [
        "contract_log_expected_revenue_id",
        "contract_log_id",
        "contract_expected_revenue_id",
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