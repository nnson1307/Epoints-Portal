<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 09/09/2021
 * Time: 18:06
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    /**
     * Lấy chi tiết chi nhánh
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this
            ->select('branches.branch_id',
                'branches.branch_name',
                'branches.address',
                'branches.description',
                'branches.phone',
                'branches.is_actived',
                'branches.is_deleted',
                'branches.created_by',
                'branches.updated_by',
                'branches.created_at',
                'branches.updated_at',
                'branches.email',
                'branches.hot_line',
                'branches.provinceid', 'branches.districtid',
                'branches.is_representative', 'branches.representative_code',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name',
                'branches.hot_line',
                'branches.hot_line',
                'latitude',
                'longitude',
                "{$this->table}.branch_code"
            )
            ->leftJoin('province', 'province.provinceid', '=', 'branches.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'branches.districtid')
            ->where('branches.branch_id', $id)
            ->first();
    }

    public function getBranchOption()
    {
        return $this->select('branch_id','branch_code', 'branch_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }

}