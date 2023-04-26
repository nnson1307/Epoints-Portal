<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/25/2020
 * Time: 10:42 AM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Admin\Http\Requests\OrderApp\SyncOrderRequest;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;


class OrderAppController extends Controller
{
    protected $orderApp;
    protected $province;

    public function __construct(
        OrderAppRepoInterface $orderApp,
        ProvinceRepositoryInterface $province
    ) {
        $this->orderApp = $orderApp;
        $this->province = $province;
    }

    /**
     * Danh sách đơn hàng từ app
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->orderApp->list(['orders$order_source_id' => 2]);

        return view('admin::order-app.index', [
            'LIST' => $data['list'],
            //            'receiptDetail' => $data['receiptDetail'],
            'FILTER' => $this->filters(),
        ]);
    }

    protected function filters()
    {
        $mBranch = new BranchTable();
        $optionBranch = $mBranch->getBranch();
        $branch = [];
        foreach ($optionBranch as $item) {
            $branch[$item['branch_id']] = $item['branch_name'];
        }
        $groupCate = (['' => __('Chọn chi nhánh')]) + $branch;

        return [
            'branches$branch_id' => [
                'data' => $groupCate
            ],
            'orders$process_status' => [
                'data' => [
                    '' => __('Trạng thái'),
                    'confirmed' => __('Đã xác nhận'),
                    'paysuccess' => __('Đã thanh toán'),
                    'pay-half' => __('Thanh toán còn thiếu'),
                    'new' => __('Mới'),
                    'ordercancle' => __('Đã hủy')
                ]
            ]
        ];
    }

    /**
     * Danh sách đơn hàng từ app
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_type',
            'search',
            'branches$branch_id',
            'created_at',
            'orders$process_status',
            'receive_at_counter',
        ]);
        $filter['orders$order_source_id'] = 2;

        $data = $this->orderApp->list($filter);

        return view('admin::order-app.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm đơn hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function create(Request $request)
    {
        $data = $this->orderApp->dateViewCreate($request->all());

        return view('admin::order-app.create', $data);
    }

    /**
     * Thêm đơn hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $this->orderApp->store($request->all());

        return $data;
    }
    public function storeOrUpdateOrderApp(Request $request)
    {
        $data = $this->orderApp->storeOrUpdateOrderApp($request->all());

        return $data;
    }

    /**
     * Thêm đơn hàng và thanh toán
     *
     * @param Request $request
     * @return mixed
     */
    public function storeReceiptAction(Request $request)
    {
        $data = $this->orderApp->storeReceipt($request->all());

        return $data;
    }

    /**
     * View thanh toán
     *
     * @param $orderId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|View|mixed
     */
    public function receiptAction(Request $request)
    {
        $orderId = $request->id;
        $paymentType = $request->type;
        $data = $this->orderApp->dataViewReceipt($orderId, $paymentType);

        if (isset($data['item']['process_status']) && in_array($data['item']['process_status'], ['new', 'confirmed'])) {
            if ($data['numberHistorySuccess'] > 0) {
                //                session()->flash('error');
                return redirect()->route('admin.order-app')->with('error_receipt', __('Đơn hàng đã có phiếu giao hàng đang giao hoặc hoàn thành rồi'));
            } else {
                return view('admin::order-app.receipt', $data);
            }
        } else {
            return redirect()->route('admin.order-app');
        }
    }

    /**
     * Chỉnh sửa lịch hẹn
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $this->orderApp->edit($request->all());

        return $data;
    }

    /**
     * Thanh toán đơn hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function submitReceiptAction(Request $request)
    {
        $data = $this->orderApp->receipt($request->all());

        return $data;
    }

    /**
     * Render thẻ dịch vụ
     *
     * @param Request $request
     * @return mixed
     */
    public function renderCardAction(Request $request)
    {
        $data = $this->orderApp->renderCard($request->all());

        return $data;
    }

    /**
     * Chi tiết đơn hàng từ app
     *
     * @param $orderId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|View|mixed
     */
    public function show($orderId)
    {
        $data = $this->orderApp->dataViewDetail($orderId);

        if ($data['order'] != null) {
            return view('admin::order-app.detail', $data);
        } else {
            return redirect()->route('admin.order-app');
        }
    }

    public function getListContactByIdCustomer(Request $request)
    {
        $idCustomer = $request->id;
        $data = $this->orderApp->getListContactByIdCus($idCustomer);
        $province = $this->province->getOptionProvince();

        //Lay district cua province
        $arrDistrict = [];


        //        var_dump($data); die;

        //        dd($province);
        $view = \View::make('admin::orders.modal-address-contact', [
            'customer_id' => $idCustomer,
            'listContact' => $data,
            'listProvince' => $province
        ])->render();

        return response()->json([
            'url' => $view
        ]);
    }
    // view detail + edit
    public function showDetailContact(Request $request)
    {
        $idCusContact = $request->id;
        $detail = $this->orderApp->getDetailContact($idCusContact);
        $province = $this->province->getOptionProvince();

        $view = \View::make('admin::orders.contact', [
            'detail' => $detail,
            'listProvince' => $province
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    public function getFullAddress(Request $request)
    {
        $idCusContact = $request->id;
        $detail = $this->orderApp->getDetailContact($idCusContact);
        return response()->json($detail);
    }

    // view add
    public function addContact(Request $request)
    {
        $province = $this->province->getOptionProvince();
        $view = \View::make('admin::orders.add-contact', [
            'listProvince' => $province
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    // submit add
    public function submitAddContact(Request $request)
    {
        $data = $request->all();
        return $this->orderApp->addContact($data);
    }

    // submit edit
    public function submitEditContact(Request $request)
    {
        $data = $request->all();
        return $this->orderApp->editContact($data);
    }

    // delete contact
    public function submitDeleteContact(Request $request)
    {
        $idContact = $request->id;
        return $this->orderApp->removeContact($idContact);
    }

    // set address default
    public function setAddressDefault(Request $request)
    {
        $idContact = $request->idContact;
        $idCustomer = $request->idCustomer;
        return $this->orderApp->setDefaultContact($idContact, $idCustomer);
    }

    /**
     * Ajax filter, phan trang contact
     *
     * @param Request $request
     * @return mixed
     */
    public function contactListAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'customer_id'
        ]);

        return $this->orderApp->listCustomerContact($filter);
    }


    /**
     * lay dia chi mac dinh cua khach hang
     *
     * @param Request $request
     * @return mixed
     */
    public function getContactDefault(Request $request)
    {
        $idCus = $request->id;
        $detail = $this->orderApp->getContactDefault($idCus);
        return $detail;
    }

    /**
     * Đồng bộ đơn hàng
     *
     * @param SyncOrderRequest $request
     * @return mixed
     */
    public function syncOrderAction(SyncOrderRequest $request)
    {
        $data = $this->orderApp->syncOrder($request->all());

        return response()->json($data);
    }

    /**
     * Export danh sách đơn hàng
     * @param Request $request
     * @return mixed
     */
    public function exportList(Request $request)
    {
        $params = $request->all();
        return $this->orderApp->exportList($params);
    }
}