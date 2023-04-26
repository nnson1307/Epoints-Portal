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

class ManageProjectTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project';
    protected $primaryKey = 'manage_project_id';
    protected $casts = [
        'budget' => 'double'
    ];

    /**
     * Tìm kiếm các dự án đang sử dụng trạng thái
     * @param $statusId
     * @return mixed
     */
    public function checkProjectByStatus($statusId)
    {
        return $this->where('manage_project_status_id', $statusId)->get();
    }
    public function allProject($input = [])
    {

        $mSelect = $this
            ->select (
                "{$this->table}.manage_project_id as project_id",
                "{$this->table}.manage_project_name as project_name",
                "{$this->table}.manage_project_describe as project_describe",
                "{$this->table}.manage_project_status_id as project_status_id",
                "manage_project_status.manage_project_status_name as project_status_name",
                "manage_project_status.manage_project_status_color as project_status_color",
                "{$this->table}.manager_id",
                "staffs.full_name as manager_name",
                "{$this->table}.customer_id",
                "customers.full_name as customer_name",
                "{$this->table}.date_start as from_date",
                "{$this->table}.date_end as to_date",
                "{$this->table}.date_finish",
                "{$this->table}.is_active",
                "{$this->table}.is_important",
                "{$this->table}.budget",
                "{$this->table}.resource as resource_total",
                "{$this->table}.progress",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.contract_id",
                "{$this->table}.contract_code",
                "{$this->table}.color_code",
                "{$this->table}.permission",
                "{$this->table}.prefix_code",
                "{$this->table}.department_id"

            )
            ->where("{$this->table}.is_deleted" , 0)
            ->leftJoin("staffs","manage_project.manager_id","staffs.staff_id")
            ->leftJoin("customers","manage_project.customer_id","customers.customer_id")
            ->leftJoin("manage_project_status","manage_project.manage_project_status_id","manage_project_status.manage_project_status_id")
            ->orderBy("{$this->table}.manage_project_id",'asc');


        if (isset($input['search']) != "") {
            $search = $input['search'];
            $mSelect->where(function ($query) use ($search) {
                $query->where("manage_project.manage_project_name", 'like', '%' . $search . '%')
                    ->orWhere("manage_project.manage_project_status_id", '%' . $search . '%')
                    ->orWhere("manage_project.manager_id", 'like', '%' . $search . '%');
            });
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        if (isset($input["updated_at"]) && $input["updated_at"] != null) {
            $arr_filter_update = explode(" - ", $input["updated_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.updated_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
//        $page = (int)($input["page"] ?? 1);
//        return ->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
      return $mSelect ->get()->toArray();
    }

    public function getDetail($projectId){
        return $this
            ->where('manage_project_id',$projectId)
            ->first();
    }

    /**
     * Job
     * Lấy danh sách dự án chưa tạo phase
     * @param $arrProjectId
     * @return mixed
     */
    public function getListNotDefault($arrProjectId){
        return $this
            ->whereNotIn('manage_project_id',$arrProjectId)
            ->get();
    }

}