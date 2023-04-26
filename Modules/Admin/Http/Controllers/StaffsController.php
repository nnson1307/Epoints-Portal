<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/29/2018
 * Time: 10:37 AM
 */

namespace Modules\Admin\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Http\Api\StaffApi;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Admin\Models\RoleGroupTable;
use Modules\Admin\Models\StaffTable;
use Modules\Admin\Models\TeamTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Department\DepartmentRepositoryInterface;
use Modules\Admin\Repositories\MapRoleGroupStaff\MapRoleGroupStaffRepositoryInterface;
use Modules\Admin\Repositories\RoleGroup\RoleGroupRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\StaffTitle\StaffTitleRepositoryInterface;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;
use Modules\ManagerWork\Http\Api\ManageFileApi;
use Modules\Shift\Repositories\TimeWorkingStaff\TimeWorkingStaffRepoInterface;
use Modules\StaffSalary\Models\StaffSalaryPayPeriodTable;
use Modules\StaffSalary\Models\StaffSalaryTypeTable;
use Modules\StaffSalary\Models\StaffSalaryUnitTable;
use Modules\StaffSalary\Repositories\StaffSalary\StaffSalaryRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryPayPeriod\StaffSalaryPayPeriodRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryConfig\StaffSalaryConfigRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryAttribute\StaffSalaryAttributeRepoInterface;
use Modules\Shift\Repositories\Timekeeping\TimekeepingRepoIf;
use Modules\StaffSalary\Repositories\StaffSalaryDetail\StaffSalaryDetailRepoInterface;
use Modules\StaffSalary\Repositories\Template\TemplateRepoInterface;

class StaffsController extends Controller
{
    protected $staff;
    protected $branch;
    protected $department;
    protected $staff_title;
    protected $roleGroup;
    protected $mapRoleGroupStaff;
    protected $staffSalary;
    protected $staffSalaryPayPeriod;
    protected $staffSalaryConfig;
    protected $staffSalaryAttribute;
    protected $timekeeping;
    protected $staffSalaryDetail;
    protected $repoStaffTemplate;

    public function __construct(
        StaffRepositoryInterface $staffs,
        BranchRepositoryInterface $branches,
        DepartmentRepositoryInterface $departments,
        StaffTitleRepositoryInterface $staff_titles,
        RoleGroupRepositoryInterface $roleGroup,
        MapRoleGroupStaffRepositoryInterface $mapRoleGroupStaff,
        StaffSalaryRepoInterface $staffSalary,
        StaffSalaryPayPeriodRepoInterface $staffSalaryPayPeriod,
        StaffSalaryConfigRepoInterface $staffSalaryConfig,
        StaffSalaryAttributeRepoInterface $staffSalaryAttribute,
        TimekeepingRepoIf $timekeeping,
        StaffSalaryDetailRepoInterface $staffSalaryDetail,
        TemplateRepoInterface $repoStaffTemplate
    )
    {
        $this->staff = $staffs;
        $this->branch = $branches;
        $this->department = $departments;
        $this->staff_title = $staff_titles;
        $this->roleGroup = $roleGroup;
        $this->mapRoleGroupStaff = $mapRoleGroupStaff;
        $this->staffSalary = $staffSalary;
        $this->staffSalaryPayPeriod = $staffSalaryPayPeriod;
        $this->staffSalaryConfig = $staffSalaryConfig;
        $this->staffSalaryAttribute = $staffSalaryAttribute;
        $this->timekeeping = $timekeeping;
        $this->staffSalaryDetail = $staffSalaryDetail;
        $this->repoStaffTemplate = $repoStaffTemplate;
    }

    //function view index
    public function indexAction()
    {
        $getStaff = $this->staff->list();
        return view('admin::staffs.index', [
            'LIST' => $getStaff,
            'FILTER' => $this->filters(),

        ]);
    }

    //function filter
    protected function filters()
    {
        return [
            'staffs$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    //function view list
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'staffs$is_actived', 'search']);
        $staffList = $this->staff->list($filter);
        return view('admin::staffs.list', ['LIST' => $staffList, 'page' => $filter['page']]);
    }

    //function add view
    public function addAction()
    {
        $getBranch = $this->branch->getBranchOption();
        $getDepar = $this->department->getStaffDepartmentOption();
        $getTitle = $this->staff_title->getStaffTitleOption();
        $roleGroup = $this->roleGroup->getOptionActive();

        return view('admin::staffs.add', [
            'branch' => $getBranch,
            'depart' => $getDepar,
            'title' => $getTitle,
            'roleGroup' => $roleGroup
        ]);
    }

    //function add
    public function submitAddAction(Request $request)
    {
        $data = [
            'full_name' => $request->full_name,
            'staff_code' => $this->generateStaffsCode(),
            'phone1' => $request->phone,
            'gender' => $request->gender,
            'branch_id' => $request->branch_id,
            'staff_title_id' => $request->staff_title_id,
            'department_id' => $request->department_id,
            'staff_type' => $request->staff_type,
            'address' => $request->address,
            'email' => $request->email,
            //            'birthday' => $birthday,
            'user_name' => $request->user_name,
            'is_admin' => $request->is_admin,
            'password' => Hash::make($request->password),
            'is_actived' => 1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'salary' => str_replace(',', '', $request->salary),
            'subsidize' => str_replace(',', '', $request->subsidize),
            'commission_rate' => str_replace(',', '', $request->commission_rate),
            'password_chat' => $request->password,
            'bank_number' => $request->bank_number,
            'bank_name' => $request->bank_name,
            'bank_branch_name' => $request->bank_branch_name,
            'team_id' => $request->team_id
        ];

        if ($request->year != null && $request->month != null && $request->day != null) {
            $birthday = $request->year . '-' . $request->month . '-' . $request->day;
            $data['birthday'] = $birthday;
            if ($birthday > date("Y-m-d")) {
                return response()->json([
                    'error_birthday' => 1,
                    'message' => __('Ngày sinh không hợp lệ')
                ]);
            }
        }
        $test_user = $this->staff->testUserName($request->user_name, 0);
        if ($test_user != "") {
            return response()->json([
                'error_user' => 1,
                'message' => __('Tài khoản đã tồn tại')
            ]);
        }
        if ($request->bank_number != "") {
            if(strlen(($request->bank_number) < 9)){
                return response()->json([
                    'error_bank_number' => 1,
                    'message' => __('Số tài khoản tối thiểu 9 kí tự')
                ]);
            }
            if(strlen(($request->bank_number) > 14)){
                return response()->json([
                    'error_bank_number' => 1,
                    'message' => __('Số tài khoản tối đa 14 kí tự')
                ]);
            }
           
        }
        if ($request->staff_avatar != null) {
            $data['staff_avatar'] = $request->staff_avatar;
        }
        $roleGroup = $request->roleGroup;

        $idStaff = $this->staff->add($data);
        //Nhóm quyền.
        if ($roleGroup != null) {
            foreach ($roleGroup as $key => $value) {
                $checkMapRoleGroup = $this->mapRoleGroupStaff->checkIssetMap($value, $idStaff);
                if ($checkMapRoleGroup == null) {
                    $data = [
                        'role_group_id' => $value,
                        'staff_id' => $idStaff,
                        'is_actived' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->mapRoleGroupStaff->add($data);
                }
            }
        }

        if (in_array('chathub.chat', session('routeList'))) {
            $mStaffApi = app()->get(StaffApi::class);
            //Call api đăng ký tài khoản chat
            $resStaffApi = $mStaffApi->registerStaffAccountChat([
                'staff_id' => $idStaff,
                'password' => $request->password
            ]);
        }

        if (in_array('management-file', session('routeList'))) {

            if (isset($staffLogin['Data']) && $staffLogin['Data'] != null) {
                $apiManageFile = app()->get(ManageFileApi::class);

                $login = $apiManageFile->loginManageFIle(session('access_token'));
            }
        }

        return response()->json([
            'success' => 1,
            'message' => __('Thêm thành công')
        ]);
    }

    //function upload image
    public function uploadAction(Request $request)
    {
        $this->validate($request, [
            "staff_avatar" => "mimes:jpg,jpeg,png,gif|max:10000"
        ], [
            "staff_avatar.mimes" => __("File này không phải file hình"),
            "staff_avatar.max" => __("File quá lớn")
        ]);
        $file = $this->uploadImageTemp($request->file('file'));
        return response()->json(["file" => $file, "success" => "1"]);
    }

    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_staff." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH . "/" . $file_name, file_get_contents($file));
        return $file_name;
    }

    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = STAFF_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(STAFF_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    //function delete image
    public function deleteTempFileAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

    //function xóa
    public function removeAction($id)
    {
        $this->staff->remove($id);

        $mStaffApi = app()->get(StaffApi::class);
        //Call api đăng ký tài khoản chat
        $mStaffApi->deleteStaffAccountChat([
            'staff_id' => $id,
        ]);

        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->staff->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }


    //function get item khi edit
    public function editAction($id)
    {
        //Lấy thông tin nhân viên
        $item = $this->staff->getItem($id);

        if ($item['birthday'] != null) {
            $birthday = explode('/', date("d/m/Y", strtotime($item['birthday'])));
            $day = $birthday[0];
            $month = $birthday[1];
            $year = $birthday[2];
        } else {
            $day = null;
            $month = null;
            $year = null;
        }

        $getBranch = $this->branch->getBranchOption();
        $getDepar = $this->department->getStaffDepartmentOption();
        $getTitle = $this->staff_title->getStaffTitleOption();
        $roleGroup = $this->roleGroup->getOptionActive();
        $mapGroupStaff = $this->mapRoleGroupStaff->getRoleGroupByStaffId($id);
        $staffSalaryType = $this->staffSalary->getListStaffSalaryType();
        $staffSalaryPayPeriod = $this->staffSalaryPayPeriod->getList();
        $arraySalaryBonusMinus = $this->staffSalary->getDetailSalaryBonusMinusByStaff($id);
        $arraySalaryAllowance = $this->staffSalary->getDetailSalaryAllowanceByStaff($id);
        $staffSalaryOvertime = $this->staffSalary->getDetailSalaryOvertimeByStaff($id);
        
        $staffSalaryConfig = $this->staffSalaryConfig->getDetailByStaff($id);
        $optionStaffSalaryTemplate = $this->repoStaffTemplate->getOption();

        $staffSalaryAttribute = $this->staffSalaryAttribute->getDetailByStaff($id);
        $arrayStaffSalaryAttribute = [];
        foreach ($staffSalaryAttribute as $key => $itemStaffSalary) {
            $arrayStaffSalaryAttribute += [
                $itemStaffSalary['staff_salary_attribute_code'] => [
                    'staff_salary_attribute_value' => $itemStaffSalary['staff_salary_attribute_value'],
                    'staff_salary_attribute_type' => $itemStaffSalary['staff_salary_attribute_type'],
                ],
            ];
        }

        $arrayMapRoleGroupStaff = [];
        if (count($mapGroupStaff) > 0) {
            foreach ($mapGroupStaff as $values) {
                $arrayMapRoleGroupStaff[] = $values['role_group_id'];
            }
        }

        $mSalaryUnit = app()->get(StaffSalaryUnitTable::class);
        $mTeam = app()->get(TeamTable::class);

        //Lấy option đơn vị lương
        $optionUnit = $mSalaryUnit->getUnit();
        //Lấy option nhóm
        $optionTeam = $mTeam->getTeamByDepartment($item['department_id']);

        return view('admin::staffs.edit', compact('item'), [
            'branch' => $getBranch,
            'depart' => $getDepar,
            'title' => $getTitle,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'roleGroup' => $roleGroup,
            'arrayMapRoleGroupStaff' => $arrayMapRoleGroupStaff,
            'staffSalaryConfig' => $staffSalaryConfig,
            'staffSalaryOvertime' => $staffSalaryOvertime,
            'staffSalaryType' => $staffSalaryType,
            'staffSalaryPayPeriod' => $staffSalaryPayPeriod,
            'arraySalaryBonusMinus' => $arraySalaryBonusMinus,
            'arraySalaryAllowance' => $arraySalaryAllowance,
            'branch_name' => $item['branch_name'],
            'branch_id' => $item['branch_id'],
            'arrayStaffSalaryAttribute' => $arrayStaffSalaryAttribute,
            'optionStaffSalaryTemplate' => $optionStaffSalaryTemplate,
            'optionUnit' => $optionUnit,
            'optionTeam' => $optionTeam
        ]);
    }

    public function editImageAction(Request $request)
    {
        $id = $request->id;
        $item = $this->staff->getItem($id);
        $data = [
            'staff_avatar' => $item->staff_avatar
        ];
        return response()->json($data);
    }

    //function submit edit
    public function submitEditAction(Request $request)
    {
        $id = $request->staff_id;
        //Nhóm quyền.
        $idRoleGroup = [];
        $roleGroup = $request->roleGroup;

        if ($roleGroup != null) {
            $this->mapRoleGroupStaff->removeByUser($id);

            foreach ($roleGroup as $key => $value) {
                $data = [
                    'role_group_id' => $value,
                    'staff_id' => $id,
                    'is_actived' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $idRol = $this->mapRoleGroupStaff->add($data);
                $idRoleGroup[] = $idRol;
            }
        }

        $data = [
            'full_name' => $request->full_name,
            'phone1' => $request->phone,
            'gender' => $request->gender,
            'branch_id' => $request->branch_id,
            'staff_title_id' => $request->staff_title_id,
            'department_id' => $request->department_id,
            'staff_type' => $request->staff_type,
            'address' => $request->address,
            'email' => $request->email,
            //            'birthday'=>$birthday,
            'user_name' => $request->user_name,
            'is_actived' => $request->is_actived,
            'salary' => str_replace(',', '', $request->salary),
            'subsidize' => str_replace(',', '', $request->subsidize),
            'commission_rate' => str_replace(',', '', $request->commission_rate),
            'bank_number' => $request->bank_number,
            'bank_name' => $request->bank_name,
            'bank_branch_name' => $request->bank_branch_name,
            'token_md5' => '', // cập nhật user update lại rỗng bỏ cache dưới mobile
            'team_id' => $request->team_id
        ];

        if ($request->password != null) {
            $data['password'] = Hash::make($request->password);
        }

        if (isset($request->is_admin) && $request->is_admin != null) {
            $data['is_admin'] = $request->is_admin;
        }

        if ($request->year != null && $request->month != null && $request->day != null) {
            $birthday = $request->year . '-' . $request->month . '-' . $request->day;
            $data['birthday'] = $birthday;
            if ($birthday > date("Y-m-d")) {
                return response()->json([
                    'error_birthday' => 1,
                    'message' => __('Ngày sinh không hợp lệ')
                ]);
            }
        }
        $test_user = $this->staff->testUserName($request->user_name, $id);
        if ($test_user != "") {
            return response()->json([
                'error_user' => 1,
                'message' => __('Tài khoản đã tồn tại')
            ]);
        }
        
        if ($request->bank_number != "") {
            $numberLenght = (int)strlen($request->bank_number);
           
            if($numberLenght < 9){
                return response()->json([
                    'error_bank_number' => 1,
                    'message' => __('Số tài khoản tối thiểu 9 kí tự')
                ]);
            }
            if($numberLenght > 14){
                
                return response()->json([
                    'error_bank_number' => 1,
                    'message' => __('Số tài khoản tối đa 14 kí tự')
                ]);
            }

        }
        if ($request->staff_avatar_upload != '') {
            $data['staff_avatar'] = $request->staff_avatar_upload;
        } else {
            $data['staff_avatar'] = $request->staff_avatar;
        }

        $this->staff->edit($data, $id);

        if (in_array('chathub.chat', session('routeList'))) {
            $mStaffApi = app()->get(StaffApi::class);
            //Call api đăng ký tài khoản chat
            $resStaffApi = $mStaffApi->updateStaffAccountChat([
                'staff_id' => $id,
                'email' => $request->email
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật thành công')
        ]);
    }

    //function get profile.
    public function profileAction($id)
    {
        $item = $this->staff->getItem($id);
        if ($item['birthday'] != null) {
            $birthday = explode('/', date("d/m/Y", strtotime($item['birthday'])));
            $day = $birthday[0];
            $month = $birthday[1];
            $year = $birthday[2];
        } else {
            $day = null;
            $month = null;
            $year = null;
        }

        $getBranch = $this->branch->getBranch();
        $getDepar = $this->department->getStaffDepartmentOption();
        $getTitle = $this->staff_title->getStaffTitleOption();
        $arrMonth = $this->getListTimekeepingStaff($id, date('m'));
        $staffSalary = $this->staffSalaryDetail->getListSalaryByStaff($id);

        $mTeam = app()->get(TeamTable::class);
        //Lấy option nhóm
        $optionTeam = $mTeam->getTeamByDepartment($item['department_id']);


        return view('admin::staffs.profile', compact('item'), [
            'branch' => $getBranch,
            'depart' => $getDepar,
            'title' => $getTitle,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'arrayMonth' => $arrMonth,
            'staffSalary' => $staffSalary,
            'optionTeam' => $optionTeam
        ]);
    }

    /**
     * Export tất cả nhân viên
     *
     * @return mixed
     */
    public function exportAllAction()
    {
        return $this->staff->exportAll();
    }

    /**
     * View chi tiết nhân viên
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function show($id, Request $request)
    {
        $data = $this->staff->dataViewDetail($id);
        $params = $request->all();
        $data['salary_id'] = isset($params['salary_id']) ? $params['salary_id'] : '';

        $timeWorkingRepo = app()->get(TimeWorkingStaffRepoInterface::class);
        //Lấy cầu hình chung của ca làm việc
        $listConfig = $timeWorkingRepo->getConfigGeneral();
        //Lưu session cấu hình chung
        $this->_setSessionConfigGeneral($listConfig);



        $arrMonth = $this->getListTimekeepingStaff($id, date('m'));
        $staffSalary = $this->staffSalaryDetail->getListSalaryByStaff($id);
        $data['arrayMonth'] = $arrMonth;
        $data['staffSalary'] = $staffSalary;

        return view('admin::staffs.detail', $data);
    }

    /**
     * Lưu cấu hình chung ca làm việc
     *
     * @param $listConfig
     */
    private function _setSessionConfigGeneral($listConfig)
    {
        //Tính đi trễ khi check in vào sau
        $lateCheckIn = 0;
        //Tính nghỉ không lương khi check in vào sau
        $offCheckIn = 0;
        //Tính về sớm khi check in ra trước
        $backSoonCheckOut = 0;
        //Tính nghỉ không lương khi check out ra trước
        $offCheckOut = 0;

        if (count($listConfig) > 0) {
            foreach ($listConfig as $v) {
                if ($v['is_actived'] == 0) {
                    continue;
                }

                $unit = 1;

                if ($v['config_general_unit'] == 'hour') {
                    $unit = 60;
                }

                switch ($v['config_general_code']) {
                    case 'late_check_in':
                        $lateCheckIn = intval($v['config_general_value']) * $unit;
                        break;
                    case 'off_check_in':
                        $offCheckIn = intval($v['config_general_value']) * $unit;
                        break;
                    case 'back_soon_check_out':
                        $backSoonCheckOut = intval($v['config_general_value']) * $unit;
                        break;
                    case 'off_check_out':
                        $offCheckOut = intval($v['config_general_value']) * $unit;
                        break;
                }
            }
        }

        //Lưu session từng case config
        session()->put('late_check_in', $lateCheckIn);
        session()->put('off_check_in', $offCheckIn);
        session()->put('back_soon_check_out', $backSoonCheckOut);
        session()->put('off_check_out', $offCheckOut);
    }

    /**
     * TẠO MÃ NHÂN VIÊN
     *
     * @param $id
     * @return string
     */
    public function generateStaffsCode()
    {
        $type_ticket = 'VSS_';
        $last_id = \DB::table('staffs')->latest('staff_id')->first();
        if ($last_id) {
            $last_id = $last_id->staff_id;
        } else {
            $last_id = 0;
        }
        $last_id = sprintf("%03d", ($last_id + 1));
        return $type_ticket . '_' . $last_id;
    }


    public function listTimekeepingAction(Request $request)
    {

        $arrMonth = $this->getListTimekeepingStaff($request->time_keeping_staff_id, $request->date_object);

        return view('shift::timekeeping.list_detail', [
            'arrayMonth' => $arrMonth,
        ]);
    }

    public function getListTimekeepingStaff($itemId, $itemMonth)
    {

        $itemYear = date('Y');
        $date = Carbon::parse($itemYear . "-" . $itemMonth . "-01");
        $dayStart = $date->format('1');
        $dayEnd = $date->format('t');
        $arrMonth = [];
        $arrMonthSunDay = [
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
            'sunday' => [],
        ];

        for ($i = $dayStart; $i <= $dayEnd; $i++) {

            $dateWeek = Carbon::parse($itemYear . "-" . $itemMonth . "-" . $i);
            $week = "";

            switch ($dateWeek->dayOfWeek) {
                case 1:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['monday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;

                case 2:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['tuesday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }

                    break;
                case 3:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['wednesday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }

                    break;
                case 4:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['thursday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 5:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['friday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 6:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['saturday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 0:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['sunday'] = $obj;
                    array_push($arrMonth, $arrMonthSunDay);
                    $arrMonthSunDay = [
                        'monday' => [],
                        'tuesday' => [],
                        'wednesday' => [],
                        'thursday' => [],
                        'friday' => [],
                        'saturday' => [],
                        'sunday' => [],
                    ];
                    break;
                default:
            }
        }

        return $arrMonth;
    }

    function checkShift($arr = [])
    {
        if (count($arr) == 0) {
            return [];
        }
        $arrData = [];
        foreach ($arr as $value => $item) {
            $strBackground = "";
            if (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                $strBackground = "#D3D3D3";
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                }
                if ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                }
            } elseif (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                $strBackground = "#DBEFDC";
                if ($item['is_check_in'] == 0 || $item['is_check_out'] == 0) {
                    $strBackground = "#FDD9D7";
                }
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                }
                if ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                }
                if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                    //Vào trễ
                    $strBackground = "#FFEACC";
                }
                if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                    //Ra sớm
                    $strBackground = "#FFEACC";
                }

                //Check có check in (nghỉ không lương so với cấu hình)
                if ($item['is_check_in'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])
                    && session()->get('off_check_in') > 0) {
                    //Nghĩ không lương
                    $strBackground = "#EBD4EF";
                }

                //Check có check out (nghỉ không lương so với cấu hình)
                if ($item['is_check_out'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])
                    && session()->get('off_check_out') > 0) {
                    //Nghĩ không lương
                    $strBackground = "#EBD4EF";
                }
            } else {
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                } elseif ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                } else {
                    if ($item['is_check_in'] === 0 && $item['is_check_out'] === 0) {
                        if ($item['is_deducted'] === 0) {
                            $strBackground = "#D9DCF0";
                        } else {
                            $strBackground = "#EBD4EF";
                        }
                    } else {
                        if ($item['is_check_in'] === 0 || $item['is_check_out'] === 0) {
                            $strBackground = "#FDD9D7";
                        }
                    }
                    if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                        //Vào trễ
                        $strBackground = "#FFEACC";
                    }
                    if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                        //Ra sớm
                        $strBackground = "#FFEACC";
                    }
                    if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time']) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time']))
                        && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time']) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time']))
                    ) {
                        //Ra vào đúng giờ
                        $strBackground = "#DBEFDC";
                    }

                    //Check có check in (nghỉ không lương so với cấu hình)
                    if ($item['is_check_in'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])
                        && session()->get('off_check_in') > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }

                    //Check có check out (nghỉ không lương so với cấu hình)
                    if ($item['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])
                        && session()->get('off_check_out') > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }
                }
            }
            $obj = [
                'working_end_day' => $item['working_end_day'],
                'working_end_time' => $item['working_end_time'],
                'working_day' => $item['working_day'],
                'is_check_in' => $item['is_check_in'],
                'is_check_out' => $item['is_check_out'],
                'check_in_day' => $item['check_in_day'],
                'check_out_day' => $item['check_out_day'],
                'check_in_time' => $item['check_in_time'],
                'check_out_time' => $item['check_out_time'],
                'is_deducted' => $item['is_deducted'],
                'is_ot' => $item['is_ot'],
                'number_time_back_soon' => $item['number_time_back_soon'],
                'number_late_time' => $item['number_late_time'],
                'branch_name' => $item['branch_name'],
                'branch_id' => $item['branch_id'],
                'shift_name' => $item['shift_name'],
                'shift_id' => $item['shift_id'],
                'staff_id' => $item['staff_id'],
                'time_working_staff_id' => $item['time_working_staff_id'],
                'background' => $strBackground,
                'working_time' => $item['working_time'],
                'is_close' => $item['is_close'],
                'is_approve_time_off' => $item['is_approve_time_off'],
                'time_off_days_id' => $item['time_off_days_id']
            ];
            array_push($arrData, $obj);
        }
        return $arrData;
    }

    /**
     * Insert nv vào nhóm quyền admin
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function insertRoleAdmin()
    {
        $mStaff = app()->get(StaffTable::class);
        $mRoleGroup = app()->get(RoleGroupTable::class);
        $mMapRoleStaff = app()->get(MapRoleGroupStaffTable::class);

        //Lấy thông tin nhân viên
        $getStaff = $mStaff->getStaffAdmin();
        //Lấy thông tin role admin
        $getRoleAdmin = $mRoleGroup->getRoleAdmin();

        $arrData = [];

        if ($getRoleAdmin != null) {
            foreach ($getStaff as $v) {
                $arrData [] = [
                    'staff_id' => $v['staff_id'],
                    'role_group_id' => $getRoleAdmin['id'],
                    'is_actived' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }
        }

        //Insert map nhóm quyền
        $mMapRoleStaff->insert($arrData);
    }

    /**
     * Thay đổi phòng ban
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeDepartmentAction(Request $request)
    {
        $data = $this->staff->changeDepartment($request->all());

        return response()->json($data);
    }
}
