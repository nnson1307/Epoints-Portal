<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 12:03
 */

namespace Modules\StaffSalary\Models;


use Illuminate\Database\Eloquent\Model;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "branch_id",
                "branch_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     */
    public function getBranchFilter($arrBranch)
    {
        
        $ds = $this
        ->select(
            "branch_id",
            "branch_name"
        )
        ->where("is_actived", self::IS_ACTIVE)
        ->where("is_deleted", self::NOT_DELETED);
        if($arrBranch != null){
           
            $ds->whereIn('branch_id', $arrBranch);
        }
        return $ds->get();
    }
}