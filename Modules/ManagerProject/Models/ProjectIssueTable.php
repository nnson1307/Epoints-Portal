<?php
namespace Modules\ManagerProject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ProjectIssueTable extends Model
{
protected $table = "manage_project_issue";
protected $primaryKey = "manage_project_issue_id";

    public function addIssue($data){
        return $this->insertGetId($data);
    }
    public function deleteIssue($id){
        return $this->where("{$this->table}.manage_project_issue_id", $id)->delete();
    }
    public function editIssue($dataEdit ,$id){
        return $this->where("{$this->table}.manage_project_issue_id", $id)->update($dataEdit);
    }
    public function listIssue($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_issue_id as project_issue_id",
                "{$this->table}.parent_id",
                "{$this->table}.manage_project_id as project_id",
                "manage_project.manage_project_name as project_name",
                "{$this->table}.content",
                "{$this->table}.status",
                "{$this->table}.created_at",
                "{$this->table}.created_by as staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar",
                "manage_project_status_config.manage_project_status_group_config_id",
            )
            ->whereNull("{$this->table}.parent_id")
            ->orderBy("{$this->table}.manage_project_issue_id",'desc')
        ->leftJoin("staffs","{$this->table}.created_by","staffs.staff_id")
        ->leftJoin("manage_project","{$this->table}.manage_project_id","manage_project.manage_project_id")
        ->leftJoin("manage_project_status_config","manage_project_status_config.manage_project_status_id","manage_project.manage_project_status_id");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $oSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        if(isset($filter['manage_project_issue_id']) && $filter['manage_project_issue_id'] != null){
            $oSelect->where("{$this->table}.manage_project_issue_id",$filter['manage_project_issue_id']);
        }
        if(isset($filter['issue_status']) && $filter['issue_status'] != null){
            $oSelect->where("{$this->table}.status",$filter['issue_status']);
        }
        if(isset($filter['staff_id']) && $filter['staff_id'] != null){
            $oSelect->where("{$this->table}.created_by",$filter['staff_id']);
        }
        if(isset($filter['arrProjectId']) && $filter['arrProjectId'] != null){
            $oSelect->whereIn("{$this->table}.manage_project_id",$filter['arrProjectId']);
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter_update = explode(" - ", $filter["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }

        return $oSelect->get()->toArray();
    }
    public function listIssueChild($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_issue_id as project_issue_id",
                "{$this->table}.parent_id",
                "{$this->table}.manage_project_id as project_id",
                "manage_project.manage_project_name as project_name",
                "{$this->table}.content",
                "{$this->table}.status",
                "{$this->table}.created_at",
                "{$this->table}.created_by as staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar"

            )
            ->whereNotNull("{$this->table}.parent_id")
            ->orderBy("{$this->table}.manage_project_issue_id",'desc')
            ->leftJoin("staffs","{$this->table}.created_by","staffs.staff_id")
            ->leftJoin("manage_project","{$this->table}.manage_project_id","manage_project.manage_project_id");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $oSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        if(isset($filter['manage_project_issue_id']) && $filter['manage_project_issue_id'] != null){
            $oSelect->where("{$this->table}.manage_project_issue_id",$filter['manage_project_issue_id']);
        }
        if(isset($filter['issue_status']) && $filter['issue_status'] != null){
            $oSelect->where("{$this->table}.status",$filter['issue_status']);
        }
        if(isset($filter['staff_id']) && $filter['staff_id'] != null){
            $oSelect->where("{$this->table}.created_by",$filter['staff_id']);
        }
        if(isset($filter['arrProjectId']) && $filter['arrProjectId'] != null){
            $oSelect->whereIn("{$this->table}.manage_project_id",$filter['arrProjectId']);
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter_update = explode(" - ", $filter["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }

        return $oSelect->get()->toArray();
    }
    public function listIssueAll($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_issue_id as project_issue_id",
                "{$this->table}.parent_id",
                "{$this->table}.manage_project_id as project_id",
                "manage_project.manage_project_name as project_name",
                "{$this->table}.content",
                "{$this->table}.status",
                "{$this->table}.created_at",
                "{$this->table}.created_by as staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar"

            )
            ->orderBy("{$this->table}.manage_project_issue_id",'desc')
            ->leftJoin("staffs","{$this->table}.created_by","staffs.staff_id")
            ->leftJoin("manage_project","{$this->table}.manage_project_id","manage_project.manage_project_id");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $oSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        if(isset($filter['manage_project_issue_id']) && $filter['manage_project_issue_id'] != null){
            $oSelect->where("{$this->table}.manage_project_issue_id",$filter['manage_project_issue_id']);
        }
        if(isset($filter['issue_status']) && $filter['issue_status'] != null){
            $oSelect->where("{$this->table}.status",$filter['issue_status']);
        }
        if(isset($filter['staff_id']) && $filter['staff_id'] != null){
            $oSelect->where("{$this->table}.created_by",$filter['staff_id']);
        }
        if(isset($filter['arrProjectId']) && $filter['arrProjectId'] != null){
            $oSelect->whereIn("{$this->table}.manage_project_id",$filter['arrProjectId']);
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter_update = explode(" - ", $filter["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }

        return $oSelect->get()->toArray();
    }

    public function getDetail($id){
        return $this->where('manage_project_issue_id',$id)->first();
    }
}