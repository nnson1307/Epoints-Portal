<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/23/2020
 * Time: 4:45 PM
 */

namespace Modules\Delivery\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Delivery\Http\Requests\DeliveryHistory\UpdateRequest;
use Modules\Delivery\Models\UserCarrierTable;
use Modules\Delivery\Repositories\DeliveryHistory\DeliveryHistoryRepoInterface;

class DeliveryHistoryController extends Controller
{
    protected $deliveryHistory;

    public function __construct(
        DeliveryHistoryRepoInterface $deliveryHistory
    ) {
        $this->deliveryHistory = $deliveryHistory;
    }

    /**
     * Danh sách phiếu giao hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
//        $listDeliveryPartner = $this->deliveryHistory->getListDeliveryPartner();
        $listTransport = $this->deliveryHistory->getListTransport();
        $data = $this->deliveryHistory->list();

        return view('delivery::delivery-history.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
            'listTransport' => $listTransport
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        //Lấy các option render view
        $mUserCarrier = new UserCarrierTable();

        $optionCarrier = $mUserCarrier->getOption()->toArray();

        $carrier = array_combine(
            array_column($optionCarrier, 'user_carrier_id'),
            array_column($optionCarrier, 'full_name')
        );

        $groupCate = (['' => __('Chọn nhân viên giao hàng')]) + $carrier;

        return [
            'delivery_history$delivery_staff' => [
                'data' => $groupCate,
            ],
            'delivery_history$status' => [
                'data' => [
                    '' => __('Trạng thái'),
                    'new' => __('Đóng gói'),
                    'inprogress' => __('Đã nhận hàng'),
                    'success' => __('Đã giao hàng'),
                    'confirm' => __('Xác nhận đã giao hàng'),
                    'cancel' => __('Hủy'),
                    'fail' => __('Thất bại'),
                    'pending' => __('Đang chờ')
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách phiếu giao hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_type',
            'search',
            'delivery_history$delivery_staff',
            'time_ship_search',
            'delivery_history$status',
            'transport_id',
            'shipping_unit'
        ]);

        $data = $this->deliveryHistory->list($filter);

        return view('delivery::delivery-history.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Chi tiết phiếu giao hàng
     *
     * @param $deliveryHistoryId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($deliveryHistoryId)
    {
        $data = $this->deliveryHistory->dataDetail($deliveryHistoryId);
        return view('delivery::delivery-history.detail', $data);
    }

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param $deliveryHistoryId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($deliveryHistoryId)
    {
        $data = $this->deliveryHistory->dataDetail($deliveryHistoryId);

        return view('delivery::delivery-history.edit', $data);
    }

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->deliveryHistory->update($request->all());
        return $data;
    }

    /**
     * Hiển thị phiếu in
     */
    public function print(Request $request){
        $data = $this->deliveryHistory->print($request->all());
        return response()->json($data);
    }

    /**
     * hiển thị popup in
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupPrint(Request $request){
        $data = $this->deliveryHistory->showPopupPrint($request->all());
        return response()->json($data);
    }
}