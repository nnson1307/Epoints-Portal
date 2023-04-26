<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BranchTable extends Model
{
    use ListTableTrait;
    protected $table = 'branches';
    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'branch_id', 'branch_name', 'slug', 'address', 'description', 'phone', 'email', 'hot_line', 'provinceid',
        'districtid', 'is_representative', 'avatar', 'representative_code', 'is_actived', 'is_deleted',
        'created_by', 'updated_by', 'created_at', 'updated_at', 'latitude', 'longitude', 'branch_code', 'site_id',
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * lấy tất cả chi nhánh
     * @return mixed
     */
    public function getAll($branchId = null)
    {
        $oSelect = $this
            ->select(
                'branch_id',
                'branch_name'
            )
            ->where('is_actived', 1)
            ->where('is_deleted', 0);

        if ($branchId != null){
            $oSelect = $oSelect->where('branch_id',$branchId);
        }


        return $oSelect
            ->orderBy('branch_id', 'DESC')
            ->get();
    }

    /**
     * lấy tất cả chi nhánh
     * @return mixed
     */
    public function getAllSearch($branchId = null)
    {
        $oSelect = $this
            ->select(
                'branch_id',
                'branch_name'
            )
            ->where('is_actived', 1)
            ->where('is_deleted', 0);

        if ($branchId != null){
            $oSelect = $oSelect->where('branch_id',$branchId);
        }

        $oSelect = $oSelect
            ->orderBy('branch_id', 'DESC')
            ->get();
        return collect($oSelect)->pluck('branch_name', 'branch_id')->toArray();
    }

    /**
     * Danh sách chi
     *
     * @param array $filter
     *
     * @return mixed
     */
    public function getListCore($filters = [])
    {
        $dataQuery =  $this
            ->select(
                'branch_id',
                'branch_name',
                'representative_code',
                'branch_code',
                'phone',
                'address'
            )
            ->where('is_actived', 1)
            ->where('is_deleted', 0)
            ->orderBy('branch_id', 'DESC');
        if (isset($filters['not_in'])) {
            $dataQuery->whereNotIn('branch_id', $filters['not_in']);
            unset($filters['not_in']);
        }

        if (isset($filters['arr_branch'])) {
            $dataQuery->whereIn('branch_id', $filters['arr_branch']);
            unset($filters['arr_branch']);
        }
        return $dataQuery;
    }

    /**
     * Get list by condition
     * @param $param
     * @return mixed
     */
    public function getByCondition($param = [])
    {
        $isActive = $param['is_actived'] ?? 1;
        $select = $this->select("{$this->table}.*")
            ->where($this->table . '.is_deleted', self::NOT_DELETED)
            ->where($this->table . '.is_actived', $isActive);
        return $select->get();
    }

    /**
     * lấy tất cả chi nhánh theo điều kiện 
     * @param array $listIdBranch
     * @return mixed
     */
    public function getListCondition($listIdBranch)
    {
        return $this->whereIn("branch_id", $listIdBranch)->get();
    }

    /**
     * lấy danh sách chi nhánh
     */
    public function getListBranch($data = []){
        $oSelect = $this
            ->select(
                'branch_id',
                'branch_name'
            )
            ->where('is_actived',1)
            ->where('is_deleted',0);

        if (isset($data['branch_name'])){
            $oSelect = $oSelect->where('branch_name','like','%'.$data['branch_name'].'%');
        }

        if (isset($data['branch_id'])){
            $oSelect = $oSelect->where('branch_id',$data['branch_id']);
        }

        return $oSelect->orderBy('created_at','DESC')->get();
    }

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
    public function getBranchOption()
    {
        return $this->select('branch_id','branch_code', 'branch_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }

}
