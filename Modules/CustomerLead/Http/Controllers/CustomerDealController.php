<?php

namespace Modules\CustomerLead\Http\Controllers;

use App\Jobs\CheckMailJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Models\CustomerAccountTable;
use Modules\Admin\Models\CustomerFileTable;
use Modules\Admin\Models\CustomerTable;
use Modules\CustomerLead\Http\Requests\CustomerDeal\StoreRequest;
use Modules\CustomerLead\Http\Requests\CustomerDeal\UpdateRequest;
use Modules\CustomerLead\Models\ConfigTable;
use Modules\CustomerLead\Models\CustomerBranchTable;
use Modules\CustomerLead\Http\Requests\CustomerLead\ManageWorkAddRequest;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Repositories\CustomerDeal\CustomerDealRepoInterface;

class CustomerDealController extends Controller
{
    protected $customerDeal;

    public function __construct(CustomerDealRepoInterface $customerDeal)
    {
        $this->customerDeal = $customerDeal;
    }

    public function index(Request $request)
    {
        $data = $this->customerDeal->list();
        $dataView = $this->customerDeal->dataViewIndex();
        return view('customer-lead::customer-deal.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
            'param' => $request->all(),
            "optionPipeline" => $dataView['optionPipeline'],
            "optionOrderSource" => $dataView['optionOrderSource'],
            "optionBranches" => $dataView['optionBranches'],
            "optionStaffs" => $dataView['optionStaffs'],
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {

        return [];
    }

    /**
     * Ajax filter, phân trang ds customer deal
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
            'pipeline_code',
            'journey_code',
            'branch_code',
            'order_source_id',
            'closing_date',
            'closing_due_date',
            'owner',
            'compare',
            'value',
        ]);
        $data = $this->customerDeal->list($filter);

        return view('customer-lead::customer-deal.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View tạo customer deal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $this->customerDeal->dataViewCreate($request->all());

        return response()->json($data);
    }

    /**
     * Lưu deal
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $this->customerDeal->store($request->all());
        return $data;
    }

    /**
     * View chỉnh sửa deal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->customerDeal->dataViewEdit($request->all());
        return response()->json($data);
    }

    public function update(UpdateRequest $request)
    {
        $data = $this->customerDeal->update($request->all());
        return $data;
    }

    public function detail(Request $request)
    {
        $data = $this->customerDeal->dataViewDetail($request->all());
        return response()->json($data);
    }

    /**
     * Tìm kiếm khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerAction(Request $request)
    {
        $search = $this->customerDeal->searchCustomerAction($request->all());
        return response()->json($search);
    }

    /**
     * Danh sách liên hệ theo customer code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadOptionCustomerContact(Request $request)
    {
        $data = $this->customerDeal->optionCustomerContact($request->all());
        return response()->json($data);
    }

    /**
     * Load danh sách các object theo object type (product, service, service_card)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadObject(Request $request)
    {
        $data = $this->customerDeal->loadObject($request->all());
        return response()->json($data);
    }

    /**
     * Lấy giá của object (sản phẩm, dịch vụ, thẻ dịch vụ)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPriceObject(Request $request)
    {
        $data = $this->customerDeal->getPriceObject($request->all());
        return response()->json($data);
    }

    /**
     * Xoa deal
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $data = $this->customerDeal->destroy($request->deal_id);
        return $data;
    }

    /**
     * Kanban view
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function kanbanView()
    {
        $optionKanban = $this->customerDeal->optionViewKanban();
        return view('customer-lead::customer-deal.kanban-view', $optionKanban);
    }

    /**
     * Kanban VueJS
     *
     */
    public function kanbanVue(){
        return view('customer-lead::customer-deal.kanban-vue');
    }

    /**
     * Kanban getSearchOption
     *
     */
    public function getSearchOption(){
        return [
            'searchConfig' => $this->customerDeal->optionViewKanban()
        ];
    }

    /**
     * load kanban view
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadKanbanView(Request $request)
    {
        $filter = $request->all();
        $data = $this->customerDeal->loadKanbanView($filter);

        return response()->json($data);
    }

    /**
     * Cập nhật hành trình khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateJourneyAction(Request $request)
    {
        $data = $this->customerDeal->updateJourney($request->all());

        return response()->json($data);
    }

    /**
     * giao dien thanh toan don hang tu deal
     *
     * @param $dealId
     * @return array
     */
    public function payment($dealId)
    {
        $data = $this->customerDeal->dataViewPayment($dealId);
        return view('customer-lead::customer-deal.receipt.receipt', $data);
    }

    /**
     * thanh toan truc tiep don hang tu deal
     *
     * @param Request $request
     * @return mixed
     */
    public function submitPayment(Request $request)
    {
        $data = $request->all();
        return $this->customerDeal->submitPayment($data);
    }

    /**
     * luu thong tin don hang tu deal (chua thanh toan)
     *
     * @param Request $request
     * @return mixed
     */
    public function saveOrder(Request $request)
    {
        $data = $request->all();
        return $this->customerDeal->saveOrder($data);
    }

    public function saveOrUpdateOrder(Request $request)
    {
        $data = $request->all();
        return $this->customerDeal->saveOrUpdateOrder($data);
    }
    /**
     * Modal tạo KHTN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modalAddCustomerLead(Request $request)
    {
        $data = $this->customerDeal->dataModalAddCustomerLead($request->all());

        return response()->json($data);
    }

    /**
     * data popup tạo KH từ deal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modalAddCustomer(Request $request)
    {
        $data = $this->customerDeal->dataModalAddCustomer($request->all());

        return response()->json($data);
    }

    /**
     * Lưu KHTN khi tìm KH ko có trong khi tạo deal
     *
     * @param \Modules\CustomerLead\Http\Requests\CustomerLead\StoreRequest $request
     * @return mixed
     */
    public function storeCustomerLead(\Modules\CustomerLead\Http\Requests\CustomerLead\StoreRequest $request)
    {
        return $this->customerDeal->storeCustomerLead($request->all());
    }

    /**
     * Tạo tag khi search không có tag
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeQuicklyTag(Request $request)
    {
        $data = $this->customerDeal->storeQuicklyTag($request->all());
        return response()->json($data);
    }

    /**
     * popup CSKH cho deal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupDealCareAction(Request $request)
    {
        $data = $this->customerDeal->popupCustomerCare($request->all());

        return response()->json($data);
    }

    /**
     * Chăm sóc KH cho deal
     *
     * @param Request $request
     * @return mixed
     */
    public function dealCareAction(ManageWorkAddRequest $request)
    {
        $data = $this->customerDeal->customerCare($request->all());
        return response()->json($data);
    }

    /**
     * Submit tạo khách hàng mới từ deal của khtn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddCustomerFromDeal(Request $request)
    {
        DB::beginTransaction();
        try {
            $mCustomer = new CustomerTable();
            $mCustomerBranch = new CustomerBranchTable();
            $mCustomerLead = new CustomerLeadTable();

            $phone1 = $request->phone;
            $data = [
                'full_name' => $request->full_name,
                'gender' => 'other',
                'phone1' => $request->phone,
                'email' => $request->email,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'address' => $request->address,
                'is_actived' => 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'branch_id' => Auth()->user()->branch_id
            ];

            if ($request->year != null && $request->month != null && $request->day != null) {
                $birthday = $request->year . '-' . $request->month . '-' . $request->day;
                $data['birthday'] = $birthday;
                if ($birthday > date("Y-m-d")) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Ngày sinh không hợp lệ')
                    ]);
                }
            }
            if ($request->gender != null) {
                $data['gender'] = $request->gender;
            }
            //Kiểm tra sđt đã tồn tại chưa
            $test_phone1 = $mCustomer->testPhone($phone1, 0);


            if (!empty($test_phone1)) {
                $mConfig = new ConfigTable();
                //Kiểm tra KH đó có ở chi nhánh này không
                $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($test_phone1['customer_id'], Auth()->user()->branch_id);

                if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại đã tồn tại')
                    ]);
                }

                //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
                $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

                if ($getInsertBranch == 1) {
                    //Tự động insert chi nhánh và lấy customer_id ra
                    $mCustomerBranch->add([
                        'customer_id' => $test_phone1['customer_id'],
                        'branch_id' => Auth()->user()->branch_id
                    ]);
                    //Cập nhật khách hàng được tạo từ deal nào
                    $mCustomerLead->updateByCode([
                        'convert_object_type' => 'customer',
                        'convert_object_code' => $test_phone1['customer_code'],
                    ], $request->customer_lead_code);

                    DB::commit();
                    return response()->json([
                        'error' => false,
                        'message' => __('Thêm khách hàng thành công')
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                    ]);
                }
            }
            //Thêm khách hàng
            $id_add = $mCustomer->add($data);
            //Cập nhật mã khách hàng
            $day_code = date('dmY');
            if ($id_add < 10) {
                $id_add = '0' . $id_add;
            }
            $customerCodeUpdate = 'KH_' . $day_code . $id_add;
            $data_code = [
                'customer_code' => $customerCodeUpdate
            ];
            //Cập nhật mã khách hàng
            $mCustomer->edit($data_code, $id_add);
            //Tự động insert chi nhánh và lấy customer_id ra
            $mCustomerBranch->add([
                'customer_id' =>  $id_add,
                'branch_id' => Auth()->user()->branch_id
            ]);

            $customerLeadCode = $request->customer_lead_code;

            //Cập nhật khách hàng được tạo từ deal nào
            $mCustomerLead->updateByCode([
                'convert_object_type' => 'customer',
                'convert_object_code' => $customerCodeUpdate,
            ], $customerLeadCode);
            DB::commit();
            //            $mNoti = new SendNotificationApi();
            //            $mNoti->sendNotification([
            //                'key' => 'customer_W',
            //                'customer_id' => $id_add,
            //                'object_id' => ''
            //            ]);

            return response()->json([
                'error' => false,
                'message' => __('Thêm khách hàng thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show modal call (on call)
     *
     * @param Request $request
     * @return mixed
     */
    public function showModalCall(Request $request)
    {
        return $this->customerDeal->showModalCall($request->all());
    }

    /**
     * Gọi (on call)
     *
     * @param Request $request
     * @return mixed
     */
    public function callUserAction(Request $request)
    {
        return $this->customerDeal->call($request->all());
    }

    /**
     * check all deal assign per page
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAllAction(Request $request)
    {
        $data = $this->customerDeal->chooseAll($request->all());

        return response()->json($data);
    }

    /**
     * check 1 deal assign
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAction(Request $request)
    {
        $data = $this->customerDeal->choose($request->all());

        return response()->json($data);
    }

    /**
     * un check all deal assign per page
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAllAction(Request $request)
    {
        $data = $this->customerDeal->unChooseAll($request->all());

        return response()->json($data);
    }

    /**
     * un check 1 deal assign
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAction(Request $request)
    {
        $data = $this->customerDeal->unChoose($request->all());

        return response()->json($data);
    }

    /**
     * check all deal assign all page
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAllDealAction(Request $request)
    {
        $data = $this->customerDeal->checkAllDeal($request->all());

        return response()->json($data);
    }
    /**
     * view phân bổ deal
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function assign()
    {
        $data = $this->customerDeal->dataViewAssign();
        $list = $this->customerDeal->listDealNotAssignYet([]);

        $arrLeadTemp = [];
        if (session()->get('lead')) {
            $arrLeadTemp = session()->get('lead');
        }
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrLeadTemp);
        session()->forget('remove_lead');

        return view('customer-lead::customer-deal.assign', [
            'list' => $list['list'],
            'optionDepartment' => $data['optionDepartment'],
            'optionPipeline' => $data['optionPipeline'],
        ]);
    }

    /**
     * ds deal trong view phân bổ deal
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getListLeadNotAssignYet(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search', 'pipeline_code', 'journey_code']);
        $list = $this->customerDeal->listDealNotAssignYet($filter);

        //Get session lead temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        return view('customer-lead::customer-deal.assign-list-lead', [
            'list' => $list['list'],
            'page' => $filter['page'],
            'arrLeadTemp' => $arrLeadTemp
        ]);
    }

    /**
     * submit assgin deal
     *
     * @param Request $request
     * @return mixed
     */
    public function submitAssign(Request $request)
    {
        return $this->customerDeal->submitAssign($request->all());
    }

    /**
     * view revoke assign
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request)
    {
        $data = $this->customerDeal->popupRevoke($request->all());
        return response()->json($data);
    }

    /**
     * revoke assign sale_id
     *
     * @param Request $request
     * @return mixed
     */
    public function submitRevoke(Request $request)
    {
        return $this->customerDeal->submitRevoke($request->all());
    }
    /**
     * View danh sách nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupListStaff(Request $request)
    {
        $data = $this->customerDeal->popupListStaff($request->all());
        return response()->json($data);
    }

    /**
     * Phân công nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function saveAssignStaff(Request $request)
    {
        return $this->customerDeal->saveAssignStaff($request->all());
    }

    /**
     * Thu hồi 1 deal
     *
     * @param Request $request
     * @return mixed
     */
    public function revokeOne(Request $request)
    {
        return $this->customerDeal->revokeOne($request->all());
    }

      /*
    *Thêm phần comment cho ticket
    *Hieupc
    */

    /**
     * lấy danh sách bình luận
     * @param Request $request
     */
    public function getListComment(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->deal_id;
            $listComment = $this->customerDeal->getListComment($id);
            $html = \View::make('customer-lead::customer-deal.append.list-customer-comment',[
                'listComment' => $listComment,
            ])->render();
            return response()->json([
                'html' => $html

            ]);
        }
    }

    /**
     * Thêm bình luận
     * @param Request $request
     */
    public function addComment(Request $request)
    {
        $param = $request->all();
        $data = $this->customerDeal->addComment($param);
        return response()->json($data);
    }
	
	/**
     * hiển thị form comment
     * @param Request $request
     */
    public function showFormComment(Request $request)
    {
        $param = $request->all();
        $data = $this->customerDeal->showFormComment($param);
        return response()->json($data);
    }
}