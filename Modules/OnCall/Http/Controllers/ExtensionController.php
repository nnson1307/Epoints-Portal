<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/07/2021
 * Time: 13:46
 */

namespace Modules\OnCall\Http\Controllers;


use Illuminate\Http\Request;
use Modules\CustomerLead\Http\Requests\CustomerLead\ManageWorkAddRequest;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\OnCall\Http\Requests\Extension\AssignRequest;
use Modules\OnCall\Http\Requests\Extension\SettingAccountRequest;
use Modules\OnCall\Repositories\Extension\ExtensionRepoInterface;

class ExtensionController extends Controller
{
    protected $extension;

    public function __construct(
        ExtensionRepoInterface $extension
    )
    {
        $this->extension = $extension;
    }

    /**
     * Danh sách extension
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy danh sách extension
        $data = $this->extension->list();

        return view('on-call::extension.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Ajax + filter ds extension
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
            'staff_id',
            'status'
        ]);

        $data = $this->extension->list($filter);

        return view('on-call::extension.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        //Lấy tất cả option ở view
        $getOption = $this->extension->getOption();

        //Lấy ds nhân viên
        $listStaff = (['' => __('Chọn người được phân bổ')]) + $getOption['optionStaff'];

        return [
            'staff_id' => [
                'data' => $listStaff
            ],
            'status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    '1' => __('Hoạt động'),
                    '0' => __('Không hoạt động')
                ]
            ]
        ];
    }

    /**
     * Show pop cấu hình tài khoản
     *
     * @return mixed
     */
    public function modalAccount()
    {
        return $this->extension->showModalAccount();
    }

    /**
     * Cấu hình tài khoản
     *
     * @param SettingAccountRequest $request
     * @return mixed
     */
    public function submitSettingAccount(SettingAccountRequest $request)
    {
        return $this->extension->submitSetting($request->all());
    }

    /**
     * Show modal phân bổ nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function modalAssignAction(Request $request)
    {
        return $this->extension->showModalAssign($request->all());
    }

    /**
     * Phân bổ nhân viên
     *
     * @param AssignRequest $request
     * @return mixed
     */
    public function submitAssignAction(AssignRequest $request)
    {
        return $this->extension->submitAssign($request->all());
    }

    /**
     * Đồng bộ dữ liệu extension
     *
     * @return mixed
     */
    public function syncExtensionAction()
    {
        return $this->extension->syncExtension();
    }

    /**
     * Cập nhật trạng thái extension
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatusAction(Request $request)
    {
        return $this->extension->updateStatus($request->all());
    }

    /**
     * get popup calling
     *
     * @param Request $request
     * @return mixed
     */
    public function getModalCalling(Request $request)
    {
        return $this->extension->getModalCalling($request->all());
    }

    /**
     * function save care/work on popup
     *
     * @param ManageWorkAddRequest $request
     * @return mixed
     */
    public function submitCareFromOncall(ManageWorkAddRequest $request)
    {
        return $this->extension->submitCareFromOncall($request->all());
    }

    /**
     * function search (paging) work lead on popup
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchWorkLead(Request $request)
    {
        $param = $request->all();
        $data = $this->extension->searchWorkLead($param);
        return response()->json($data);
    }

    public function getInfoDeal(Request $request)
    {
        return $this->extension->getInfoDeal($request->all());
    }

    public function listDealAction(Request $request)
    {
        $filterDeal = $request->all();
        $mCustomerDeal = new CustomerDealTable();
        $filterDeal['perpage'] = 3;
        $listDeal = $mCustomerDeal->getListFromOncall($filterDeal);
        $html = \View::make('on-call::on-calling.list-deal', [
            'LIST_DEAL' => $listDeal,
            'page' => $filterDeal['page']
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }
}