<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Delivery\Http\Requests\Delivery\StoreRequest;
use Modules\Delivery\Http\Requests\Delivery\UpdateRequest;
use Modules\Delivery\Repositories\Delivery\DeliveryRepoInterface;
use Modules\FNB\Models\ConfigTable;

class DeliveryController extends Controller
{
    protected $delivery;

    public function __construct(
        DeliveryRepoInterface $delivery
    )
    {
        $this->delivery = $delivery;
    }

    /**
     * View danh sách đơn hàng cần giao
     *
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function indexAction()
    {
        $data = $this->delivery->list();

        return view('delivery::delivery.index', [
            'LIST' => $data['list'],
//            'receipt' => $data['receipt'],
            'FILTER' => $this->filters(),
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
        $option = $this->delivery->getOption();

        $branch = array_combine(
            array_column($option['optionBranch'], 'branch_id'),
            array_column($option['optionBranch'], 'branch_name')
        );
        $groupCate = (['' => __('Chọn chi nhánh')]) + $branch;

        return [
            'branches$branch_id' => [
                'data' => $groupCate,
            ],
            'deliveries$delivery_status' => [
                'data' => [
                    '' => __('Trạng thái'),
                    'packing' => __('Đóng gói'),
                    'preparing' => __('Chuẩn bị'),
                    'delivering' => __('Đang giao'),
                    'delivered' => __('Đã giao'),
                    'cancel' => __('Hủy')
                ]
            ]
        ];
    }

    /**
     * Danh sách đơn hàng cần giao
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
            'deliveries$delivery_status'
        ]);

        $data = $this->delivery->list($filter);

        return view('delivery::delivery.list', [
            'LIST' => $data['list'],
//            'receipt' => $data['receipt'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View chỉnh sửa đơn hàng cần giao
     *
     * @param $deliveryId
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function editAction($deliveryId)
    {
        $data = $this->delivery->dataEdit($deliveryId);
        $mConfig = new ConfigTable();
        $data['decimalQuantity'] = $mConfig->getInfoByKey('decimal_quantity')['value'] ?? 0;
        if ($data['item']['delivery_status'] != 'delivered' && $data['item']['delivery_status'] != 'cancel') {
            return view('delivery::delivery.edit', $data);
        } else {
            return redirect()->route('delivery');
        }
    }

    /**
     * Chỉnh sửa đơn hàng cần giao
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->delivery->update($request->all());

        return response()->json($data);
    }

    /**
     * View tạo phiếu giao hàng
     *
     * @param $deliveryId
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function createHistoryAction($deliveryId)
    {
        $data = $this->delivery->dataCreateHistory($deliveryId);
        $mConfig = new ConfigTable();
        $data['decimalQuantity'] = $mConfig->getInfoByKey('decimal_quantity')['value'] ?? 0;
        if ($data['item']['delivery_status'] != 'delivered' && $data['item']['delivery_status'] != 'cancel') {
            return view('delivery::delivery.create-history', $data);
        } else {
            return redirect()->route('delivery');
        }
    }

    /**
     * Chọn sản phẩm
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseProductAction(Request $request)
    {
        $data = $this->delivery->chooseProduct($request->all());

        return $data;
    }

    /**
     * Tạo phiếu giao hàng
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function storeHistoryAction(StoreRequest $request)
    {
        $data = $this->delivery->storeHistory($request->all());

        return $data;
    }

    /**
     * Preview đơn hàng
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function previewOrderAction(Request $request){
        $data = $this->delivery->previewOrderAction($request->all());

        return $data;
    }

    /**
     * Chi tiết đơn hàng cần giao
     *
     * @param $deliveryId
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function detailAction($deliveryId)
    {
        $data = $this->delivery->dataDetail($deliveryId);
        $mConfig = new ConfigTable();
        $data['decimalQuantity'] = $mConfig->getInfoByKey('decimal_quantity')['value'] ?? 0;
        return view('delivery::delivery.detail', $data);
    }

    /**
     * Cập nhật trạng thái đơn hàng cần giao
     *
     * @param Request $request
     * @return mixed
     */
    public function saveDetailAction(Request $request)
    {
        $data = $this->delivery->saveDetail($request->all());

        return $data;
    }

    /**
     * Xác nhận thanh toán
     *
     * @param Request $request
     * @return mixed
     */
    public function confirmReceiptAction(Request $request)
    {
        $data = $this->delivery->confirmReceipt($request->all());

        return $data;
    }

    /**
     * Load lại số tiền cần thu khi thay đổi số lượng
     *
     * @param Request $request
     * @return mixed
     */
    public function loadAmountAction(Request $request)
    {
        $data = $this->delivery->loadAmount($request->all());

        return $data;
    }

    /**
     * Chi tiết phiếu giao hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function detailDeliveryHistoryAction(Request $request)
    {
        $data = $this->delivery->detailHistory($request->all());

        return $data;
    }

    /**
     * Modal Chỉnh sửa phiếu giao hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function editHistoryAction(Request $request)
    {
        $data = $this->delivery->editHistory($request->all());

        return $data;
    }

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function updateHistoryAction(Request $request)
    {
        $data = $this->delivery->updateHistory($request->all());

        return $data;
    }

    /**
     * Show modal xác nhận thanh toán phiếu giao hàng
     *
     * @param Request $request
     * @return mixed
     */
    public function modalConfirmReceiptAction(Request $request)
    {
        return $this->delivery->modalConfirmReceipt($request->all());
    }

    /**
     * Cập nhật is_active đơn hàng cần giao
     *
     * @param Request $request
     * @return mixed
     */
    public function updateIsActiveDelivery(Request $request)
    {
        return $this->delivery->updateIsActiveDelivery($request->all());
    }

    /**
     * Thêm đơn hàng cần giao
     *
     * @param Request $request
     * @return mixed
     */
    public function storeDelivery(Request $request)
    {
        return $this->delivery->storeDelivery($request->all());
    }
}