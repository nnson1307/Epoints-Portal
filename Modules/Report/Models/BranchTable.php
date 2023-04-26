<?php


namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    const NOT_DELETED = 0;

    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select('branch_id', 'branch_name', 'address', 'phone');
        if (Auth::user()->is_admin != 1) {
            $select->where('branch_id', Auth::user()->branch_id);
        }
        return $select->get()->toArray();
    }

    /**
     * Lấy thông tin chi nhánh
     *
     * @param $branchId
     * @return mixed
     */
    public function getBranch($branchId)
    {
        return $this
            ->select(
                "branch_id",
                "branch_name"
            )
            ->where("branch_id", $branchId)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }
}