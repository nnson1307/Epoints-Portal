<?php

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class TimeOffDaysTable extends Model
{
    use ListTableTrait;
    protected $table = 'time_off_days';
    protected $primaryKey = 'time_off_days_id';
    protected $fillable = [
        'time_off_days_id',
        'time_off_type_id',
        'time_off_days_start',
        'time_off_days_end',
        'time_off_note',
        'staff_id',
        'is_approve',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'staff_id_level1',
        'staff_id_level2',
        'staff_id_level3',
        'time_off_days_time',
        'is_approve_level1',
        'is_approve_level2',
        'is_approve_level3',
        'date_type_select'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách hoa hồng nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
      
        $select = $this
            ->select(
                "{$this->table}.time_off_days_id",
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_days_start",
                "{$this->table}.time_off_days_end",
                "{$this->table}.time_off_note",
                "{$this->table}.staff_id",
                "{$this->table}.is_approve",
                "{$this->table}.is_deleted",
                "{$this->table}.created_at",
                "{$this->table}.date_type_select",
                "s.full_name",
                "s.staff_avatar",
                "tot.time_off_type_name",
                "s1.full_name as full_name_level1",
                "s2.full_name as full_name_level2",
                "s3.full_name as full_name_level3",
                "{$this->table}.staff_id_level1",
                "{$this->table}.staff_id_level2",
                "{$this->table}.staff_id_level3",
                "{$this->table}.is_approve_level1",
                "{$this->table}.is_approve_level2",
                "{$this->table}.is_approve_level3"

            )
            ->leftJoin("staffs as s","s.staff_id","{$this->table}.staff_id")
            ->leftJoin("time_off_type as tot","tot.time_off_type_id","{$this->table}.time_off_type_id")
            ->leftJoin("staffs as s1","s1.staff_id","{$this->table}.staff_id_level1")
            ->leftJoin("staffs as s2","s2.staff_id","{$this->table}.staff_id_level2")
            ->leftJoin("staffs as s3","s3.staff_id","{$this->table}.staff_id_level3")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.time_off_days_id", "desc");
        
        if (isset($filter["time_off_type_id"]) && $filter["time_off_type_id"] != "") {
            $select->where("{$this->table}.time_off_type_id", '=', $filter["time_off_type_id"]);
        }
       
        if (isset($filter["staff_id_level1"]) && $filter["staff_id_level1"] != "") {

            $select->where("{$this->table}.staff_id_level1", '=', $filter["staff_id_level1"]);
            $select->orWhere("{$this->table}.staff_id_level2", '=', $filter["staff_id_level1"]);
            $select->orWhere("{$this->table}.staff_id_level3", '=', $filter["staff_id_level1"]);
            unset($filter['staff_id_level1']);
        }
        if (isset($filter["is_approve"])) {
            $select->where("{$this->table}.is_approve", '=', $filter["is_approve"]);
        }
        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.time_off_days_start", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            $select->whereBetween("{$this->table}.time_off_days_end", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
       
        if (isset($filter["staff_id"]) && $filter["staff_id"] != "") {
           
            $select->where("{$this->table}.staff_id", $filter["staff_id"]);
        }

        if (Auth::user()->is_admin != 1) {
            $select->where(function ($select) {
                $select->where("tot.staff_id_approve_level2", Auth()->id())
                    ->orWhere("tot.staff_id_approve_level3", Auth()->id())
                    ->orWhere("{$this->table}.staff_id_level1", Auth()->id())
                    ->orWhere("{$this->table}.staff_id_level2", Auth()->id())
                    ->orWhere("{$this->table}.staff_id_level3", Auth()->id());
                // if(isset($data['staff_id_approve_level2'])){
                //     $select->orWhere("tot.staff_id_approve_level2", Auth()->id());
                // }
            });
        }
        
        return $select;
    }

    public function getListTimeOffDay($filter = [])
    {
      
        $select = $this
            ->select(
                "{$this->table}.time_off_days_id",
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_days_start",
                "{$this->table}.time_off_days_end",
                "{$this->table}.time_off_note",
                "{$this->table}.staff_id",
                "{$this->table}.is_approve",
                "{$this->table}.is_deleted",
                "{$this->table}.created_at",
                "{$this->table}.date_type_select",
                "s.full_name",
                "s.staff_avatar",
                "s.department_id",
                "tot.time_off_type_name",
                "tot.direct_management_approve",
                "tot.staff_id_approve_level2",
                "tot.staff_id_approve_level3",
                "s1.full_name as full_name_level1",
                "s2.full_name as full_name_level2",
                "s3.full_name as full_name_level3",
                "{$this->table}.staff_id_level1",
                "{$this->table}.staff_id_level2",
                "{$this->table}.staff_id_level3",
                "{$this->table}.is_approve_level1",
                "{$this->table}.is_approve_level2",
                "{$this->table}.is_approve_level3"

            )
            ->leftJoin("staffs as s","s.staff_id","{$this->table}.staff_id")
            ->leftJoin("time_off_type as tot","tot.time_off_type_id","{$this->table}.time_off_type_id")
            ->leftJoin("staffs as s1","s1.staff_id","{$this->table}.staff_id_level1")
            ->leftJoin("staffs as s2","s2.staff_id","{$this->table}.staff_id_level2")
            ->leftJoin("staffs as s3","s3.staff_id","{$this->table}.staff_id_level3")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.time_off_days_id", "desc");
        
        if (isset($filter["time_off_type_id"]) && $filter["time_off_type_id"] != "") {
            $select->where("{$this->table}.time_off_type_id", '=', $filter["time_off_type_id"]);
        }

        if (isset($filter["staff_id_level1"]) && $filter["staff_id_level1"] != "") {

            $select->where("{$this->table}.staff_id_level1", '=', $filter["staff_id_level1"]);
            $select->orWhere("{$this->table}.staff_id_level2", '=', $filter["staff_id_level1"]);
            $select->orWhere("{$this->table}.staff_id_level3", '=', $filter["staff_id_level1"]);
            unset($filter['staff_id_level1']);
        }
        if (isset($filter["is_approve"])) {
            $select->where("{$this->table}.is_approve", '=', $filter["is_approve"]);
        }
        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.time_off_days_start", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            $select->whereBetween("{$this->table}.time_off_days_end", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter["staff_id"]) && $filter["staff_id"] != "") {
            $select->where("{$this->table}.staff_id", $filter["staff_id"]);
        }else {
            if (Auth::user()->is_admin != 1) {
                $select->where(function ($select) use ($filter) {
                    $select
                        ->whereJsonContains("tot.staff_id_approve_level2", Auth()->id())
                        ->orWhereJsonContains("tot.staff_id_approve_level3" ,Auth()->id())
                        ->orWhere("{$this->table}.staff_id_level1", Auth()->id())
                        ->orWhere("{$this->table}.staff_id_level2", Auth()->id())
                        ->orWhere("{$this->table}.staff_id_level3", Auth()->id())
                        ->orWhereIn("{$this->table}.staff_id", $filter['arr_staff'] ?? []);
                   
                });
            }
        }
        
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? 10);
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Thêm loại thông tin kèm theo
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Chi tiết loại
     *
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        return $this->select(
            "{$this->table}.time_off_days_id",
            "{$this->table}.time_off_type_id",
            "{$this->table}.time_off_days_start",
            "{$this->table}.time_off_days_end",
            "{$this->table}.time_off_note",
            "{$this->table}.is_approve",
            "{$this->table}.is_deleted",
            "{$this->table}.staff_id_level1",
            "{$this->table}.staff_id_level2",
            "{$this->table}.staff_id_level3",
            "{$this->table}.is_approve_level1",
            "{$this->table}.is_approve_level2",
            "{$this->table}.is_approve_level3",
            "{$this->table}.time_off_days_time",
            "{$this->table}.date_type_select",
            "s.full_name",
            "s.staff_avatar",
            "s.department_id",
            "tot.time_off_type_name",
            "tot.time_off_type_code",
            "{$this->table}.time_off_days_start",
            "{$this->table}.time_off_days_end",
            "tot.direct_management_approve",
            "tot.staff_id_approve_level2",
            "tot.staff_id_approve_level3"
        )
            ->leftJoin("staffs as s","s.staff_id","{$this->table}.staff_id")
            ->leftJoin("time_off_type as tot","tot.time_off_type_id","{$this->table}.time_off_type_id")

            ->where("{$this->table}.{$this->primaryKey}", $id)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->first();


    }

    /**
     * Tổng ngày phép
     *
     * @param array $data
     * @return mixed
     */
    public function total($id)
    {
        return $this
            ->select(
                "{$this->table}.time_off_days_id",
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_days_start",
                "{$this->table}.time_off_days_end",
                "{$this->table}.time_off_note",
                "{$this->table}.created_at",
                "{$this->table}.time_off_days_time",
                "{$this->table}.staff_id_level1",
                "{$this->table}.staff_id_level2",
                "{$this->table}.staff_id_level3",
                "{$this->table}.is_approve_level1",
                "{$this->table}.is_approve_level2",
                "{$this->table}.is_approve_level3",
            )

            ->where("{$this->table}.staff_id", $id)
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_approve", 1)    
            ->get();
    }

    /**
     *  reportByType
     *
     * @param array $data
     * @return mixed
     */
    public function reportByType($params)
    {
        $select =  $this
            ->select(
                DB::raw('count(*) as total'),
                'tot.time_off_type_name'
            )
            ->join("time_off_type as tot", "tot.time_off_type_id", "=", "{$this->table}.time_off_type_id")
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_approve", 1);

            if (isset($params['branch_id'])) {
                $select->where("staffs.branch_id", "=",  $params['branch_id']);
            }

            if (isset($params['department_id'])) {
                $select->where("staffs.department_id", "=",  $params['department_id']);
            }
            if( isset($params['month'])){
                $select->whereMonth($this->table.".created_at", $params['month']);
                $select->whereYear($this->table.".created_at", date('Y'));
            }
            

            $select->groupBy("{$this->table}.time_off_type_id");
        
        return  $select->get()->toArray();
    }


    /**
     *  reportByPrecious
     *
     * @param array $data
     * @return mixed
     */
    public function reportByPrecious($params)
    {
        $select =  $this
            ->select(
                DB::raw('count(*) as total'),
                'tot.time_off_type_name',
                DB::raw("MONTH({$this->table}.created_at) month")
            )
            ->join("time_off_type as tot", "tot.time_off_type_id", "=", "{$this->table}.time_off_type_id")
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")

            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_approve", 1);

            if (isset($params['branch_id'])) {
                $select->where("staffs.branch_id", "=",  $params['branch_id']);
            }

            if (isset($params['department_id'])) {
                $select->where("staffs.department_id", "=",  $params['department_id']);
            }
            
            if(isset($params['precious'])){
                if($params['precious'] == 1){
                    $select->whereMonth("{$this->table}.created_at", ">=", 1);
                    $select->whereMonth("{$this->table}.created_at", "<=", 3);
                }else if($params['precious'] == 2){
                    $select->whereMonth("{$this->table}.created_at", ">=", 4);
                    $select->whereMonth("{$this->table}.created_at", "<=", 6);
                }else if($params['precious'] == 3){
                    $select->whereMonth("{$this->table}.created_at", ">=", 7);
                    $select->whereMonth("{$this->table}.created_at", "<=", 9);
                }else if($params['precious'] == 4){
                    $select->whereMonth("{$this->table}.created_at", ">=", 10);
                    $select->whereMonth("{$this->table}.created_at", "<=", 12);
                } 
                $select->whereYear("{$this->table}.created_at",  date('Y'));
            }
            

            $select->groupBy("month");
        
        return  $select->get()->toArray();
    }

    /**
     *  reportByTopTen
     *
     * @param array $data
     * @return mixed
     */
    public function reportByTopTen($params)
    {
        $select = $this
            ->select(
                DB::raw('count(*) as total'),
                'staffs.full_name'
            )
            ->leftJoin("time_off_type as tot", "tot.time_off_type_id", "=", "{$this->table}.time_off_type_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")

            ->where("{$this->table}.is_deleted", 0)
            
            ->where("{$this->table}.is_approve", 1);
            
            if( isset($params['month'])){
                $select->whereMonth("{$this->table}.created_at", $params['month']);
                $select->whereYear("{$this->table}.created_at", date('Y'));
            }

            $select->groupBy("{$this->table}.staff_id");
            $select->offset(0)->take(10);
            
        return  $select->get()->toArray();
    }
}  