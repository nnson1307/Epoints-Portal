<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionConfigTable extends Model
{
    protected $table      = 'commission_config';
    protected $primaryKey = 'commission_config_id';
    protected $fillable = [
        'commission_config_id',
        'commission_id',
        'min_value',
        'max_value',
        'commission_value',
        'is_deleted'
    ];


    /**
     * Thêm cấu hình hoa hồng
     */
    public function addConfigCommission($data) 
    {
        return $this->insert($data);
    }

    /**
     * Lấy cấu hình hoa hồng theo id
     */
    public function getConfigCommissionById($id)
    {
        return $this->select(
                        "{$this->table}.min_value",
                        "{$this->table}.max_value",
                        "{$this->table}.commission_value",
                        "{$this->table}.config_operation"
                    )
                    ->where("{$this->table}.commission_id", $id)
                    ->where("{$this->table}.is_deleted", 0)
                    ->get()
                    ->toArray();
    }
}
