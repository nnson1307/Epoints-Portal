<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AdminLockOutTable extends Model
{
    use ListTableTrait;
    protected $table = 'admin_lock_out';
    protected $primaryKey = 'admin_lock_out_id';

    public function remove($condition)
    {
        if (is_array($condition)) {
            return $this->where($condition)->delete();
        } else {
            return $this->where('admin_id', $condition)->delete();
        }
    }
}
