<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/23/2019
 * Time: 1:55 PM
 */

namespace Modules\Admin\Http\Controllers;

use App\Exports\CustomerGroupExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CustomerGroupFilter\StoreRequest;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\CustomerGroupFilter\CustomerGroupFilterRepositoryInterface;
use Modules\Admin\Http\Requests\CustomerGroupFilter\UpdateRequest;

class CustomerGroupFilterController extends Controller
{
    protected $customerGroupFilter;
    protected $customerRepository;

    public function __construct(
        CustomerGroupFilterRepositoryInterface $customerGroupFilter,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerGroupFilter = $customerGroupFilter;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Index
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction()
    {
        $customGroupList = $this->customerGroupFilter->list();
        return view('admin::customer-group-filter.index', [
            'LIST' => $customGroupList
        ]);
    }

    /**
     * Tải file mẫu
     * @return mixed
     */
    public function exportExcelAction()
    {

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new CustomerGroupExport(), 'Khách hàng.xlsx');
    }

    public function addDefineAction(Request $request)
    {   // request survey //
        $route = $request->survey ?? '';
        $idSurvey = $request->id ?? '';

        return view('admin::customer-group-filter.user-define.create', [
            'route' => $route,
            'idSurvey' => $idSurvey,
        ]);
    }

    /**
     * Thêm khách hàng vào nhóm từ file excel
     * @param Request $request
     * @return mixed
     */
    public function importExcel(Request $request)
    {
        $file = $request->file('file');
        $arrayPhoneExist = [];
        $result = $this->customerGroupFilter->importExcel($file, $arrayPhoneExist);
        return $result;
    }

    /**
     * Tìm kiếm danh sách khách hàng trong mảng
     * @param Request $request
     * @return mixed
     */
    public function searchWhereInUser(Request $request)
    {
        $data = $request->all();
        return $this->customerGroupFilter->searchWhereInUser($data);
    }

    /**
     * Tìm kiếm tất cả khách hàng.
     * @param Request $request
     * @return mixed
     */
    public function searchAllCustomer(Request $request)
    {
        $filters = $request->all();
        $result = $this->customerGroupFilter->searchAllCustomer($filters);
        return $result;
    }

    /**
     * Thêm khách hàng đã chọn cho group.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function addCustomerGroupDefine(Request $request)
    {
        $data = ($request->all());
        return $this->customerGroupFilter->addCustomerGroupDefine($data);
    }

    public function submitAddGroupDefine(StoreRequest $request)
    {
        $data = $request->all();
        return $this->customerGroupFilter->submitAddGroupDefine($data);
    }

    public function listAction(Request $request)
    {
        $filter = $request->only(
            [
                'page', 'display', 'search_type', 'search_keyword',
                'filter_group_type'
            ]
        );
        $list = $this->customerGroupFilter->list($filter);

        return view(
            'admin::customer-group-filter.list',
            [
                'LIST' => $list,
                'page' => $filter['page']
            ]
        );
    }

    /**
     * Load sang trang edit nhóm động.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUserDefine($id)
    {
        $data = $this->customerGroupFilter->getItem($id);
        if ($data != null) {
            $user = $this->customerGroupFilter->getCustomerByGroupDefine($id);
            return view(
                'admin::customer-group-filter.user-define.edit',
                [
                    'id'   => $id,
                    'user' => $user,
                    'data' => $data
                ]
            );
        } else {
            return redirect()->route('admin.customer-group-filter');
        }
    }

    /**
     * Danh sách khách hàng của nhóm động.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerByGroupDefine(Request $request)
    {
        $id = $request->id;
        $user = null;
        $typeItem = $this->customerGroupFilter->getItem($id);
        if ($typeItem['filter_group_type'] == 'auto') {
            $user = $this->customerRepository->searchCustomerGroupFilter('auto', $id);
        } else {
            $user = $this->customerGroupFilter->getCustomerByGroupDefine($id);
        }
        return response()->json($user);
    }

    /**
     * Cập nhật nhóm tự định nghĩa.
     *
     * @param UpdateRequest $request
     *
     * @return mixed
     */
    public function updateCustomerGroupDefine(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->customerGroupFilter->updateCustomerGroupDefine($data);
    }

    /**
     * Load sang trang edit nhóm động.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailCustomerGroupDefine($id)
    {
        $data = $this->customerGroupFilter->getItem($id);
        if ($data != null) {
            $user = $this->customerGroupFilter->getCustomerByGroupDefine($id);
            return view(
                'admin::customer-group-filter.user-define.detail',
                [
                    'id'   => $id,
                    'user' => $user,
                    'data' => $data
                ]
            );
        } else {
            return redirect()->route('admin.customer-group-filter');
        }
    }

    /**
     * Load sang trang thêm nhóm khách hàng động
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function addAutoAction(Request $request)
    {
        $condition = $this->customerGroupFilter->getCondition();
        $customerGroupDefine
            = $this->customerGroupFilter->getCustomerGroupDefine();
        $listServiceCard = $this->customerGroupFilter->getListAllServiceCard();
        $listRank = $this->customerGroupFilter->getListAllRank();
        $listService = $this->customerGroupFilter->getListAllService();
        $listProduct = $this->customerGroupFilter->getListAllProduct();
        // request bên surevey để redirect //
        $route = $request->survey ?? '';
        $idSurvey = $request->id ?? '';
//        if ($route == '' || $idSurvey == '' ) {
//            $condition = $condition->take(17);
//        }
//
        $listSourceCustomer = $this->customerGroupFilter->getListCustomerSource();
        $listGroupCustomer = $this->customerGroupFilter->getListCustomerGroup();
        $listProvinces = $this->customerGroupFilter->getProvinces();

        $mCustomerGroup = app()->get(CustomerGroupTable::class);
        //Lấy nhóm KH của hệ thống
        $customerGroupSystem = $mCustomerGroup->getOption();

        return view(
            'admin::customer-group-filter.auto.create',
            [
                'condition'           => $condition,
                'customerGroupDefine' => $customerGroupDefine,
                'listService'         => $listService,
                'listProduct'         => $listProduct,
                'listServiceCard'         => $listServiceCard,
                'listRank'         => $listRank,
                'listProvinces'    => $listProvinces,
                'listSourceCustomer'    => $listSourceCustomer,
                'listGroupCustomer'    => $listGroupCustomer,
                'route' => $route,
                'idSurvey' => $idSurvey,
                'customerGroupSystem' => $customerGroupSystem
            ]
        );
    }

    public function getCondition(Request $request)
    {
        $array = $request->arrayCondition;
        $getCondition = $this->customerGroupFilter->getCondition($array);
        return response()->json($getCondition);
    }

    /**
     * Thêm mới nhóm tự động
     *
     * @param StoreRequest $request
     *
     * @return mixed
     */
    public function submitAddAutoAction(StoreRequest $request)
    {
        $data = $request->all();
        $result = $this->customerGroupFilter->submitAddAutoAction($data);
        return $result;
    }

    /**
     * Load sang trang chỉnh sửa nhóm khách hàng động
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function editAutoAction($id)
    {
        $listServiceCard = $this->customerGroupFilter->getListAllServiceCard();
        $listRank = $this->customerGroupFilter->getListAllRank();
        $condition = $this->customerGroupFilter->getCondition();
        $customerGroupDefine
            = $this->customerGroupFilter->getCustomerGroupDefine();
        $listService = $this->customerGroupFilter->getListAllService();
        $listProduct = $this->customerGroupFilter->getListAllProduct();
        $customerGroup = $this->customerGroupFilter->getItem($id);
        $customerGroupDetail = $this->customerGroupFilter->getCustomerGroupDetail($id);
        $listSourceCustomer = $this->customerGroupFilter->getListCustomerSource();
        $listGroupCustomer = $this->customerGroupFilter->getListCustomerGroup();
        $listProvinces = $this->customerGroupFilter->getProvinces();

        $mCustomerGroup = app()->get(CustomerGroupTable::class);
        //Lấy nhóm KH của hệ thống
        $customerGroupSystem = $mCustomerGroup->getOption();

        $arrayConditionA = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0,
            '13' => 0,
            '14' => 0,
            '15' => 0,
            '16' => 0,
            '17' => 0,
            '18' => 0,
            '19' => 0,
            '20' => 0,
            '21' => 0,
            '22' => 0
        ];
        $arrayConditionB = $arrayConditionA;
        foreach ($customerGroupDetail as $item) {
            if ($item['group_type'] == 'A') {
                if (isset($arrayConditionA[$item['condition_id']])) {
                    switch ($item['condition_id']) {
                        case 1:
                            $arrayConditionA[$item['condition_id']] = $item['customer_group_define_id'];
                            break;
                        case 2:
                            $arrayConditionA[$item['condition_id']] = $item['day_appointment'];
                            break;
                        case 3:
                            $arrayConditionA[$item['condition_id']] = $item['status_appointment'];
                            break;
                        case 4:
                            $arrayConditionA[$item['condition_id']] = $item['time_appointment'];
                            break;
                        case 5:
                            $arrayConditionA[$item['condition_id']] = $item['not_appointment'];
                            break;
                        case 6:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['use_service']);
                            break;
                        case 7:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['not_use_service']);
                            break;
                        case 8:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['use_product']);
                            break;
                        case 9:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['not_use_product']);
                            break;
                        case 10:
                            $arrayConditionA[$item['condition_id']] = $item['not_order'];
                            break;
                        case 11:
                            $arrayConditionA[$item['condition_id']] = $item['inactive_app'];
                            break;
                        case 12:
                            $arrayConditionA[$item['condition_id']] = $item['use_promotion'];
                            break;
                        case 13:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['is_rank']);
                            break;
                        case 14:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['range_point']);
                            break;
                        case 15:
                            $arrayConditionA[$item['condition_id']] = $item['top_high_revenue'];
                            break;
                        case 16:
                            $arrayConditionA[$item['condition_id']] = $item['top_low_revenue'];
                            break;
                        case 17:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['use_service_card']);
                            break;
                        case 18:
                            $arrayConditionA[$item['condition_id']] = json_decode($item['address']);
                            break;
                        case 19:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['type_customer']);
                            break;
                        case 20:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['group_customer']);
                            break;
                        case 21:
                            $arrayConditionA[$item['condition_id']] = explode(',', $item['source_customer']);
                            break;
                    }
                }
            } elseif ($item['group_type'] == 'B') {
                if (isset($arrayConditionB[$item['condition_id']])) {
                    switch ($item['condition_id']) {
                        case 1:
                            $arrayConditionB[$item['condition_id']] = $item['customer_group_define_id'];
                            break;
                        case 2:
                            $arrayConditionB[$item['condition_id']] = $item['day_appointment'];
                            break;
                        case 3:
                            $arrayConditionB[$item['condition_id']] = $item['status_appointment'];
                            break;
                        case 4:
                            $arrayConditionB[$item['condition_id']] = $item['time_appointment'];
                            break;
                        case 5:
                            $arrayConditionB[$item['condition_id']] = $item['not_appointment'];
                            break;
                        case 6:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['use_service']);
                            break;
                        case 7:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['not_use_service']);
                            break;
                        case 8:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['use_product']);
                            break;
                        case 9:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['not_use_product']);
                            break;
                        case 10:
                            $arrayConditionB[$item['condition_id']] = $item['not_order'];
                            break;
                        case 11:
                            $arrayConditionB[$item['condition_id']] = $item['inactive_app'];
                            break;
                        case 12:
                            $arrayConditionB[$item['condition_id']] = $item['use_promotion'];
                            break;
                        case 13:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['is_rank']);
                            break;
                        case 14:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['range_point']);
                            break;
                        case 15:
                            $arrayConditionB[$item['condition_id']] = $item['top_high_revenue'];
                            break;
                        case 16:
                            $arrayConditionB[$item['condition_id']] = $item['top_low_revenue'];
                            break;
                        case 17:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['use_service_card']);
                            break;
                        case 18:
                            $arrayConditionB[$item['condition_id']] = json_decode($item['address']);
                            break;
                        case 19:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['type_customer']);
                            break;
                        case 20:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['group_customer']);
                            break;
                        case 21:
                            $arrayConditionB[$item['condition_id']] = explode(',', $item['source_customer']);
                            break;
                    }
                }
            }
        }

        if ($customerGroup != null) {
            return view(
                'admin::customer-group-filter.auto.edit',
                [
                    'listServiceCard'         => $listServiceCard,
                    'listRank'         => $listRank,
                    'condition'            => $condition,
                    'customerGroupDefine'  => $customerGroupDefine,
                    'listService'          => $listService,
                    'listProduct'          => $listProduct,
                    'customerGroup'        => $customerGroup,
                    'listProvinces'    => $listProvinces,
                    'listSourceCustomer'    => $listSourceCustomer,
                    'listGroupCustomer'    => $listGroupCustomer,
                    'listDistrict' => $listDistrict ?? '',
                    'listDistrictB' => $listDistrictB ?? '',
                    'customerGroupDetail' => $customerGroupDetail,
                    'arrayConditionA' => $arrayConditionA,
                    'arrayConditionB' => $arrayConditionB,
                    'customerGroupSystem' => $customerGroupSystem
                ]
            );
        } else {
            return redirect()->route('admin.customer-group-filter');
        }
    }

    /**
     * Chỉnh sửa nhóm khách hàng tự động
     * @param StoreRequest $request
     * @return mixed
     */
    public function submitEditAutoAction(UpdateRequest $request)
    {
        $data = $request->all();
        $result = $this->customerGroupFilter->submitEditAutoAction($data);
        return $result;
    }

    public function getCustomerInGroupAuto(Request $request)
    {
        $id = intval($request->id);
        return $this->customerGroupFilter->getCustomerInGroupAuto($id);
    }

    public function getCustomerInGroup(Request $request)
    {
        $id = intval($request->id);
        return $this->customerGroupFilter->getCustomerInGroup($id);
    }

    /**
     * Search danh sách nhóm KH động dựa vào loại (auto/user_define)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerGroupFilterOption(Request $request)
    {
        $data =  $this->customerGroupFilter->getOptionByType($request->filter_type_group);
        return response()->json($data);
    }

    /**
     * Xoá nhóm KH động
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGroupAuto(Request $request)
    {
        $data =  $this->customerGroupFilter->deleteGroupAuto($request->id);
        return response()->json($data);
    }

    /**
     * Xoá nhóm KH tự định nghĩa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGroupDefine(Request $request)
    {
        $data =  $this->customerGroupFilter->deleteGroupDefine($request->id);
        return response()->json($data);
    }
}
