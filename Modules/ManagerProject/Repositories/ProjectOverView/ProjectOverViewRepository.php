<?php
namespace Modules\ManagerProject\Repositories\ProjectOverView;



use Carbon\Carbon;
use Modules\ManagerProject\Models\ManageProjectHistoryTable;
use Modules\ManagerProject\Models\ManageProjectTable;
use Modules\ManagerProject\Models\DepartmentTable;
use Modules\ManagerProject\Models\ManageProjectStatusTable;
use Modules\ManagerProject\Models\ProjectStaffTable;
use Modules\ManagerProject\Models\ProjectExpenditureTable;
use Modules\ManagerProject\Models\ProjectIssueTable;
use Modules\ManagerProject\Models\WorkTable;
use Modules\ManagerProject\Models\PaymentTable;
use Modules\ManagerProject\Models\ReceiptTable;
use phpDocumentor\Reflection\DocBlock\Description;

class ProjectOverViewRepository implements ProjectOverViewRepositoryInterface
{
    public function getDepartment($filter = [])
    {
            $mDepartment = app()->get(DepartmentTable::class);
            $data = $mDepartment->getAll($filter)->toArray();
            return $data;
    }
    public function getStatus()
    {
            $mStatus = app()->get(ManageProjectStatusTable::class);
            $data = $mStatus->getAll()->toArray();
            return $data;
    }
    public function getManager($filter = [])
    {
        $mProject = app()->get(ProjectStaffTable::class);
        $data = $mProject->getManager($filter)->toArray();
        return $data;
    }
    public function getStaffProject($filter = [])
    {
        $mProject = app()->get(ProjectStaffTable::class);
        $data = $mProject->getStaffProject($filter);
        return $data;
    }
    public function dataAllProject($input){
        $mProject = app()->get(ManageProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $data = $mProject->allProject($input);
        $filter = [
            'department_id' => isset($input['department_id']) && $input['department_id'] != [] ? $input['department_id'] : null,
            'month' => isset($input['month']) && $input['month'] != [] ? $input['month'] : getdate()['mon'],
            'year' => isset($input['year']) && $input['year'] != [] ? $input['year'] : getdate()['year'],
        ];
        $input['arrIdProject'] = collect($data)->pluck('project_id')->toArray();
        //danh sach cong viec
        $listWork = $mWork -> getAllWork($input);
        if ($filter['department_id'] != null) {
            $listWorkGroupByDepartment = collect($listWork)->groupBy('department_id')->toArray();
            $listWorkSelected = $listWorkGroupByDepartment[$filter['department_id']];
            $listIdProjectByWork = collect($listWorkSelected)->pluck('project_id')->toArray();
            foreach ($data as $key => $val){
                if(in_array($val['project_id'],$listIdProjectByWork ) == false){
                    unset($data[$key]);
                }
            }
            $data = array_values($data);
        }
        $filter['month'] = $filter['month'] < 10 ? '0'.$filter['month'] : $filter['month'];
        $startDate  = $filter['year'].'-'.$filter['month'].'-01';
        $endDate = date("Y-m-t", strtotime($startDate));
        foreach ($data as $key => $val){
            $projectStart = Carbon::createFromFormat('Y-m-d',$val['from_date'])->format('Y-m-d');
            $projectEnd = Carbon::createFromFormat('Y-m-d',$val['to_date'])->format('Y-m-d');
            if($projectEnd < $startDate || $projectStart >  $endDate){
                unset($data[$key]);
            }
        }
        $data = array_values($data);
        return $data;
    }
    public function overViewProjects($input){
        $mWork = app()->get(WorkTable::class);
        $dataAllProject = $this->dataAllProject($input);
        $now  = Carbon::parse(now())->format('Y-m-d');
        if(isset($dataAllProject) && count($dataAllProject)>0){
            $dataOverView = [];
            //số dự án trong kì
            $progressProject['project_in_the_period']= $dataAllProject ? count($dataAllProject) : 0;
            $projectOnTime = 0; //dự án đúng hạn
            $projectCompleteLate = 0; //dự án hoàn thành trễ hạn
            $projectLate = 0; //dự án đã trễ hạn
            $projectMayBeLate = 0; //dự án có nguy cơ trễ hạn
            if(isset($dataAllProject) && count($dataAllProject) > 0){
                foreach ($dataAllProject as $key => $value){
                    $toDate = $value['to_date'];
                    if(isset($value['date_finish'])){
                        $dateComplete =Carbon::createFromFormat('Y-m-d H:i:s',$value['date_finish'])->format('Y-m-d');
                        if($toDate > $dateComplete){
                            $projectOnTime += 1;
                        }elseif($toDate < $dateComplete){
                            $projectCompleteLate += 1;
                        }
                    }else{
                        if($now > $toDate){
                            $projectLate += 1;
                        }
                    }

                }
            }
            //tinh dự án có nguy cơ trễ hạn
            $arrIdProject = collect($dataAllProject)->pluck('project_id')->toArray();
            $input['arrIdProject'] = $arrIdProject;
            $listWorkByIdProject= $mWork->getAllWork($input);
            foreach ($listWorkByIdProject as $key => $val){
                $a = $val['date_start'];
                $b = $val['date_end'];
                $c = $val['date_finish'];
                if($c == null) {
                    $first_date = strtotime($a);
                    $second_date = strtotime($b);

                }else{
                    $first_date = strtotime($a);
                    $second_date = strtotime($c);
                }
                $datediff = abs($first_date - $second_date);
                $val['time_work'] = $datediff / (60 * 60 * 24);
                $listWorkByIdProject[$key] = $val;
            }
             $listWorkByIdProject = collect($listWorkByIdProject)->groupBy('project_id');
            foreach ($listWorkByIdProject as $key => $val){
                $val['totalWork'] = count($val);
                $totalTimeWork = collect($val)->sum('time_work');
                $val['totalTimeWork'] = $totalTimeWork;
                $totalWorkComplete =collect($val)->where('status_id' , 6)->count();
                $val['totalWorkComplete'] = $totalWorkComplete;
                $listWorkByIdProject[$key] = $val;
            }
            $projectHighRisk = [];
            foreach ($dataAllProject as $key => $val){
                if (isset($listWorkByIdProject[$val['project_id']])){
                    $val['listWork'] = $listWorkByIdProject[$val['project_id']];
                    $ratioWork =  $val['listWork']['totalWorkComplete'] != 0 && $val['listWork']['totalWork'] != 0  ?  $val['listWork']['totalWorkComplete']/$val['listWork']['totalWork']*100 : 0;
                    $ratioTimeWork  = $val['listWork']['totalTimeWork'] != 0 && $val['resource_total'] != 0 ? $val['listWork']['totalTimeWork']/$val['resource_total']*100 : 0;
                    $ratio = $ratioTimeWork - $ratioWork;
                    $val['risk'] = 'normal';
                    if($val['date_finish'] == null && $val['to_date'] > $now){
                        if($ratio < 0){
                            $val['risk'] = 'low';
                        }elseif($ratio > 20){
                            $val['risk'] = 'high';
                        }else{
                            $val['risk'] = 'normal';
                        }
                    }else{
                        $val['risk'] = 'normal';
                    }


                }else{
                    $val['listWork']  = [];
                    $val['risk'] = 'normal';
                }
                if($val['risk'] == 'high'){
                    $projectHighRisk[] = $val;
                }
                $dataAllProject[$key] = $val;
            }
            //dự án có nguy cơ trễ hạn
            $progressProject['project_may_be_late'] = isset($dataAllProject) && count($dataAllProject) > 0 ? collect($dataAllProject)->where('risk','high')->count() : 0;
            //dự án hoàn thành đúng hạn
            $progressProject['project_on_time'] = $projectOnTime;
            //dự án hoàn thành trễ hạn
            $progressProject['project_complete_late'] = $projectCompleteLate;
            //dự án đã trễ hạn và chưa hoàn thành
            $progressProject['project_late'] = $projectLate;

            $dataOverView['progress_projects'] = $progressProject;
        }else{
            $progressProject['project_in_the_period'] = 0;
            $progressProject['project_may_be_late'] = 0;
            $progressProject['project_on_time'] = 0;
            $progressProject['project_complete_late'] = 0;
            $progressProject['project_late']  = 0;
            $dataOverView['progress_projects'] = $progressProject;
        }
        $dataOverView['listProjectInPeriod']  = $dataAllProject;
        return $dataOverView;
    }

    public function chartStatus($input){
        $dataAllProject = $this->dataAllProject($input);
        $listStatus = $this->getStatus();
        if(isset($dataAllProject) && count($dataAllProject) > 1){
            //danh sách dự án theo trạng thái
            $listProjectGroupByStatus = collect($dataAllProject)->groupBy('project_status_id')->toArray();
            //chart trạng thái
            $chartStatus = collect($listStatus)->keyBy('manage_project_status_id')->toArray();
            foreach ($chartStatus as $k=>$v){
                $v['y'] = 0;
                if(isset($listProjectGroupByStatus[$v['manage_project_status_id']])){
                    $v['y'] = count($listProjectGroupByStatus[$v['manage_project_status_id']]);
                }
                $chartStatus[$k] = $v;
            }
            ///Thông tin chart Tổng quan dự án theo trạng thái
            $chartStatusTrue = array_values($chartStatus);
            foreach ($chartStatusTrue as $k=>$v){
                $v['name'] = $v['manage_project_status_name'];
                unset($v['manage_project_status_id']);
                unset($v['manage_project_status_name']);
                unset($v['manage_project_status_color']);
                $chartStatusTrue[$k] = $v;
            }
            if(isset($dataAllProject) && count($dataAllProject) > 0){
                foreach ($dataAllProject as $key => $value){
                    //số dự án theo trạng thái
                    if(isset($chartStatus[$value['project_status_id']])){
                        $chartStatus[$value['project_status_id']]['y'] += 1;
                    }
                }
            }
            $quantityY = collect($chartStatusTrue)->pluck('y')->toArray();
            $maxY = max($quantityY);
            if($maxY == 0){
                $chartStatusTrue = [] ;
            }
        }else{
            $chartStatusTrue = [];
        }
        return $chartStatusTrue;
    }
    public function chartRisk($input){
        $mWork = app()->get(WorkTable::class);
        $dataAllProject = $this->overViewProjects($input)['listProjectInPeriod'];
        if(isset($dataAllProject) && count($dataAllProject) > 1){
            $y1 = collect($dataAllProject) -> where('risk','high')->count();
            $y2 = collect($dataAllProject) -> where('risk','normal')->count();
            $y3 = collect($dataAllProject) -> where('risk','low')->count();
            $dataChartRisk = [
                [
                    'name' => __('Cao'),
                    'y' => $y1
                ],
                [
                    'name' => __('Bình thường'),
                    'y' => $y2
                ],
                [
                    'name' => __('Thấp'),
                    'y' => $y3
                ]
            ];
            $result = $dataChartRisk;
        }else{
            $result = [];
        }
        return $result;
    }
    public function chartManager($input)
    {
        $listStatus = $this->getStatus();
        //chart danh sách quản trị
        $dataAllProject = $this->dataAllProject($input);
        $input['arrManagerId'] = isset($dataAllProject) && count($dataAllProject) > 0 ? collect($dataAllProject)->pluck('manager_id')->toArray() : [];
        if(isset($dataAllProject) && count($dataAllProject) > 1){
            $listManagerName = array_values(array_unique(collect($dataAllProject)->pluck('manager_name')->toArray()));
            $listManagerId = array_values(array_unique(collect($dataAllProject)->pluck('manager_id')->toArray()));
            $dataChartManager = [];
            foreach ($listManagerId as $key => $value){
                $seriesChart = [];
                foreach ($listStatus as $keyStatus => $valueStatus){
                    $dataChartManager[$key]['status_project'][$keyStatus] = $valueStatus;
                    $dataChartManager[$key]['status_project'][$keyStatus]['total_project'] = collect($dataAllProject)
                        ->where('project_status_id',$valueStatus['manage_project_status_id'])
                        ->where('manager_id',$value)
                        ->count();
                    $seriesChart[]['name'] = $valueStatus['manage_project_status_name'];
                }
            }
            //danh sach ten quan tri
            foreach ($seriesChart as $keySeriesChart => $valueSeriesChart) {
                $valueSeriesChart['data'] = [];
                $seriesChart[$keySeriesChart] = $valueSeriesChart;
            }
            $seriesChart = collect($seriesChart)->keyBy('name')->toArray();
            $statusProject = collect($dataChartManager)->pluck('status_project')->toArray();
            foreach ($statusProject as $a => $b) {
                foreach ($b as $k => $v) {
                    if (isset($seriesChart[$v['manage_project_status_name']])) {
                        $seriesChart[$v['manage_project_status_name']]['data'][] = $v['total_project'];
                    }
                }
            }
            $seriesChart = array_values($seriesChart);
            $chartManager = [
                'categories' => $listManagerName,
                'series' => $seriesChart,
            ];
        }else{
            $chartManager = [];
        }
        return $chartManager;
    }

    public function chartDepartment($input)
    {
        $mDepartment = app()->get(DepartmentTable::class);
        $listStatus = $this->getStatus();
        $dataAllProject = $this->dataAllProject($input);
        if(isset($dataAllProject) && count($dataAllProject) > 1){
            $input['arrProjectId'] = isset($dataAllProject) && count($dataAllProject) > 0 ? collect($dataAllProject)->pluck('project_id')->toArray() : [];
            $listStaffProject = $this->getStaffProject($input);
            $dataAllProjectGroupByProjectId = collect($listStaffProject)->groupBy('project_id')->toArray();
            //danh sách phòng ban
            $listDepartment = $this->getDepartment();
            $listDepartmentName = collect($listDepartment)->pluck('department_name')->toArray();
            $dataChartDepartment = [];
            $dataAllProjectGroupByStatusId = collect($dataAllProject)->groupBy('project_status_id')->toArray();
            foreach ($listStatus as $key => $val){
                $dataChartDepartment[$key]['name'] = $val['manage_project_status_name'];
                $dataChartDepartment[$key]['data'] = [];
                foreach ($listDepartment as $k => $v){
                    $dataChartDepartment[$key]['data'][$v['department_id']] = 0;
                    if(isset($dataAllProjectGroupByStatusId[$val['manage_project_status_id']])){
                        foreach ($dataAllProjectGroupByStatusId[$val['manage_project_status_id']] as $valueGroup){
                            if($valueGroup['department_id'] == $v['department_id'] && $valueGroup['project_status_id'] == $val['manage_project_status_id']){
                                $dataChartDepartment[$key]['data'][$v['department_id']] += 1;
                            }
                        }
                    }
                }
            }
            foreach ($dataChartDepartment as $key => $val){
                $val['data'] = array_values($val['data']);
                $dataChartDepartment[$key]  = $val;
            }
            $chartDepartment = [
                'categories' => $listDepartmentName,
                'series' => $dataChartDepartment,
            ];
        }else{
            $chartDepartment = [];
        }
        return $chartDepartment;
    }
    public function chartBudget($input){
        $mExpenditure = app()->get(ProjectExpenditureTable::class);
        $dataAllProject = $this->dataAllProject($input);
        if(isset($dataAllProject) && count($dataAllProject) > 1){
            //danh sách tên dự án
            $listProjectName = collect($dataAllProject)->pluck('project_name')->toArray();
            //danh sách id dự án
            $listProjectId = collect($dataAllProject)->pluck('project_id');
            //danh sách ngân sách dự án
            $listBudget = collect($dataAllProject)->pluck('budget')->toArray();

            foreach ($listBudget as $k=>$v){
                if($v == null){
                    $listBudget[$k] = 0;
                }
            }
            //danh sách phiếu thu
            $listReceipt = $mExpenditure->getExpenditureReceipt();
            //danh sách phiếu chi
            $listPayment = $mExpenditure->getExpenditurePayment();
            $dataReceipt = $listProjectId;
            $dataPayment = $listProjectId;
            foreach ($dataReceipt as $key=>$value){
                $value = [
                    'id' => $value,
                    'total_money' => 0,
                ];

                foreach ($listReceipt as $k=>$v){
                    if($v['manage_project_id'] == $value['id']){
                        $value['total_money'] = $value['total_money'] + $v['total_money'];
                    }
                }
                $dataReceipt[$key] = $value;
            }
            foreach ($dataPayment as $key=>$value){
                $value = [
                    'id' => $value,
                    'total_money' => 0,
                ];

                foreach ($listPayment as $k=>$v){
                    if($v['manage_project_id'] == $value['id']){
                        $value['total_money'] = $value['total_money'] + $v['total_amount'];
                    }
                }
                $dataPayment[$key] = $value;
            }
            $dataChart[] = [
                'name' => __('Tổng ngân sách'),
                'data' => $listBudget
            ];
            $dataChart[] = [
                'name' => __('Đã thu'),
                'data' => collect($dataReceipt)->pluck('total_money')->toArray(),
            ];
            $dataChart[] = [
                'name' => __('Đã chi'),
                'data' => collect($dataPayment)->pluck('total_money')->toArray(),
            ];
            $dataChartBudget = [
                'categories' => $listProjectName,
                'series' => $dataChart
            ];
        }else{
            $dataChartBudget = [];
        }
        return $dataChartBudget;
    }
    public function chartResource($input){
        $mWork = app()->get(WorkTable::class);
        $mDepartment = app()->get(DepartmentTable::class);
        $dataAllProject = $this->dataAllProject($input);
        if(isset($dataAllProject) && count($dataAllProject) > 1){
            $listStatus = $this->getStatus();
            //danh sách phòng ban
            $listDepartment = $this->getDepartment();
            $arrIdProject = collect($dataAllProject)->pluck('project_id')->toArray();
            $input['arrIdProject'] = $arrIdProject;
            $listDepartmentName = collect($listDepartment)->pluck('department_name')->toArray();
            $dataChartResource = [];
            $dataAllProjectGroupByStatusId = collect($dataAllProject)->groupBy('project_status_id')->toArray();
            foreach ($listStatus as $key => $val){
                $dataChartResource[$key]['name'] = $val['manage_project_status_name'];
                $dataChartResource[$key]['data'] = [];
                foreach ($listDepartment as $k => $v){
                    $dataChartResource[$key]['data'][$v['department_id']] = 0;
                    if(isset($dataAllProjectGroupByStatusId[$val['manage_project_status_id']])){
                        foreach ($dataAllProjectGroupByStatusId[$val['manage_project_status_id']] as $valueGroup){
                            if($valueGroup['department_id'] == $v['department_id'] && $valueGroup['project_status_id'] == $val['manage_project_status_id']){
                                $dataChartResource[$key]['data'][$v['department_id']] += $valueGroup['resource_total'];
                            }
                        }
                    }
                }
            }
            foreach ($dataChartResource as $key => $val){
                $val['data'] = array_values($val['data']);
                $dataChartResource[$key]  = $val;
            }


            $chartResource= [
                'categories' => $listDepartmentName,
                'series' => $dataChartResource,
            ];
        }else{
            $chartResource = [];
        }
        return $chartResource;
    }
    public function projectHighRisk($input){
        $ext = $this->overViewProjects($input)['listProjectInPeriod'];
        $extHigh = collect($ext)->where('risk','high')->toArray();
        if($ext != []){
            $dataAllProject = $extHigh;
            $input['arrIdProject'] = collect($dataAllProject)->pluck('project_id');
            $mWork = app()->get(WorkTable::class);
            $mProjectStaff = app()->get(ProjectStaffTable::class);
            $mExpenditure = app()->get(ProjectExpenditureTable::class);
            $mReceipt =  app()->get(ReceiptTable :: class);
            $mPayment =  app()->get(PaymentTable :: class);
            $listWork = $mWork->getAllWork($input);
            foreach ( $listWork as $key => $value){
                $dateStart = strtotime($value['date_start']);
                $dateEnd = strtotime($value['date_end']);
                $dateDiff = ceil(abs($dateStart - $dateEnd) / (60*60*24));
                $value['date_work'] = $dateDiff;
                $listWork[$key] = $value;
            }
            //lấy nguồn lực từ công việc theo dự án
            $listWorkGroupByProjectId = collect($listWork)->groupBy('project_id')->toArray();
            foreach ($listWorkGroupByProjectId as $key => $value){
                $sumDay =array_sum(collect($value)->pluck('date_work')->toArray());


                $value['totalWork'] = count($value);
                $totalTimeWork = collect($value)->sum('time_work');
                $value['totalTimeWork'] = $totalTimeWork;
                $totalWorkComplete =collect($value)->where('status_id' , 6)->count();
                $value['totalWorkComplete'] = $totalWorkComplete;


                $value['sum_day'] = $sumDay;
                $dataSumDay[$key] = $value;
                $listWorkGroupByProjectId[$key] = $value;

            }

            //lấy phiếu thu chi
            $listExpenditure = $mExpenditure->getListExpenditure($inputt['arrIdProject'] = [16]);
            foreach ($listExpenditure as $k => $v){
                if($v['type'] == 'receipt'){
                    $infoReceipt = $mReceipt->getListReceipt($v);
                    $v['total_money'] = isset($infoReceipt) ? $infoReceipt[0]['total_money'] : 0;
                }else{
                    $infoPayment = $mPayment->getListPayment($v);
                    $v['total_money'] = isset($infoPayment) ? $infoPayment[0]['total_money'] : 0;
                }
                $listExpenditure[$k] = $v;
            }
            $listExpenditureGroupByProjectId = collect($listExpenditure)->groupBy('manage_project_id')->toArray();

            //lấy thành viên dự án
            $listProjectStaff = $mProjectStaff->getStaffProject($input);
            $listStaffGroupByProjectId = collect($listProjectStaff)->groupBy('project_id')->toArray();
            foreach ($dataAllProject as $key => $value){
                if(isset($dataSumDay[$value['project_id']])){
                    $value['resource_implement'] = $dataSumDay[$value['project_id']]['sum_day'];
                }else{
                    $value['resource_implement'] = 0;
                }
                if (isset($listStaffGroupByProjectId[$value['project_id']])){
                    $value['total_member'] =count($listStaffGroupByProjectId[$value['project_id']]);
                }else{
                    $value['total_member'] =0;
                }
                if(isset($listWorkGroupByProjectId[$value['project_id']])){
                    $value['total_work'] = count($listWorkGroupByProjectId[$value['project_id']]);
                }else{
                    $value['total_work'] = 0;
                }
                if (isset($listExpenditureGroupByProjectId[$value['project_id']])){
                    $value['total_receipt'] = array_sum(collect($listExpenditureGroupByProjectId[$value['project_id']])->where('type','receipt')->pluck('total_money')->toArray());
                    $value['total_payment'] = array_sum(collect($listExpenditureGroupByProjectId[$value['project_id']])->where('type','payment')->pluck('total_money')->toArray());
                }else{
                    $value['total_receipt'] = 0;
                    $value['total_payment'] = 0;
                }
                if($value['progress'] == null){
                    $value['progress'] = 0;
                }
                if($value['budget'] == null){
                    $value['budget'] = 0;
                }
                $now = strtotime(Carbon::now()->format('Y-m-d'));
                $dateFinish = $value['date_finish'] != null ? strtotime(Carbon::createFromFormat('Y-m-d H:i:s', $value['date_finish'])->format('Y-m-d')) : $now;
                $toDate= strtotime($value['to_date']);
                if( $value['date_finish'] == null && $now > $toDate){
                    $dateRemainng = abs($now - $toDate) / (60*60*24);
                    $value['condition'] = 'Quá hạn '.floor($dateRemainng). ' ngày';
                }elseif($value['date_finish'] == null && $now < $toDate){
                    $dateRemainng = abs($toDate - $now) / (60*60*24);
                    $value['condition'] = __('Còn lại ').floor($dateRemainng). __(' ngày');
                }else{
                    $value['condition'] = __('Bình thường');
                }
                $dataAllProject[$key] = $value;
            }
        }else{
            $dataAllProject = [];
        }
        return $dataAllProject;
    }
    public function longTimeInactiveProject($input){
        $mProjectHistory = new ManageProjectHistoryTable();
        $dataAllProject = $this->dataAllProject($input);
        $dataChartInactive = [];
        if(isset($dataAllProject) && count($dataAllProject) > 0 ){
            $arrProjectId = collect($dataAllProject)->pluck('project_id')->toArray();
            $input['arrProjectId'] = $arrProjectId;
            $listProjectHistory= $mProjectHistory->getListhistory($input)->toArray();
            $listProjectHistoryGroupByProjectName = collect($listProjectHistory) -> groupBy('manage_project_name');
            $now = Carbon::now()->format('Y-m-d H:i:s');

            foreach ($listProjectHistoryGroupByProjectName as $k => $v){
                $listUpdatedAt = collect($v)->pluck('updated_at')->toArray();
                $maxUpdatedAt = max($listUpdatedAt);
                $v['max_updated_at'] = $maxUpdatedAt;
                $first_date = strtotime($now);
                $second_date = strtotime($maxUpdatedAt);
                $datediff = (abs($first_date - $second_date))/(60 * 60 * 24);
                $v['time_inactive'] = floor($datediff);
                if($datediff < 15){
                    unset($listProjectHistoryGroupByProjectName[$k]);
                }
            }
            if (count($listProjectHistoryGroupByProjectName) < 1) {
                return $dataChartInactive;
            } else {
                foreach ($listProjectHistoryGroupByProjectName as $k => $v) {
                    $listProjectName[] = $k;
                }
                $listTimeInactive = collect($listProjectHistoryGroupByProjectName)->pluck('time_inactive')->toArray();
                $dataChartInactive = [
                    'categories' => $listProjectName,
                    'series' => $listTimeInactive
                ];
            }

        }else{
            return $dataChartInactive;
        }
        return $dataChartInactive;
    }
    public function listIssue($input){
        $mProjectIssue = app()->get(ProjectIssueTable::class);
        $dataAllProject = $this->dataAllProject($input);
        if(isset($dataAllProject) && count($dataAllProject) > 0 ){
            $arrProjectId = collect($dataAllProject)->pluck('project_id')->toArray();
            $input['arrProjectId'] = $arrProjectId;
            $listIssue = $mProjectIssue->listIssue($input);
        }else{
            $listIssue = [];
        }
        return $listIssue;
    }

}