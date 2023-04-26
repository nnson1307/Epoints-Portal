<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerProject\Repositories\Project;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerProject\Models\BranchTable;
use Modules\ManagerProject\Models\DepartmentTable;
use Modules\ManagerProject\Models\ManageProjectContactTable;
use Modules\ManagerProject\Models\ManageProjectDocumentTable;
use Modules\ManagerProject\Models\ManageProjectPhareTable;
use Modules\ManagerProject\Models\ManageProjectStatusConfigTable;
use Modules\ManagerProject\Models\ManageProjectStatusTable;
use Modules\ManagerProject\Models\Customers;
use Modules\ManagerProject\Models\ManageProjectHistoryTable;
use Modules\ManagerProject\Models\ManageProjectStaffTable;
use Modules\ManagerProject\Models\ManageStatusConfigTable;
use Modules\ManagerProject\Models\ManageStatusTable;
use Modules\ManagerProject\Models\PaymentTable;
use Modules\ManagerProject\Models\ProjectExpenditureTable;
use Modules\ManagerProject\Models\ProjectStaffTable;
use Modules\ManagerProject\Models\ProjectTable;
use Modules\ManagerProject\Models\ManagerWorkTable;
use Modules\ManagerProject\Models\ManageProjectRoleTable;
use Modules\ManagerProject\Models\ManagerConfigListTable;
use Modules\ManagerProject\Models\ReceiptTable;
use Modules\ManagerProject\Models\StaffsTable;
use Modules\ManagerProject\Models\TypeWorkTable;
use Modules\ManagerProject\Models\WorkTable;
use Modules\ManagerProject\Models\ProjectTagTable;
use Modules\ManagerProject\Models\ManageRemindTable;
use Modules\ManagerProject\Models\ProjectIssueTable;
use Modules\ManagerProject\Models\ProjectPhaseTable;
use Modules\ManagerProject\Models\ObjectAccountingTypeTable;
use Modules\ManagerProject\Models\PaymentMethodTable;
use Modules\ManagerProject\Models\PaymentTypeTable;
use Modules\ManagerProject\Models\ReceiptTypeTable;
use Modules\ManagerProject\Models\SupplierTable;
use Modules\ManagerProject\Repositories\Contract\ContractRepositoryInterface;
use Modules\ManagerProject\Repositories\Project\ProjectTrait;
use Modules\Payment\Http\Api\PaymentOnline;
use Modules\Payment\Models\CustomerTable;
use Modules\Payment\Models\ReceiptDetailTable;
use Modules\Payment\Models\ReceiptOnlineTable;
use Modules\Payment\Models\StaffTable;

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
                $checkStaff = $mManageProjectStaff->checkStaffProject(Auth::id(), $item['manage_project_id']);
                $progressProject = intval($mManageWork->getProgress($item->work())->total_progress);
                $item->progress = $progressProject;
                $item->is_staff = count($checkStaff) != 0 ? 1 : 0;
                $item->listStaffManage = $mManageProjectStaff->getListAdmin($item['manage_project_id'], 'administration');

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

    public function getStatus()
    {
        $mStatus = app()->get(ManageStatusTable::class);
        $listStatus = $mStatus->getAll();
        return $listStatus;
    }

    public function getTypeWork()
    {
        $mTypeWork = app()->get(TypeWorkTable::class);
        $listTypeWork = $mTypeWork->getListTypeWork();
        return $listTypeWork;
    }

    public function listStaff()
    {
        $mStaff = app()->get(StaffsTable::class);
        $listStaff = $mStaff->getInfoManager();
        return $listStaff;
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

        return $this->textEn($result);
    }

//    Text thay đổi bỏ dấu
    function textEn($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ','_',$str);
        return $str;
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
            if (isset($params['manage_project_status_id']) && $params['manage_project_status_id'] == 6) {
                $params['date_finish'] = Carbon::now();
            }

            if (isset($params['budget'])) {
                $params['budget'] = str_replace(",", "", $params['budget']);
            }

            if (isset($params['resource'])) {
                $params['resource'] = str_replace(",", "", $params['resource']);
            }

            $roleAdminProject = $mManageProjectRole->getInfoAdmin('administration');

            $dataProject = [
                'manage_project_name' => strip_tags($params['manage_project_name']),
                'contract_id' => isset($params['contract_id']) ? $params['contract_id'] : null,
                'contract_code' => isset($params['contract_code']) ? $params['contract_code'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                'progress' => isset($params['progress']) ? $params['progress'] : 0,
                'manager_id' => $params['manager_id'],
                'department_id' => $params['department_id'],
                'date_start' => $params['date_start'],
                'date_end' => $params['date_end'],
                'customer_type' => isset($params['customer_type']) ? $params['customer_type'] : null,
                'customer_id' => isset($params['customer_id']) ? $params['customer_id'] : null,
                'color_code' => isset($params['color_code']) ? $params['color_code'] : null,
                'permission' => isset($params['permission']) ? $params['permission'] : null,
                'prefix_code' => isset($params['prefix_code']) ? $params['prefix_code'] : null,
                'manage_project_describe' => isset($params['manage_project_describe']) ? $params['manage_project_describe'] : null,
                'manage_project_status_id' => isset($params['manage_project_status_id']) ? $params['manage_project_status_id'] : null,
                'resource' => isset($params['resource']) ? $params['resource'] : null,
                'budget' => isset($params['budget']) ? $params['budget'] : null,
                'is_important' => isset($params['is_important']) ? $params['is_important'] : 0,
                'is_active' => isset($params['is_active']) ? $params['is_active'] : 1,
                'is_deleted' => isset($params['is_deleted']) ? $params['is_deleted'] : 0,
            ];

            $project = $this->project->create($dataProject);

//            Tạo giai đoạn mặc định
            $dataPhase = [
                'manage_project_id' => $project['manage_project_id'],
                'name' =>  __('Chưa có giai đoạn'),
                'date_start' => $params['date_start'],
                'date_end' => $params['date_end'],
                'pic' => $params['manager_id'],
                'is_deleted' => 0,
                'status' => 'new',
                'created_at' =>Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                'is_default' => 1
            ];

            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);

            $mManageProjectPhase->addPhase($dataPhase);

            $dataRole = [
                'manage_project_id' => $project['manage_project_id'],
                'staff_id' => $params['manager_id'],
                'manage_project_role_id' => $roleAdminProject['manage_project_role_id'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
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
            if (isset($params['document']) && count($params['document']) != 0) {
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

            $dataContact = [];
            if (isset($params['contact']) && count($params['contact']) != 0) {
                foreach ($params['contact'] as $item) {
                    $dataContact[] = [
                        'manage_project_id' => $project['manage_project_id'],
                        'name' => $item['name'],
                        'phone' => $item['phone'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }
            }

            if (count($dataContact) != 0) {
                $mManageProjectContact = app()->get(ManageProjectContactTable::class);
                $mManageProjectContact->insertArrayData($dataContact);
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

            $this->checkDataCreateHistory($project['manage_project_id'], $data);

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error add project : ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('Thêm dự án thất bại'),
                '__message' => $e->getMessage()
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

            if ($project['manage_project_status_id'] != 6 && $params['manage_project_status_id'] == 6) {
                $params['date_finish'] = Carbon::now();
            }

            if (isset($params['budget'])) {
                $params['budget'] = str_replace(",", "", $params['budget']);
            }

            if (isset($params['resource'])) {
                $params['resource'] = str_replace(",", "", $params['resource']);
            }

            if ($project) {

                $oldManager = (int)$project->manager_id;
                $newManager = (int)$params['manager_id'];

                $dataProject = [
                    'manage_project_name' => strip_tags($params['manage_project_name']),
                    'contract_id' => isset($params['contract_id']) ? $params['contract_id'] : null,
                    'contract_code' => isset($params['contract_code']) ? $params['contract_code'] : null,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                    'progress' => isset($params['progress']) ? $params['progress'] : 0,
                    'department_id' => $params['department_id'],
                    'manager_id' => $params['manager_id'],

                    'date_start' => $params['date_start'],
                    'date_end' => $params['date_end'],
                    'customer_type' => isset($params['customer_type']) ? $params['customer_type'] : null,
                    'customer_id' => isset($params['customer_id']) ? $params['customer_id'] : null,
                    'color_code' => isset($params['color_code']) ? $params['color_code'] : null,
                    'permission' => isset($params['permission']) ? $params['permission'] : null,
                    'manage_project_describe' => isset($params['manage_project_describe']) ? $params['manage_project_describe'] : null,
                    'manage_project_status_id' => isset($params['manage_project_status_id']) ? $params['manage_project_status_id'] : null,
                    'resource' => isset($params['resource']) ? $params['resource'] : null,
                    'budget' => isset($params['budget']) ? $params['budget'] : null,
                    'is_important' => isset($params['is_important']) ? $params['is_important'] : 0,
                ];

                // Cập nhật dự án
                $project->update($dataProject);

                //co thay doi manager
                if($oldManager != $newManager){
                    //xoa manager cu
                    //add manager moi
                    $mManageProjectRole = app()->get(ManageProjectRoleTable::class);
                    $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

                    $mManageProjectStaff->deleteStaff($project->manage_project_id, $oldManager);
                    $mManageProjectStaff->deleteStaff($project->manage_project_id, $newManager);

                    $roleAdminProject = $mManageProjectRole->getInfoAdmin('administration');
                    $dataRoleAdmin = [
                        'manage_project_id' => $project['manage_project_id'],
                        'staff_id' => $newManager,
                        'manage_project_role_id' => $roleAdminProject['manage_project_role_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ];
                    $mManageProjectStaff->insertStaff($dataRoleAdmin);

                    $roleMemberProject = $mManageProjectRole->getInfoAdmin('member');
                    $dataRoleMember = [
                        'manage_project_id' => $project['manage_project_id'],
                        'staff_id' => $oldManager,
                        'manage_project_role_id' => $roleMemberProject['manage_project_role_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ];
                    $mManageProjectStaff->insertStaff($dataRoleMember);
                }

//                Xóa các liên hệ
                $mManageProjectContact = app()->get(ManageProjectContactTable::class);

                $mManageProjectContact->removeContact($params['manage_project_id']);

//                Thêm lại danh sách liên hệ mới
                $dataContact = [];
                if (isset($params['contact']) && count($params['contact']) != 0) {
                    foreach ($params['contact'] as $item) {
                        $dataContact[] = [
                            'manage_project_id' => $params['manage_project_id'],
                            'name' => $item['name'],
                            'phone' => $item['phone'],
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                    }
                }

                if (count($dataContact) != 0) {
                    $mManageProjectContact->insertArrayData($dataContact);
                }

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

            $this->checkDataCreateHistory($params['manage_project_id'], [], $params, $project);

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
        $listColumShowConfig = $this->getColumShowConfig();
        // danh sách cột hiển thị tìm kiếm 
        $listColumnSearchConfig = $this->getColumSearchConfig();
        // danh sách côt hiển thị tìm kiếm mặc định
        $listColumSearchDefault = $this->listColumPrjectDeafault();
        // danh sách cột hiển thị mặc định
        $listColumShowDefault = $this->listColumProjectShowDefault();

        // de lai nhu cu cho no chay :))
//        if(!count($listColumShowConfig)){
//            $listColumShowConfig = $listColumShowDefault;
//        }
//
//        if(!count($listColumnSearchConfig)){
//            $listColumnSearchConfig = $listColumSearchDefault;
//        }
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
        if (count($listStaffDepartment) != 0) {
            $listStaffDepartment = collect($listStaffDepartment)->groupBy('department_id');
        }


        $project->listStaffs = $listStaffs;
        // tổng số giờ làm việc //
        $totalDayWorkProject = $this->cacTotalHourWork($project->work()->whereNotNull("date_finish")->get());
        $project->totalWorkTime = $totalDayWorkProject;
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
        if (count($listStaffDepartment) != 0) {
            $listStaffDepartment = collect($listStaffDepartment)->groupBy('department_id');
        }

        $project->listStaffs = $listStaffs;

//        Lấy danh sách công việc theo dự án

        $listWork = $mManageWork->getListWorkByProjectId($idProject);
        $listWorkTmp = [];
        $listWorkDepartment = [];
        if (count($listWork) != 0) {
            $listWorkTmp = collect($listWork)->groupBy('manage_status_id');
            $listWorkDepartment = collect($listWork)->groupBy('department_id');
        }

        $project->totalWork = count($listWork);

//        Danh sách công việc quá hạn
        $listWorkOverdue = $mManageWork->getListOverdue(['staff_manage_project_id' => $idProject, 'status_overdue' => true]);

        $project->totaWorkOverdue = count($listWorkOverdue);

        if (count($listWorkOverdue) != 0) {
            $listWorkOverdue = collect($listWorkOverdue)->groupBy('department_id');
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

        foreach ($listStatus as $item) {
            $dataShow[$n]['status'][$item['manage_status_id']] = isset($listWorkTmp[$item['manage_status_id']]) ? count($listWorkTmp[$item['manage_status_id']]) : 0;
        }
        $n = 1;

//        thêm theo danh sách phòng ban
        foreach ($listStaffDepartment as $key => $item) {
            $dataShow[$n] = [
                'department_name' => $item[0]['department_name'],
                'department_id' => $key,
                'total_staff' => isset($listStaffDepartment[$key]) ? count($listStaffDepartment[$key]) : 0,
                'total_work' => isset($listWorkDepartment[$key]) ? count($listWorkDepartment[$key]) : 0,
                'total_overdue' => isset($listWorkOverdue[$key]) ? count($listWorkOverdue[$key]) : 0,
            ];

            foreach ($listStatus as $itemStatus) {
                $dataShow[$n]['status'][$itemStatus['manage_status_id']] = isset($listWorkDepartment[$key]) ? collect($listWorkDepartment[$key])->where('manage_status_id', $itemStatus['manage_status_id'])->count() : 0;
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
                $totalWork += $totalItemWork;
            }
        }
        return $totalWork;
    }

    public function checkDataCreateHistory($manage_project_id, $dataInsert = [], $dataNew = [], $dataOld = null)
    {
        if (count($dataInsert) != 0) {
            $this->createHistoryProject($dataInsert);
            return true;
        }

        if (count($dataNew) != 0 && isset($dataOld)) {
//            Tên dự án
            if (isset($dataNew['manage_project_name']) && $dataNew['manage_project_name'] != $dataOld['manage_project_name']) {
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataNew['manage_project_name'],
                    'old' => $dataOld['manage_project_name'],
                    'key' => 'manage_project_name'
                ];
                $this->createHistoryProject($data);
            }
//            Khách hàng
            if (isset($dataNew['customer_id']) && $dataNew['customer_id'] != $dataOld['customer_id']) {
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
            if (isset($dataNew['manage_project_status_id']) && $dataNew['manage_project_status_id'] != $dataOld['manage_project_status_id']) {
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
            if (isset($dataNew['permission']) && $dataNew['permission'] != $dataOld['permission']) {
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataOld['permission'] == 'private' ? __('Nội bộ') : __('Công khai'),
                    'old' => $dataNew['permission'] == 'private' ? __('Nội bộ') : __('Công khai'),
                    'key' => 'permission'
                ];
                $this->createHistoryProject($data);
            }

//            Nội dung dự án

            if (isset($dataNew['manage_project_describe']) && $dataNew['manage_project_describe'] != $dataOld['manage_project_describe']) {
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'key' => 'describe'
                ];
                $this->createHistoryProject($data);
            }

//            Ngày bắt đầu
            if (isset($dataNew['date_start']) && $dataNew['date_start'] != $dataOld['date_start']) {
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataOld['date_start'],
                    'old' => $dataNew['date_start'],
                    'key' => 'date_start'
                ];
                $this->createHistoryProject($data);
            }

//            Ngày kết thúc
            if (isset($dataNew['date_end']) && $dataNew['date_end'] != $dataOld['date_end']) {
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
    public function createHistoryProject($data)
    {
//        $data['manage_project_id'] : Id Dự án
//        $data['old'] : Giá trị cũ
//        $data['new'] : Giá trị mới
//        $data['key'] : khóa để check . status : trạng thái, manage_id : người quản lý
        $message = '';
        switch ($data['key']) {
//        Cập nhật nội dung nhiều giá trị
            case 'update' :
                $message = __('đã cập nhật dự án');
                break;
            case 'created' :
                $message = __('đã tạo dự án :new', ['new' => $data['new']]);
                break;
            case 'manage_project_name' :
                $message = __('đã cập nhật tiêu đề từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'tag' :
                $message = __('đã cập nhật tag dự án');
                break;
            case 'customer' :
                $message = __('đã cập nhật khách hàng từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                if ($data['old'] == '') {
                    $message = __('đã cập nhật khách hàng thành :new', ['old' => $data['old'], 'new' => $data['new']]);
                }
                break;
            case 'status' :
                $message = __('đã cập nhật trạng thái từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'permission' :
                $message = __('đã cập nhật quyền truy cập từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'add_document' :
                $message = __('đã thêm tài liệu :new', ['new' => $data['new']]);
                break;
            case 'delete_document' :
                $message = __('đã xóa tài liệu :new', ['new' => $data['new']]);
                break;
            case 'describe' :
                $message = __('đã thay đổi nội dung dự án');
                break;
            case 'date_start' :
                $message = __('đã cập nhật ngày bắt đầu từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'date_end' :
                $message = __('đã cập nhật ngày kết thúc từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'staff' :
                $message = __('đã cập nhật danh sách thành viên');
                break;
            case 'staff_edit' :
                $message = __('đã cập nhật thành viên :old', ['old' => $data['old']]);
                break;
            case 'staff_delete' :
                $message = __('đã xóa thành viên :old', ['old' => $data['old']]);
                break;
            case 'document' :
                $message = __('đã cập nhật danh sách tài liệu');
                break;
            case 'document_delete' :
                $message = __('đã xóa tài liệu :old', ['old' => $data['old']]);
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

    public function getDepartment()
    {
        $mDepartment = app()->get(DepartmentTable::class);
        $data = $mDepartment->getOption()->toArray();
        return $data;
    }

    public function getBranch()
    {
        $mBranch = app()->get(BranchTable::class);
        $data = $mBranch->getAll();
        return $data;
    }

    public function deleteRemind($input)
    {
        $mRemind = app()->get(ManageRemindTable::class);
        $data = $mRemind->deleteRemind($input['data']['manage_remind_id']);
        if (empty($data) || !isset($data)) {
            return [
                'error' => true,
                'message' => 'Xóa nhắc nhở thất bại.'
            ];

        } else {
            return [
                'error' => false,
                'message' => 'Xóa nhắc nhở thành công.'
            ];
        }
    }

    public function getProjectInfo($id)
    {
        $input['manage_project_id'] = $id;
        $mProjectInfo = app()->get(ProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $mCustomer = app()->get(Customers::class);
        $staff = app()->get(StaffsTable::class);
        $mTags = app()->get(ProjectTagTable::class);
        $mDocument = app()->get(ManageProjectDocumentTable::class);
        $memberProject = app()->get(ProjectStaffTable::class);
        $mManageRemind = app()->get(ManageRemindTable::class);
        $mIssue = app()->get(ProjectIssueTable::class);
        $mStatus = app()->get(ManageStatusTable::class);

        $data = $mProjectInfo->projectInfo($input);
        $filter['manage_project_id'] = $data['project_id'];
        $listWork = $mWork->getAllWork($filter);
        //tinh do rui ro
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        $data['risk'] = 'normal';
        $listWorkByIdProject = $listWork;
        if(isset($listWorkByIdProject)){
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
            $totalWorkk = count($listWorkByIdProject);
            $totalWorkCompletee = collect($listWorkByIdProject)->where('status_id' , 6)->count();
            $totalTimeWork = collect($listWorkByIdProject)->sum('time_work');
            $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee/$totalWorkk*100 : 0;
            $ratioTimeWork  = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork/$data['resource']*100 : 0;
            $ratio = $ratioTimeWork - $ratioWork;
            if($ratio < 0){
                $data['risk'] = 'low';
            }elseif($ratio > 20){
                $data['risk'] = 'high';
            }else{
                $data['risk'] = 'normal';
            }
        }


        ///nguồn lực(tính ra ngay)
        if (isset($listWork)) {
            $data['resource_implement'] = 0;
            foreach ($listWork as $k => $item) {

                $a = $item['date_start'];
                $b = $item['date_end'];
                $c = $item['date_finish'];
                if($c == null) {
                    $first_date = strtotime($a);
                    $second_date = strtotime($b);

                }else{
                    $first_date = strtotime($a);
                    $second_date = strtotime($c);
                }
                if($item['time'] != null && $item['time_type'] == 'h'){
                    $datediff = $item['time'] / 24;
                }elseif($item['time'] != null && $item['time_type'] == 'd'){
                    $datediff = $item['time'];
                }else{
                    $datediff = 0;
                }
//                $datediff = abs($first_date - $second_date);
//                $item['time_work'] = $datediff / (60 * 60);
                $item['time_work'] = $datediff / 8;
                $listWork[$k] = $item;
            }
            $data['resource_implement'] = round(collect($listWork)->where('status_id','=',6)->sum('time_work'),1);
        } else {
            $data['resource_implement'] = 0;
        }

        //mức độ quan trọng
        if ($data['is_important'] == 1) {
            $data['important_name'] = "Quan trọng";
        } else {
            $data['important_name'] = "Bình thường";
        }
        //tình trạng dự án
        $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
        if (count($arrDateEndWork) > 0) {
            if (in_array(null, $arrDateEndWork)) {
                $maxDateEndWork = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
            }
            if ($maxDateEndWork > $data['to_date']) {

                $data['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn")
                ];
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
        } else {
            $data['condition'] = [
                'condition_color' => "#87CEFF",
                'condition_name' => __("Bình thường")
            ];
        }

        //ngày trễ hạn
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        if ($data['to_date'] != null && $data['date_finish'] != null) {
            $dateEnd = Carbon::parse($data['to_date']);
            $dateFinish = Carbon::parse($data['date_finish']);
            if ($dateEnd < $dateFinish) {
                $data['date_late'] = $dateEnd->diffInDays($dateFinish);
            } else {
                $data['date_late'] = $dateEnd->diffInDays($now);
            }
        } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
            $dateEnd = Carbon::parse($data['to_date']);
            $data['date_late'] = $dateEnd->diffInDays($now);
        } else {
            $data['date_late'] = 0;
        }
        $totalWork = $mWork->getTotalWork($input);
        $totalWorkComplete = $mWork->getTotalWorkComplete($input);

        $numberTotalWork = 0;
        $numberTotalComplete = 0;

        if ($totalWork != null && $totalWork != []) {
            $numberTotalWork = $totalWork[0]['total'];
        }

        if ($totalWorkComplete != null && $totalWorkComplete != []) {
            $numberTotalComplete = $totalWorkComplete[0]['total'];
        }

        $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : ceil($numberTotalComplete * 100 / $numberTotalWork);

        //thông tin khách hàng
        if (isset($data['customer_id']) && $data['customer_id'] != null) {
            $infoCustomer = $mCustomer->getCustomerAll($data);
            if (!empty($infoCustomer)) {
                $data['customer'] = [$infoCustomer[0]];
            } else {
                $data['customer'] = [];
            }
        } else {
            $data['customer'] = [];
        }

        unset($data['customer_id']);
        //thông tin người quản trị
        if (isset($data['manager_id']) && $data['manager_id'] != null) {
            $filter['arrIdManager'] = explode(',', $data['manager_id']);
            $infoManager = $staff->getInfoManager($filter);
            if (!empty($infoManager)) {
                $data['manager'] = $infoManager;
            } else {
                $data['manager'] = [];
            }
        } else {
            $data['manager'] = [];
        }
        unset($data['manager_id']);
        //thong tin nguoi tao
        $infoCreator = $staff->getInfoManager($data);
        if (count($infoCreator) > 0 && !empty($infoManager)) {
            $data['creator'] = [$infoCreator[0]];
        } else {
            $data['creator'] = [];
        }

        //danh sach tag
        $data['tag'] = $mTags->getTagProject($input);

        //so luong tai lieu
        $numberDocument = $mDocument->getNumberDocument($input);
        $data['document'] = $numberDocument ? $numberDocument[0]['total'] : 0;

        //thanh vien du an
        $totalMember = $memberProject->getMemberProject($input);
        $data['member'] = $totalMember ? $totalMember[0]['total'] : 0;

        //so luong cong viec
        $data['work'] = $totalWork ? $totalWork[0]['total'] : 0;

        //danh sách nhắc nhở
        $listRemind = $mManageRemind->getListRemindProject($input);
        foreach ($listRemind as $k => $v) {
            $now = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $dateRemind = strtotime($v['date_remind']);
            if ($dateRemind > $now) {
                $timeRemainng = abs($now - $dateRemind) / (60 * 60 * 24);
                $listRemind[$k]['time_remainng'] = 'Còn lại ' . floor($timeRemainng) . ' ngày';
            } else {
                $timeRemainng = abs($now - $dateRemind) / (60 * 60 * 24);
                $listRemind[$k]['time_remainng'] = 'Quá hạn ' . floor($timeRemainng) . ' ngày';
            }
        }
        $data['remind'] = $listRemind ? $listRemind : [];
        //danh sách vấn đề
        $listIssue = $mIssue->listIssue($input);
        $data['issue'] = $listIssue ? $listIssue : [];
        //data table Tóm tắt
        $listStatus = $mStatus->getAll();
        $listStaffProject = $memberProject->getStaffProject($input);
        $listWorkGroupByDepartment = collect($listWork)->groupBy('department_name');

        $listDepartmentByStaff = array_values(array_unique(collect($listStaffProject)->where('department_name','<>',null)->pluck('department_name')->toArray()));
        $listDepartmentByWork = array_values(array_unique(collect($listWork)->pluck('department_name')->toArray()));
        $listProjectKeyDepartmentName = collect($listStaffProject)->keyBy('department_name');
        foreach ($listDepartmentByStaff as $k => $v) {
            if (!in_array($v, $listDepartmentByWork)) {
                $listWorkGroupByDepartment[$v] = [];
            }
        }
        $data['summary'] = [
            'listStatus' => $listStatus,
            'listWorkGroupByDepartment' => $listWorkGroupByDepartment,
            'listStaffProject' => $listStaffProject,
            'listProjectKeyDepartmentName' => $listProjectKeyDepartmentName
        ];
        return $data;
    }

    public function getAllIssueProject($id)
    {
        //danh sách vấn đề
        $mIssue = app()->get(ProjectIssueTable::class);
        $input['manage_project_id'] = $id;
        $listIssue = $mIssue->listIssue($input);
        return $listIssue;
    }

    public function projectInfoReport($id)
    {
        $input['manage_project_id'] = $id;
        $mProjectInfo = app()->get(ProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $staff = app()->get(StaffsTable::class);
        $mTags = app()->get(ProjectTagTable::class);
        $memberProject = app()->get(ProjectStaffTable::class);
        $mPhase = app()->get(ProjectPhaseTable::class);
        $mStatus = app()->get(ManageProjectStatusTable::class);
        $mWorkStatus = app()->get(ManageStatusTable::class);

        $data = $mProjectInfo->projectInfo($input);

        $filter['manage_project_id'] = $data['project_id'];
        $listWork = $mWork->getAllWork($filter);
        //tinh do rui ro
        $data['risk'] = 'normal';
        $listWorkByIdProject = $listWork;
        $data['resource_implement'] = 0;
        if(isset($listWorkByIdProject)){
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
//                $datediff = abs($first_date - $second_date);
                if($val['time'] != null && $val['time_type'] == 'h'){
                    $datediff = $val['time'] / 24;
                }elseif($val['time'] != null && $val['time_type'] == 'd'){
                    $datediff = $val['time'];
                }else{
                    $datediff = 0;
                }

//                $val['time_work'] = $datediff / (60 * 60 * 24);
                $val['time_work'] = $datediff / 8;
                $listWorkByIdProject[$key] = $val;
            }
            $data['resource_implement'] = round(collect($listWorkByIdProject)->where('status_id','=',6)->sum('time_work'),1);
            $totalWorkk = count($listWorkByIdProject);
            $totalWorkCompletee = collect($listWorkByIdProject)->where('status_id' , 6)->count();
            $totalTimeWork = collect($listWorkByIdProject)->sum('time_work');
            $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee/$totalWorkk*100 : 0;
            $ratioTimeWork  = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork/$data['resource']*100 : 0;
            $ratio = $ratioTimeWork - $ratioWork;
            if($ratio < 0){
                $data['risk'] = 'low';
            }elseif($ratio > 20){
                $data['risk'] = 'high';
            }else{
                $data['risk'] = 'normal';
            }
        }
        ///nguồn lực(tính ra giờ)
        $workCompleteOnTime = 0;
        $workCompleteLate = 0;
        $workOutOfDate = 0;
        if (isset($listWork)) {
            foreach ($listWork as $k => $item) {
                //tính hạn công việc
                $dateStartWork = strtotime($item['date_start']);
                $dateEndWork = strtotime($item['date_end']);
                $now = strtotime(Carbon::parse(Carbon::parse(now())->format('Y-m-d H:i:s')));
                $dateFinishWork = isset($item['date_finish']) && $item['date_finish'] != null ? strtotime($item['date_finish']) : null;

                if ($dateFinishWork != null) {
                    if ($dateFinishWork < $dateEndWork) {
                        $workCompleteOnTime += 1;
                    } elseif ($dateFinishWork > $dateEndWork) {
                        $workCompleteLate += 1;
                    }
                } elseif($dateFinishWork == null && $now > $dateEndWork) {
                    $workOutOfDate += 1;
                }
                //tính thời gian thực hiện công việc
                $dateStartWork = strtotime($item['date_start']);
                if ($dateFinishWork != null) {
                    $implementTime = ceil(abs($dateFinishWork - $dateStartWork) / (60 * 60));
                    if ($dateFinishWork > $dateEndWork) {
                        $typeTimeWork = 'late';
                    } else {
                        $typeTimeWork = 'onTime';
                    }

                } else {
                    $implementTime = 0;
                    $typeTimeWork = 'other';
                }

                $item['implement_time'] = $implementTime;
                $item['type_time_work'] = $typeTimeWork;
                $listWork[$k] = $item;
            }
        } else {

        }
        //mức độ quan trọng
        if ($data['is_important'] == 1) {
            $data['important_name'] = "Quan trọng";
        } else {
            $data['important_name'] = "Bình thường";
        }
        //tình trạng dự án
        $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
        if (count($arrDateEndWork) > 0) {
            if (in_array(null, $arrDateEndWork)) {
                $maxDateEndWork = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
            }
            if ($maxDateEndWork > $data['to_date']) {

                $data['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn")
                ];
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
        } else {
            $data['condition'] = [
                'condition_color' => "#87CEFF",
                'condition_name' => __("Bình thường")
            ];
        }

        //ngày trễ hạn
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        if ($data['to_date'] != null && $data['date_finish'] != null) {
            $dateEnd = Carbon::parse($data['to_date']);
            $dateFinish = Carbon::parse($data['date_finish']);
            if ($dateEnd < $dateFinish) {
                $data['date_late'] = $dateEnd->diffInDays($dateFinish);
            } else {
                $data['date_late'] = $dateEnd->diffInDays($now);
            }
        } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
            $dateEnd = Carbon::parse($data['to_date']);
            $data['date_late'] = $dateEnd->diffInDays($now);
        } else {
            $data['date_late'] = 0;
        }
        $totalWork = $mWork->getTotalWork($input);
        $totalWorkComplete = $mWork->getTotalWorkComplete($input);

        $numberTotalWork = 0;
        $numberTotalComplete = 0;

        if ($totalWork != null && $totalWork != []) {
            $numberTotalWork = $totalWork[0]['total'];
        }

        if ($totalWorkComplete != null && $totalWorkComplete != []) {
            $numberTotalComplete = $totalWorkComplete[0]['total'];
        }

        $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : ceil($numberTotalComplete * 100 / $numberTotalWork);
        //so luong cong viec theo tiến độ
        $data['work-duration'] = $totalWork ? [
            'totalWork' => $totalWork[0]['total'],
            'workCompleteOnTime' => $workCompleteOnTime,
            'workCompleteLate' => $workCompleteLate,
            'workOutOfDate' => $workOutOfDate,
        ] : [];
        //thông tin người quản trị
        if (isset($data['manager_id']) && $data['manager_id'] != null) {
            $filter['arrIdManager'] = explode(',', $data['manager_id']);
            $infoManager = $staff->getInfoManager($filter);
            if (!empty($infoManager)) {
                $data['manager'] = $infoManager;
            } else {
                $data['manager'] = [];
            }
        } else {
            $data['manager'] = [];
        }
        unset($data['manager_id']);
        //danh sach tag
        $data['tag'] = $mTags->getTagProject($input);

        //chart giai đoạn
        $listStatus = $mStatus->getAll()->toArray();
        $listPhase = $mPhase->getPhaseNoPage($input);
        if (isset($listPhase) && count($listPhase) > 0) {
            $arrPhaseId = collect($listPhase)->pluck('manage_project_phase_id')->toArray();
            $filter = [
                'arrPhaseId' => $arrPhaseId,
                'manage_project_id' => $input['manage_project_id']
            ];
            //danh sách công việc theo giai đoạn
            $listWorkByPhase = $mWork->getAllWork($filter);
            $dataChartPhase = [];
            $phaseName = [];
            foreach ($listPhase as $key => $value) {
                $dataChartPhase[$key]['phase'] = $value;
                $phaseName[$key] = $value['phase_name'];
                $seriesChart = [];
                foreach ($listStatus as $keyStatus => $valueStatus) {
                    $dataChartPhase[$key]['status_project'][$keyStatus] = $valueStatus;
                    $dataChartPhase[$key]['status_project'][$keyStatus]['total_work'] = collect($listWorkByPhase)
                        ->where('status_id', $valueStatus['manage_project_status_id'])
                        ->where('phase_id', $value['manage_project_phase_id'])
                        ->count();
                    $seriesChart[]['name'] = $valueStatus['manage_project_status_name'];
                }
            }
            $listPhaseName = collect($listPhase)->pluck('phase_name')->toArray();

            foreach ($seriesChart as $keySeriesChart => $valueSeriesChart) {
                $valueSeriesChart['data'] = [];
                $seriesChart[$keySeriesChart] = $valueSeriesChart;
            }
            $seriesChart = collect($seriesChart)->keyBy('name')->toArray();
            $statusProject = collect($dataChartPhase)->pluck('status_project')->toArray();
            foreach ($statusProject as $a => $b) {
                foreach ($b as $k => $v) {
                    if (isset($seriesChart[$v['manage_project_status_name']])) {
                        $seriesChart[$v['manage_project_status_name']]['data'][] = $v['total_work'];
                    }
                }
            }
            $seriesChart = array_values($seriesChart);
            $chartPhase = [
                'categories' => $listPhaseName,
                'series' => $seriesChart,
            ];
        } else {
            $chartPhase = [];
        }
        $data['chart_phase'] = $chartPhase;
        //phiếu thu-chi
        $mExpenditure = app()->get(ProjectExpenditureTable::class);
        $listExpenditure = $mExpenditure->getListExpenditure($input);
        $listExpenditureGroupByType = collect($listExpenditure)->groupBy('type')->toArray();
        //lấy amount phiếu thu
        $arrIdReceipt = count($listExpenditureGroupByType) > 0 && isset($listExpenditureGroupByType['receipt']) ?  collect($listExpenditureGroupByType['receipt'])->pluck('obj_id')->toArray() : [];
        $mReceipt = app()->get(ReceiptTable::class);
        if ($arrIdReceipt != []) {
            $input['arrIdReceipt'] = $arrIdReceipt;
            $dataReceipt = $mReceipt->getListReceipt($input);
            $totalReceipt = collect($dataReceipt)->sum('total_money');
        } else {
            $totalReceipt = 0;
        }

        //lấy amount phiếu chi
        $arrIdPayment = count($listExpenditureGroupByType) > 0 && isset($listExpenditureGroupByType['payment']) ? collect($listExpenditureGroupByType['payment'])->pluck('obj_id')->toArray() : [];
        $mPayment = app()->get(PaymentTable::class);
        if ($arrIdPayment != []) {
            $input['arrIdPayment'] = $arrIdPayment;
            $dataPayment = $mPayment->getListPayment($input);
            $totalPayment = collect($dataPayment)->sum('total_money');
        } else {
            $totalPayment = 0;
        }
        if (($data['budget'] != null && $data['budget'] != 0) || $totalReceipt != 0 || $totalPayment != 0) {
            $data['chart_budget'] = [
                [
                    'name' => 'Ngân sách',
                    'y' => $data['budget'] != null && $data['budget'] != 0 ? $data['budget'] : 0
                ],
                [
                    'name' => 'Thu',
                    'y' => $totalReceipt
                ],
                [
                    'name' => 'Chi',
                    'y' => $totalPayment
                ]
            ];
        } else {
            $data['chart_budget'] = [];
        }


        //thành viên dự án
        $listStaffProject = $memberProject->getStaffProject($input);
        $datachartMember = [];
        if (isset($listStaffProject) && count($listStaffProject) > 0) {
            $listStaffProjectGroupByDepartment = collect($listStaffProject)->groupBy('department_name')->toArray();
            $listDepartment = array_unique(collect($listStaffProject)->pluck('department_name')->toArray());
            foreach ($listDepartment as $key => $val) {
                $datachartMember[] = [
                    'name' => $val,
                    'y' => isset($listStaffProjectGroupByDepartment[$val]) ? count($listStaffProjectGroupByDepartment[$val]) : 0,
                    'z' => 50
                ];
            }
        }

        $data['chart_member'] = $datachartMember;
        //data table Tóm tắt
        $listWorkGroupByDepartment = collect($listWork)->groupBy('department_name');
        $listDepartmentByStaff = array_values(array_unique(collect($listStaffProject)->pluck('department_name')->toArray()));
        $listDepartmentByWork = array_values(array_unique(collect($listWork)->pluck('department_name')->toArray()));
        foreach ($listDepartmentByStaff as $k => $v) {
            if (!in_array($v, $listDepartmentByWork)) {
                $listWorkGroupByDepartment[$v] = [];
            }
        }
        $data['summary'] = [
            'listStatusWork' => $mWorkStatus->getAll(),
            'listWorkGroupByDepartment' => $listWorkGroupByDepartment,
            'listWork' => $listWork,
        ];
        return $data;
    }

    public function projectInfoWork($id)
    {
        $input['manage_project_id'] = $id;
        $mProjectInfo = app()->get(ProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $staff = app()->get(StaffsTable::class);
        $mTags = app()->get(ProjectTagTable::class);

        $data = $mProjectInfo->projectInfo($input);

        $filter['manage_project_id'] = $data['project_id'];
        $listWork = $mWork->getAllWork($filter);
        //tinh do rui ro
        $data['risk'] = 'normal';
        $listWorkByIdProject = $listWork;
        if(isset($listWorkByIdProject)){
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
            $totalWorkk = count($listWorkByIdProject);
            $totalWorkCompletee = collect($listWorkByIdProject)->where('status_id' , 6)->count();
            $totalTimeWork = collect($listWorkByIdProject)->sum('time_work');
            $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee/$totalWorkk*100 : 0;
            $ratioTimeWork  = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork/$data['resource']*100 : 0;
            $ratio = $ratioTimeWork - $ratioWork;
            if($ratio < 0){
                $data['risk'] = 'low';
            }elseif($ratio > 20){
                $data['risk'] = 'high';
            }else{
                $data['risk'] = 'normal';
            }
        }
        ///nguồn lực(tính ra giờ)
        $workCompleteOnTime = 0;
        $workCompleteLate = 0;
        $workOutOfDate = 0;
        $data['resource_implement'] = 0;
        if (isset($listWork)) {
            foreach ($listWork as $k => $item) {
                $a = $item['date_start'];
                $b = $item['date_end'];
                $c = $item['date_finish'];
                if ($c == null) {
                    $first_date = strtotime($a);
                    $second_date = strtotime($b);

                } else {
                    $first_date = strtotime($a);
                    $second_date = strtotime($c);
                }
//                $datediff = abs($first_date - $second_date);
//                $item['time_work'] = $datediff / (60 * 60 * 24);
                if ($item['time'] != null && $item['time_type'] == 'h') {
                    $datediff = $item['time'] / 24;
                } elseif ($item['time'] != null && $item['time_type'] == 'd') {
                    $datediff = $item['time'];
                } else {
                    $datediff = 0;
                }
                $item['time_work'] = $datediff / 8;
                $listWork[$k] = $item;
                //tính hạn công việc
                $dateEndWork = strtotime($item['date_end']);
                if (isset($item['date_finish']) && $item['date_finish'] != null) {
                    $dateFinishWork = strtotime($item['date_finish']);
                } else {
                    $dateFinishWork = $item['date_finish'];
                }
                if ($dateFinishWork != null) {
                    if ($dateFinishWork < $dateEndWork) {
                        $workCompleteOnTime += 1;
                    } elseif ($dateFinishWork > $dateEndWork) {
                        $workCompleteLate += 1;
                    }
                } else {
                    $workOutOfDate += 1;
                }
                //tính thời gian thực hiện công việc
                $dateStartWork = strtotime($item['date_start']);
                if ($dateFinishWork != null) {
//                    $implementTime = ceil(abs($dateFinishWork - $dateStartWork) / (60 * 60));
                    if ($dateFinishWork > $dateEndWork) {
                        $typeTimeWork = 'late';
                    } else {
                        $typeTimeWork = 'onTime';
                    }

                } else {
                    $implementTime = 0;
                    $typeTimeWork = 'other';
                }

//                $item['implement_time'] = $implementTime;
                $item['type_time_work'] = $typeTimeWork;
                $listWork[$k] = $item;
            }
            $data['resource_implement'] = round(collect($listWork)->where('status_id','=',6)->sum('time_work'),1);

        } else {
        }
        //mức độ quan trọng
        if ($data['is_important'] == 1) {
            $data['important_name'] = "Quan trọng";
        } else {
            $data['important_name'] = "Bình thường";
        }
        //tình trạng dự án
        $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
        if (count($arrDateEndWork) > 0) {
            if (in_array(null, $arrDateEndWork)) {
                $maxDateEndWork = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
            }
            if ($maxDateEndWork > $data['to_date']) {

                $data['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn")
                ];
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
        } else {
            $data['condition'] = [
                'condition_color' => "#87CEFF",
                'condition_name' => __("Bình thường")
            ];
        }

        //ngày trễ hạn
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        if ($data['to_date'] != null && $data['date_finish'] != null) {
            $dateEnd = Carbon::parse($data['to_date']);
            $dateFinish = Carbon::parse($data['date_finish']);
            if ($dateEnd < $dateFinish) {
                $data['date_late'] = $dateEnd->diffInDays($dateFinish);
            } else {
                $data['date_late'] = $dateEnd->diffInDays($now);
            }
        } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
            $dateEnd = Carbon::parse($data['to_date']);
            $data['date_late'] = $dateEnd->diffInDays($now);
        } else {
            $data['date_late'] = 0;
        }
        $totalWork = $mWork->getTotalWork($input);
        $totalWorkComplete = $mWork->getTotalWorkComplete($input);

        $numberTotalWork = 0;
        $numberTotalComplete = 0;

        if ($totalWork != null && $totalWork != []) {
            $numberTotalWork = $totalWork[0]['total'];
        }

        if ($totalWorkComplete != null && $totalWorkComplete != []) {
            $numberTotalComplete = $totalWorkComplete[0]['total'];
        }

        $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : ceil($numberTotalComplete * 100 / $numberTotalWork);
        //so luong cong viec theo tiến độ
        $data['work-duration'] = $totalWork ? [
            'totalWork' => $totalWork[0]['total'],
            'workCompleteOnTime' => $workCompleteOnTime,
            'workCompleteLate' => $workCompleteLate,
            'workOutOfDate' => $workOutOfDate,
        ] : [];
        //thông tin người quản trị
        if (isset($data['manager_id']) && $data['manager_id'] != null) {
            $filter['arrIdManager'] = explode(',', $data['manager_id']);
            $infoManager = $staff->getInfoManager($filter);
            if (!empty($infoManager)) {
                $data['manager'] = $infoManager;
            } else {
                $data['manager'] = [];
            }
        } else {
            $data['manager'] = [];
        }
        unset($data['manager_id']);
        //danh sach tag
        $data['tag'] = $mTags->getTagProject($input);
        //danh sách giai đoạn
        $mWork = app()->get(WorkTable::class);
        $mPhaseProject = app()->get(ProjectPhaseTable ::class);

        $dataWork = $mWork->getAllWork($input);
        $listPhase = $mPhaseProject->getPhase($input);
        $dataWork = collect($dataWork)->groupBy('phase_name')->toArray();
        foreach ($listPhase as $key => $value) {
            if (isset($dataWork[$value['phase_name']])) {
                $listPhase[$key]['work_list'] = $dataWork[$value['phase_name']];
            } else {
                $listPhase[$key]['work_list'] = [];
            }
        }
        $data['workByPhase'] = $listPhase;

        return $data;
    }

    public function getInfoPhase($id, $param)
    {
        $input['manage_project_id'] = $id;
        $mProjectInfo = app()->get(ProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $staff = app()->get(StaffsTable::class);
        $mTags = app()->get(ProjectTagTable::class);
        $mPhase = app()->get(ProjectPhaseTable::class);

        $data = $mProjectInfo->projectInfo($input);

        $filter['manage_project_id'] = $data['project_id'];
        $listWork = $mWork->getAllWork($filter);
        //tinh do rui ro
        $data['risk'] = 'normal';
        $listWorkByIdProject = $listWork;
        if(isset($listWorkByIdProject)){
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
            $totalWorkk = count($listWorkByIdProject);
            $totalWorkCompletee = collect($listWorkByIdProject)->where('status_id' , 6)->count();
            $totalTimeWork = collect($listWorkByIdProject)->sum('time_work');
            $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee/$totalWorkk*100 : 0;
            $ratioTimeWork  = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork/$data['resource']*100 : 0;
            $ratio = $ratioTimeWork - $ratioWork;
            if($ratio < 0){
                $data['risk'] = 'low';
            }elseif($ratio > 20){
                $data['risk'] = 'high';
            }else{
                $data['risk'] = 'normal';
            }
        }
        ///nguồn lực(tính ra giờ)
        $workMayBeLate = 0;
        $workCompleteOnTime = 0;
        $workCompleteLate = 0;
        $workOutOfDate = 0;
        $data['resource_implement'] = 0;
        if (isset($listWork)) {
            foreach ($listWork as $k => $item) {
                $a = $item['date_start'];
                $b = $item['date_end'];
                $c = $item['date_finish'];
                if($c == null) {
                    $first_date = strtotime($a);
                    $second_date = strtotime($b);

                }else{
                    $first_date = strtotime($a);
                    $second_date = strtotime($c);
                }
//                $datediff = abs($first_date - $second_date);
//                $item['time_work'] = $datediff / (60 * 60 * 24);
//                $listWork[$k] = $item;
                if ($item['time'] != null && $item['time_type'] == 'h') {
                    $datediff = $item['time'] / 24;
                } elseif ($item['time'] != null && $item['time_type'] == 'd') {
                    $datediff = $item['time'];
                } else {
                    $datediff = 0;
                }
                $item['time_work'] = $datediff / 8;
                $listWork[$k] = $item;
                //tính hạn công việc
                $dateEndWork = strtotime($item['date_end']);
                if (isset($item['date_finish']) && $item['date_finish'] != null) {
                    $dateFinishWork = strtotime($item['date_finish']);
                } else {
                    $dateFinishWork = $item['date_finish'];
                }
                if ($dateFinishWork != null) {
                    if ($dateFinishWork < $dateEndWork) {
                        $workCompleteOnTime += 1;
                    } elseif ($dateFinishWork > $dateEndWork) {
                        $workCompleteLate += 1;
                    }
                } else {
                    $workOutOfDate += 1;
                }
                //tính thời gian thực hiện công việc
                $dateStartWork = strtotime($item['date_start']);
                if ($dateFinishWork != null) {
                    $implementTime = ceil(abs($dateFinishWork - $dateStartWork) / (60 * 60));
                    if ($dateFinishWork > $dateEndWork) {
                        $typeTimeWork = 'late';
                    } else {
                        $typeTimeWork = 'onTime';
                    }

                } else {
                    $implementTime = 0;
                    $typeTimeWork = 'other';
                }

                $item['implement_time'] = $implementTime;
                $item['type_time_work'] = $typeTimeWork;
                $listWork[$k] = $item;
            }
            $data['resource_implement'] = round(collect($listWork)->where('status_id','=',6)->sum('time_work'),1);
        } else {
        }
        //mức độ quan trọng
        if ($data['is_important'] == 1) {
            $data['important_name'] = "Quan trọng";
        } else {
            $data['important_name'] = "Bình thường";
        }
        //tình trạng dự án
        $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
        if (count($arrDateEndWork) > 0) {
            if (in_array(null, $arrDateEndWork)) {
                $maxDateEndWork = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
            }
            if ($maxDateEndWork > $data['to_date']) {

                $data['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn")
                ];
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
        } else {
            $data['condition'] = [
                'condition_color' => "#87CEFF",
                'condition_name' => __("Bình thường")
            ];
        }

        //ngày trễ hạn
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        if ($data['to_date'] != null && $data['date_finish'] != null) {
            $dateEnd = Carbon::parse($data['to_date']);
            $dateFinish = Carbon::parse($data['date_finish']);
            if ($dateEnd < $dateFinish) {
                $data['date_late'] = $dateEnd->diffInDays($dateFinish);
            } else {
                $data['date_late'] = $dateEnd->diffInDays($now);
            }
        } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
            $dateEnd = Carbon::parse($data['to_date']);
            $data['date_late'] = $dateEnd->diffInDays($now);
        } else {
            $data['date_late'] = 0;
        }
        $totalWork = $mWork->getTotalWork($input);
        $totalWorkComplete = $mWork->getTotalWorkComplete($input);

        $numberTotalWork = 0;
        $numberTotalComplete = 0;

        if ($totalWork != null && $totalWork != []) {
            $numberTotalWork = $totalWork[0]['total'];
        }

        if ($totalWorkComplete != null && $totalWorkComplete != []) {
            $numberTotalComplete = $totalWorkComplete[0]['total'];
        }

        $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : ceil($numberTotalComplete * 100 / $numberTotalWork);
        //so luong cong viec theo tiến độ
        $data['work-duration'] = $totalWork ? [
            'totalWork' => $totalWork[0]['total'],
            'workMayBeLate' => $workMayBeLate,
            'workCompleteOnTime' => $workCompleteOnTime,
            'workCompleteLate' => $workCompleteLate,
            'workOutOfDate' => $workOutOfDate,
        ] : [];
        //thông tin người quản trị
        if (isset($data['manager_id']) && $data['manager_id'] != null) {
            $filter['arrIdManager'] = explode(',', $data['manager_id']);
            $infoManager = $staff->getInfoManager($filter);
            if (!empty($infoManager)) {
                $data['manager'] = $infoManager;
            } else {
                $data['manager'] = [];
            }
        } else {
            $data['manager'] = [];
        }
        unset($data['manager_id']);
        //danh sach tag
        $data['tag'] = $mTags->getTagProject($input);
        //
        $param['manage_project_id'] = $input['manage_project_id'];
        if (isset($param['date_finish'])) {
            $dataPhase = $mPhase->getAllPhase();
            ///danh sách công việc theo giai đoạn
            $dataWorkIdByPhase = collect($listWork)->groupBy('phase_id')->toArray();
            $a = $dataWorkIdByPhase;
            ///tìm max date end theo từng giai đoạn
            foreach ($dataWorkIdByPhase as $key => $value) {
                $arrDateEnd = collect($value)->pluck('date_end')->toArray();
                if (in_array(null, $arrDateEnd)) {
                    $maxDateEnd = Carbon::now()->format('Y-m-d');
                } else {
                    $maxDateEnd = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEnd))->format('Y-m-d');
                }
                $value = $maxDateEnd;
                $dataWorkIdByPhase[$key] = $value;
            }
            foreach ($dataPhase as $k => $v) {
                $v['max_date_end_work'] = null;
                if (isset($dataWorkIdByPhase[$v['manage_project_phase_id']])) {
                    $v['max_date_end_work'] = $dataWorkIdByPhase[$v['manage_project_phase_id']];
                }
                if ($v['max_date_end_work'] > $v['phase_end']) {
                    $dateWork = strtotime($v['max_date_end_work']);
                    $datePhase = strtotime($v['phase_end']);
                    $dateLate = abs($dateWork - $datePhase) / (60 * 60 * 24);
                    $v['condition'] = [
                        'condition_color' => "#FFB6C1",
                        'condition_name' => __("Quá hạn").' '. $dateLate .' '.__("ngày")
                    ];
                } else {
                    $v['condition'] = [
                        'condition_color' => "#87CEFF",
                        'condition_name' => __("Bình thường")
                    ];
                }
                if (isset($a[$v['manage_project_phase_id']])) {
                    $v['work'] = count($a[$v['manage_project_phase_id']]);
                } else {
                    $v['work'] = 0;
                }

                $dataPhase[$k] = $v;
                $ex = explode(' - ', $param['date_finish']);
                $m = Carbon::createFromFormat('d/m/Y', $ex[0])->format('Y-m-d');
                $n = Carbon::createFromFormat('d/m/Y', $ex[1])->format('Y-m-d');
                foreach ($dataPhase as $keyPhase => $valPhase) {
                    if ($valPhase['max_date_end_work'] < $m
                        || $valPhase['max_date_end_work'] > $n
                        || $valPhase['max_date_end_work'] == null) {
                        unset($dataPhase[$keyPhase]);
                    }
                }

            }
        }
        $arrIdPhase = isset($dataPhase) && count($dataPhase) > 0 ? collect($dataPhase)->pluck('manage_project_phase_id')->toArray() : [99999999999999];
        if (isset($param['date_finish'])) {
            $param['arrIdPhase'] = $arrIdPhase;
        }
        $dataPhase = $mPhase->getPhase($param);

        ///danh sách công việc theo giai đoạn
        $dataWorkIdByPhase = collect($listWork)->groupBy('phase_id')->toArray();
        $a = $dataWorkIdByPhase;
        ///tìm max date end theo từng giai đoạn
        foreach ($dataWorkIdByPhase as $key => $value) {
            $arrDateEnd = collect($value)->pluck('date_end')->toArray();
            if (in_array(null, $arrDateEnd)) {
                $maxDateEnd = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEnd = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEnd))->format('Y-m-d');
            }
            $value = $maxDateEnd;
            $dataWorkIdByPhase[$key] = $value;
        }
        foreach ($dataPhase as $k => $v) {
            if (isset($dataWorkIdByPhase[$v['manage_project_phase_id']])) {
                $v['max_date_end_work'] = $dataWorkIdByPhase[$v['manage_project_phase_id']];
            }
            if ($v['max_date_end_work'] > $v['phase_end']) {
                $dateWork = strtotime($v['max_date_end_work']);
                $datePhase = strtotime($v['phase_end']);
                $dateLate = abs($dateWork - $datePhase) / (60 * 60 * 24);
                $v['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn").' '. $dateLate .' '.__("ngày")
                ];
            } else {
                $v['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
            if (isset($a[$v['manage_project_phase_id']])) {
                $v['work'] = count($a[$v['manage_project_phase_id']]);
            } else {
                $v['work'] = 0;
            }
            $dataPhase[$k] = $v;
        }
        //thong tin giai doan
        $data['listPhase'] = count($dataPhase) > 0 ? $dataPhase : [];
        return $data;

    }

    public function getInfoIssue($id, $param)
    {
        $input['manage_project_id'] = $id;
        $mProjectInfo = app()->get(ProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $staff = app()->get(StaffsTable::class);
        $mTags = app()->get(ProjectTagTable::class);
        $mPhase = app()->get(ProjectPhaseTable::class);

        $data = $mProjectInfo->projectInfo($input);
        $filter['manage_project_id'] = $data['project_id'];
        $param['manage_project_id'] = $id;
        $listWork = $mWork->getAllWork($filter);
        //tinh do rui ro
        $data['risk'] = 'normal';
        $listWorkByIdProject = $listWork;
        if(isset($listWorkByIdProject)){
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
            $totalWorkk = count($listWorkByIdProject);
            $totalWorkCompletee = collect($listWorkByIdProject)->where('status_id' , 6)->count();
            $totalTimeWork = collect($listWorkByIdProject)->sum('time_work');
            $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee/$totalWorkk*100 : 0;
            $ratioTimeWork  = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork/$data['resource']*100 : 0;
            $ratio = $ratioTimeWork - $ratioWork;
            if($ratio < 0){
                $data['risk'] = 'low';
            }elseif($ratio > 20){
                $data['risk'] = 'high';
            }else{
                $data['risk'] = 'normal';
            }
        }
        ///nguồn lực(tính ra giờ)
        $workMayBeLate = 0;
        $workCompleteOnTime = 0;
        $workCompleteLate = 0;
        $workOutOfDate = 0;
        $data['resource_implement'] = 0;
        if (isset($listWork)) {
            foreach ($listWork as $k => $item) {
                $a = $item['date_start'];
                $b = $item['date_end'];
                $c = $item['date_finish'];
                if($c == null) {
                    $first_date = strtotime($a);
                    $second_date = strtotime($b);

                }else{
                    $first_date = strtotime($a);
                    $second_date = strtotime($c);
                }
//                $datediff = abs($first_date - $second_date);
//                $item['time_work'] = $datediff / (60 * 60 * 24);
//                $listWork[$k] = $item;
                if ($item['time'] != null && $item['time_type'] == 'h') {
                    $datediff = $item['time'] / 24;
                } elseif ($item['time'] != null && $item['time_type'] == 'd') {
                    $datediff = $item['time'];
                } else {
                    $datediff = 0;
                }
                $item['time_work'] = $datediff / 8;
                $listWork[$k] = $item;
                //tính hạn công việc
                $dateEndWork = strtotime($item['date_end']);
                if (isset($item['date_finish']) && $item['date_finish'] != null) {
                    $dateFinishWork = strtotime($item['date_finish']);
                } else {
                    $dateFinishWork = $item['date_finish'];
                }
                if ($dateFinishWork != null) {
                    if ($dateFinishWork < $dateEndWork) {
                        $workCompleteOnTime += 1;
                    } elseif ($dateFinishWork > $dateEndWork) {
                        $workCompleteLate += 1;
                    }
                } else {
                    $workOutOfDate += 1;
                }
                //tính thời gian thực hiện công việc
                $dateStartWork = strtotime($item['date_start']);
                if ($dateFinishWork != null) {
                    $implementTime = ceil(abs($dateFinishWork - $dateStartWork) / (60 * 60));
                    if ($dateFinishWork > $dateEndWork) {
                        $typeTimeWork = 'late';
                    } else {
                        $typeTimeWork = 'onTime';
                    }

                } else {
                    $implementTime = 0;
                    $typeTimeWork = 'other';
                }

                $item['implement_time'] = $implementTime;
                $item['type_time_work'] = $typeTimeWork;
                $listWork[$k] = $item;
            }
            $data['resource_implement'] = round(collect($listWork)->where('status_id','=',6)->sum('time_work'),1);
        } else {

        }
        //mức độ quan trọng
        if ($data['is_important'] == 1) {
            $data['important_name'] = "Quan trọng";
        } else {
            $data['important_name'] = "Bình thường";
        }
        //tình trạng dự án
        $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
        if (count($arrDateEndWork) > 0) {
            if (in_array(null, $arrDateEndWork)) {
                $maxDateEndWork = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
            }
            if ($maxDateEndWork > $data['to_date']) {

                $data['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn")
                ];
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
        } else {
            $data['condition'] = [
                'condition_color' => "#87CEFF",
                'condition_name' => __("Bình thường")
            ];
        }

        //ngày trễ hạn
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        if ($data['to_date'] != null && $data['date_finish'] != null) {
            $dateEnd = Carbon::parse($data['to_date']);
            $dateFinish = Carbon::parse($data['date_finish']);
            if ($dateEnd < $dateFinish) {
                $data['date_late'] = $dateEnd->diffInDays($dateFinish);
            } else {
                $data['date_late'] = $dateEnd->diffInDays($now);
            }
        } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
            $dateEnd = Carbon::parse($data['to_date']);
            $data['date_late'] = $dateEnd->diffInDays($now);
        } else {
            $data['date_late'] = 0;
        }
        $totalWork = $mWork->getTotalWork($input);
        $totalWorkComplete = $mWork->getTotalWorkComplete($input);

        $numberTotalWork = 0;
        $numberTotalComplete = 0;

        if ($totalWork != null && $totalWork != []) {
            $numberTotalWork = $totalWork[0]['total'];
        }

        if ($totalWorkComplete != null && $totalWorkComplete != []) {
            $numberTotalComplete = $totalWorkComplete[0]['total'];
        }

        $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : ceil($numberTotalComplete * 100 / $numberTotalWork);
        //so luong cong viec theo tiến độ
        $data['work-duration'] = $totalWork ? [
            'totalWork' => $totalWork[0]['total'],
            'workMayBeLate' => $workMayBeLate,
            'workCompleteOnTime' => $workCompleteOnTime,
            'workCompleteLate' => $workCompleteLate,
            'workOutOfDate' => $workOutOfDate,
        ] : [];
        //thông tin người quản trị
        if (isset($data['manager_id']) && $data['manager_id'] != null) {
            $filter['arrIdManager'] = explode(',', $data['manager_id']);
            $infoManager = $staff->getInfoManager($filter);
            if (!empty($infoManager)) {
                $data['manager'] = $infoManager;
            } else {
                $data['manager'] = [];
            }
        } else {
            $data['manager'] = [];
        }
        unset($data['manager_id']);
        //danh sach tag
        $data['tag'] = $mTags->getTagProject($input);
        //danh sach van de
        $arrIdIssue = [];
        $mIssue = app()->get(ProjectIssueTable::class);
        $dataIssue = $mIssue->listIssue($param);
        foreach ($dataIssue as $k => $v){
            $arrIdIssue[] = $v['project_issue_id'];
        }
        //danh sach van de con
        $dataIssueChild = $mIssue->listIssueChild($param);
        foreach ($dataIssueChild as $k => $v){
            $arrIdIssue[] = $v['project_issue_id'];
        }
        //danh sách công việc liên quan tới vấn đề
        $mWork = app()->get(WorkTable::class);
        $issueId['arrIdIssue'] = $arrIdIssue;
        $listWorkByIdIssue = $mWork->getAllWork($issueId);
        if ($listWorkByIdIssue != []) {
            $listWorkByIdIssueGrByIssueId = collect($listWorkByIdIssue)->groupBy('manage_project_issue_id')->toArray();
        } else {
            $listWorkByIdIssueGrByIssueId = [];
        }
        foreach ($dataIssueChild as $k => $v){
            $v['list_work'] = [];
            if(isset($listWorkByIdIssueGrByIssueId[$v['project_issue_id']])){
                $v['list_work'] = $listWorkByIdIssueGrByIssueId[$v['project_issue_id']];
            }
            $dataIssueChild[$k] = $v;
        }

        $dataIssueChildGroupByParentId = isset($dataIssueChild) && count($dataIssueChild) > 0 ? collect($dataIssueChild)->groupBy('parent_id')->toArray() : [];
        foreach ($dataIssue as $key => $val) {
            $val['list_work'] = [];
            if(isset($listWorkByIdIssueGrByIssueId[$val['project_issue_id']])){
                $val['list_work'] = $listWorkByIdIssueGrByIssueId[$val['project_issue_id']];
            }
            if (isset($dataIssueChildGroupByParentId[$val['project_issue_id']])) {
                $val['issue_child'] = $dataIssueChildGroupByParentId[$val['project_issue_id']];
            }
            $dataIssue[$key] = $val;
        }
        $data['issue'] = $dataIssue;
        return $data;
    }

    public function addIssue($input)
    {
        $mIssue = app()->get(ProjectIssueTable::class);
        $dataInsert = [
            'parent_id' => isset($input['parent_id']) ? $input['parent_id'] : null,
            'manage_project_id' => $input['manage_project_id'],
            'content' => $input['content'],
            'status' => isset($input['status']) ? $input['status'] : 'new',
            'created_at' => Carbon::now(),
            'created_by' => Auth()->id()
        ];

        $data = $mIssue->addIssue($dataInsert);
        if (isset($data) && $data != null && $data != '') {
            return [
                'error' => false,
                'message' => __('Thêm vấn đề thành công!')
            ];
        } else {
            return [
                'error' => false,
                'message' => __('Thêm vấn đề thất bại!')
            ];
        }
    }

    public function deleteIssue($id)
    {
        $mIssue = app()->get(ProjectIssueTable::class);
        $data = $mIssue->deleteIssue($id['id']);
        if (isset($data) && $data != null && $data != '') {
            return [
                'error' => false,
                'message' => __('Xóa vấn đề thành công!')
            ];
        } else {
            return [
                'error' => false,
                'message' => __('Xóa vấn đề thất bại!')
            ];
        }
    }

    public function popupEditIssue($id)
    {
        $filter['manage_project_issue_id'] = $id['id'];
        $mIssue = app()->get(ProjectIssueTable::class);
        $dataIssue = $mIssue->listIssueAll($filter);
        if (isset($id['job'])) {
            return $dataIssue;
        }
        if (isset($dataIssue) && count($dataIssue) > 0) {
            $view = view('manager-project::project-info.popup.edit-issue', ['dataIssue' => $dataIssue[0]])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        } else {
            return [
                'error' => true,
                'message' => __('Lỗi')
            ];
        }
    }

    public function editIssue($input)
    {
        $mIssue = app()->get(ProjectIssueTable::class);
        $dataEdit = [
            'content' => $input['content'],
            'status' => $input['issue_status'],
            'updated_at' => Carbon::now(),
            'updated_by' => Auth()->id(),
        ];

        $data = $mIssue->editIssue($dataEdit, $input['project_issue_id']);
        if (isset($data) && $data != null && $data != '') {
            return [
                'error' => false,
                'message' => __('Chỉnh sửa vấn đề thành công!')
            ];
        } else {
            return [
                'error' => false,
                'message' => __('Chỉnh sửa vấn đề thất bại!')
            ];
        }
    }

    public function getInfoExpenditure($id = null, $param)
    {
        if ($id != null) {
            $input['manage_project_id'] = $id;
        } else {
            $input = $param;
            $input['manage_project_id'] = isset($param['manage_project_id']) ? $param['manage_project_id'] : null;
        }

        $mProjectInfo = app()->get(ProjectTable::class);
        $mWork = app()->get(WorkTable::class);
        $staff = app()->get(StaffsTable::class);
        $mTags = app()->get(ProjectTagTable::class);
        $mExpenditure = app()->get(ProjectExpenditureTable :: class);
        $mReceipt = app()->get(ReceiptTable :: class);
        $mPayment = app()->get(PaymentTable :: class);

        $data = $mProjectInfo->projectInfo($input);
        $filter['manage_project_id'] = $data['project_id'];
        $param['manage_project_id'] = $id;
        $listWork = $mWork->getAllWork($filter);
        //tinh do rui ro
        $data['risk'] = 'normal';
        $listWorkByIdProject = $listWork;
        if(isset($listWorkByIdProject)){
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
            $totalWorkk = count($listWorkByIdProject);
            $totalWorkCompletee = collect($listWorkByIdProject)->where('status_id' , 6)->count();
            $totalTimeWork = collect($listWorkByIdProject)->sum('time_work');
            $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee/$totalWorkk*100 : 0;
            $ratioTimeWork  = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork/$data['resource']*100 : 0;
            $ratio = $ratioTimeWork - $ratioWork;
            if($ratio < 0){
                $data['risk'] = 'low';
            }elseif($ratio > 20){
                $data['risk'] = 'high';
            }else{
                $data['risk'] = 'normal';
            }
        }
        ///nguồn lực(tính ra giờ)
        $workMayBeLate = 0;
        $workCompleteOnTime = 0;
        $workCompleteLate = 0;
        $workOutOfDate = 0;
        $data['resource_implement'] = 0;
        if (isset($listWork)) {
            foreach ($listWork as $k => $item) {
                $a = $item['date_start'];
                $b = $item['date_end'];
                $c = $item['date_finish'];
                if($c == null) {
                    $first_date = strtotime($a);
                    $second_date = strtotime($b);

                }else{
                    $first_date = strtotime($a);
                    $second_date = strtotime($c);
                }
//                $datediff = abs($first_date - $second_date);
//                $item['time_work'] = $datediff / (60 * 60 * 24);
//                $listWork[$k] = $item;
                if ($item['time'] != null && $item['time_type'] == 'h') {
                    $datediff = $item['time'] / 24;
                } elseif ($item['time'] != null && $item['time_type'] == 'd') {
                    $datediff = $item['time'];
                } else {
                    $datediff = 0;
                }
                $item['time_work'] = $datediff / 8;
                $listWork[$k] = $item;
                //tính hạn công việc
                $dateEndWork = strtotime($item['date_end']);
                if (isset($item['date_finish']) && $item['date_finish'] != null) {
                    $dateFinishWork = strtotime($item['date_finish']);
                } else {
                    $dateFinishWork = $item['date_finish'];
                }
                if ($dateFinishWork != null) {
                    if ($dateFinishWork < $dateEndWork) {
                        $workCompleteOnTime += 1;
                    } elseif ($dateFinishWork > $dateEndWork) {
                        $workCompleteLate += 1;
                    }
                } else {
                    $workOutOfDate += 1;
                }
                //tính thời gian thực hiện công việc
                $dateStartWork = strtotime($item['date_start']);
                if ($dateFinishWork != null) {
                    $implementTime = ceil(abs($dateFinishWork - $dateStartWork) / (60 * 60));
                    if ($dateFinishWork > $dateEndWork) {
                        $typeTimeWork = 'late';
                    } else {
                        $typeTimeWork = 'onTime';
                    }

                } else {
                    $implementTime = 0;
                    $typeTimeWork = 'other';
                }

                $item['implement_time'] = $implementTime;
                $item['type_time_work'] = $typeTimeWork;
                $listWork[$k] = $item;
            }
            $data['resource_implement'] = round(collect($listWork)->where('status_id','=',6)->sum('time_work'),1);
        } else {
        }
        //mức độ quan trọng
        if ($data['is_important'] == 1) {
            $data['important_name'] = "Quan trọng";
        } else {
            $data['important_name'] = "Bình thường";
        }
        //tình trạng dự án
        $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
        if (count($arrDateEndWork) > 0) {
            if (in_array(null, $arrDateEndWork)) {
                $maxDateEndWork = Carbon::now()->format('Y-m-d');
            } else {
                $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
            }
            if ($maxDateEndWork > $data['to_date']) {

                $data['condition'] = [
                    'condition_color' => "#FFB6C1",
                    'condition_name' => __("Quá hạn")
                ];
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => __("Bình thường")
                ];
            }
        } else {
            $data['condition'] = [
                'condition_color' => "#87CEFF",
                'condition_name' => __("Bình thường")
            ];
        }

        //ngày trễ hạn
        $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
        if ($data['to_date'] != null && $data['date_finish'] != null) {
            $dateEnd = Carbon::parse($data['to_date']);
            $dateFinish = Carbon::parse($data['date_finish']);
            if ($dateEnd < $dateFinish) {
                $data['date_late'] = $dateEnd->diffInDays($dateFinish);
            } else {
                $data['date_late'] = $dateEnd->diffInDays($now);
            }
        } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
            $dateEnd = Carbon::parse($data['to_date']);
            $data['date_late'] = $dateEnd->diffInDays($now);
        } else {
            $data['date_late'] = 0;
        }
        $totalWork = $mWork->getTotalWork($input);
        $totalWorkComplete = $mWork->getTotalWorkComplete($input);

        $numberTotalWork = 0;
        $numberTotalComplete = 0;

        if ($totalWork != null && $totalWork != []) {
            $numberTotalWork = $totalWork[0]['total'];
        }

        if ($totalWorkComplete != null && $totalWorkComplete != []) {
            $numberTotalComplete = $totalWorkComplete[0]['total'];
        }

        $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : ceil($numberTotalComplete * 100 / $numberTotalWork);
        //so luong cong viec theo tiến độ
        $data['work-duration'] = $totalWork ? [
            'totalWork' => $totalWork[0]['total'],
            'workMayBeLate' => $workMayBeLate,
            'workCompleteOnTime' => $workCompleteOnTime,
            'workCompleteLate' => $workCompleteLate,
            'workOutOfDate' => $workOutOfDate,
        ] : [];
        //thông tin người quản trị
        if (isset($data['manager_id']) && $data['manager_id'] != null) {
            $filter['arrIdManager'] = explode(',', $data['manager_id']);
            $infoManager = $staff->getInfoManager($filter);
            if (!empty($infoManager)) {
                $data['manager'] = $infoManager;
            } else {
                $data['manager'] = [];
            }
        } else {
            $data['manager'] = [];
        }
        unset($data['manager_id']);
        //danh sach tag
        $data['tag'] = $mTags->getTagProject($input);
        //
        $listExpenditure = $mExpenditure->getListExpenditurePaginate($input);

        $filter['created_at'] = isset($input['created_at']) ? $input['created_at'] : null;

        if ($listExpenditure && count($listExpenditure) > 0) {
            $ext = collect($listExpenditure)->toArray()['data'];
            $listExpenditureByType = collect($ext)->groupBy('type');
            if (isset($listExpenditureByType['receipt'])) {
                $arrIdReceipt = collect($listExpenditureByType['receipt'])->pluck('obj_id')->toArray();
                //lấy danh sách phiếu thu
                $filter['arrIdReceipt'] = $arrIdReceipt;
                $listReceipt = $mReceipt->getListReceipt($filter, $param);
                $listReceipt = collect($listReceipt)->keyBy('receipt_id');
            } else {
                $listReceipt = [];
            }
            if (isset($listExpenditureByType['payment'])) {
                $arrIdPayment = collect($listExpenditureByType['payment'])->pluck('obj_id')->toArray();
                //lấy danh sách phiếu chi
                $filter['arrIdPayment'] = $arrIdPayment;
                $listPayment = $mPayment->getListPayment($filter, $param);
                $listPayment = collect($listPayment)->keyBy('payment_id');
            } else {
                $listPayment = [];
            }
            foreach ($listExpenditure as $key => $value) {
                if ($value['type'] == 'receipt' && isset($listReceipt[$value['obj_id']])) {
                    $listExpenditure[$key]['expenditure_info'] = $listReceipt[$value['obj_id']];
                } elseif ($value['type'] == 'payment' && isset($listPayment[$value['obj_id']])) {
                    $listExpenditure[$key]['expenditure_info'] = $listPayment[$value['obj_id']];
                } else {
                    unset($listExpenditure[$key]);
                }
            }
        } else {
            $listExpenditure = [];
        }
        $listExpenditureNoPage = $mExpenditure->getListExpenditure($input);

        $listExpenditureGroupByType = collect($listExpenditureNoPage)->groupBy('type')->toArray();
        //lấy amount phiếu thu
        $arrIdReceipt = count($listExpenditureGroupByType) > 0 && isset($listExpenditureGroupByType['receipt']) ? collect($listExpenditureGroupByType['receipt'])->pluck('obj_id')->toArray() : [];
        $mReceipt = app()->get(ReceiptTable::class);
        if ($arrIdReceipt != []) {
            $input['arrIdReceipt'] = $arrIdReceipt;
            $dataReceipt = $mReceipt->getListReceipt($input);
            $totalReceipt = collect($dataReceipt)->sum('total_money');
        } else {
            $totalReceipt = 0;
        }
        //lấy amount phiếu chi
        $arrIdPayment = count($listExpenditureGroupByType) > 0 && isset($listExpenditureGroupByType['payment']) ? collect($listExpenditureGroupByType['payment'])->pluck('obj_id')->toArray() : [];
        $mPayment = app()->get(PaymentTable::class);
        if ($arrIdPayment != []) {
            $input['arrIdPayment'] = $arrIdPayment;
            $dataPayment = $mPayment->getListPayment($input);
            $totalPayment = collect($dataPayment)->sum('total_money');
        } else {
            $totalPayment = 0;
        }

        $listExpenditure = $listExpenditure != [] ? $listExpenditure : [];
        $data['listExpenditure'] = $listExpenditure;
        $data['totalReceipt'] = $totalReceipt;
        $data['totalPayment'] = $totalPayment;
        return $data;
    }

    public function popupAddPayment($param)
    {
        $branchTable = app()->get(BranchTable::class);
        $objectAccountTypeTable = new ObjectAccountingTypeTable();
        $paymentMethodTable = new PaymentMethodTable();
        $getBranch = $branchTable->getBranchOption();
        $paymentTypeTable = new PaymentTypeTable();
        $getPaymentType = $paymentTypeTable->getPaymentTypeOption();
        $getPaymentMethod = $paymentMethodTable->getPaymentMethodOption();
        $getObjectAccountingType = $objectAccountTypeTable->getObjectAccountTypeOption();
        $data = [
//            'LIST' => $data,
            'BRANCH' => $getBranch->toArray(),
//            'STAFF' => $getStaff,
            'PAYMENT_TYPE' => $getPaymentType,
            'PAYMENT_METHOD' => $getPaymentMethod,
            'OBJECT_ACCOUNTING_TYPE' => $getObjectAccountingType,
//            'SUPPLIER' => $getSupplier,
//            'CUSTOMER' => $getCustomer,
//            'CUSTOMER' => $getCustomer,
            'manage_project_id' => $param['id'],

        ];
        $view = view('manager-project::project-info.popup.add-payment', ['data' => $data])->render();
        $result = [
            'error' => false,
            'view' => $view,
        ];
        return $result;
    }
    public function popupAddReceipt($param){
        $mReceiptType = new ReceiptTypeTable();
        $mObjAccountingType = new ObjectAccountingTypeTable();
        $mPaymentMethod = new PaymentMethodTable();
        $optionReceiptType = $mReceiptType->getOption()->toArray();
        $optionObjAccType = $mObjAccountingType->getOption()->toArray();
        $optionPaymentMethod = $mPaymentMethod->getOption()->toArray();

        $data = [
            'optionReceiptType' => $optionReceiptType,
            'optionObjAccType' => $optionObjAccType,
            'optionPaymentMethod' => $optionPaymentMethod,
            'manage_project_id' => $param['id'],
        ];
        $view = view('manager-project::project-info.popup.add-receipt', ['data' => $data])->render();
        $result = [
            'error' => false,
            'view' => $view,
        ];
        return $result;
    }
    public function addNewPayment($dataCreate){
        try {
            $mPayment = app()->get(PaymentTable::class);
            $mExpenditure = app()->get(ProjectExpenditureTable::class);
            // generate payment_code (P + ddmmyyyy + số tự tăng)
            $payment_code_old = $mPayment->getPaymentMaxId();
            $max_id_old = isset($payment_code_old['payment_code']) ?
                substr($payment_code_old['payment_code'],9,strlen($payment_code_old['payment_code'])) : null;

            if($max_id_old == null){
                $max_id_old = 0;
            }
            $max_id_new = (int)$max_id_old + 1;
            $currentDay = (new \DateTime())->format('d');
            $currentMonth = (new \DateTime())->format('m');
            $currentYear = (new \DateTime())->format('Y');
            $payment_code_new = 'P' .$currentDay . $currentMonth . $currentYear.$max_id_new;
            if($dataCreate['object_accounting_type_code'] == null || $dataCreate['object_accounting_type_code'] == []){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn nhóm người nhận')
                ];
            }
            if($dataCreate['payment_type'] == null || $dataCreate['payment_type']  == []){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn loại phiếu chi')
                ];
            }
            if($dataCreate['document_code'] == null || $dataCreate['document_code']  == []){
                return [
                    'error' => true,
                    'message' => __('Vui lòng nhập mã tham chiếu')
                ];
            }
            if($dataCreate['payment_method'] == null || $dataCreate['payment_method']  == []){
                return [
                    'error' => true,
                    'message' => __('Vui lòng nhập hình thức thanh toán')
                ];
            }
            if($dataCreate['branch_code'] == null || $dataCreate['branch_code'] == []){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn chi nhánh')
                ];
            }


            if($dataCreate['note'] == null || $dataCreate['note']  == []){
                return [
                    'error' => true,
                    'message' => __('Vui lòng nhập mô tả')
                ];
            }
            $dataInsert = [
                'payment_code' => $payment_code_new,
                'branch_code' => $dataCreate['branch_code'],
                'total_amount' =>
                    $dataCreate['total_amount'] = str_replace(',', '', isset($dataCreate['total_amount']) ? $dataCreate['total_amount']: 0),
                'status'=> 'new',
                'note' => $dataCreate['note'],
                'object_accounting_type_code' => $dataCreate['object_accounting_type_code'],
                'accounting_id' => isset($dataCreate['accounting_id']) ? $dataCreate['accounting_id'] : '',
                'accounting_name' => isset($dataCreate['accounting_name']) ? $dataCreate['accounting_name'] : '',
                'payment_type' => $dataCreate['payment_type'],
                'document_code' => $dataCreate['document_code'],
                'payment_method' => $dataCreate['payment_method'],
                'is_delete' => '0',
                'created_by' => Auth()->id(),
                'staff_id' => Auth()->id(),
            ];
            $result =$mPayment->createPayment($dataInsert);
           //add expenditure
            $dataPayment= $mPayment->getDataDetail($result);
            $dataExpenditure =[
                    'manage_project_id' => $dataCreate['manage_project_id'],
                    'type' => 'payment',
                    'obj_id' => isset($dataPayment['payment_id']) ? $dataPayment['payment_id'] : '',
                    'obj_code' => isset($dataPayment['payment_code']) ? $dataPayment['payment_code'] : '',
            ];
            $addExpenditure = $mExpenditure->addExpenditure($dataExpenditure);
            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        }
        catch (\Exception $ex){
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => __($ex->getMessage()) . $ex->getLine()
            ];
        }
    }
    /**
     * Load option các đối tượng theo loại
     *
     * @param $input
     * @return mixed
     */
    public function loadOptionObjectAccounting($input)
    {
        $type = $input['objAccountingType'];
        $option = [];
        switch ($type) {
            case 'OAT_CUSTOMER':
                $mCustomer = new Customers();
                $option = $mCustomer->getOption();
                break;
            case 'OAT_SUPPLIER':
                $mSupplier = new SupplierTable();
                $option = $mSupplier->getOption();
                break;
            case 'OAT_EMPLOYEE':
                $mStaff = new StaffsTable();
                $option = $mStaff->getOption();
                break;
            default:
                break;
        }
        return $option->toArray();
    }
    public function addNewReceipt($input){
        DB::beginTransaction();
        try {
            $mReceiptDetail = new ReceiptDetailTable();
            $mReceipt = app()->get(ReceiptTable::class);
            $mExpenditure = app()->get(ProjectExpenditureTable::class);

            // Check loại đối tượng thu chi
            $objectAccountingType = $input['objectAccountingTypeCode'];
            $money = str_replace(',', '', $input['money']);
            $dataInsert = [
                'status' => 'unpaid',
                'staff_id' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
                'total_money' => $money,
                'amount' => $money,
                'amount_paid' => 0,
                'receipt_type_code' => $input['receiptTypeCode'],
                'type_insert' => 'manual',
                'object_accounting_type_code' => $objectAccountingType,
                'note' => $input['note'],
                'created_by' => Auth::id(),
                'created_at' => Carbon::now()
            ];
            switch ($objectAccountingType) {
                case 'OAT_OTHER':
                case 'OAT_SHIPPER':
                    $dataInsert['object_accounting_name'] = $input['objectAccountingName'];
                    break;
                default:
                    $dataInsert['customer_id'] = (int)$input['objectAccountingId'];
                    $dataInsert['object_accounting_id'] = (int)$input['objectAccountingId'];
                    break;
            }
            $receiptId = $mReceipt->addReceipt($dataInsert);

            //update receipt code
            $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);

            $dataExpenditure =[
                'manage_project_id' => $input['manage_project_id'],
                'type' => 'receipt',
                'obj_id' => $receiptId != null ? $receiptId : '',
                'obj_code' => isset($receiptCode) ? $receiptCode : '',
            ];
            $addExpenditure = $mExpenditure->addExpenditure($dataExpenditure);

            $mReceipt->editReceipt(['receipt_code' => $receiptCode], $receiptId);
            // insert receipt detail
            $mReceiptDetail->add([
                'receipt_id' => $receiptId,
                'cashier_id' => Auth::id(),
                'receipt_type' => 'cash',
                'amount' => $money,
                'payment_method_code' => $input['paymentMethodId'],
                'created_by' => Auth::id()
            ]);

            $mPaymentMethod = app()->get(PaymentMethodTable::class);
            //Lấy thông tin phương thức thanh toán
            $getMethod = $mPaymentMethod->getInfoByCode($input['paymentMethodId']);

            $url = "";

            if ($input['paymentMethodId'] == "VNPAY") {
                $performerName = null;
                $performerPhone = null;
                //Lấy thông tin đối tượng thực hiện
                switch ($input['objectAccountingTypeCode']) {
                    case 'OAT_CUSTOMER':
                        $mCustomer = new CustomerTable();
                        //Lấy thông tin KH
                        $info = $mCustomer->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['phone1'];
                        }
                        break;
                    case 'OAT_SUPPLIER':
                        $mSupplier = new SupplierTable();
                        //Lấy nhà cung cấp
                        $info = $mSupplier->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['contact_phone'];
                        }
                        break;
                    case 'OAT_EMPLOYEE':
                        $mStaff = new StaffTable();
                        //Lấy nhà cung cấp
                        $info = $mStaff->getItem($input['objectAccountingId']);

                        if ($info != null) {
                            $performerName = $info['accounting_name'];
                            $performerPhone = $info['phone'];
                        }
                        break;
                    default:
                        $performerName = $input['objectAccountingName'];
                        break;
                }

                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                //Lưu vào bảng receipt_online
                $idReceiptOnline = $mReceiptOnline->add([
                    "receipt_id" => $receiptId,
                    "object_type" => "receipt",
                    "object_id" => $receiptId,
                    "object_code" => $receiptCode,
                    "payment_method_code" => $input['paymentMethodId'],
                    "amount_paid" => $money,
                    "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                    "type" => $getMethod['payment_method_type'],
                    "performer_name" => $performerName,
                    "performer_phone" => $performerPhone
                ]);
                //Nếu là vn pay thì call api thanh toán vn pay
                $callVnPay = $this->_paymentVnPay(
                    $receiptCode,
                    $money,
                    $input['objectAccountingId'],
                    Auth()->user()->branch_id,
                    'web',
                    ""
                );

                if ($callVnPay['ErrorCode'] == 0) {
                    $url = $callVnPay['Data']['payment_url'];

                    //Update transaction_code cho receipt_online khi call api thành công
                    $mReceiptOnline->edit([
                        "payment_transaction_code" => $callVnPay['Data']['payment_transaction_code']
                    ], $idReceiptOnline);
                } else {
                    return [
                        'error' => true,
                        'message' => __('Tạo qr thất bại')
                    ];
                }
            }

            DB::commit();

            return [
                'error' => false,
                'data' => ['receiptId' => $receiptId],
                "url" => $url,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    /**
     * Call api thanh toán vn pay
     *
     * @param $orderCode
     * @param $amount
     * @param $userId
     * @param $branchId
     * @param $platform
     * @param $paramsExtra
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function _paymentVnPay($orderCode, $amount, $userId, $branchId, $platform, $paramsExtra)
    {
        $mPaymentOnline = app()->get(PaymentOnline::class);

        //Call api thanh toán vn pay
        return $mPaymentOnline->paymentVnPay([
            'method' => 'vnpay',
            'order_id' => $orderCode,
            'amount' => $amount,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'platform' => $platform,
            'params_extra' => $paramsExtra
        ]);
    }
}
