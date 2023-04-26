<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomerLeadExport;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CustomerLead\Models\CustomerCareTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Http\Requests\CustomerLead\StoreRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\UpdateRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\ManageWorkAddRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\UpdateFromOncallRequest;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoInterface;

class CustomerLeadController extends Controller
{
    protected $customerLead;

    public function __construct(
        CustomerLeadRepoInterface $customerLead
    ) {
        $this->customerLead = $customerLead;
    }

    /**
     * Danh sách KH tiềm năng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        //Option đầu mối doanh nghiệp
        $optionBusiness = $this->customerLead->getOptionBusiness();

        return view('customer-lead::customer-lead.index', [
            // 'LIST' => [],
            'FILTER' => $this->filters(),
            'param' => $request->all(),
            'optionBusiness' => $optionBusiness
        ]);
    }

    public function detail($customerLeadId){
        $data = $this->customerLead->dataDetail($customerLeadId);
        return view('customer-lead::customer-lead.detail', $data);
    }

    public function editLead($customerLeadId){
        $data = $this->customerLead->dataEdit($customerLeadId);
        return view('customer-lead::customer-lead.edit', $data);
    }

    public function add(){
        $data = $this->customerLead->dataCreate();
        return view('customer-lead::customer-lead.add', $data);
    }
    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        $listTag = (['' => __('Chọn tag')]) + $this->customerLead->getListTag();
        $listAssign = ([
            '' => __('Trạng thái'),
            'assigned' => __('Đã phân công'),
            'not_assign' => __('Chưa phân công'),
        ]);
        $listCS = (['' => __('Chọn nguồn khách hàng')]) + $this->customerLead->getListCustomerSource();
        //Lấy ds nhân viên
        $listStaff = (['' => __('Chọn người được phân bổ')]) + $this->customerLead->getListStaff();
        //Danh sách pipeline
        $listPipeline = (['' => __('Chọn pipeline')]) + $this->customerLead->getListPipeline();

        return [
            'tag_id' => [
                'data' => $listTag
            ],
            'customer_type' => [
                'data' => [
                    '' => __('Chọn loại khách hàng'),
                    'personal' => __('Cá nhân'),
                    'business' => __('Doanh nghiệp')
                ]
            ],
            'assign' => [
                'data' => $listAssign
            ],
            'customer_source' => [
                'data' => $listCS
            ],
            'sale_id' => [
                'data' => $listStaff
            ],
            'pipeline_code' => [
                'data' => $listPipeline
            ]
        ];
    }

    /**
     * Ajax filter, phân trang ds customer lead
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
            'is_convert',
            'created_at',
            'tag_id',
            'assign',
            'customer_source',
            'sale_id',
            'customer_type',
            'pipeline_code',
            'journey_code',
            'allocation_date'
        ]);

        $data = $this->customerLead->list($filter);

        return view('customer-lead::customer-lead.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm KH tiềm năng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create(Request $request)
    {
        $data = $this->customerLead->dataViewCreate($request->all());

        return response()->json($data);
    }

    /**
     * Thêm KH tiềm năng
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    
    {
        return $this->customerLead->store($request->all());
    }

    /**
     * View chỉnh sửa KH tiềm năng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->customerLead->dataViewEdit($request->all());

        return response()->json($data);
    }

    /**
     * Chỉnh sửa KH tiềm năng
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->customerLead->update($request->all());
    }
    public function updateFromOncall(UpdateFromOncallRequest $request)
    {
        return $this->customerLead->updateFromOncall($request->all());
    }

    /**
     * Xóa KH tiềm năng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->customerLead->destroy($request->all());

        return response()->json($data);
    }

    /**
     * Show popup chăm sóc khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupCustomerCareAction(Request $request)
    {
        $data = $this->customerLead->popupCustomerCare($request->all());

        return response()->json($data);
    }

    /**
     * Chăm sóc khách hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function customerCareAction(ManageWorkAddRequest $request)
    {
        $data = $this->customerLead->customerCare($request->all());
        return response()->json($data);
    }

    /**
     * Chi tiết khách hàng tiềm năng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show(Request $request)
    {
        $data = $this->customerLead->dataViewEdit($request->all());

        return response()->json($data);
    }

    /**
     * KHTN->detail : tab danh sách CSKH
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function showListCare(Request $request)
    {
        $filter = $request->all();
        $mCustomerCare = new CustomerCareTable();
        $data = $mCustomerCare->getListCustomerCare($filter);

        return view('customer-lead::customer-lead.list-care', [
            'dataCare' => $data,
            'page' => $filter['page']
        ]);
    }

    /**
     * KHTN->detail : tab danh sách deal
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function showListDeal(Request $request)
    {
        $filter = $request->all();
        $mCustomerCare = new CustomerDealTable();
        $data = $mCustomerCare->getListDealLeadDetail($filter);

        return view('customer-lead::customer-lead.list-deal', [
            'dataDeal' => $data,
            'page' => $filter['page']
        ]);
    }

    /**
     * View kan ban view KH tiềm năng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function kanBanViewNewAction(){
        return view('customer-lead::customer-lead.custom.index');
    }

    /**
     * View kan ban view KH tiềm năng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function kanBanViewAction(){
        $optionKanban = $this->customerLead->optionViewKanban();
        return view('customer-lead::customer-lead.kan-ban-view', $optionKanban);
    }

    public function getSearchOption(){
        return [
            'searchConfig' => $this->customerLead->optionViewKanban()
        ];
    }

    /**
     * View kan ban view KH tiềm năng (VueJS)
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function kanBanViewVueAction(){
        return view('customer-lead::customer-lead.kanban-vue');
    }

    /**
     * Load view kan ban
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadKanBanViewAction(Request $request)
    {
        $data = $this->customerLead->loadKanBanView($request->all());

        return response()->json($data);
    }

    /**
     * Load view kanban (Vuejs)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadKanBanVueAction(Request $request)
    {
        $data = $this->customerLead->loadKanBanVue($request->all());

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
        $data = $this->customerLead->updateJourney($request->all());

        return response()->json($data);
    }

    /**
     * Load danh sách hành trình theo pipeline code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadOptionJourney(Request $request)
    {
        $pipelineCode = $request->pipeline_code;
        $data = $this->customerLead->loadOptionJourney($pipelineCode);
        return response()->json($data);
    }

    /**
     * Chuyển đổi KH, và insert KH không kèm deal
     *
     * @param Request $request
     * @return mixed
     */
    public function convertCustomerNoDeal(Request $request)
    {
        return $this->customerLead->convertCustomerNoDeal($request->all());
    }

    /**
     * Tạo deal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDeal(Request $request)
    {
        $data = $this->customerLead->dataViewCreateDeal($request->all());

        return response()->json($data);
    }

    /**
     * Xuat file excel không filter
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelAll(Request $request)
    {
        return $this->customerLead->exportExcelAll($request->all());
    }

    public function importExcel(Request $request)
    {
        return $this->customerLead->importExcel($request->file('file'));
    }

    public function exportExcelTemplate()
    {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new CustomerLeadExport(), 'customer-lead.xlsx');
    }

    /**
     * View danh sách nhân viên marketing
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupListStaff(Request $request)
    {
        $data = $this->customerLead->popupListStaff($request->all());
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
        return $this->customerLead->saveAssignStaff($request->all());
    }

    /**
     * Thu hồi 1 lead
     *
     * @param Request $request
     * @return mixed
     */
    public function revokeOne(Request $request)
    {
        return $this->customerLead->revokeOne($request->all());
    }

    /**
     * View màn hình phân bổ nhiều
     *
     */
    public function assign()
    {
        $data = $this->customerLead->dataViewAssign();
        $list = $this->customerLead->listLeadNotAssignYet([]);

        $arrLeadTemp = [];
        if (session()->get('lead')) {
            $arrLeadTemp = session()->get('lead');
        }
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrLeadTemp);
        session()->forget('remove_lead');

        return view('customer-lead::customer-lead.assign', [
            'list' => $list['list'],
            'FILTER' => $this->listLeadNotAssignYetFilter(),
            'optionDepartment' => $data['optionDepartment'],
            'listPipeline' => $this->customerLead->getListPipeline(),
        ]);
    }

    public function submitAssign(Request $request)
    {
        return $this->customerLead->submitAssign($request->all());
    }

    /**
     * View màn hình thu hồi 1 lần nhiều
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request)
    {
        $data = $this->customerLead->popupRevoke($request->all());
        return response()->json($data);
    }

    /**
     * submit thu hồi
     *
     * @param Request $request
     * @return mixed
     */
    public function submitRevoke(Request $request)
    {
        return $this->customerLead->submitRevoke($request->all());
    }

    /**
     * Filter danh sách lead chưa được phân bổ
     *
     * @return array
     */
    public function listLeadNotAssignYetFilter()
    {
        $listCS = (['' => __('Chọn nguồn khách hàng')]) + $this->customerLead->getListCustomerSource();
        return [
            'customer_source' => [
                'data' => $listCS
            ],
        ];
    }

    /**
     * Danh sách lead chưa được phân bổ
     *
     * @param Request $request
     * @return mixed
     */
    public function getListLeadNotAssignYet(Request $request)
    {

        $filter = $request->only(['page', 'display', 'search', 'customer_source', 'journey_code_', 'pipeline_code_']);

        $list = $this->customerLead->listLeadNotAssignYet($filter);

        //Get session lead temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        return view('customer-lead::customer-lead.assign-list-lead', [
            'list' => $list['list'],
            'page' => $filter['page'],
            'arrLeadTemp' => $arrLeadTemp
        ]);
    }

    /**
     * Danh sách sale theo mảng department
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionSaleByArrayDepartment(Request $request)
    {
        $data = $this->customerLead->loadOptionSale($request->all());
        return response()->json($data);
    }

    /**
     * Chọn all trên 1 page lead
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAllAction(Request $request)
    {
        $data = $this->customerLead->chooseAll($request->all());

        return response()->json($data);
    }

    /**
     * Chọn lead
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAction(Request $request)
    {
        $data = $this->customerLead->choose($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn all trên 1 page lead
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAllAction(Request $request)
    {
        $data = $this->customerLead->unChooseAll($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn lead
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAction(Request $request)
    {
        $data = $this->customerLead->unChoose($request->all());

        return response()->json($data);
    }

    public function checkAllLeadAction(Request $request)
    {
        $data = $this->customerLead->checkAllLead($request->all());

        return response()->json($data);
    }

    /**
     * Export excel file lỗi
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelError(Request $request)
    {
        return $this->customerLead->exportError($request->all());
    }

    /**
     * Tạo deal tự động
     *
     * @param Request $request
     * @return mixed
     */
    public function createDealAutoAction(Request $request)
    {
        return $this->customerLead->createDealAuto($request->all());
    }

    /**
     * Show modal gọi on call
     *
     * @param Request $request
     * @return mixed
     */
    public function showModalCall(Request $request)
    {
        return $this->customerLead->showModalCall($request->all());
    }

    /**
     * Gọi (on call)
     *
     * @param Request $request
     * @return mixed
     */
    public function callUserAction(Request $request)
    {
        return $this->customerLead->call($request->all());
    }

    /**
     * Upload file
     */
    public function uploadFileAction(Request $request)
    {
        $param = $request->all();
        $data = $this->customerLead->uploadFile($param);
        return response()->json($data);
    }

    /**
     * Tìm kiếm công việc
     * @param Request $request
     */
    public function searchWorkLead(Request $request)
    {
        $param = $request->all();
        $data = $this->customerLead->searchWorkLead($param);
        return response()->json($data);
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
            $id = $request->customer_lead_id;
            $listComment = $this->customerLead->getListComment($id);
            $html = \View::make('customer-lead::customer-lead.append.list-customer-comment',[
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
        $data = $this->customerLead->addComment($param);
        return response()->json($data);
    }
	
	/**
     * hiển thị form comment
     * @param Request $request
     */
    public function showFormComment(Request $request)
    {
        $param = $request->all();
        $data = $this->customerLead->showFormComment($param);
        return response()->json($data);
    }

    public function addNoteAction(Request $request){
        try {
            $param = $request->all();
            $data = $this->customerLead->addNote($param);
    
            if($data){
                return response()->json([
                    'error' => 0,
                    'data' => $data,
                    'message' => 'Thêm ghi chú thành công'
                ]);
            }
    
            return response()->json([
                'error' => 1,
                'message' => 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 1,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function popupAddFileAction(Request $request){
        try {
            $param = $request->all();
            $data = $this->customerLead->showPopupAddFile($param);
    
            if($data){
                return response()->json([
                    'error' => 0,
                    'data' => $data,
                    'message' => 'Xử lý thành công'
                ]);
            }
    
            return response()->json([
                'error' => 1,
                'message' => 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 1,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function addFileAction(Request $request){
        try {
            $param = $request->all();
            $data = $this->customerLead->addFile($param);
    
            if($data){
                return response()->json([
                    'error' => 0,
                    'data' => $data,
                    'message' => 'Xử lý thành công'
                ]);
            }
    
            return response()->json([
                'error' => 1,
                'message' => 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 1,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function showEditFileAction(Request $request){
        try {
            $param = $request->all();
            $html = $this->customerLead->showEditFile($param);
    
            if($html){
                return response()->json([
                    'error' => 0,
                    'data' => $html,
                    'message' => 'Xử lý thành công'
                ]);
            }
    
            return response()->json([
                'error' => 1,
                'message' => 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 1,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function addContactAction(Request $request){
        try {
            $param = $request->all();
            $data = $this->customerLead->addContact($param);
    
            if($data){
                return response()->json([
                    'error' => 0,
                    'data' => $data,
                    'message' => 'Thêm liên hệ thành công'
                ]);
            }
    
            return response()->json([
                'error' => 1,
                'message' => 'Có lỗi xảy ra'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 1,
                'message' => $th->getMessage()
            ]);
        }
    }
}
