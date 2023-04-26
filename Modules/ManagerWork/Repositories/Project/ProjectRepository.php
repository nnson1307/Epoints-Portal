<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\Project;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerProject\Models\ManageProjectDocumentTable;
use Modules\ManagerProject\Models\ManageProjectStatusConfigTable;
use Modules\ManagerProject\Models\ManageProjectStatusTable;
use Modules\ManagerWork\Models\Customers;
use Modules\ManagerWork\Models\ManageProjectHistoryTable;
//use Modules\ManagerWork\Models\ManageProjectRole;
//use Modules\ManagerWork\Models\ManageProjectRoleConfig;
//use Modules\ManagerWork\Models\ManageProjectRoleConfigDefault;
//use Modules\ManagerWork\Models\ManageProjectRoleDefault;
use Modules\ManagerWork\Models\ManageProjectStaffTable;
use Modules\ManagerWork\Models\ManageStatusConfigTable;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManageProjectRoleTable;
use Modules\ManagerWork\Models\ManagerConfigListTable;
use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Repositories\Project\ProjectTrait;

class ProjectRepository implements ProjectRepositoryInterface
{
    use ProjectTrait;
    /**
     * @var ProjectT ableTable
     */
    protected $project;
    protected $timestamps = true;

    public function __construct(ProjectTable $project)
    {
        $this->project = $project;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        $progress = $filters['progress'] ?? "";
        unset($filters['progress']);
        $list = $this->project->getList($filters);
        $mManageWork = app()->get(ManagerWorkTable::class);
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
        if ($list->count() > 0) {
            foreach ($list as $key => $item) {
                $checkStaff = $mManageProjectStaff->checkStaffProject(Auth::id(),$item['manage_project_id']);
                $progressProject = intval($mManageWork->getProgress($item->work())->total_progress);
                $item->progress = $progressProject;
                $item->is_staff = count($checkStaff) != 0 ? 1 : 0;
                $item->listStaffManage = $mManageProjectStaff->getListAdmin($item['manage_project_id'],'administration');

                if ($progress != '') {
                    if ($progressProject != $progress) {
                        unset($list[$key]);
                    }
                }
            }
        }
        return $list;
    }

    public function getAll(array $filters = [])
    {
        return $this->project->getAll($filters);
    }
    public function getName()
    {
        return $this->project->getName();
    }

    /**
     * Xoá dự án và các công việc liên quan
     * @param $id 
     * @return mixed
     */
    public function remove($id)
    {
        DB::beginTransaction();
        try {
            $project = $this->project->find($id);

            if (!$project) return [
                'error' => true,
                'message' => __('Xoá dự án thất bại')
            ];

            // danh sách công việc của dự án 
            if ($project->work->count() > 0) {
                // SortDelete công việc
                foreach ($project->work as $work) {
                    $this->softDeleteWork($work);
                }
            }
            // SortDelete dự án 
            $project->update(
                [
                    'is_deleted' => $this->project::IS_DELETED
                ]
            );
            DB::commit();
            return [
                'error' => false,
                'message' => __('Xoá dự án thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::info("Remove project error :" . $e->getMessage());
            return [
                'error' => true,
                'message' => __('Xoá dự án thất bại')
            ];
        }
    }

    /**
     * softdelete danh sách công việc
     * @param $itemWork
     * @return void 
     */

    public function softDeleteWork(ManagerWorkTable $itemWork)
    {
//        $itemWork->update([
//            'is_deleted' => ManagerWorkTable::IS_DELETED
//        ]);

        $itemWork->delete();
    }

    /**
     * 
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->project->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->project->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->project->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->project->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '')
    {
        return $this->project->checkExist($name, $id);
    }

    /**
     * Lấy tên tiền tố dự án ngẫu nhiên
     * @param $param
     * @return string
     */


    public function getNamePrefix($param)
    {
        // lấy danh sách tên tiền tố dự án //
        $listPrefixProject = $this->getListNamePrefix();
        $result = $this->hanldeStringPrefixProject($listPrefixProject, $param);
        return $result;
    }

    /**
     * Lấy danh sách tên tiền tố dự án
     * @return array
     */

    public function getListNamePrefix()
    {

        return $this->project->getAll()->pluck('prefix_code')->toArray();
    }

    /**
     * Xử lý chuổi ngẫu nhiên tiền tố dự án
     * @param $listNamPrefixDefault
     * @param $value 
     * @return string
     */

    public function hanldeStringPrefixProject($listNamPrefixDefault, $value)
    {
        $strRandom = strtoupper(Str::random(2));
        $prefixCode = $value . $strRandom;
        if (in_array($prefixCode, $listNamPrefixDefault)) {
            $this->hanldeStringPrefixProject($listNamPrefixDefault, $value);
        }
        return $prefixCode;
    }

    /**
     * Thêm dự án 
     * @param array $params
     * @return mixed
     */


    public function store($params)
    {
        DB::beginTransaction();
        try {

            $mManageProjectRole = app()->get(ManageProjectRoleTable::class);
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
            // thêm dự án
            $params['created_by'] = Auth::id();
            if (isset($params['manage_project_status_id']) && $params['manage_project_status_id'] == 6){
                $params['date_finish'] = Carbon::now();
            }

            $roleAdminProject = $mManageProjectRole->getInfoAdmin('administration');

            $project = $this->project->create($params);

            $dataRole = [
                'manage_project_id' => $project['manage_project_id'],
                'staff_id' => $params['manager_id'],
                'manage_project_role_id' => $roleAdminProject['manage_project_role_id'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageProjectStaff->insertStaff($dataRole);

            // thêm tag
            if (!empty($params['tags'])) {
                $listTags = $this->hanldeTagInserts($params['tags']);
                $project->tags()->attach($listTags);
            }

//            Thêm quyền sau khi tạo dự án thành công
//            Lấy từ bảng quyền mặc định
//            $mManageProjectRoleDefault = app()->get(ManageProjectRoleDefault::class);
//            $mManageProjectRole = app()->get(ManageProjectRole::class);
//            $mManageProjectRoleConfigDefault = app()->get(ManageProjectRoleConfigDefault::class);
//            $mManageProjectRoleConfig = app()->get(ManageProjectRoleConfig::class);
//
//            $listRoleDefault = $mManageProjectRoleDefault->getListDefault();
//            $listRoleConfigDefault = $mManageProjectRoleConfigDefault->getListConfigDefault();
//
//            $mManageProjectRole->createRole($listRoleDefault);
//            $mManageProjectRoleConfig->createdRoleConfig($listRoleConfigDefault);

            $mManageProjectDocument = app()->get(ManageProjectDocumentTable::class);
            if (isset($params['document']) && count($params['document']) != 0){
                foreach ($params['document'] as $itemDocument) {
                    $dataImage = [
                        'file_name' => strip_tags($itemDocument['file_name']),
                        'manage_project_id' => $project['manage_project_id'],
                        'path' => $itemDocument['path'],
                        'type' => $itemDocument['file_type'],
                        //                        'note' => strip_tags($data['note']),
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                    $mManageProjectDocument->insertArr($dataImage);

                }
            }

            $result = [
                'error' => false,
                'message' => __('Thêm dự án thành công')
            ];
            DB::commit();

            $data = [
                'manage_project_id' => $project['manage_project_id'],
                'new' => $project['manage_project_name'],
                'key' => 'created'
            ];

            $this->checkDataCreateHistory($project['manage_project_id'],$data);

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error add project : ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('Thêm dự án thất bại')
            ];
        }
    }
    /**
     * Undocumented function
     *
     * @param array $params
     * @return void
     */

    public function update($params)
    {
        DB::beginTransaction();
        try {

            $params['update_by'] = Auth::id();
            // Dự án
            $project = $this->project->find($params['manage_project_id']);

            if ($project['manage_project_status_id'] != 6 && $params['manage_project_status_id'] == 6){
                $params['date_finish'] = Carbon::now();
            }
            if ($project) {
                // Cập nhật dự án
                $project->update($params);
                // thêm tag 
                $tags = $params['tags'] ?? [];
                $listTags = $this->hanldeTagInserts($tags);
                $project->tags()->sync($listTags);
                $result = [
                    'error' => false,
                    'message' => __('Cập nhật dự án thành công')
                ];
            } else {
                $result = [
                    'error' => true,
                    'message' => __('Cập nhật dự án thất bại')
                ];
            }
            DB::commit();

            $this->checkDataCreateHistory($params['manage_project_id'],[],$params,$project);

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Cập nhật dự án thất bại')
            ];
        }
    }
    /**
     * Xử lý data tag insert
     * @param $array
     * @return array
     */

    public function hanldeTagInserts($listTag)
    {
        $data = [];
        foreach ($listTag as $item) {
            $data[] = [
                'tag_id' => $item
            ];
        }
        return $data;
    }

    /**
     * Lấy record dự án
     * @param $id
     * @return mixed
     */

    public function getItemProject($id)
    {
        $project = $this->project->with('tags')
            ->find($id);
        if (!$project) return abort(404);
        return $project;
    }

    /**
     * Danh sách hiển thị thông tin cấu hình danh sách dự án
     * @return array
     */

    public function getConfigListProject()
    {
        // danh sách cột hiển thị theo cấu hình 
        $listColumShowConfig =  $this->getColumShowConfig();
        // danh sách cột hiển thị tìm kiếm 
        $listColumnSearchConfig = $this->getColumSearchConfig();
        // danh sách côt hiển thị tìm kiếm mặc định
        $listColumSearchDefault = $this->listColumPrjectDeafault();
        // danh sách cột hiển thị mặc định
        $listColumShowDefault = $this->listColumProjectShowDefault();
        $res = [
            'listColumShowConfig' => $listColumShowConfig,
            'listColumnSearchConfig' => $listColumnSearchConfig,
            'listColumSearchDefault' => $listColumSearchDefault,
            'listColumShowDefault' => $listColumShowDefault
        ];
        return $res;
    }

    /**
     * Cấu hình danh sách hiển thị và lọc dự án
     * @param array $params
     * @return mixed
     */

    public function configListProject($params)
    {
        $dataConfigProject = [
            'search' => $params['dataSearch'],
            'column' => $params['dataColumn']
        ];
        $mManagerConfigList = app()->get(ManagerConfigListTable::class);
        $configProjectList = $mManagerConfigList->getItemByRoute(ProjectTable::PROJECT_ROUTE_LIST);
        if (!$configProjectList)
            return [
                'error' => true,
                'message' => __('Cấu hình danh sách thất bại')
            ];
        $configProjectList->update([
            'value' => json_encode($dataConfigProject)
        ]);

        return [
            'error' => false,
            'message' => __('Cấu hình danh sách thành công')
        ];
    }

    /**
     * Lấy thông tin dự án 
     * @param $idProject
     * @return mixed
     */

    public function getDetail($idProject)
    {
        $project = $this->project->find($idProject);
        $mManageProjectRole = app()->get(ManageProjectRoleTable::class);
        $mManageWork = app()->get(ManagerWorkTable::class);
        // danh nhân viên trong dự án và vai trò
        $listStaffs = [];
        if ($project->staffs->count() > 0) {
            foreach ($project->staffs as $item) {
                $idManageProjectRole = $item->pivot->manage_project_role_id;
                $manageProjectRole = $mManageProjectRole->find($idManageProjectRole);
                if ($manageProjectRole) {
                    $listStaffs[$manageProjectRole->manage_project_role_name][] = $item;
                }
            }
        }
        //        Danh sách nhân viên theo phòng ban
        $mStaff = app()->get(StaffsTable::class);

        $listStaffDepartment = $mStaff->listStaffDepartment($idProject);
        $project->totalStaffs = count($listStaffDepartment);
        if (count($listStaffDepartment) != 0){
            $listStaffDepartment = collect($listStaffDepartment)->groupBy('department_id');
        }


        $project->listStaffs = $listStaffs;
        // tổng số giờ làm việc //
        $totalDayWorkProject = $this->cacTotalHourWork($project->work()->whereNotNull("date_finish")->get());
        $project->totalWorkTime =  $totalDayWorkProject;
        // danh công việc của nhân viên theo từng trạng thái dự án
        $listWork = $mManageWork->getListWorkFollowStatus($idProject);
        // cột hiển thị mặc định 
        $columDefault = [__('Phòng ban'), __('Tổng thành viên'), __('Tổng công việc')];
        if ($listWork->count() > 0) {
            // danh sách trạng thái công việc của dự án 
            $listStatusWork = $listWork->pluck("name_status", "manage_status_id")->unique()->toArray();
            foreach ($listStatusWork as $item) {
                $columDefault[] = $item;
            }
            // danh sách công việc theo phòng ban
            $listWorkDepartment = $listWork->groupBy("department_name");
            // danh sách tổng tất cả thông tin công việc theo dự án
            $listTotalInfoWorkDepartment = [];
            // tổng thành viên
            $totalUser = 0;
            // tổng công việc
            $totalWork = 0;
            // danh sách trạng thái
            $filedStatus = [];
            // danh sách công việc trễ hạn 
            $totalLate = 0;
            foreach ($listWorkDepartment as $key => $value) {
                // tổng thời gian trễ hạn 
                $totalLateDepartment = $value->where("date_end", "<", Carbon::now()->format('Y-m-d H:i:s'))
                    ->whereNotIn("manage_status_group_config_id", [3, 4])
                    ->count();
                $totalLate += $totalLateDepartment;
                $dataTotalWorkLateDepartment = [$totalLateDepartment];
                // tổng thành viên phòng ban
//                $totalUserDepartment = $value->pluck('staff_id')->unique()->count();
                $totalUserDepartment = isset($listStaffDepartment[$value[0]['department_id']]) ? count($listStaffDepartment[$value[0]['department_id']]) : 0;
                $totalUser += $totalUserDepartment;
                // tổng công việc phòng ban
                $totalWorkDepartment = $value->count();
                $totalWork += $totalWorkDepartment;
                // thông tin trạng thái công việc phòng ban
                $listStatus = $value->groupBy('manage_status_id');
                // danh sách trang thái phòng ban //
                $filedStatusDepartment = [];
                $itemTotalInfoWorkProject = [
                    $key,
                    $totalUserDepartment,
                    $totalWorkDepartment
                ];

                foreach ($listStatusWork as $kw => $vw) {
                    $filedStatus[$kw] = isset($filedStatus[$kw]) ? $filedStatus[$kw] : 0;
                    $filedStatusDepartment[$kw . '_status'] = isset($listStatus[$kw]) ? $listStatus[$kw]->count() : 0;
                    $filedStatus[$kw] += $filedStatusDepartment[$kw . '_status'];
                }
                $itemTotalInfoWorkProject = array_merge($itemTotalInfoWorkProject, $filedStatusDepartment, $dataTotalWorkLateDepartment);
                $listTotalInfoWorkDepartment[$value[0]['department_id']] = $itemTotalInfoWorkProject;
            }
            $totalInfoWorkDepartment = [$totalUser, $totalWork, $filedStatus, $totalLate];
            $project->listTotalInfoWorkDepartment = $listTotalInfoWorkDepartment;
            $project->totalInfoWorkDepartment = $totalInfoWorkDepartment;
        }
        $project->columDefault = $columDefault;
        // tiến độ dự án
        $progressProject = intval($mManageWork->getProgress($project->work())->total_progress);
        $project->progressProject = $progressProject;

        return $project;
    }

    public function getDetailFix($idProject)
    {
//        $project = $this->project->find($idProject);
        $project = $this->project->getItem($idProject);
        $mManageProjectRole = app()->get(ManageProjectRoleTable::class);
        $mManageStatusConfig = app()->get(ManageStatusConfigTable::class);
        $mManageWork = app()->get(ManagerWorkTable::class);
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
        // danh nhân viên trong dự án và vai trò
        $listStaffs = [];
        if ($project->staffs->count() > 0) {
            foreach ($project->staffs as $item) {
                $idManageProjectRole = $item->pivot->manage_project_role_id;
                $manageProjectRole = $mManageProjectRole->find($idManageProjectRole);
                if ($manageProjectRole) {
                    $listStaffs[$manageProjectRole->manage_project_role_name][] = $item;
                }
            }
        }
        //        Danh sách nhân viên theo phòng ban
        $mStaff = app()->get(StaffsTable::class);

        $listStaffDepartment = $mStaff->listStaffDepartment($idProject);

        $project->totalStaffs = count($listStaffDepartment);
        if (count($listStaffDepartment) != 0){
            $listStaffDepartment = collect($listStaffDepartment)->groupBy('department_id');
        }

        $project->listStaffs = $listStaffs;

//        Lấy danh sách công việc theo dự án

        $listWork = $mManageWork->getListWorkByProjectId($idProject);
        $listWorkTmp = [];
        $listWorkDepartment = [];
        if (count($listWork) != 0){
            $listWorkTmp = collect($listWork)->groupBy('manage_status_id');
            $listWorkDepartment = collect($listWork)->groupBy('department_id');
        }

        $project->totalWork = count($listWork);

//        Danh sách công việc quá hạn
        $listWorkOverdue = $mManageWork->getListOverdue(['staff_manage_project_id' => $idProject,'status_overdue' => true]);

        $project->totaWorkOverdue = count($listWorkOverdue);

        if (count($listWorkOverdue) != 0){
            $listWorkOverdue  = collect($listWorkOverdue)->groupBy('department_id');
        }

//        Danh sách trạng thái đang hoạt động

        $listStatus = $mManageStatusConfig->getListStatusActive();

        $n = 0;

//        Thêm dữ liệu cho hàng tổng
        $dataShow[$n] = [
            'department_name' => __('Tổng'),
            'department_id' => 0,
            'total_staff' => $project->totalStaffs,
            'total_work' => $project->totalWork,
            'total_overdue' => $project->totaWorkOverdue
        ];

        foreach ($listStatus as $item){
            $dataShow[$n]['status'][$item['manage_status_id']] = isset($listWorkTmp[$item['manage_status_id']]) ? count($listWorkTmp[$item['manage_status_id']]) : 0;
        }
        $n = 1;

//        thêm theo danh sách phòng ban
        foreach ($listStaffDepartment as $key => $item){
            $dataShow[$n] = [
                'department_name' => $item[0]['department_name'],
                'department_id' => $key,
                'total_staff' => isset($listStaffDepartment[$key]) ? count($listStaffDepartment[$key]) : 0,
                'total_work' => isset($listWorkDepartment[$key]) ? count($listWorkDepartment[$key]) : 0,
                'total_overdue' => isset($listWorkOverdue[$key]) ? count($listWorkOverdue[$key]) : 0,
            ];

            foreach ($listStatus as $itemStatus){
                $dataShow[$n]['status'][$itemStatus['manage_status_id']] = isset($listWorkDepartment[$key]) ? collect($listWorkDepartment[$key])->where('manage_status_id',$itemStatus['manage_status_id'])->count() : 0;
            }
            $n++;
        }
        $project->dataShow = $dataShow;
        $project->listStatus = $listStatus;

//        Lấy danh sách công việc theo id dự án
        $progressProject = intval($mManageWork->getProgress($project->work())->total_progress);
        $project->progressProject = $progressProject;
        return $project;
    }

    /**
     * tổng số giờ làm việc
     * @param $listWork
     * @return int
     */

    public function cacTotalHourWork($listWork)
    {
        $totalWork = 0;
        if ($listWork->count() > 0) {
            foreach ($listWork as $item) {
                $dateStart = Carbon::parse($item->date_start);
                $dateFinish = Carbon::parse($item->date_finish);
                $totalItemWork = $dateFinish->diffInHours($dateStart);
                $totalWork+= $totalItemWork;
            }
        }
        return $totalWork;
    }

    public function checkDataCreateHistory($manage_project_id,$dataInsert = [],$dataNew = [],$dataOld = null){
        if (count($dataInsert) != 0){
            $this->createHistoryProject($dataInsert);
            return true;
        }

        if (count($dataNew) != 0 && isset($dataOld)){
//            Tên dự án
            if (isset($dataNew['manage_project_name']) && $dataNew['manage_project_name'] != $dataOld['manage_project_name']){
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataNew['manage_project_name'],
                    'old' => $dataOld['manage_project_name'],
                    'key' => 'manage_project_name'
                ];
                $this->createHistoryProject($data);
            }
//            Khách hàng
            if (isset($dataNew['customer_id']) && $dataNew['customer_id'] != $dataOld['customer_id']){
                $mCustomer = app()->get(Customers::class);
                $olDCustomer = $mCustomer->getDetail($dataOld['customer_id']);
                $newCustomer = $mCustomer->getDetail($dataNew['customer_id']);
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => isset($newCustomer) ? $newCustomer['full_name'] : '',
                    'old' => isset($olDCustomer) ? $olDCustomer['full_name'] : '',
                    'key' => 'customer'
                ];
                $this->createHistoryProject($data);
            }

//            Status
            if (isset($dataNew['manage_project_status_id']) && $dataNew['manage_project_status_id'] != $dataOld['manage_project_status_id']){
                $mManageProjectStatus = app()->get(ManageProjectStatusTable::class);
                $olDStatus = $mManageProjectStatus->getDetail($dataOld['manage_project_status_id']);
                $newStatus = $mManageProjectStatus->getDetail($dataNew['manage_project_status_id']);
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => isset($newStatus) ? $newStatus['manage_project_status_name'] : '',
                    'old' => isset($olDStatus) ? $olDStatus['manage_project_status_name'] : '',
                    'key' => 'status'
                ];
                $this->createHistoryProject($data);
            }

//            Quyền truy cập
            if (isset($dataNew['permission']) && $dataNew['permission'] != $dataOld['permission']){
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataOld['permission'] == 'private' ? __('Nội bộ') : __('Công khai'),
                    'old' => $dataNew['permission'] == 'private' ? __('Nội bộ') : __('Công khai'),
                    'key' => 'permission'
                ];
                $this->createHistoryProject($data);
            }

//            Nội dung dự án

            if (isset($dataNew['manage_project_describe']) && $dataNew['manage_project_describe'] != $dataOld['manage_project_describe']){
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'key' => 'describe'
                ];
                $this->createHistoryProject($data);
            }

//            Ngày bắt đầu
            if (isset($dataNew['date_start']) && $dataNew['date_start'] != $dataOld['date_start']){
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataOld['date_start'],
                    'old' => $dataNew['date_start'],
                    'key' => 'date_start'
                ];
                $this->createHistoryProject($data);
            }

//            Ngày kết thúc
            if (isset($dataNew['date_end']) && $dataNew['date_end'] != $dataOld['date_end']){
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataOld['date_end'],
                    'old' => $dataNew['date_end'],
                    'key' => 'date_end'
                ];
                $this->createHistoryProject($data);
            }
        }

    }

    /**
     * Tạo lịch sử dự án
     * @param $data
     */
    public function createHistoryProject($data){
//        $data['manage_project_id'] : Id Dự án
//        $data['old'] : Giá trị cũ
//        $data['new'] : Giá trị mới
//        $data['key'] : khóa để check . status : trạng thái, manage_id : người quản lý
        $message = '';
        switch ($data['key']){
//        Cập nhật nội dung nhiều giá trị
            case 'update' :
                $message = __('đã cập nhật dự án');
                break;
            case 'created' :
                $message = __('đã tạo dự án :new',['new' => $data['new']]);
                break;
            case 'manage_project_name' :
                $message = __('đã cập nhật tiêu đề từ :old sang :new',['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'tag' :
                $message = __('đã cập nhật tag dự án');
                break;
            case 'customer' :
                $message = __('đã cập nhật khách hàng từ :old sang :new',['old' => $data['old'], 'new' => $data['new']]);
                if ($data['old'] == ''){
                    $message = __('đã cập nhật khách hàng thành :new',['old' => $data['old'], 'new' => $data['new']]);
                }
                break;
            case 'status' :
                $message = __('đã cập nhật trạng thái từ :old sang :new',['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'permission' :
                $message = __('đã cập nhật quyền truy cập từ :old sang :new',['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'add_document' :
                $message = __('đã thêm tài liệu :new',['new' => $data['new']]);
                break;
            case 'delete_document' :
                $message = __('đã xóa tài liệu :new',['new' => $data['new']]);
                break;
            case 'describe' :
                $message = __('đã thay đổi nội dung dự án');
                break;
            case 'date_start' :
                $message = __('đã cập nhật ngày bắt đầu từ :old sang :new',['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'date_end' :
                $message = __('đã cập nhật ngày kết thúc từ :old sang :new',['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'staff' :
                $message = __('đã cập nhật danh sách thành viên');
                break;
            case 'staff_edit' :
                $message = __('đã cập nhật thành viên :old',['old' => $data['old']]);
                break;
            case 'staff_delete' :
                $message = __('đã xóa thành viên :old',['old' => $data['old']]);
                break;
            case 'document' :
                $message = __('đã cập nhật danh sách tài liệu');
                break;
            case 'document_delete' :
                $message = __('đã xóa tài liệu :old',['old' => $data['old']]);
                break;
            default:
                break;
        }

        $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
        $mManageProjectHistory->addHistory([
            'manage_project_id' => $data['manage_project_id'],
            'staff_id' => Auth::id(),
            'message' => $message,
            'created_at' => \Carbon\Carbon::now(),
            'created_by' => Auth::id(),
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id(),
        ]);

        return true;
    }
}
