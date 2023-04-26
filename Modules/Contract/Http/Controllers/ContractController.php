<?php

namespace Modules\Contract\Http\Controllers;

use App\Jobs\JobExpireContract;
use App\Jobs\JobSoonExpireContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Contract\Http\Requests\Contract\StoreRequest;
use Modules\Contract\Http\Requests\Contract\UpdateInfoRequest;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ContractController extends Controller
{
    protected $contract;

    public function __construct(
        ContractRepoInterface $contract
    )
    {
        $this->contract = $contract;
    }

    /**
     * View ds hợp đồng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $data = $this->contract->getDataViewIndex($filter);
       
        return view('contract::contract.index',
            $data
        );
    }

    /**
     * load lại ds hợp đồng có filter
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
      
        $filter = $request->all();
        $mContract = new ContractTable();
        $page = (int)($filter['page'] ?? 1);
        // get phân quyền data
        $mRoleGroupStaff = new MapRoleGroupStaffTable();
        $lstRoleDataContract = $mRoleGroupStaff->getRoleDataContractByStaffId(auth()->id());
        $groupRoleData = collect($lstRoleDataContract)->groupBy("role_data_type");
        if (count($groupRoleData) > 0) {
            // có phân quyền thì lấy cao nhất (all -> branch -> department)
            if (isset($groupRoleData['department'])) {
                $filter['role_data'] = 'department';
            }
            if (isset($groupRoleData['branch'])) {
                $filter['role_data'] = 'branch';
            }
            if (isset($groupRoleData['all'])) {
                $filter['role_data'] = 'all';
            }
        }
        $lstContract = $mContract->getList($filter);
       
        $currItems = $lstContract->getCollection();
        foreach ($currItems as $key => $value) {
            $dataFile = $mContract->getListFileNameOfContract($value['contract_id']);
            $dataGood = $mContract->getListGoodOfContract($value['contract_id']);
            $currItems[$key]['list_file_name'] = '';
            $currItems[$key]['list_link'] = '';
            $currItems[$key]['list_object_name'] = $dataGood['list_object_name'];
            if ($dataFile != null) {
                $currItems[$key]['list_file_name'] = $dataFile['list_file_name'];
                $currItems[$key]['list_link'] = $dataFile['list_link'];
            }
        }
        $lstContract->setCollection($currItems);
        return view('contract::contract.list',
            [
                'LIST' => $lstContract,
                'page' => $page,
            ]
        );
    }

    /**
     * load status list by category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadStatusAction(Request $request)
    {
        $contractCategoryId = $request->contract_category_id;
        $data = $this->contract->loadStatusAction($contractCategoryId);
        return response()->json($data);
    }

    /**
     * View thêm hợp đồng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create(Request $request)
    {
        $data = $this->contract->getDataViewCreate($request->all());

        if (isset($data['error']) && $data['error'] == 1) {
            return redirect($data['route']);
        }

        return view('contract::contract.create', $data);
    }

    /**
     * view cấu hình
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function configAction()
    {
        return view('contract::contract.config');
    }

    public function submitSaveConfig(Request $request)
    {
        try {
//            $str = json_encode($request->arrColumn);
//            $len = strlen($str);
//            $arrColumn1 = substr($str,0,(int)$len/2);
//            $arrColumn2 = substr($str,(int)$len/2,$len-1);
            Cookie::forget('arrSearch');
            Cookie::queue(Cookie::forever('arrSearch', json_encode($request->arrSearch)));
            Cookie::forget('arrFilter');
            Cookie::queue(Cookie::forever('arrFilter', json_encode($request->arrFilter)));
            Cookie::forget('arrColumn');
//            Cookie::forget('arrColumn1');
//            Cookie::forget('arrColumn2');
//            Cookie::queue(Cookie::forever('arrColumn1', $arrColumn1));
//            Cookie::queue(Cookie::forever('arrColumn2', $arrColumn2));
            Cookie::queue(Cookie::forever('arrColumn', json_encode($request->arrColumn)));
            return [
                'error' => false,
                'message' => __('Lưu cấu hình thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu cấu hình thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Chọn loại HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseCategoryAction(Request $request)
    {
        return $this->contract->chooseCategory($request->all());
    }

    /**
     * Lưu tag
     *
     * @param Request $request
     * @return mixed
     */
    public function insertTagAction(Request $request)
    {
        return $this->contract->insertTag($request->all());
    }

    /**
     * Chọn loại đối tác
     *
     * @param Request $request
     * @return mixed
     */
    public function changePartnerTypeAction(Request $request)
    {
        return $this->contract->changePartnerType($request->all());
    }

    /**
     * Chọn đối tác
     *
     * @param Request $request
     * @return mixed
     */
    public function changePartnerAction(Request $request)
    {
        return $this->contract->changePartner($request->all());
    }

    /**
     * Lưu phương thức thanh toán
     *
     * @param Request $request
     * @return mixed
     */
    public function insertPaymentMethodAction(Request $request)
    {
        return $this->contract->insertPaymentMethod($request->all());
    }

    /**
     * Lưu đơn vị thanh toán
     *
     * @param Request $request
     * @return mixed
     */
    public function insertPaymentUnitAction(Request $request)
    {
        return $this->contract->insertPaymentUnit($request->all());
    }

    /**
     * Thêm HĐ
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->contract->store($request->all());
    }

    /**
     * Chỉnh sửa HĐ
     *
     * @param $contractId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($contractId)
    {
        $tab = '';
        if(isset($_GET['tab'])){
            $tab = $_GET['tab'];
        }
        session()->put('is_detail', 0);

        $data = $this->contract->getDataViewEdit($contractId, 1);
        if (isset($data['infoGeneral']['is_deleted']) && $data['infoGeneral']['is_deleted'] == 1) {
            return redirect()->route('contract.contract');
        }


        if (isset($data['error']) && $data['error'] == 1) {
            return redirect()->route('contract.contract');
        }

        if ($data['infoGeneral']['is_browse'] == 1) {
            return redirect()->route('contract.contract');
        }
        $data['tab'] = $tab;
        return view('contract::contract.edit', $data);
    }

    /**
     * Chỉnh sửa thông tin HĐ
     *
     * @param UpdateInfoRequest $request
     * @return mixed
     */
    public function updateInfoAction(UpdateInfoRequest $request)
    {
        return $this->contract->updateInfo($request->all());
    }

    /**
     * Chi tiết HĐ
     *
     * @param $contractId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($contractId)
    {
    
        session()->put('is_detail', 1);

        $data = $this->contract->getDataViewEdit($contractId, 0);
        if (isset($data['infoGeneral']['is_deleted']) && $data['infoGeneral']['is_deleted'] == 1) {
            return redirect()->route('contract.contract');
        }

        return view('contract::contract.detail', $data);
    }

    /**
     * Lấy giá trị theo hàng hoá
     *
     * @param Request $request
     * @return mixed
     */
    public function changeValueGoodsAction(Request $request)
    {
        return $this->contract->changeValueGoods($request->all());
    }

    /**
     * Lấy trạng thái đơn hàng gần nhất
     *
     * @param Request $request
     * @return mixed
     */
    public function getStatusOrder(Request $request)
    {
        return $this->contract->getStatusOrder($request->all());
    }

    /**
     * Show modal nhập lý do xoá
     *
     * @param Request $request
     * @return mixed
     */
    public function showModalReasonAction(Request $request)
    {
        return $this->contract->showModalReason($request->all());
    }

    /**
     * Xoá hợp đồng
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->contract->destroy($request->all());
    }

    /**
     * export excel contract
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcel(Request $request)
    {
        return $this->contract->exportExcel($request->all());
    }

    /**
     * show popup add customer quickly
     *
     * @param Request $request
     * @return mixed
     */
    public function getPopupCustomerQuickly(Request $request)
    {
        return $this->contract->getPopupCustomerQuickly($request->all());
    }

    /**
     * submit add customer quickly
     *
     * @param Request $request
     * @return mixed
     */
    public function submitCustomerQuickly(Request $request)
    {
        return $this->contract->submitCustomerQuickly($request->all());
    }

    /**
     * submit supplier quickly
     *
     * @param Request $request
     * @return mixed
     */
    public function submitSupplierQuickly(Request $request)
    {
        return $this->contract->submitSupplierQuickly($request->all());
    }

    /**
     * Show modal cập nhật trạng thái HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function showModalStatusAction(Request $request)
    {
        return $this->contract->showModalStatus($request->all());
    }

    /**
     * Cập nhật trạng thái HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatusAction(Request $request)
    {
        return $this->contract->updateStatus($request->all());
    }

    /**
     * Show modal import HĐ
     *
     * @return mixed
     */
    public function showModalImportAction()
    {
        return $this->contract->showModalImport();
    }

    /**
     * Import file HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function importExcelAction(Request $request)
    {
        return $this->contract->importExcel($request->all());
    }

    /**
     * Xuất file lỗi khi import HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelError(Request $request)
    {
        return $this->contract->exportError($request->all());
    }

    /**
     * Đồng bộ template HĐ
     *
     * @return mixed
     */
    public function syncTemplateContractAction()
    {
        return $this->contract->syncTemplateContract();
    }
}
