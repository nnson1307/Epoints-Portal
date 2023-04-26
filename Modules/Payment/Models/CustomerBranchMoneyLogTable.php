<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBranchMoneyLogTable extends Model
{
    protected $table = "customer_branch_money_log";
    protected $primaryKey = "customer_branch_money_log_id";
    protected $fillable = [
        "customer_branch_money_log_id",
        "customer_id",
        "branch_id",
        "source",
        "type",
        "money",
        "screen",
        "screen_object_code",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm tiền chi nhánh của KH
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_branch_money_log_id;
    }
}