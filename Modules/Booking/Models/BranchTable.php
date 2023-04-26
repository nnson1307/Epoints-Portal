<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    //function fillable
    protected $fillable = [
        'branch_id', 'branch_name', 'address', 'description', 'is_actived', 'is_deleted', 'created_by',
        'updated_by', 'created_at', 'updated_at', 'phone', 'email', 'hot_line', 'provinceid', 'districtid',
        'is_representative', 'representative_code', 'slug', 'avatar'
    ];

    public function getItem($id)
    {
        return $this->leftJoin('province', 'province.provinceid', '=', 'branches.provinceid')
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
                'branches.avatar',
                'branches.hot_line',
                'branches.provinceid', 'branches.districtid',
                'branches.is_representative', 'branches.representative_code',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name')
            ->where('branches.branch_id', $id)->first();
    }

    //search where in branch.
    public function searchWhereIn(array $branch)
    {
        return $this->whereIn('branch_id', $branch)->where('is_deleted', 0)->get();
    }

    public function getBranch($filters)
    {
//        $page = (int)($filter['page'] ?? 1);
//        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
            'branch_id', 'branch_name',
            'representative_code', 'phone',
            'email', 'hot_line', 'description', 'avatar','address','branches.provinceid','branches.districtid',
            DB::raw('CONCAT(prv.type, " " ,prv.name) as province_name'),
            DB::raw('CONCAT(dis.type, " " ,dis.name) as district_name')
        )
            ->leftJoin('province as prv', 'prv.provinceid', '=', "branches.provinceid")
            ->leftJoin('district as dis', 'dis.districtid', '=', "branches.districtid")
            ->where('is_deleted', 0)
            ->where('is_actived', 1);
        if (isset($filters['province_id']) && $filters['province_id'] != null) {
            $select->where("branches.provinceid", $filters['province_id']);

        }
        if (isset($filters['district_id']) && $filters['district_id'] != null) {
            $select->where("branches.districtid", $filters['district_id']);
        }
//        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        return $select->get();
    }

    public function getListBrand($filter){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this->where('is_actived',1)->where('is_deleted',0);
        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}