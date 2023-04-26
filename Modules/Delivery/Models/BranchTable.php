<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 2:49 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BranchTable extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'branch_id';

    const NOT_DELETED = 0;
    CONST IN_ACTIVE = 1;

    /**
     * Lấy option branch
     *
     * @param array $listId
     * @param $branchUser
     * @return mixed
     */
    public function getBranch($branchUser, array $listId = [])
    {
        if ($listId != null) {
            return $this->select('branch_id', 'branch_name', 'address', 'phone')
                ->whereNotIn('branch_id', $listId)
                ->where('is_deleted', self::NOT_DELETED)->get()->toArray();
        } else {
            $select = $this->select('branch_id', 'branch_name', 'address', 'phone')
                ->where('is_deleted', self::NOT_DELETED);
            if (Auth::user()->is_admin != 1) {
                $select->where('branch_id', $branchUser);
            }
            return $select->get()->toArray();
        }

    }
}