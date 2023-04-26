<?php

namespace Modules\Survey\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\ManagerWork\Models\BranchTable;
use Modules\Admin\Models\CustomerGroupFilterTable;
use Modules\Survey\Http\Requests\Survey\UpdateApplyRequest;
use Modules\Survey\Repositories\Branch\ApplyRepositoryInterface;
use Modules\Survey\Repositories\Survey\SurveyRepositoryInterface;
use Modules\Survey\Http\Requests\Survey\conditionStaffGroupRequest;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;

class ApplyBranchController extends Controller
{
    protected $rApply;
    protected $province;
    protected $rSurvey;
    protected $mdepartment;
    protected $mstaffTitle;
    protected $mbranch;

    public function __construct(
        ApplyRepositoryInterface $rApply,
        ProvinceRepositoryInterface $provinces,
        SurveyRepositoryInterface $rSurvey,
        DepartmentTable $mdepartment,
        BranchTable $mbranch,
        StaffTitleTable $mstaffTitle
    ) {
        $this->rApply = $rApply;
        $this->province = $provinces;
        $this->rSurvey = $rSurvey;
        $this->mdepartment = $mdepartment;
        $this->mbranch = $mbranch;
        $this->mstaffTitle = $mstaffTitle;
    }
    /**
     * Render popup thêm khách hàng
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function renderPopupCustomer(Request $request)
    {
        // danh sách thông tin khách hàng (loại khách hàng, nguồn khách hàng)
        $optionCustomer = $this->rSurvey->getAllInfoCustomer();
        $optionProvince = $this->province->getOptionProvince();
        $view = view('survey::branch.popup.customer', [
            "optionProvince" => $optionProvince,
            "optionCustomer" => $optionCustomer,
        ])->render();
        return $view;
    }

    /**
     * Render popup thêm nhân viên
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */

    public function renderPopupStaff(Request $request)
    {
        // danh sách thông tin khách hàng (loại khách hàng, nguồn khách hàng)
        $optionProvince = $this->province->getOptionProvince();
        // department //
        $department = $this->mdepartment->getAll()->get();
        // staffTitle //
        $staffTitle = $this->mstaffTitle->_getList()->get();
        // branch //
        $branch = $this->mbranch->getAll();
        $view = view('survey::branch.popup.staff', [
            "optionProvince" => $optionProvince,
            'branch' => $branch,
            'staffTitle' => $staffTitle,
            'department' => $department,
        ])->render();
        return $view;
    }

    /**
     * Render popup thêm khách hàng
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function renderPopupCustomerAuto(Request $request)
    {
        // danh sách thông tin khách hàng (loại khách hàng, nguồn khách hàng)
        $idSurvey = $request->id;
        $view = view('survey::branch.popup.customer-auto' , ['id' => $idSurvey])->render();
        return $view;
    }

    /**
     * Tìm kiếm khách hàng 
     * @param Request $request
     * @return mixed
     */
    public function searchCustomer(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->searchCustomer($params);
        return $result;
    }

    /**
     * Tìm kiếm Nhân viên 
     * @param Request $request
     * @return mixed
     */
    public function searchStaff(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
      
        $result = $this->rApply->searchStaff($params);
        return $result;
    }

    /**
     * Tìm kiếm khách hàng động
     * @param Request $request
     * @return mixed
     */
    public function searchCustomerAuto(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->searchCustomerAuto($params);
        return $result;
    }

    /**
     * Checked item (chi nhánh) - tạm
     * @param Request $request
     * @return mixed
     */
    public function checkedItemTempCustomer(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->checkedItemTempCustomer($params);
        return $result;
    }

    /**
     * Checked item (chi nhánh) - tạm
     * @param Request $request
     * @return mixed
     */
    public function checkedItemTempStaff(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->checkedItemTempStaff($params);
        return $result;
    }

    /**
     * Submit thên khách hàng temp đã chọn
     * @param Request $request
     * @return mixed
     */
    public function submitAddItemTemp(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->submitAddItemTemp($params);
        return $result;
    }

    /**
     * Submit thên nhân viên  temp đã chọn
     * @param Request $request
     * @return mixed
     */
    public function submitAddItemTempStaff(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->submitAddItemTempStaff($params);
        return $result;
    }

    /**
     * Submit thên khách hàng temp đã chọn
     * @param Request $request
     * @return mixed
     */
    public function submitAddItemTempAuto(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->submitAddItemTempAuto($params);
        return $result;
    }

    /**
     * Load danh sách chi nhánh  đã chọn
     * @param Request $request
     * @return mixed
     */
    public function loadItemSelectCustomer(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->loadItemSelectCustomer($params);
        return $result;
    }

    /**
     * Load danh sách chi nhánh  đã chọn
     * @param Request $request
     * @return mixed
     */
    public function loadItemSelectStaff(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->loadItemSelectStaff($params);
        return $result;
    }

    /**
     * Remove chi nhánh trong danh sách chi nhánh đã chọn
     * @param Request $request
     * @return mixed
     */
    public function removeItemSelectedCustomer(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->removeItemSelectedCustomer($params);
        return $result;
    }

    /**
     * Remove chi nhánh trong danh sách chi nhánh đã chọn
     * @param Request $request
     * @return mixed
     */
    public function removeItemSelectedStaff(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->removeItemSelectedStaff($params);
        return $result;
    }

    /**
     * Tìm kiếm danh sách nhóm cửa hàng
     * @param Request $request
     * @return mixed
     */
    public function searchAllOutletGroup(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->searchAllOutletGroup($params);
        return $result;
    }

    /**
     * Render modal import excel
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function showModalImport(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $view = view('outlet::apply.popup.import-excel', $params)->render();
        return response()->json($view);
    }

    /**
     * Lưu chi nhánh của tab chi nhánh áp dụng
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateApplyRequest $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->update($params);
        return $result;

    }

    /**
     * Xoá session chi nhánh 
     * @param Request $request
     * @return mixed
     */
    public function forgetSessionItemSelected(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        session()->forget($params['unique'] . '.item_selected');
    }

    /**
     * lấy danh sách condition staff
     * @param Request $request
     * @return mixed
     */

    public function getConditionStaff(Request $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->getConditionStaff($params);
        return response()->json($result);
    }

    /**
     * lấy danh sách condition staff selected 
     * @param Request $request
     */

    public function getConditionStaffSelected(conditionStaffGroupRequest $request)
    {
        $params = $request->all();
        $params = stripTagParam($params);
        $result = $this->rApply->getConditionStaffSeleted($params);
        return response()->json($result);
    }
}
