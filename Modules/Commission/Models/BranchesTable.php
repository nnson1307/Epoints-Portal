<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class BranchesTable extends Model
{
    protected $table      = 'branches';
    protected $primaryKey = 'branch_id';

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách chi nhánh
     */
    public function getListBranch()
    {
        return $this->select("{$this->table}.branch_id",
                            "{$this->table}.branch_name")
                    ->where("{$this->table}.is_deleted", self::NOT_DELETED)
                    ->where("{$this->table}.is_actived", self::IS_ACTIVE)
                    ->get();
    }
}
