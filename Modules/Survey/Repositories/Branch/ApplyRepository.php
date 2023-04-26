<?php


namespace Modules\Survey\Repositories\Branch;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\StaffsTable;
use Modules\Survey\Models\SurveyTable;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\Customer\Models\CustomerTable;
use Modules\Survey\Models\StaffGroupTable;
use Modules\ManagerWork\Models\BranchTable;
use Modules\Survey\Models\SurveyBranchTable;
use Modules\Admin\Models\CustomerGroupFilterTable;
use Modules\Survey\Repositories\Branch\ApplyRepositoryInterface;




class ApplyRepository implements ApplyRepositoryInterface
{

    const SURVEY = 'survey';

    /**
     * Tìm kiếm chi nhánh
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function searchBranch($params)
    {
        $unique = $params['unique'] ?? '0';
        // tên chi nhánh // 
        $filter['keyword_branches$branch_name'] = $params['branchName'] ?? null;
        // mã đại diện //
        $filter['keyword_branches$representative_code'] = $params['representativeCode'] ?? null;
        // mã chi nhánh //
        $filter['keyword_branches$branch_code'] = $params['branchCode'] ?? null;
        // page //
        $filter['page'] = (int) ($params['page'] ?? 1);
        // số lượng record trên 1 page //
        $filter['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;
        // lấy các record đã hiển thị ở danh sách 
        $ssSelected = $unique . '.item_selected';
        $itemSelected = session()->get($ssSelected, []);
        //Không lấy các chi nhánh  đã ở danh sách.
        $filter['not_in'] = $itemSelected;
        $mBranch = new BranchTable();
        $list = $mBranch->getListNew($filter);
        $ssTemp = $unique . '.item_temp';
        $itemTemp = array_flip(session()->get($ssTemp, []));
        $view = view('survey::branch.list.branch', [
            'list' => $list,
            'itemTemp' => $itemTemp,
        ])->render();
        return [
            'view'       => $view,
            'itemTemp' => $itemTemp,
        ];
    }

    /**
     * Checked item (cửa hàng/ nhóm cửa hàng) - tạm
     * @param array $params
     *
     * @return array|mixed
     */
    public function checkedItemTemp($params = [])
    {
        $arrayItem = $params['array_item'] ?? [];
        if ($arrayItem != []) {
            $type = $params['type'];
            $unique = $params['unique'];
            //Danh sách chi nhánh đã chọn - tạm
            $ssTemp = $unique . '.item_temp';
            $data = session()->get($ssTemp, []);
            //Checked
            if ($type == 'checked') {
                $data = array_merge($data, $arrayItem);
            } else { //Uncheck
                foreach ($data as $key => $value) {
                    foreach ($arrayItem as $k => $v) {
                        if ($v == $value) {
                            unset($data[$key]);
                        }
                    }
                }
            }
            session()->put($ssTemp, $data);
            return $data;
        }
    }

    /**
     * Submit thêm item (cửa hàng/ nhóm cửa hàng) tạm vào chính
     * @param array $params
     * @return array|mixed
     */
    public function submitAddItemTemp($params = [])
    {
        $unique = $params['unique'];
        $ssSelected = $unique . '.item_selected_customer';
        $ssTemp = $unique . '.item_temp_customer';
        //Data chính
        $dataSelected = session()->get($ssSelected, []);
        //Data temp
        $dataTemp = session()->get($ssTemp, []);
        $result = array_merge($dataSelected, $dataTemp);
        //Put vào lại Danh sách chi nhánh  đã chọn - đã chọn
        $result = array_flip(array_flip($result));
        session()->put($ssSelected, $result);
        $resultTest = [
            'item_selected' => $result,
        ];
        session()->forget($ssTemp);
        return $resultTest;
    }

    /**
     * Load danh sách outlet đã chọn
     * @param $params
     * @return array
     * @throws \Throwable
     */
    public function loadItemSelect($params)
    {
        $unique = $params['unique'];
        $isShow = $params['is_show'] ?? 0;
        $ssSelected = $unique . '.item_selected';
        if (!empty($params['apply_all'])) {
            session()->forget($ssSelected);
        }
        $itemSelected = session()->get($ssSelected, []);
        $filter['keyword_branches$branch_name'] = $params['branchName'] ?? null;
        $filter['keyword_branches$representative_code'] = $params['representativeCode'] ?? null;
        $filter['keyword_branches$branch_code'] = $params['branchCode'] ?? null;
        $filter['keyword_branches$phone'] = $params['phone'] ?? null;
        $filter['keyword_branches$address'] = $params['address'] ?? null;
        $filter['keyword_branches$provinceid'] = $params['provinceId'] ?? null;
        $filter['keyword_branches$districtid'] = $params['districtId'] ?? null;
        $filter['keyword_branches$ward_id'] = $params['wardId'] ?? null;
        $filter['arr_branch'] = $itemSelected;
        $filter['perpage'] = (int)($params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP);
        $filter['page'] = (int)($params['page'] ?? 1);
        $page = $filter['page'];
        //Case xóa chi nhánh xảy ra khi xóa record cuối cùng của page
        $filter2 = $filter;
        $mBranch = new BranchTable();
        $list = $mBranch->getListNew($filter);
        //Nếu page truyền vào lớn hơn page có thì page = page - 1
        if ($list->lastPage() < $page || $list->currentPage() < $page) {
            $filter2['page'] = $page - 1;
            $list = $mBranch->getListNew($filter2);
        }
        $view = view('survey::branch.list.branch-selected', [
            'list' => $list,
            'isShow' => $isShow,
        ])->render();
        return [
            'view' => $view,
            'itemSelected' => $itemSelected,
        ];
    }

    /**
     * Remove outlet trong danh sách outlet đã chọn
     * @param $params
     * @return mixed|void
     */
    public function removeItemSelected($params)
    {
        $ssSelected = $params['unique'] . '.item_selected';
        $dataSelected = session()->get($ssSelected, []);
        if ($dataSelected != []) {
            foreach ($dataSelected as $key => $value) {
                if ($value == $params['id']) {
                    unset($dataSelected[$key]);
                }
            }
            session()->put($ssSelected, $dataSelected);
        }
    }

    /**
     * Tìm kiếm danh sách nhóm cửa hàng
     * @param $params
     * @return array|mixed|string
     * @throws \Throwable
     */
    public function searchAllOutletGroup($params)
    {
        $unique = $params['unique'] ?? '0';
        $filter['keyword_mystore_filter_group$name'] = $params['name'] ?? '';
        $filter['keyword_mystore_filter_group$filter_group_type'] = $params['filter_group_type'] ?? '';
        $filter['page'] = (int)($params['page'] ?? 1);
        $filter['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;;
        //Lấy hết những nhóm có trên hệ thống 17/03/2021 RET-3660
        // $filter['is_active'] = 1;
        // $filter['is_from_dms'] = 0;
        $mStoreGroup = app()->get(StoreGroupRepositoryInterface::class);
        $list = $mStoreGroup->getList($filter);
        $ssTemp = $unique . '.item_temp';
        $itemTemp = array_flip(session()->get($ssTemp, []));
        $view = view('outlet::apply.list.outlet-group', [
            'list' => $list,
            'itemTemp' => $itemTemp,
        ])
            ->render();
        return $view;
    }

    /**
     * Nối cặp code của cửa hàng lại với nhau
     * return array
     * @param $collection
     * @return array
     */
    private function resultStore($collection)
    {
        $result = [];
        if ($collection != null) {
            foreach ($collection as $item) {
                $result[] = $item['customer_code'] . '_' . $item['ship_to_code'];
            }
        }
        return $result;
    }

    public function update($params)
    {
        // kiểu áp dụng đối tượng (nhân viên, khách hàng) //
        $typeApply =  $params['typeApply'];
        $unique = $params['unique'];
        $surveyID = $params['survey_id'];
        if ($typeApply == 'staffs') {
            $conditionBranch = $params['condition_branch'] ?? null;
            $conditionDepartment = $params['condition_department'] ?? null;
            $conditionTitile = $params['condition_titile'] ?? null;
            $typeCondition = $params['type_condition'] ?? null;
            $this->handleStaffApyly(
                $unique,
                $surveyID,
                $conditionBranch,
                $conditionTitile,
                $conditionDepartment,
                $typeCondition
            );
        } else if ($typeApply == 'customers') {
            $idGroupAutoCustomer = $params['idGroupAutoCustomer'];
            $this->handleCustomerApply(
                $unique,
                $surveyID,
                $idGroupAutoCustomer
            );
        } else if ($typeApply == 'all_staff') {
            $this->handleStaffApplyAll($surveyID);
        } else {
            $this->handleCustomerApplyAll($surveyID);
        }
        return [
            'error' => false,
            'message' => __('Cập nhật đối tượng khảo sát thành công')
        ];
    }

    public function handleStaffApyly(
        $unique,
        $surveyID,
        $conditionBranch,
        $conditionTitile,
        $conditionDepartment,
        $typeCondition
    ) {
        try {
            DB::beginTransaction();

            $mSurvey = new SurveyTable();
            $ssSelected = $unique . '.item_selected_staff';
            $itemSelected = session()->get($ssSelected, []);
            $conditionBranch = !empty($conditionBranch) ? json_encode($conditionBranch) : "";
            $conditionTitile = !empty($conditionTitile) ? json_encode($conditionTitile) : "";
            $conditionDepartment = !empty($conditionDepartment) ? json_encode($conditionDepartment) : "";
            $itemSurvey = $mSurvey->find($surveyID);
            $itemSurvey->staffs()->sync($itemSelected);
            if (!$typeCondition) {
                $itemSurvey->staffConditionApply()->delete();
                $itemSurvey->conditionApply()->delete();
            } else {
                $conditionStaff = $itemSurvey->staffConditionApply()->updateOrCreate([
                    "survey_id" => $surveyID
                ], [
                    "survey_id" => $surveyID,
                    "condition_branch" => $conditionBranch,
                    "condition_titile" => $conditionTitile,
                    "condition_department" => $conditionDepartment,
                    'condition_type' => $typeCondition,
                ]);
                $itemSurvey->conditionApply()->updateOrCreate(["survey_id" => $surveyID], [
                    "survey_id" => $surveyID,
                    "group_id" => $conditionStaff->survey_group_staff_id,
                    "type_group" => 'staff'
                ]);
            }

            $itemSurvey->update(
                [
                    "type_user" => 'staff',
                    "type_apply" => 'staffs'
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error.' . $e->getMessage());
        }
    }

    public function handleCustomerApply(
        $unique,
        $surveyID,
        $idGroupAutoCustomer
    ) {
        try {
            DB::beginTransaction();
            $mSurvey = new SurveyTable();
            $ssSelected = $unique . '.item_selected_customer';
            $itemSelected = session()->get($ssSelected, []);
            $itemSurvey = $mSurvey->find($surveyID);
            $itemSurvey->customers()->sync($itemSelected);
            if ($idGroupAutoCustomer) {
                $itemSurvey->conditionApply()->updateOrCreate(["survey_id" => $surveyID], [
                    'group_id' => $idGroupAutoCustomer,
                    'type_group' => 'customer'
                ]);
            } else {
                $itemSurvey->conditionApply()->delete();
            }
            $itemSurvey->update(
                [
                    "type_user" => 'customer',
                    "type_apply" => 'customers'
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error.' . $e->getMessage());
        }
    }

    public function handleStaffApplyAll(
        $surveyID
    ) {
        try {
            DB::beginTransaction();
            $mSurvey = new SurveyTable();
            $itemSurvey = $mSurvey->find($surveyID);
            $itemSurvey->staffs()->detach();
            $itemSurvey->conditionApply()->delete();
            $itemSurvey->staffConditionApply()->delete();
            $itemSurvey->update([
                "type_user" => 'staff',
                "type_apply" => 'all_staff'
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("error." . $e->getMessage());
        }
    }

    public function handleCustomerApplyAll(
        $surveyID
    ) {
        try {
            DB::beginTransaction();
            $mSurvey = new SurveyTable();
            $itemSurvey = $mSurvey->find($surveyID);
            $itemSurvey->customers()->detach();
            $itemSurvey->conditionApply()->delete();
            $itemSurvey->update([
                "type_user" => 'customer',
                "type_apply" => 'all_customer'
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("error." . $e->getMessage());
        }
    }

    /**
     * Lưu phần khảo sát
     * @param $params
     * @param $listOutlet
     */
    private function saveSurvey($params, $listOutlet)
    {
        $id = $params['id'];
        $mSurveyBranch = new SurveyBranchTable();
        $mSurvey = new SurveyTable();
        $mSurveyBranch->remove($id);
        $now = Carbon::now();
        $authId = auth()->id();
        $dataBranch = [];
        $dataUpdate = [
            'allow_all_branch' => $params['apply_all'],
            'updated_at' => $now,
            'updated_by' => $authId,
        ];
        $mSurvey->edit($id, $dataUpdate);
        foreach ($listOutlet as $key => $outId) {
            $dataBranch[] = [
                'survey_id' => $params['id'],
                'branch_id' => $outId,
                'created_at' => $now,
                'created_by' => $authId,
                'updated_at' => $now,
                'updated_by' => $authId,
            ];
            //500 record insert 1 lần
            if (count($dataBranch) == MAX_SIZE_INSERT_ARRAY) {
                $mSurveyBranch->addInsert($dataBranch);
                $dataBranch = [];
            }
        }
        if ($dataBranch != []) {
            $mSurveyBranch->addInsert($dataBranch);
        }
    }

    /**
     * Tìm kiếm khách hàng 
     * @param $params
     * @return mixed
     */

    public function searchCustomer($params)
    {
        $unique = $params['unique'] ?? '0';
        // loại khách hàng // 
        $filters['customers$customer_type'] = $params['customerType'] ?? null;
        // Nhóm khách hàng //
        $filters['customers$customer_group_id'] = $params['customerGroup'] ?? null;
        // nguồn khách hàng //
        $filters['customers$customer_source_id'] = $params['customerSource'] ?? null;
        // Tỉnh thành phố  //
        $filters['customers$province_id'] = $params['customerProvince'] ?? null;
        // Quận huyện  //
        $filters['customers$district_id'] = $params['customerDistrict'] ?? null;
        // Phường xã //
        $filters['customers$ward_id'] = $params['customerWard'] ?? null;
        // name or code //
        $filters['nameOrCode'] = $params['nameOrCode'] ?? null;
        // page //
        $filters['page'] = (int) ($params['page'] ?? 1);
        // số lượng record trên 1 page //
        $filters['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;
        // lấy các record đã hiển thị ở danh sách 
        $ssSelected = $unique . '.item_selected_customer';
        $itemSelected = session()->get($ssSelected, []);
        //Không lấy các chi nhánh  đã ở danh sách.
        $filters['not_in'] = $itemSelected;
        $mCustomer = new CustomerTable();
        $list = $mCustomer->getListNew($filters);
        $ssTemp = $unique . '.item_temp_customer';
        $itemTemp = array_flip(session()->get($ssTemp, []));
        $view = view('survey::branch.list.customer', [
            'list' => $list,
            'itemTemp' => $itemTemp,
        ])->render();
        return [
            'view'       => $view,
            'itemTemp' => $itemTemp,
        ];
    }

    /**
     * Checked item (Khách hàng) - tạm
     * @param $params
     * @return mixed
     */
    public function checkedItemTempCustomer($params)
    {
        $arrayItem = $params['array_item'] ?? [];
        if ($arrayItem != []) {
            $type = $params['type'];
            $unique = $params['unique'];
            //Danh sách chi nhánh đã chọn - tạm
            $ssTemp = $unique . '.item_temp_customer';
            $data = session()->get($ssTemp, []);
            //Checked
            if ($type == 'checked') {
                $data = array_merge($data, $arrayItem);
            } else { //Uncheck
                foreach ($data as $key => $value) {
                    if (in_array($value, $arrayItem)) {
                        unset($data[$key]);
                    }
                }
            }
            session()->put($ssTemp, $data);
            return $data;
        }
    }

    /**
     * Load danh sách outlet đã chọn
     * @param $params
     * @return mixed
     */
    public function loadItemSelectCustomer($params)
    {
        $unique = $params['unique'];
        $isShow = $params['is_show'] ?? 0;
        $ssSelected = $unique . '.item_selected_customer';
        $itemSelected = session()->get($ssSelected, []);
        // loại khách hàng // 
        $filters['customers$customer_type'] = $params['customerType'] ?? null;
        // Nhóm khách hàng //
        $filters['customers$customer_group_id'] = $params['customerGroup'] ?? null;
        // nguồn khách hàng //
        $filters['customers$customer_source_id'] = $params['customerSource'] ?? null;
        // tên hoặc mã khách hàng //
        $filters['nameOrCode'] = $params['nameOrCode'] ?? null;
        // Tỉnh thành phố  //
        $filters['customers$province_id'] = $params['provinceId'] ?? null;
        // Quận huyện  //
        $filters['customers$district_id'] = $params['districtId'] ?? null;
        // Phường xã //
        $filters['customers$ward_id'] = $params['wardId'] ?? null;
        // trạng thái hoạt động //
        $filters['where_in'] = $itemSelected;
        $filters['perpage'] = (int)($params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP);
        $filters['page'] = (int)($params['page'] ?? 1);
        $page = $filters['page'];
        //Case xóa chi nhánh xảy ra khi xóa record cuối cùng của page
        $filter2 = $filters;
        $mCustomer = new CustomerTable();
        $list = $mCustomer->getListNew($filters);
        //Nếu page truyền vào lớn hơn page có thì page = page - 1
        if ($list->lastPage() < $page || $list->currentPage() < $page) {
            $filter2['page'] = $page - 1;
            $list = $mCustomer->getListNew($filter2);
        }
        $view = view('survey::branch.list.customer-selected', [
            'list' => $list,
            'isShow' => $isShow,
        ])->render();
        return [
            'view' => $view,
            'itemSelected' => $itemSelected,
            'filters' => $filters,

        ];
    }

    /**
     * Xoá khách hàng  trong danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function removeItemSelectedCustomer($params)
    {
        $ssSelected = $params['unique'] . '.item_selected_customer';
        $dataSelected = session()->get($ssSelected, []);
        if ($dataSelected != []) {
            foreach ($dataSelected as $key => $value) {
                if ($value == $params['id']) {
                    unset($dataSelected[$key]);
                }
            }
            session()->put($ssSelected, $dataSelected);
        }
    }

    /**
     * Tìm kiếm khách hàng 
     * @param $params
     * @return mixed
     */
    public function searchCustomerAuto($params)
    {
        $unique = $params['unique'] ?? '0';
        // Tên nhóm khách hàng // 
        $filters['keyword_customer_group_filter$name'] = $params['nameGroupCustomer'] ?? null;
        // Phường xã //
        $filters['customer_group_filter$filter_group_type'] = $params['typeGroupCustomer'] ?? null;
        // page //
        $filters['page'] = (int) ($params['page'] ?? 1);
        // số lượng record trên 1 page //
        $filters['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;
        // lấy các record đã hiển thị ở danh sách 
        $ssSelected = $unique . '.item_selected_customer_auto';
        $itemSelected = session()->get($ssSelected, []);
        //Không lấy các chi nhánh  đã ở danh sách.
        $filters['not_in'] = $itemSelected;
        $mCustomer = new CustomerGroupFilterTable();
        $list = $mCustomer->getAllGroups($filters);
        $ssTemp = $unique . '.item_temp_customer_auto';
        $itemTemp = array_flip(session()->get($ssTemp, []));
        $view = view('survey::branch.list.customer-auto', [
            'list' => $list,
            'itemTemp' => $itemTemp,
        ])->render();
        return [
            'view'       => $view,
            'itemTemp' => $itemTemp,
        ];
    }

    /**
     * Submit thêm item (khách hàng) tạm vào chính tự động
     * @param $params
     * @return mixed
     */
    public function submitAddItemTempAuto($params)
    {
        $idGroupCustomer = $params['id'];
        $mCustomer = new CustomerGroupFilterTable();
        $itemGroupCustomer = $mCustomer->find($idGroupCustomer);
        $view = view('survey::branch.list.customer-auto-selected', ['itemGroup' => $itemGroupCustomer])->render();
        $result = [
            'view' => $view
        ];
        return $result;
    }

    /**
     * Tìm kiếm nhân viên
     * @param $params
     * @return mixed
     */

    public function searchStaff($params)
    {
        $unique = $params['unique'] ?? '0';
        // phòng ban // 
        $filters['staffs$department_id'] = $params['staffDepartment'] ?? null;
        // chức vụ //
        $filters['staffs$staff_title_id'] = $params['staffPosition'] ?? null;
        // chi nhánh  //
        $filters['staffs$branch_id'] = $params['staffBranch'] ?? null;
        // page //
        $filters['page'] = (int) ($params['page'] ?? 1);
        // name or code //
        $filters['nameOrCode'] = $params['nameOrCodeStaff'] ?? null;
        // số lượng record trên 1 page //
        $filters['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;
        $filters['address'] = $params['address'] ?? null;
        // lấy các record đã hiển thị ở danh sách 
        $ssSelected = $unique . '.item_selected_staff';
        $itemSelected = session()->get($ssSelected, []);
        //Không lấy các chi nhánh  đã ở danh sách.
        $filters['not_in'] = $itemSelected;
        $mStaff = new StaffsTable();
        $list = $mStaff->getAllStaffCondition($filters);
        $ssTemp = $unique . '.item_temp_staff';
        $itemTemp = array_flip(session()->get($ssTemp, []));
        $view = view('survey::branch.list.staff', [
            'list' => $list,
            'itemTemp' => $itemTemp,
        ])->render();
        return [
            'view'       => $view,
            'itemTemp' => $itemTemp,
            'filters' => $filters
        ];
    }

    /**
     * Checked item (Nhân viên) - tạm
     * @param $params
     * @return mixed
     */
    public function checkedItemTempStaff($params)
    {
        $arrayItem = $params['array_item'] ?? [];
        if ($arrayItem != []) {
            $type = $params['type'];
            $unique = $params['unique'];
            //Danh sách chi nhánh đã chọn - tạm
            $ssTemp = $unique . '.item_temp_staff';
            $data = session()->get($ssTemp, []);
            //Checked
            if ($type == 'checked') {
                $data = array_merge($data, $arrayItem);
            } else { //Uncheck
                foreach ($data as $key => $value) {
                    if (in_array($value, $arrayItem)) {
                        unset($data[$key]);
                    }
                }
            }
            session()->put($ssTemp, $data);
            return $data;
        }
    }

    /**
     * Submit thêm item (Nhân viên) tạm vào chính
     * @param $params
     * @return mixed
     */
    public function submitAddItemTempStaff($params)
    {
        $unique = $params['unique'];
        $ssSelected = $unique . '.item_selected_staff';
        $ssTemp = $unique . '.item_temp_staff';
        //Data chính
        $dataSelected = session()->get($ssSelected, []);
        //Data temp
        $dataTemp = session()->get($ssTemp, []);
        $result = array_merge($dataSelected, $dataTemp);
        //Put vào lại Danh sách chi nhánh  đã chọn - đã chọn
        $result = array_flip(array_flip($result));
        session()->put($ssSelected, $result);
        $resultTest = [
            'item_selected' => $result,
        ];
        session()->forget($ssTemp);
        return $resultTest;
    }

    /**
     * Load danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function loadItemSelectStaff($params)
    {
        $unique = $params['unique'];
        $isShow = $params['is_show'] ?? 0;
        $ssSelected = $unique . '.item_selected_staff';
        $itemSelected = session()->get($ssSelected, []);
        // phòng ban // 
        $filters['staffs$department_id'] = $params['staffDepartment'] ?? null;
        // chức vụ //
        $filters['staffs$staff_title_id'] = $params['staffPosition'] ?? null;
        // chi nhánh  //
        $filters['staffs$branch_id'] = $params['staffBranch'] ?? null;
        // tên hoặc mã khách hàng //
        $filters['nameOrCode'] = $params['nameOrCode'] ?? null;
        // trạng thái hoạt động //
        $filters['address'] = $params['address'] ?? null;
        $filters['where_in'] = $itemSelected;
        $filters['perpage'] = (int)($params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP);
        $filters['page'] = (int)($params['page'] ?? 1);
        $page = $filters['page'];
        //Case xóa chi nhánh xảy ra khi xóa record cuối cùng c  ủa page
        $filter2 = $filters;
        $mStaff = new StaffsTable();
        $list = $mStaff->getAllStaffCondition($filters);
        //Nếu page truyền vào lớn hơn page có thì page = page - 1
        if ($list->lastPage() < $page || $list->currentPage() < $page) {
            $filter2['page'] = $page - 1;
            $list = $mStaff->getListNew($filter2);
        }
        $view = view('survey::branch.list.staff-seleted', [
            'list' => $list,
            'isShow' => $isShow,
        ])->render();
        return [
            'view' => $view,
            'itemSelected' => $itemSelected,
            'filters' => $filters,

        ];
    }

    /**
     * Xoá nhân viên  trong danh sách khách hàng đã chọn
     * @param $params
     * @return mixed
     */
    public function removeItemSelectedStaff($params)
    {
        $ssSelected = $params['unique'] . '.item_selected_staff';
        $dataSelected = session()->get($ssSelected, []);
        if ($dataSelected != []) {
            foreach ($dataSelected as $key => $value) {
                if ($value == $params['id']) {
                    unset($dataSelected[$key]);
                }
            }
            session()->put($ssSelected, $dataSelected);
        }
    }

    /**
     * Lấy tât cả điều kiện mặc đinh của nhân viên 
     * @param $params
     * @return mixed
     */

    public function getConditionStaff($params)
    {
        $listConditonCurrent = $params['arrayCondition'];
        $mStaffGroup = new StaffGroupTable();
        $listQueryStaff = $mStaffGroup->getConditonQuery();
        foreach ($listConditonCurrent as $value) {
            if (array_key_exists($value, $listQueryStaff)) {
                unset($listQueryStaff[$value]);
            }
        }
        return $listQueryStaff;
    }

    /**
     * Lấy danh sách điều kiện nhóm nhân viên động seleted
     * @param $params
     * @return mixed
     */
    public function getConditionStaffSeleted($params)
    {
        $mdepartments = new DepartmentTable();
        $mbranchs = new BranchTable();
        $mtitles  = new  StaffTitleTable();
        $listItemDepartment = $params['listConditionDepartment'] ?? [];
        $listItemBranch = $params['listConditionBranch'] ?? [];
        $listItemTitile = $params['listConditionTitle'] ?? [];
        $typeCondition = $params['typeCondition'] ?? [];
        $listItemDepartment = $mdepartments->getListCondition($listItemDepartment);
        $listItemBranch = $mbranchs->getListCondition($listItemBranch);
        $listItemTitile = $mtitles->getListCondition($listItemTitile);
        $view = view('survey::branch.list.staff-seleted-auto', [
            'listItemDepartment' => $listItemDepartment,
            'listItemBranch' => $listItemBranch,
            'listItemTitile' => $listItemTitile,
            'typeCondition' => $typeCondition

        ])->render();
        return [
            'view' => $view
        ];
    }
}
