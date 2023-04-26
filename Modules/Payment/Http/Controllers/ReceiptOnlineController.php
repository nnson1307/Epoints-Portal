<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 04/01/2022
 * Time: 14:11
 */

namespace Modules\Payment\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Payment\Repositories\ReceiptOnline\ReceiptOnlineRepoInterface;

class ReceiptOnlineController extends Controller
{
    protected $receiptOnline;

    public function __construct(
        ReceiptOnlineRepoInterface $receiptOnline
    ) {
        $this->receiptOnline = $receiptOnline;
    }

    /**
     * Danh sách giao dịch online
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy danh sách giao dịch online
        $data = $this->receiptOnline->list();

        return view('payment::receipt-online.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Các option filter
     *
     * @return \array[][]
     */
    public function filters()
    {
        return [
            'object_type' => [
                'data' => ([
                    '' => __('Đối tượng'),
                    'order' => __('Đơn hàng'),
                    'order_online' => __('Đơn hàng online'),
                    'receipt' => __('Phiếu thu'),
                    'debt' => __('Công nợ'),
                    'maintenance' => __('Phiếu bảo trì'),
                ])
            ],
            'type' => [
                'data' => ([
                    '' => __('Hình thức xác nhận'),
                    'auto' => __('Tự động'),
                    'manual' => __('Thủ công'),
                ])
            ],
            'status' => [
                'data' => ([
                    '' => __('Trạng thái'),
                    'inprocess' => __('Đang thực hiện'),
                    'success' => __('Thành công'),
                    'cancel' => __('Huỷ'),
                ])
            ],
            'payment_method_code' => [
                'data' => ([
                    '' => __('Hình thức thanh toán'),
                    'TRANSFER' => __('Chuyển khoản'),
                    'MOMO' => __('Momo'),
                    'VNPAY' => __('Vn Pay'),
                ])
            ],
        ];
    }

    /**
     * Filter, phân trang danh sách giao dịch online
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
            'payment_time',
            'object_type',
            'type',
            'status',
            'payment_method_code'
        ]);

        //Lấy danh sách giao dịch online
        $data = $this->receiptOnline->list($filter);

        return view('payment::receipt-online.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Huỷ thanh toán chuyển khoản
     *
     * @param Request $request
     * @return mixed
     */
    public function cancelAction(Request $request)
    {
        return $this->receiptOnline->cancel($request->all());
    }

    /**
     * Thanh toán chuyển khoản thành công
     *
     * @param Request $request
     * @return mixed
     */
    public function successAction(Request $request)
    {
        return $this->receiptOnline->success($request->all());
    }
}