<?php

namespace Modules\Commission\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Commission\Http\Requests\Commission\StoreRequest;
use Modules\Commission\Models\CommissionTable;
use Modules\Commission\Models\StaffsTable;
use Modules\Commission\Repositories\CommissionRepoInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CommissionController extends Controller
{
    protected $commission;


    public function __construct(CommissionRepoInterface $commission)
    {
        $this->commission = $commission;
    }

    /**
     * Function trang chủ danh sách hoa hồng
     * @return Response
     */
    public function indexAction()
    {
        $commissionData = $this->commission->listCommission();
        $tags = $this->commission->listTag();

        return view('commission::index', [
            'COMMISSION_LIST' => $commissionData,
            'TAG_LIST' => $tags
        ]);
    }

    /**
     * Function trang hoa hồng thực nhận
     * @return Response
     */
    public function indexStaffCommisionAction()
    {
        //Lấy danh sách hoa hồng của nhân viên
        $staffData = $this->commission->listCommissionReceived();

        $branchData = $this->commission->getListBranch();
        $departmentData = $this->commission->getListDepartment();
        $titleData = $this->commission->getListTitle();

        return view('commission::index-staff', [
            'STAFF_DATA' => $staffData,
            'BRANCH_DATA' => $branchData,
            'DEPARTMENT_DATA' => $departmentData,
            'TITLE_DATA' => $titleData,
        ]);
    }

    /**
     * Function trang hoa hồng thực nhận
     * @return Response
     */
    public function listStaffCommisionAction(Request $request)
    {
        $filter = $request->all();
        $staffData = $this->commission->listCommissionReceived($filter);

        return view('commission::list-commission-staff', [
            'STAFF_DATA' => $staffData,
            'page' => $filter['page']
        ]);
    }

    /**
     * Hiển thị danh sách hoa hồng khi có param filter
     */
    public function listAction(Request $request)
    {
        $filter = $request->all();
        $commissionData = $this->commission->listCommission($filter);

        return view('commission::list-commission', [
            'COMMISSION_LIST' => $commissionData
        ]);
    }

    /**
     * Trang chi tiết hoa hồng
     */
    public function detailAction($id)
    {
        //Lấy thông tin hoa hồng
        $data = $this->commission->getDetailCommission($id);
        //Lấy list tag
        $tags = $this->commission->listTag();
        //Lấy ds nhân viên được phân bổ
        $staff = $this->commission->getStaffByCommission($id);
        //Lấy option loại hợp đồng
        $optionContractCategory = $this->commission->getOptionContractCategory();

        $kpiCriteriaType = "";

        switch ($data['commission_scope']) {
            case 'personal':
                $kpiCriteriaType = "S";
                break;
            case 'group':
                $kpiCriteriaType = "T";
                break;
            case 'branch':
                $kpiCriteriaType = "B";
                break;
            case 'department':
                $kpiCriteriaType = "D";
                break;
        }

        //Lấy option tiêu chí kpi
        $optionCriteria = $this->commission->getOptionCriteria($kpiCriteriaType);

        return view('commission::detail-commission', [
            'item' => $data,
            'TAG_LIST' => $tags,
            'STAFF_DATA' => $staff,
            'optionContractCategory' => $optionContractCategory,
            'optionCriteria' => $optionCriteria
        ]);
    }

    /**
     * Soft delete hoa hồng
     * @param $id
     */
    public function removeAction($id)
    {
        $this->commission->removeCommission($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * Trang thêm mới hoa hồng
     */
    public function addAction()
    {
        $tags = $this->commission->listTag();
        $category = $this->commission->getListCategory();
        //Lấy option loại hợp đồng
        $optionContractCategory = $this->commission->getOptionContractCategory();

        return view('commission::add-commission', [
            'TAG_LIST' => $tags,
            'CATEGORY' => $category,
            'optionContractCategory' => $optionContractCategory
        ]);
    }

    /**
     * Thêm hoa hồng
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAction(StoreRequest $request)
    {
        $data = $this->commission->saveCommission($request->all());

        return response()->json($data);
    }

    /**
     * Trang chủ phân bổ hoa hồng
     */
    public function allocationAction(Request $request)
    {
        //Forget session tạm
        session()->forget('staff_temp');
        session()->forget('commission_temp');

        //Lấy filter table nhân viên
        $staffFilters = $this->staffFilters();
        //Lấy ds nhân viên
        $listStaff = $this->commission->listStaff();
        //Lấy filter table hoa hồng
        $commissionFilters = $this->commissionFilters();
        //Lấy ds hoa hồng
        $listCommission = $this->commission->listCommission([
            'status' => 1
        ]);

        return view('commission::allocation', [
            'STAFF_FILTER' => $staffFilters,
            'listStaff' => $listStaff,
            'COMMISSION_FILTER' => $commissionFilters,
            'listCommission' => $listCommission
        ]);
    }

    /**
     * Lấy data filter nhân viên
     *
     * @return array
     */
    protected function staffFilters()
    {
        //Lấy option chi nhánh
        $branchData = $this->commission->getListBranch()->toArray();

        $branch = array_combine(
            array_column($branchData, 'branch_id'),
            array_column($branchData, 'branch_name')
        );

        $groupBranch = (['' => __('Chọn chi nhánh')]) + $branch;

        //Lấy option phòng ban
        $departmentData = $this->commission->getListDepartment()->toArray();

        $department = array_combine(
            array_column($departmentData, 'department_id'),
            array_column($departmentData, 'department_name')
        );

        $groupDepartment = (['' => __('Chọn phòng ban')]) + $department;

        //Lấy option chức vụ
        $titleData = $this->commission->getListTitle()->toArray();

        $title = array_combine(
            array_column($titleData, 'staff_title_id'),
            array_column($titleData, 'staff_title_name')
        );

        $groupTitle = (['' => __('Chọn chức vụ')]) + $title;

        return [
            'staffs$staff_type' => [
                'data' => [
                    '' => __('Loại nhân viên'),
                    'probationers' => __('Thử việc'),
                    'staff' => __('Chính thức')
                ]
            ],
            'staffs$branch_id' => [
                'data' => $groupBranch
            ],
            'staffs$department_id' => [
                'data' => $groupDepartment
            ],
            'staffs$staff_title_id' => [
                'data' => $groupTitle
            ]
        ];
    }

    /**
     * DS nhân viên ajax (filter, phân trang)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listStaffAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'staffs$staff_type',
            'staffs$branch_id',
            'staffs$department_id',
            'staffs$staff_title_id'
        ]);

        //Danh sách nhân viên
        $listStaff = $this->commission->listStaff($filter);

        return view('commission::components.allocation.list-staff', [
            'listStaff' => $listStaff,
            'page' => $filter['page'],
            'arrCheckTemp' => session()->get('staff_temp')
        ]);
    }

    /**
     * Lấy data filter tag
     *
     * @return \array[][]
     */
    protected function commissionFilters()
    {
        //Lấy option tags
        $tagsData = $this->commission->listTag();

        $tag = array_combine(
            array_column($tagsData, 'tags_id'),
            array_column($tagsData, 'tags_name')
        );

        $groupTags = (['' => __('Chọn tag')]) + $tag;

        return [
            'commission_type' => [
                'data' => [
                    '' => __('Loại hoa hồng'),
                    'order' => __('Theo doanh thu đơn hàng'),
                    'kpi' => __('Theo KPI'),
                    'contract' => __('Theo hợp đồng')
                ]
            ],
            'tags_id' => [
                'data' => $groupTags
            ],
        ];
    }

    /**
     * Lưu phân bổ vào database
     */
    public function submitAllocationAction(Request $request)
    {
        $data = $this->commission->saveCommissionAllocation($request->all());

        return response()->json($data);

    }

    /**
     * Thay đổi loại hoa hồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeTypeAction(Request $request)
    {
        $htmlTable = '';

        switch ($request->commission_type) {
            case 'order':
                $htmlTable = \View::make('commission::components.order-commission-table')->render();

                break;
            case 'kpi':
                $htmlTable = \View::make('commission::components.kpi-commission-table')->render();

                break;
            case 'contract':
                $htmlTable = \View::make('commission::components.contract-commission-table')->render();

                break;
        }

        return response()->json([
            'htmlTable' => $htmlTable,
            'commission_type' => $request->commission_type
        ]);
    }

    /**
     * Thay đổi loại hàng hoá load nhóm hàng hoá
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOrderTypeAction(Request $request)
    {
        //Lấy data nhóm hàng hoá theo loại hàng hoá
        $data = $this->commission->getDataOrderGroupByType($request->all());

        return response()->json([
            'data' => $data,
            'order_commission_type' => $request->order_commission_type
        ]);
    }

    /**
     * Thay đổi nhóm hàng hoá
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionOrderObjectAction(Request $request)
    {
        $data = $this->commission->listOptionOrderObject($request->all());

        return response()->json($data);
    }

    /**
     * Chọn nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseStaffAction(Request $request)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        if (isset($request->arrChoose) && count($request->arrChoose) > 0) {
            foreach ($request->arrChoose as $v) {
                //Push nhân viên mới chọn vào
                $arrCheckTemp[$v['staff_id']] = [
                    "staff_id" => $v['staff_id'],
                    "commission_coefficient" => $v['commission_coefficient']
                ];
            }
        }

        //Forget session tạm
        session()->forget('staff_temp');
        //Push session tạm mới
        session()->put('staff_temp', $arrCheckTemp);

        return response()->json([
            'number_staff' => count($arrCheckTemp)
        ]);
    }

    /**
     * Bỏ chọn nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseStaffAction(Request $request)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        if (isset($request->arrUnChoose) && count($request->arrUnChoose)) {
            foreach ($request->arrUnChoose as $v) {
                //Unset các nhân viên bỏ chọn
                unset($arrCheckTemp[$v['staff_id']]);
            }
        }

        //Forget session tạm
        session()->forget('staff_temp');
        //Push session tạm mới
        session()->put('staff_temp', $arrCheckTemp);

        return response()->json([
            'number_staff' => count($arrCheckTemp)
        ]);
    }

    /**
     * Update các giá trị trong table nhân viên đã chọn
     *
     * @param Request $request
     */
    public function updateObjectStaffAction(Request $request)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        $arrCheckTemp[$request->staff_id]['commission_coefficient'] = $request->commission_coefficient;

        //Forget session tạm
        session()->forget('staff_temp');
        //Push session tạm mới
        session()->put('staff_temp', $arrCheckTemp);
    }

    /**
     * Danh sách hoa hồng (phân trang)
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listCommissionAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'commission_name',
            'commission_type',
            'tags_id',
        ]);

        $filter['status'] = 1;

        //Danh sách hoa hồng
        $listCommission = $this->commission->listCommission($filter);

        return view('commission::components.allocation.list-commission', [
            'listCommission' => $listCommission,
            'page' => $filter['page'],
            'arrCheckTempCommission' => session()->get('commission_temp')
        ]);
    }

    /**
     * Chọn hoa hồng
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function chooseCommissionAction(Request $request)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('commission_temp')) {
            $arrCheckTemp = session()->get('commission_temp');
        }

        if (isset($request->arrChoose) && count($request->arrChoose) > 0) {
            foreach ($request->arrChoose as $v) {
                //Push nhân viên mới chọn vào
                $arrCheckTemp[$v['commission_id']] = [
                    "commission_id" => $v['commission_id'],
//                    "priority" => $v['priority']
                ];
            }
        }

        //Forget session tạm
        session()->forget('commission_temp');
        //Push session tạm mới
        session()->put('commission_temp', $arrCheckTemp);

        return response()->json([
            'number_commission' => count($arrCheckTemp)
        ]);
    }

    /**
     * Bỏ chọn hoa hồng
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function unChooseCommissionAction(Request $request)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('commission_temp')) {
            $arrCheckTemp = session()->get('commission_temp');
        }

        if (isset($request->arrUnChoose) && count($request->arrUnChoose)) {
            foreach ($request->arrUnChoose as $v) {
                //Unset các nhân viên bỏ chọn
                unset($arrCheckTemp[$v['commission_id']]);
            }
        }

        //Forget session tạm
        session()->forget('commission_temp');
        //Push session tạm mới
        session()->put('commission_temp', $arrCheckTemp);

        return response()->json([
            'number_commission' => count($arrCheckTemp)
        ]);
    }

    /**
     * Update các giá trị trong table hoa hồng đã chọn
     *
     * @param Request $request
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function updateObjectCommissionAction(Request $request)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('commission_temp')) {
            $arrCheckTemp = session()->get('commission_temp');
        }

        $arrCheckTemp[$request->commission_id]['priority'] = $request->priority;

        //Forget session tạm
        session()->forget('commission_temp');
        //Push session tạm mới
        session()->put('commission_temp', $arrCheckTemp);
    }

    /**
     * Load table phân bổ hoa hồng
     *
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadAllocationTableAction()
    {
        $tempStaff = session()->get('staff_temp');
        $tempCommission = session()->get('commission_temp');

        $arrStaff = [];
        $arrCommission = [];

        if (count($tempStaff) > 0) {
            $mStaff = app()->get(StaffsTable::class);

            foreach ($tempStaff as $v) {
                //Lấy thông tin nhân viên
                $info = $mStaff->getInfo($v['staff_id']);

                $arrStaff [] = [
                    'staff_id' => $v['staff_id'],
                    'staff_name' => $info['staff_name'],
                    'staff_avatar' => $info['staff_avatar'],
                    'branch_name' => $info['branch_name'],
                    'department_name' => $info['department_name'],
                    'commission_coefficient' => $v['commission_coefficient']
                ];

            }
        }

        if (count($tempCommission) > 0) {
            $mCommission = app()->get(CommissionTable::class);

            foreach ($tempCommission as $v) {
                //Lấy thông tin hoa hồng
                $info = $mCommission->getInfo($v['commission_id']);

                $arrCommission [] = [
                    'commission_id' => $v['commission_id'],
                    'commission_name' => $info['commission_name'],
//                    'priority' => $v['priority']
                ];
            }
        }

        $html = \View::make('commission::components.allocation.table-allocation', [
            'arrStaff' => $arrStaff,
            'arrCommission' => $arrCommission
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show pop chỉnh sửa hoa hồng được phân bổ cho nhân viên

     * @param Request $request
     * @return JsonResponse
     */
    public function showPopEditReceivedAction(Request $request)
    {
        //Lấy hoa hồng được phân bổ cho nhân viên
        $getAllocation = $this->commission->getAllocationByStaff($request->staff_id);

        $html = \View::make('commission::components.received.pop-edit', [
            'listAllocation' => $getAllocation,
            'staff_id' => $request->staff_id
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa hoa hồng được phân bổ cho nhân viên
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submitEditReceivedAction(Request $request)
    {
        $data = $this->commission->editReceived($request->all());

        return response()->json($data);
    }

    /**
     * Load tiêu chí kpi khi thay đổi giá trị
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeScopeAction(Request $request)
    {
        $kpiCriteriaType = "";

        switch ($request->commission_scope) {
            case 'personal':
                $kpiCriteriaType = "S";
                break;
            case 'group':
                $kpiCriteriaType = "T";
                break;
            case 'branch':
                $kpiCriteriaType = "B";
                break;
            case 'department':
                $kpiCriteriaType = "D";
                break;
        }

        //Lấy option tiêu chí kpi
        $optionCriteria = $this->commission->getOptionCriteria($kpiCriteriaType);

        return response()->json([
            'optionCriteria' => $optionCriteria
        ]);
    }

    /**
     * Chi tiết hoa hồng nhân viên
     *
     * @param $idStaff
     * @return Application|Factory|View
     */
    public function staffCommissionDetailAction($idStaff)
    {
        $data = $this->commission->getDataDetailReceived($idStaff);

        return view('commission::detail-received', $data);
    }

    /**
     * Lấy danh sách hoa hồng của nhân viên
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listStaffCommissionAction(Request $request)
    {
        //Lấy ds hoa hồng đã nhận của nhân viên
        $data = $this->commission->listStaffCommission($request->all());

        return view('commission::components.received.detail.list-staff-commission', [
            'LIST' => $data,
            'page' => $request->page
        ]);
    }

    /**
     * Thêm nhanh tags
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createTagAction(Request $request)
    {
        $data = $this->commission->createTag($request->all());

        return response()->json($data);
    }

    /**
     * Cập nhật trạng thái hoa hồng
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->commission->changeStatus($request->all());

        return response()->json($data);
    }
}
