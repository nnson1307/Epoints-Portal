<?php

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FNBAreasTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_areas';
    protected $primaryKey = 'area_id';
    protected $fillable
        = [
            'area_id',
            'branch_id',
            'area_code',
            'name',
            'note',
            'is_active',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ];

//    Lấy danh sách có filter
    public function _getList(&$filters = []) {
        $oSelect = $this
            ->select(
                $this->table.'.area_id',
                $this->table.'.branch_id',
                $this->table.'.area_code',
                $this->table.'.name as area_name',
                $this->table.'.note as area_note',
                $this->table.'.is_active',
                $this->table.'.created_at',
                $this->table.'.updated_at',
                'branch.branch_name',
                'createdBy.full_name as created_name',
                'updatedBy.full_name as updated_name',
                DB::raw("(SELECT COUNT(*) FROM fnb_table where fnb_table.area_id = {$this->table}.area_id) as total_table")
            )
            ->leftJoin('branches as branch','branch.branch_id',$this->table.'.branch_id')
            ->leftJoin('staffs as createdBy','createdBy.staff_id',$this->table.'.created_by')
            ->leftJoin('staffs as updatedBy','updatedBy.staff_id',$this->table.'.updated_by');

        if(isset($filters['search'])){
            $oSelect = $oSelect
                ->where($this->table.'.name','like','%'.$filters['search'].'%')
                ->orWhere($this->table.'.area_code','like','%'.$filters['search'].'%');

            unset($filters['search']);
        }

        if (isset($filters['branch_id'])){
            $oSelect = $oSelect->where($this->table.'.branch_id',$filters['branch_id']);
            unset($filters['branch_id']);
        }

        if (isset($filters['created_at'])){
            $time = explode(' - ',$filters['created_at']);
            $start = Carbon::createFromFormat('d/m/Y',$time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$time[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
            unset($filters['created_at']);
        }

        if (isset($filters['created_by'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filters['created_by']);
            unset($filters['created_by']);
        }

        if (isset($filters['updated_at'])){
            $time1 = explode(' - ',$filters['updated_at']);
            $start1 = Carbon::createFromFormat($time1[0])->format('Y-m-d 00:00:00');
            $end1 = Carbon::createFromFormat($time1[0])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.updated_at',[$start1,$end1]);
            unset($filters['updated_at']);
        }

        if (isset($filters['updated_by'])){
            $oSelect = $oSelect->where($this->table.'.updated_by',$filters['updated_by']);
            unset($filters['updated_by']);
        }

        if (isset($filters['is_active'])){
            $oSelect = $oSelect->where($this->table.'.is_active',$filters['is_active']);
            unset($filters['is_active']);
        }

        return $oSelect->orderBy($this->table.'.area_id','DESC');
    }

    public function getListPagination(&$filters = []) {

        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.area_id',
                $this->table.'.branch_id',
                $this->table.'.area_code',
                $this->table.'.name as area_name',
                $this->table.'.note as area_note',
                $this->table.'.is_active',
                $this->table.'.created_at',
                $this->table.'.updated_at',
                'branch.branch_name',
                'createdBy.full_name as created_name',
                'updatedBy.full_name as updated_name',
                DB::raw("(SELECT COUNT(*) FROM fnb_table where fnb_table.area_id = {$this->table}.area_id) as total_table")
            )
            ->leftJoin('branches as branch','branch.branch_id',$this->table.'.branch_id')
            ->leftJoin('staffs as createdBy','createdBy.staff_id',$this->table.'.created_by')
            ->leftJoin('staffs as updatedBy','updatedBy.staff_id',$this->table.'.updated_by')
            ->where($this->table.'.branch_id',$filters['branch_id']);

        if(isset($filters['search'])){
            $oSelect = $oSelect
                ->where($this->table.'.name','like','%'.$filters['search'].'%')
                ->orWhere($this->table.'.area_code','like','%'.$filters['search'].'%');

            unset($filters['search']);
        }

        if (isset($filters['created_at'])){
            $time = explode(' - ',$filters['created_at']);
            $start = Carbon::parse($time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::parse($time[0])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
            unset($filters['created_at']);
        }

        if (isset($filters['created_by'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filters['created_by']);
            unset($filters['created_by']);
        }

        if (isset($filters['updated_at'])){
            $time = explode(' - ',$filters['updated_at']);
            $start = Carbon::parse($time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::parse($time[0])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.updated_at',[$start,$end]);
            unset($filters['updated_at']);
        }

        if (isset($filters['updated_by'])){
            $oSelect = $oSelect->where($this->table.'.updated_by',$filters['updated_by']);
            unset($filters['updated_by']);
        }

        if (isset($filters['is_active'])){
            $oSelect = $oSelect->where($this->table.'.is_active',$filters['is_active']);
            unset($filters['is_active']);
        }

        return $oSelect
            ->orderBy($this->table.'.area_id','DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    public  function getAllAreas($param = []){
        $oSelect = $this
            ->select(
                $this->table.'.area_id',
                $this->table.'.branch_id',
                $this->table.'.area_code',
                $this->table.'.name as area_name',
                $this->table.'.note as area_note',
                $this->table.'.is_active',
                $this->table.'.created_at',
                $this->table.'.updated_at'
            );
        return $oSelect->get();

    }
    public function createAreas($dataAreas){
        return $this ->insertGetId($dataAreas);
    }
    public function editAreas($areasId,$input){
        return $this
            ->where("{$this->table}.area_id",$areasId)
            ->update($input);
    }
    public function deleteAreas($areasId){
        return $this
            ->where("{$this->table}.area_id",$areasId)
            ->delete();
    }

    /**
     * lấy danh sách
     * @return mixed
     */
    public function getAll(){
        return $this
            ->orderBy($this->table.'.area_id','DESC')
            ->get();
    }
    public function getListNoPage($filters = []) {
        $oSelect = $this
            ->select(
                $this->table.'.area_id',
                $this->table.'.branch_id',
                $this->table.'.area_code',
                $this->table.'.name as area_name',
                $this->table.'.note as area_note',
                $this->table.'.is_active',
                $this->table.'.created_at',
                $this->table.'.updated_at',
                'branch.branch_name',
                'createdBy.full_name as created_name',
                'updatedBy.full_name as updated_name',
                DB::raw("(SELECT COUNT(*) FROM fnb_table where fnb_table.area_id = {$this->table}.area_id) as total_table")
            )
            ->leftJoin('branches as branch','branch.branch_id',$this->table.'.branch_id')
            ->leftJoin('staffs as createdBy','createdBy.staff_id',$this->table.'.created_by')
            ->leftJoin('staffs as updatedBy','updatedBy.staff_id',$this->table.'.updated_by');

        if(isset($filters['search'])){
            $oSelect = $oSelect
                ->where($this->table.'.name','like','%'.$filters['search'].'%')
                ->orWhere($this->table.'.area_code','like','%'.$filters['search'].'%');

            unset($filters['search']);
        }

        if (isset($filters['branch_id'])){
            $oSelect = $oSelect->where($this->table.'.branch_id',$filters['branch_id']);
            unset($filters['branch_id']);
        }

        if (isset($filters['created_at'])){
            $time = explode(' - ',$filters['created_at']);
            $start = Carbon::parse($time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::parse($time[0])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
            unset($filters['created_at']);
        }

        if (isset($filters['created_by'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filters['created_by']);
            unset($filters['created_by']);
        }

        if (isset($filters['updated_at'])){
            $time = explode(' - ',$filters['updated_at']);
            $start = Carbon::parse($time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::parse($time[0])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.updated_at',[$start,$end]);
            unset($filters['updated_at']);
        }

        if (isset($filters['updated_by'])){
            $oSelect = $oSelect->where($this->table.'.updated_by',$filters['updated_by']);
            unset($filters['updated_by']);
        }

        if (isset($filters['is_active'])){
            $oSelect = $oSelect->where($this->table.'.is_active',$filters['is_active']);
            unset($filters['is_active']);
        }

        return $oSelect->orderBy($this->table.'.area_id','DESC')->get();
    }

}