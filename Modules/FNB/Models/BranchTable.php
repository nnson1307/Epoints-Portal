<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class BranchTable extends Model
{
    use ListTableTrait;
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    //function fillable
    protected $fillable = [
        'branch_id',
        'branch_name',
        'address',
        'description',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'phone',
        'email',
        'hot_line',
        'provinceid',
        'districtid',
        'is_representative',
        'representative_code',
        'slug',
        'latitude',
        'longitude',
        'branch_code'
    ];

    //function get list
    protected function _getList($filter = [])
    {
        $oSelect = $this->leftJoin('province', 'province.provinceid', '=', 'branches.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'branches.districtid')
            ->select(
                'branches.branch_id',
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
                'branches.representative_code',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name')
            ->where('branches.is_deleted', 0)->orderBy('branch_id', 'desc');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $oSelect->where(function ($query) use ($search) {
                $query->where('branch_name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->where('is_deleted', 0);
            });
        }
        return $oSelect;
    }

    public function add(array $data)
    {
        $branch = $this->create($data);
        return $branch->branch_id;
    }

    public function getBranch(array $listId = [])
    {
        if ($listId != null) {
            return $this->select('branch_id', 'branch_name', 'address', 'phone')
                ->whereNotIn('branch_id', $listId)
                ->where('is_deleted', 0)->get()->toArray();
        } else {
            $select = $this->select('branch_id', 'branch_name', 'address', 'phone')
                ->where('is_deleted', 0);
//            if (Auth::user()->is_admin != 1) {
//                $select->where('branch_id', Auth::user()->branch_id);
//            }
            return $select->get()->toArray();
        }

    }

    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    public function getItem($id)
    {
        return $this
            ->leftJoin('province', 'province.provinceid', '=', 'branches.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'branches.districtid')
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
            ->where('branches.branch_id', $id)->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function testName($name, $id)
    {
        return $this->where('slug', $name)->where('branch_id', '<>', $id)->where('is_deleted', 0)->first();
    }

    public function getName()
    {
        $oSelect = self::select("branch_id", "branch_name")->where('is_deleted', 0)->get();
        return (["" => __("Tất cả")]) + ($oSelect->pluck("branch_name", "branch_id")->toArray());
    }

    public function getBranchInId($arr_id)
    {
        $oSelect = $this->select('branch_id', 'branch_name', 'address', 'description', 'phone',
            'is_actived', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at')
            ->whereIn("branch_id", $arr_id)
            ->where('branches.is_deleted', 0);
//        dd($oSelect->get());

        return $oSelect->get();
    }

    //search where in branch.
    public function searchWhereIn(array $branch)
    {
        return $this->whereIn('branch_id', $branch)->where('is_deleted', 0)->get();
    }

    public function getBranchOption()
    {
        return $this->select('branch_id','branch_code', 'branch_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }

    /**
     * Kiểm tra chi nhánh chính
     *
     * @param $branchId
     * @return mixed
     */
    public function checkRepresentative($branchId)
    {
        return $this
            ->where('is_representative', 1)
            ->where('branch_id', '<>', $branchId)
            ->where('is_deleted', 0)
            ->first();
    }

    public function getNameBranch($id){
        return $this
            ->where('branch_id', $id)
            ->where('is_deleted', 0)
            ->first();
    }

    /**
     * Lấy hết danh sách chi nhánh
     */
    public function getAll(){
        return $this
            ->where('is_actived',1)
            ->where('is_deleted',0)
            ->orderBy('branch_id','DESC')
            ->get();
    }
}