<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BranchesTable
 * @author HaoNMN
 * @since Jul 2022
 */
class BranchesTable extends Model
{
    protected $table      = 'branches';
    protected $primaryKey = 'branch_id';
    protected $fillable   = [
        'branch_id',
        'branch_name'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;


    /**
     * Get list of branch
     * @return array
     */
    public function getBranch()
    {
        return $this->select(
                        "{$this->table}.branch_id",
                        "{$this->table}.branch_name"
                    )
                    ->where("{$this->table}.is_actived", self::IS_ACTIVE)
                    ->where("{$this->table}.is_deleted", self::NOT_DELETED)
                    ->get()
                    ->toArray();
    }
}
