<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCardSoldAccrualLogTable extends Model
{
    protected $table = "service_card_sold_accrual_logs";
    protected $primaryKey = "service_card_sold_accrual_log_id";
    protected $fillable = [
        'service_card_sold_accrual_log_id',
        'card_code_destination',
        'card_code_target',
        'number_of_days',
        'number_of_uses',
        'created_bu',
        'created_at',
        'updated_at',
    ];

    /**
     * Thêm log
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

}