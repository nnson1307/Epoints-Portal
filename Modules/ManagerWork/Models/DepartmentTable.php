<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DepartmentTable extends Model
{
    use ListTableTrait;
    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillable = [
        'department_id', 'department_name', 'is_inactive', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at', 'slug'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách phòng ban
     * @return mixed
     */
    public function getAll($departmentId = null)
    {
        $oSelect = $this
            ->select(
                'department_id',
                'department_name'
            )
            ->where('is_inactive', 1)
            ->where('is_deleted', 0);

        if ($departmentId != null){
            $oSelect = $oSelect->where('department_id',$departmentId);
        }

        return $oSelect
            ->orderBy('department_id', 'DESC')
            ->get();
    }

    /**
     * Lấy phòng ban theo chi nhánh
     *
     * @param $branchId
     * @return mixed
     */
    public function getDepartmentByBranch($branchId)
    {
        return $this
            ->select(
                "department_id",
                "department_name"
            )
            ->where("branch_id", $branchId)
            ->where('is_inactive', 1)
            ->where('is_deleted', 0)
            ->get();
    }

    /**
     * Lấy option phòng ban
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "department_id",
                "department_name"
            )
            ->where("is_inactive", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}