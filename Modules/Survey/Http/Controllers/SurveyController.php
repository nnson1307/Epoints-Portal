<?php

namespace Modules\Survey\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\ManagerWork\Models\BranchTable;
use Modules\Survey\Http\Requests\Survey\StoreRequest;
use Modules\Survey\Http\Requests\Survey\UpdateRequest;
use Modules\Survey\Http\Requests\Survey\UpdateConfigPointRequest;
use Modules\Survey\Repositories\Survey\SurveyRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Payment\Repositories\CompanyBranch\CompanyBranchRepositoryInterface;

class SurveyController extends Controller
{
    protected $rSurvey;
    protected $province;
    protected $mdepartment;
    protected $mstaffTitle;
    protected $mbranch;

    const NEW = 'N';

    public function __construct(
        SurveyRepositoryInterface $rSurvey,
        ProvinceRepositoryInterface $provinces,
        DepartmentTable $mdepartment,
        BranchTable $mbranch,
        StaffTitleTable $mstaffTitle
    ) {
        $this->rSurvey = $rSurvey;
        $this->province = $provinces;
        $this->mdepartment = $mdepartment;
        $this->mbranch = $mbranch;
        $this->mstaffTitle = $mstaffTitle;
    }


    /**
     * Danh sách khảo sát
     * @return Response
     */
    public function index()
    {
        $filters = request()->all();
        $pathView = 'survey::survey.index';
        if (isset($filters['view']) && $filters['view'] == 'banner') {
            $pathView = 'survey::survey.list-ajax';
            $filter = $filters;
            unset($filters['view'], $filters['bannerId'], $filters['end_point_value']);
            $list = $this->rSurvey->getList($filters);
            return view($pathView, [
                'list' => $list,
                'filter' => $filter,
            ]);
        }
        $list = $this->rSurvey->getList($filters);
        return view($pathView, [
            'list' => $list,
            'filters' => $filters,
        ]);
    }

    /**
     * load tất cả survey
     * @param $request
     * @return mixed
     */

    public function loadAllSurvey(Request $request)
    {
        $filters = $request->all();
        $list = $this->rSurvey->getList($filters);
        $perpage = $filters['perpage'] ?? PAGING_ITEM_PER_PAGE;
        $numberSttCurr = $filters['page'] > 1 ?  ($filters['page'] - 1) * $perpage : 0;
        $view =  view('survey::survey.list', [
            'list' => $list,
            'numberSttCurr' => $numberSttCurr
        ])->render();
        $result = [
            'view' => $view,
            'page' => $filters['page'] ?? 1
        ];
        return response()->json($result);
    }

    public function create()
    {
        return view('survey::survey.create');
    }

    /**
     * Chi tiết khảo sát
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {

        $id = strip_tags($id);
        $detail = $this->rSurvey->getItem($id);
        if (!$detail) {
            return redirect()->route('survey.index');
        }
        return view('survey::survey.show', [
            'detail' => $detail,
        ]);
    }

    /**
     * Chỉnh sửa khảo sát
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $id = strip_tags($id);
        $detail = $this->rSurvey->getItem($id);
        if (!$detail || $detail['status'] != self::NEW) {
            return redirect()->route('survey.index');
        }
        return view('survey::survey.edit', [
            'detail' => $detail,
        ]);
    }

    /**
     * RET-1371
     * Tạo khảo sát
     * @param Request $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $params  = $request->all();
        $result = $this->rSurvey->store($params);
        return $result;
    }

    /**
     * Ngày đóng chương trình
     * @param Request $request
     * @return array
     */
    public function formatCloseDate(Request $request)
    {
        $params  = $request->all();
        $params['date'] = Carbon::createFromFormat('H:i:s d/m/Y', $params['date'])
            ->format('H:i:s d/m/Y');
        return $params;
    }

    /**
     * RET-1757
     * [Brand portal] Chỉnh sửa thông tin chung khảo sát
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $params  = $request->all();
        $result = $this->rSurvey->update($params);
        return $result;
    }

    /**
     * Chỉnh sửa câu hỏi khảo sát
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editQuestion($id)
    {
        $id = strip_tags($id);
        $detail = $this->rSurvey->getItem($id);
        if (!$detail || $detail['status'] != self::NEW) {
            return redirect()->route('survey.index');
        }
        $unique = Carbon::now()->format('YmdHisu');
        // Tạo session default cho tab câu hỏi khảo sát
        $this->rSurvey->setSessionDefaultQuestion($id, $unique);
        return view('survey::survey.question.edit', [
            'detail' => $detail,
            'unique' => $unique,
        ]);
    }

    /**
     * Chi tiết câu hỏi khảo sát
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showQuestion($id)
    {
        $id = strip_tags($id);
        $detail = $this->rSurvey->getItem($id);
        if (!$detail) {
            return redirect()->route('survey.index');
        }
        $unique = Carbon::now()->format('YmdHisu');
        // Tạo session default cho tab câu hỏi khảo sát
        $this->rSurvey->setSessionDefaultQuestion($id, $unique);
        return view('survey::survey.question.show', [
            'detail' => $detail,
            'unique' => $unique,
        ]);
    }

    /**
     * RET-1761
     * [Brand portal] Thêm, sửa và xóa nhóm câu hỏi (block) trong khảo sát
     * @param Request $request
     * @return mixed
     */
    public function addBlock(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->addBlock($params);
        return $result;
    }

    /**
     * Load html Block
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function loadBlock(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->loadBlock($params);
        return $result;
    }

    /**
     * Thay đổi gì đó ở block
     * @param Request $request
     * @return mixed
     */
    public function onChangeBlock(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->onChangeBlock($params);
        return $result;
    }

    /**
     * Render modal chọn loại câu hỏi
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function renderModalQuestionType(Request $request)
    {
        $params = $request->all();
        // kiểm tra khảo sát có tính điểm //
        $itemSurvey = $this->rSurvey->getItem($params['idSurvey']);
        $countPoint = 0;
        if ($itemSurvey) {
            $countPoint = $itemSurvey['count_point'];
        }
        return [
            'html' => view('survey::survey.modal.question-type', [
                'params' => $params,
                'countPoint' => $countPoint,
            ])->render(),
            'params' => $params,
        ];
    }

    /**
     * Render html để thêm câu hỏi vào block
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function addQuestion(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->addQuestion($params);
        return $result;
    }

    /**
     * Render html để thêm câu hỏi vào block
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function loadQuestionInBlock(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->loadQuestionInBlock($params);
        return $result;
    }

    /**
     * Xóa câu hỏi trong block
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function removeQuestion(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->removeQuestion($params);
        return $result;
    }

    /**
     * Thay đổi vị trí của câu hỏi trong block
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function changeQuestionPosition(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->changeQuestionPosition($params);
        return $result;
    }

    /**
     * Chi tiết cài đặt của câu hỏi
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function showConfigQuestion(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->showConfigQuestion($params);
        return $result;
    }

    /**
     * Thay đổi gì đó của câu hỏi
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function onChangeQuestion(Request $request)
    {   
        $params = $request->all();
        $result = $this->rSurvey->onChangeQuestion($params);
        return $result;
    }

    /**
     * Submit save câu hỏi khảo sát
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function updateSurveyQuestion(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->updateSurveyQuestion($params);
        return $result;
    }

    /**
     * Hiển thị mẫu list câu hỏi
     * @param Request $request
     * @return mixed
     */

    public function templateQuestion(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->loadTemplateQuestion($params);
        return response()->json($result);
    }

    /**
     * Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param Request $request
     * @return mixed
     */
    public function showModalNotification(Request $request)
    {
        $params = $request->all();
        return $this->rSurvey->showModalNotification($params);
    }

    /**
     * Cài đặt hiển thị sau khi có tính điểm 
     * @param Request $request
     * @return mixed
     */

    public function showModalConfigPoint(Request $request)
    {
        $params = $request->all();
        return $this->rSurvey->showModalConfigPoint($params);
    }

    /**
     * Update template Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param Request $request
     * @return mixed
     */
    public function updateTemplate(Request $request)
    {
        $params = $request->all();
        return $this->rSurvey->updateTemplate($params);
    }

    /**
     * Update config point Cài đặt cấu hình tính điểm
     * @param Request $request
     * @return mixed
     */

    public function updateConfigPoint(UpdateConfigPointRequest $request)
    {
        $params = $request->all();
        return $this->rSurvey->updateConfigPoint($params);
    }

    /**
     * Option load more
     * @param Request $request
     * @return mixed
     */
    public function optionLoadMore(Request $request)
    {
        $param = $request->all();
        $result = $this->rSurvey->optionLoadMore($param);
        return $result;
    }

    /**
     * Lấy dữ liệu chi tiết khảo sát
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItem(Request $request)
    {
        $param = $request->all();
        stripTagParam($param);
        $detail = $this->rSurvey->getItem($param['id']);
        return response()->json($detail);
    }

    /**
     * Chi tiết tab đối tượng áp dụng
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showBranch($id)
    {
        $id = strip_tags($id);
        $getDetailSurvey = $this->rSurvey->getItemNews($id);
        $detail = $getDetailSurvey['survey'];
        $unique = Carbon::now()->format('YmdHisu');
        if (!$detail) {
            return redirect()->route('survey.index');
        }
        $unique = Carbon::now()->format('YmdHisu');
        $optionProvice = $this->province->getOptionProvince();
        // danh sách thông tin khách hàng (loại khách hàng, nguồn khách hàng)
        $optionCustomer = $this->rSurvey->getAllInfoCustomer();
        // department //
        $department = $this->mdepartment->getAll()->get();
        // staffTitle //
        $staffTitle = $this->mstaffTitle->_getList()->get();
        // branch //
        $branch = $this->mbranch->getAll();
        if (isset($getDetailSurvey['typeApply']) && $getDetailSurvey['typeApply'] == 'staff') {
            return  $this->editBranchStaff(
                $getDetailSurvey,
                $unique,
                $optionProvice,
                $optionCustomer,
                $branch,
                $staffTitle,
                $department,
                $id,
                $detail,
                'show',
                ''
            );
        } elseif (isset($getDetailSurvey['typeApply']) && $getDetailSurvey['typeApply'] == 'customer') {
            return $this->editBranchCustomer(
                $getDetailSurvey,
                $unique,
                $optionProvice,
                $optionCustomer,
                $branch,
                $staffTitle,
                $department,
                $id,
                $detail,
                'show',
                ''
            );
        } else {
            return view('survey::survey.branch.show', [
                'id' => $id,
                'unique' => $unique,
                'detail' => $detail,
                'optionProvince' => $optionProvice,
                'optionCustomer' => $optionCustomer,
                'branch' => $branch,
                'staffTitle' => $staffTitle,
                'department' => $department
            ]);
        }
    }

    /**
     * Chỉnh sửa tab đối tượng áp dụng
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editBranch($id, Request $request)
    {
        $id = strip_tags($id);
        $getDetailSurvey = $this->rSurvey->getItemNews($id);
        $detail = $getDetailSurvey['survey'];
        $unique = Carbon::now()->format('YmdHisu');
        if (!$detail || $detail['status'] != self::NEW) {
            return redirect()->route('survey.index');
        }
        $typeShowModalCustomerGroup = $request->type ?? '';
        $optionProvice = $this->province->getOptionProvince();
        // danh sách thông tin khách hàng (loại khách hàng, nguồn khách hàng)
        $optionCustomer = $this->rSurvey->getAllInfoCustomer();
        // department //
        $department = $this->mdepartment->getAll()->get();
        // staffTitle //
        $staffTitle = $this->mstaffTitle->_getList()->get();
        // branch //
        $branch = $this->mbranch->getAll();
        if (isset($getDetailSurvey['typeApply']) &&  $getDetailSurvey['typeApply'] == 'staff') {
            return  $this->editBranchStaff(
                $getDetailSurvey,
                $unique,
                $optionProvice,
                $optionCustomer,
                $branch,
                $staffTitle,
                $department,
                $id,
                $detail,
                'edit',
                $typeShowModalCustomerGroup
            );
        } elseif (isset($getDetailSurvey['typeApply']) && $getDetailSurvey['typeApply'] == 'customer') {
            return $this->editBranchCustomer(
                $getDetailSurvey,
                $unique,
                $optionProvice,
                $optionCustomer,
                $branch,
                $staffTitle,
                $department,
                $id,
                $detail,
                'edit',
                $typeShowModalCustomerGroup
            );
        } else {
            return view('survey::survey.branch.edit', [
                'id' => $id,
                'unique' => $unique,
                'detail' => $detail,
                'optionProvince' => $optionProvice,
                'optionCustomer' => $optionCustomer,
                'branch' => $branch,
                'staffTitle' => $staffTitle,
                'department' => $department,
                'typeShowModalCustomerGroup' => $typeShowModalCustomerGroup
            ]);
        }
    }

    /**
     * Chỉnh sửa tab đối tượng áp dụng (nhân viên)
     * @param $getDetailSurvey
     * @param $unique
     * @param $optionProvice
     * @param $optionCustomer
     * @param $branch
     * @param $staffTitle
     * @param $department
     * @param $id
     * @param $detail
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function editBranchStaff(
        $getDetailSurvey,
        $unique,
        $optionProvice,
        $optionCustomer,
        $branch,
        $staffTitle,
        $department,
        $id,
        $detail,
        $type,
        $typeShowModalCustomerGroup
    ) {
        $listStaff = $getDetailSurvey['listStaff'];
        $ssSelected = $unique . '.item_selected_staff';
        $listItemDepartment = $getDetailSurvey['listItemDepartment'];
        $listItemBranch = $getDetailSurvey['listItemBranch'];
        $listItemTitile = $getDetailSurvey['listItemTitile'];
        $listItemDepartmentPopup = $getDetailSurvey['listItemDepartmentPopup'];
        $listItemBranchPopup = $getDetailSurvey['listItemBranchPopup'];
        $listItemTitilePopup = $getDetailSurvey['listItemTitilePopup'];
        $typeCondition = $getDetailSurvey['typeCondition'];
        session()->put($ssSelected, $listStaff);
        return view("survey::survey.branch.$type", [
            'id' => $id,
            'unique' => $unique,
            'detail' => $detail,
            'optionProvince' => $optionProvice,
            'optionCustomer' => $optionCustomer,
            'branch' => $branch,
            'staffTitle' => $staffTitle,
            'department' => $department,
            'listItemDepartment' => $listItemDepartment,
            'listItemTitile' => $listItemTitile,
            'listItemBranch' => $listItemBranch,
            'listItemDepartmentPopup' => $listItemDepartmentPopup,
            'listItemBranchPopup' => $listItemBranchPopup,
            'listItemTitilePopup' => $listItemTitilePopup,
            'typeCondition' => $typeCondition,
            'typeShowModalCustomerGroup' => $typeShowModalCustomerGroup
        ]);
    }

    /**
     * Chỉnh sửa tab đối tượng áp dụng (khách hàng)
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function editBranchCustomer(
        $getDetailSurvey,
        $unique,
        $optionProvice,
        $optionCustomer,
        $branch,
        $staffTitle,
        $department,
        $id,
        $detail,
        $type,
        $typeShowModalCustomerGroup
    ) {
        $listCustomer = $getDetailSurvey['listCustomer'];
        $itemCustomerFilter = $getDetailSurvey['itemCustomerFilter'];
        $ssSelected = $unique . '.item_selected_customer';
        session()->put($ssSelected, $listCustomer);
        return view("survey::survey.branch.$type", [
            'id' => $id,
            'unique' => $unique,
            'detail' => $detail,
            'optionProvince' => $optionProvice,
            'optionCustomer' => $optionCustomer,
            'branch' => $branch,
            'staffTitle' => $staffTitle,
            'department' => $department,
            'itemCustomerFilter' => $itemCustomerFilter,
            'typeShowModalCustomerGroup' => $typeShowModalCustomerGroup
        ]);
    }

    /**
     * Xóa khảo sát
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $param = $request->all();
        stripTagParam($param);
        $detail = $this->rSurvey->destroy($param['id']);
        return response()->json($detail);
    }

    /**
     * Xóa khảo sát
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $param = $request->all();
        stripTagParam($param);
        $detail = $this->rSurvey->changeStatus($param);
        return response()->json($detail);
    }

    /**
     * RET-1767 [Brand portal] Báo cáo kết quả thực hiện khảo sát (multi choice, text entry)
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *
     */
    public function report($id)
    {
        $id = strip_tags($id);
        $detail = $this->rSurvey->getItem($id);
        if (!$detail) {
            return redirect()->route('survey.index');
        }
        $optionProvice = $this->province->getOptionProvince();
        $data = ['detail' => $detail, 'optionProvice' => $optionProvice];
        return view('survey::survey.report.index', $data);
    }

    /**
     * Option question
     * @param Request $request
     * @return mixed
     */
    public function optionQuestion(Request $request)
    {
        $param = $request->all();
        $result = $this->rSurvey->optionQuestion($param);
        return $result;
    }

    /**
     * RET-8593 [Brand portal] Xuất dữ liệu báo cáo khảo sát
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportReport(Request $request)
    {
        $id = $request->idSurvey;
        $result = $this->rSurvey->exportReport($id);
        return $result;
    }

    /**
     * hiển thị modal xoá block
     * @param $request  
     * @return mixed
     */

    public function showModalRemoveBlock(request $request)
    {
        $key = $request->only(['key']);
        $view = view('survey::survey.question.modal.remove_question', ['key' => $key])->render();
        $result = ['view' => $view];
        return response()->json($result);
    }

    /**
     * load tất cả danh sách báo cáo
     * @param $request
     * @return mixed
     */

    public function loadAllReport(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->getListReportAll($params);

        return response()->json($result);
    }



    /**
     * hiển thị báo cáo chi tiết 
     * @param $request
     * @return mixed
     */

    public function showReportDetail($survey_id)
    {
        $params = ['survey_id' => $survey_id];
        $result = $this->rSurvey->getItemFirstReportUser($params);
        return $result;
    }

    /**
     * hiển thị báo cáo chi tiết ajax
     */

    public function loadDetailReport(Request $request)
    {
        $params = $request->all();
        $result = $this->rSurvey->getListAllReportUser($params);
        return response()->json($result);
    }

    /**
     * báo cáo tổng quan
     */
    public function overviewReport($id)
    {
        $result = $this->rSurvey->getAllQuestionReport($id);
        return view('survey::survey.report.overview.index', $result);
    }

    /**
     * chi tiết các trả lời 
     * @param $idAnswer
     * @return mixed
     */
    public function showReportItemDetail($id_answer)
    {
        $result = $this->rSurvey->showAnswerByUser($id_answer);
        return $result;
    }

    /**
     * hiển thị modal coppy
     * @param Request $request
     * @return mixed
     */

    public function showModalCoppy(Request $request)
    {
        $idSurvey = $request->idSurvey;
        $view = view('survey::survey.modal.coppy-survey', ['id' => $idSurvey])->render();
        $result = [
            'view' => $view
        ];
        return response()->json($result);
    }

    /**
     * hiển thị modal coppy url
     * @param Request $request
     * @return mixed
     */

    public function showModalCoppyUrl(Request $request)
    {
        $idSurvey = $request->idSurvey;
        $data = $this->rSurvey->getItem($idSurvey);
        $view = view('survey::survey.modal.coppy-survey-url', ['data' => $data])->render();
        $result = [
            'view' => $view
        ];
        return response()->json($result);
    }

    /**
     * Coppy khảo sát
     * @param request $request
     * @return mixed
     */

    public function coppySurvey(Request $request)
    {
        $idSurvey = $request->idSurvey;
        $result = $this->rSurvey->coppySurvey($idSurvey);
        return $result;
    }
}
