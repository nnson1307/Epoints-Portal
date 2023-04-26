<?php
namespace Modules\ManagerProject\Http\Controllers;


use Illuminate\Http\Request;
use Modules\ManagerProject\Repositories\ProjectOverView\ProjectOverViewRepositoryInterface;

class ProjectOverViewController extends Controller
{
 protected $projectOverView;
 public function __construct(ProjectOverViewRepositoryInterface $projectOverView){
     $this->projectOverView = $projectOverView;
 }

    public function index(Request $request)
    {
        $input = $request->all();
        if(!isset($input['month']) || $input['month'] == null || $input['month']  == []){
            $input['month'] = getdate()['mon'];
        }
        if(!isset($input['year']) || $input['year'] == null || $input['year']  == []){
            $input['year'] = getdate()['year'];
        }
        //phòng ban
        $department = $this->getDepartment();
        //danh sách trạng thái dự án
        $listStatus = $this -> getStatus();
        //danh sách dự án trong kì
        $listProject = $this -> allProject($input);
        //thông tin tổng quan
        $dataOverView = $this->overViewProjects($input);
        //chart trạng thái
        $dataOverView['chart_status'] = $this->chartStatus($input);
        //chart rủi ro
        $dataOverView['chart_risk'] = $this->chartRisk($input);
        //chart quản trị
        $dataOverView['chart_manager'] = $this->chartManager($input);
        //chart phòng ban
        $dataOverView['chart_department'] = $this->chartDepartment($input);
        //chart Ngân sách
        $dataOverView['chart_budget'] = $this->chartBudget($input);
        //chart Ngân sách
        $dataOverView['chart_resource'] = $this->chartResource($input);
        //danh sách dự án có mức độ rủi ro cao
        $dataOverView['project-high-risk'] = $this->projectHighRisk($input);
        //dự án lâu không hoạt động
        $dataOverView['long-time-inactive'] = $this->longTimeInactiveProject($input);
        //danh sách vấn đề
        $dataOverView['list-issue'] = $this->listIssue($input);
        return view('manager-project::project_overview.index' ,
        [
            'input' => $input,
            'listProject' => $listProject,
            'department' => $department,
            'progressProjects' => $dataOverView['progress_projects'],
            'chartStatus' => $dataOverView['chart_status'],
            'chartRisk' => $dataOverView['chart_risk'],
            'chartManager' => $dataOverView['chart_manager'],
            'chartDepartment' => $dataOverView['chart_department'],
            'chartBudget' => $dataOverView['chart_budget'],
            'chartResource' => $dataOverView['chart_resource'],
            'projectHighRisk' =>   $dataOverView['project-high-risk'],
            'projectLongTimeInactive' =>  $dataOverView['long-time-inactive'] ,
            'listIssue' =>   $dataOverView['list-issue'],
        ]);

    }
    //danh sách tất cả dự án
    public function allProject($input){
        $dataAllProject = $this->projectOverView->dataAllProject($input);
        return $dataAllProject;
    }
    //danh sách phòng ban
    public function getDepartment()
    {
        $data = $this->projectOverView ->getDepartment();
        return $data;
    }
    //danh sách trạng thái
    public function getStatus()
    {
        $data = $this->projectOverView ->getStatus();
        return $data;
    }
    //danh sách quản trị
    public function getManager()
    {
        $data = $this->projectOverView ->getManager();
        return $data;
    }
    //danh sách nhân viên dự án
    public function getStaffProject()
    {
        $data = $this->projectOverView ->getStaffProject();
        return $data;
    }
    //thông tin tổng quan dự án trong kì
    public function overViewProjects($input)
    {
        $data = $this->projectOverView ->overViewProjects($input);
        return $data;
    }
    //thông tin chart trạng thái
    public function chartStatus($input)
    {
        $data = $this->projectOverView ->chartStatus($input);
        return $data;
    }
    //thông tin chart trạng thái
    public function chartRisk($input)
    {
        $data = $this->projectOverView ->chartRisk($input);
        return $data;
    }
    //thông tin chart quản trị
    public function chartManager($input)
    {
        $data = $this->projectOverView ->chartManager($input);
        return $data;
    }
    //thông tin chart phòng ban
    public function chartDepartment($input)
    {
        $data = $this->projectOverView ->chartDepartment($input);
        return $data;
    }
    //thông tin chart ngân sách
    public function chartBudget($input)
    {
        $data = $this->projectOverView ->chartBudget($input);
        return $data;
    }
    //thông tin chart ngân sách
    public function chartResource($input)
    {
        $data = $this->projectOverView ->chartResource($input);
        return $data;
    }
    //Danh sách dự án rủi ro cao
    public function projectHighRisk($input)
    {
        $data = $this->projectOverView ->projectHighRisk($input);
        return $data;
    }
    //chart dự án lâu không hoạt động
    public function longTimeInactiveProject($input)
    {
        $data = $this->projectOverView ->longTimeInactiveProject($input);
        return $data;
    }
    //Danh sách vấn đề
    public function listIssue($input)
    {
        $data = $this->projectOverView ->listIssue($input);
        return $data;
    }

}