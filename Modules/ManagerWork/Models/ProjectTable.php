<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\ManagerWork\Models\StaffsTable;
use MyCore\Models\Traits\ListTableTrait;
use Modules\ManagerWork\Models\Customers;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManageTagsTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ProjectStatusTable;
use Modules\ManagerWork\Models\ManageTagProjectTable;

class ProjectTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project';
    protected $primaryKey = 'manage_project_id';

    protected $fillable = [
        'manage_project_id',
        'manage_project_name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'manager_id',
        'department_id',
        'date_start',
        'date_end',
        'date_finish',
        'customer_type',
        'customer_id',
        'color_code',
        'permission',
        'progress',
        'prefix_code',
        'manage_project_describe',
        'manage_project_status_id',
        'is_active',
        'is_deleted'
    ];

    const PREFIX_DEFAULT = 'DA';
    const PROJECT_ROUTE_LIST = 'manager-work.project';
    const IS_DELETED = 1;
    // ORM //
    // người tạo //
    public function staff_created()
    {
        return $this->belongsTo('Modules\ManagerWork\Models\StaffTable', 'created_by', 'staff_id');
    }
    // người quản trị
    public function manager()
    {
        return $this->belongsTo(StaffsTable::class, 'manager_id', 'staff_id');
    }

    // người quản trị
    public function managerList()
    {
        return $this
            ->hasMany(ManageProjectStaffTable::class, 'manage_project_id', 'manage_project_id');
    }
    // người cập nhật
    public function staff_updated()
    {
        return $this->belongsTo(StaffsTable::class, 'updated_by');
    }
    // công việc
    public function work()
    {
        return $this->hasMany(ManagerWorkTable::class, 'manage_project_id');
    }
    // tags
    public function tags()
    {
        return $this->belongsToMany(ManageTagsTable::class, 'manage_project_tag', 'manage_project_id', 'tag_id');
    }

    // phòng ban
    public function department()
    {

        return $this->belongsTo(DepartmentTable::class, 'department_id');
    }

    // customer
    public function customer()
    {

        return $this->belongsTo(Customers::class, 'customer_id');
    }

    // trạng thái

    public function status()
    {

        return $this->belongsTo(ProjectStatusTable::class, 'manage_project_status_id');
    }

    // Vai trò (quyền )

    public function staffs()
    {

        return $this->belongsToMany(StaffsTable::class, 'manage_project_staff', 'manage_project_id', 'staff_id')->withPivot('manage_project_role_id');
    }

    //  Query //
    protected function _getList(&$filters = [])
    {
        $tags = $filters['tags'] ?? "";
        $query = $this
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->select(
            "{$this->table}.*",
            'manage_project_status_config.is_edit',
            'manage_project_status_config.is_deleted',
            DB::raw("CONCAT({$this->table}.date_start, ' - ' ,{$this->table}.date_end) AS date_start_and_end")
//            DB::raw("IF({$this->table}.permission = 'public','Công khai', 'Nội bộ') AS permission")
        )->where("{$this->table}.is_deleted", "<>", self::IS_DELETED);
        if (!empty($filters['date_between'])) {
            $arrFilter = explode(" - ", $filters["date_between"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrFilter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrFilter[1])->format('Y-m-d');
            $query->where(function ($q) use ($startTime, $endTime) {
                $q->where("{$this->table}.date_start", "<=", $startTime)
                    ->orwhere("{$this->table}.date_end", "<=", $endTime);
            });
            unset($filters['date_between']);
        }
        if (!empty($filters['created_at'])) {

            $date = Carbon::createFromFormat('d/m/Y', $filters['created_at'])->format('Y-m-d');
            $query->whereDate("{$this->table}.created_at", $date);
            unset($filters['created_at']);
        }

        if (!empty($filters['updated_at'])) {
            $date = Carbon::createFromFormat('d/m/Y', $filters['updated_at'])->format('Y-m-d');
            $query->whereDate("{$this->table}.updated_at", $date);
            unset($filters['updated_at']);
        }

        if (!empty($filters['date_complete'])) {
            $date = Carbon::createFromFormat('d/m/Y', $filters['date_complete'])->format('Y-m-d');
            $query->whereDate("{$this->table}.date_end", $date);
            unset($filters['date_complete']);
        }

        if (isset($filters['manage_project_name'])){
            $query->where($this->table.'.manage_project_name','like','%'.$filters['manage_project_name'].'%');
            unset($filters['manage_project_name']);
        }

        if (isset($filters['manage_project_status_id'])){
            $query->where($this->table.'.manage_project_status_id',$filters['manage_project_status_id']);
            unset($filters['manage_project_status_id']);
        }

        $query
            ->with([
                'tags' => function ($q) use ($tags) {
                    if (!empty($tags)) {
                        $q->whereIn("manage_tags.manage_tag_id", $tags);
                    }
                    $q->select("manage_tags.manage_tag_id", "manage_tags.manage_tag_name");
                },
                'manager' => function ($q) {
                    $q->select("staffs.staff_id", "staffs.full_name");
                },
                'staff_created' => function ($q) {
                    $q->select("staffs.staff_id", "staffs.full_name");
                },
                'staff_updated' => function ($q) {
                    $q->select("staffs.staff_id", "staffs.full_name");
                },
                'department' => function ($q) {
                    $q->select("departments.department_id", "departments.department_name");
                },
                'customer' => function ($q) {
                    $q->select("customers.customer_id", "customers.full_name");
                },
                'status' => function ($q) {
                    $q->select(
                        "manage_project_status.manage_project_status_id",
                        "manage_project_status.manage_project_status_name",
                        "manage_project_status.manage_project_status_color"
                    );
                },
                'managerList' => function($q) {
                    $q->join('manage_project_role','manage_project_role.manage_project_role_id','manage_project_staff.manage_project_role_id')
                        ->where('manage_project_role.manage_project_role_code','administration');
                }
            ]);
        unset($filters['tags']);
        unset($filters['manage_project_status_id']);

        $query = $this->getPermission($query);

        return $query->groupBy($this->table.'.manage_project_id')->orderBy($this->table.'.manage_project_id', 'desc');
    }

    public function getAll()
    {
        $query = $this
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->where($this->table . '.is_active', 1)
            ->whereNotIn('manage_project_status_config.manage_project_status_group_config_id', [3,4])
            ->orderBy($this->table.'.manage_project_id', 'desc');

        $query = $this->getPermission($query);

        return $query->groupBy($this->table.'.manage_project_id')
            ->select($this->table.'.*')
            ->get();
    }

    public function getName()
    {
        $oSelect = self::select($this->table.".manage_project_id", $this->table.".manage_project_name")->where($this->table . '.is_active', 1);

        $oSelect = $this->getPermission($oSelect);

        return ($oSelect->groupBy($this->table.'.manage_project_id')->orderBy($this->table.'.manage_project_name', 'asc')->get()->pluck("manage_project_name", "manage_project_id")->toArray());
    }

    public function testCode($code, $id)
    {
        $oSelect = $this->where($this->table.'.manage_project_name', $code)->where($this->table.'.manage_project_id', '<>', $id);

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->groupBy($this->table.'.manage_project_id')->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_project_id;
    }

    public function remove($id)
    {
        return $this->where($this->table.'.manage_project_id', $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->table.'.manage_project_id', $id)->update($data);
    }

    public function getItem($id)
    {
        return $this
            ->with('work')
            ->select($this->table.'.*','manage_project_status_config.manage_project_status_group_config_id')
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->where($this->table.'.manage_project_id', $id)
            ->first();
    }

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '')
    {
        $select = $this->where($this->table.'.manage_project_name', $name)
            ->where($this->table.'.manage_project_id', '<>', $id);
        return $select->first();
    }

    /**
     * Lấy chi tiết dự án
     * @param $idProject
     * @return mixed
     */
    public function getDetailProject($idProject){
        $oSelect = $this
            ->where($this->table.'.manage_project_id',$idProject);

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->groupBy($this->table.'.manage_project_id')->first();
    }

    /**
     * Lấy danh sách dự án đang hoạt động và khác nhóm hoàn thành , đóng
     */
    public function getAllActive(){
        $oSelect = $this
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->where($this->table.'.is_active',1)
            ->where($this->table.'.is_deleted',0)
            ->whereNotIn('manage_project_status_config.manage_project_status_group_config_id',[3,4]);

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->groupBy($this->table.'.manage_project_id')->get();
    }

    public function getPermission($oSelect){
        $userId = Auth::id();
        $oSelect
            ->leftJoin('manage_project_staff','manage_project_staff.manage_project_id',$this->table.'.manage_project_id')
            ->where(function ($query) use ($userId){
                $query->where($this->table.'.permission','public')
                    ->orWhere(function ($query1) use ($userId){
                        $query1
                            ->where('manage_project_staff.staff_id',$userId)
                            ->where($this->table.'.permission','private');
                    });
            });


        return $oSelect;
    }

    public function getAllPermission($userId)
    {
        $query = $this
            ->leftJoin('manage_project_staff','manage_project_staff.manage_project_id',$this->table.'.manage_project_id')
            ->where($this->table . '.is_active', 1)
            ->where('manage_project_staff.staff_id', $userId)
            ->orderBy($this->table.'.manage_project_id', 'desc');

        return $query->groupBy($this->table.'.manage_project_id')
            ->select($this->table.'.*')
            ->get();
    }

}
