<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class StaffCommissionLogTable extends Model
{
    protected $table = 'staff_commission_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'staff_commission_log_id',
        'order_commission_id',
        'action_type',
        'staff_id',
        'staff_money',
        'content',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    /**
     * Thêm log hoa hồng nhân viên
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }
}