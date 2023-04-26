<?php
/**
 * Created by PhpStorm
 * User: Nhandt
 * Date: 03/05/2021
 * Time: 11:34 PM
 */

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\Payment\StoreRequest;
use Modules\Payment\Http\Requests\Payment\UpdateRequest;
use Modules\Payment\Repositories\Payment\PaymentRepositoryInterface;

class PaymentController extends Controller
{
    protected $payment;
    public function __construct(PaymentRepositoryInterface $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Danh sách phiếu chi
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $payments = $this->payment->list();
        $param = $request->all();
        return view('payment::payment.index', [
            'LIST' => $payments['LIST'],
            'FILTER' => $this->filters(),
            'BRANCH' => $payments['BRANCH'],
            'STAFF' => $payments['STAFF'],
            'PAYMENT_TYPE' => $payments['PAYMENT_TYPE'],
            'PAYMENT_METHOD' => $payments['PAYMENT_METHOD'],
            'OBJECT_ACCOUNTING_TYPE' => $payments['OBJECT_ACCOUNTING_TYPE'],
            'param'=> $param
        ]);
    }

    public function filters()
    {
        return [];
    }

    /**
     * Danh sách phiếu chi, phân trang, filter
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword','branch_code', 'status',
            'created_at','created_by', 'search']);
        $paymentList = $this->payment->list($filter);
        return view('payment::payment.list', ['LIST' => $paymentList['LIST'], 'page' => $filter['page']]);
    }

    /**
     * Lấy thông tin các object accounting type
     *
     * @param Request $request
     * @return mixed
     */
    public function appendObjectAccountingType(Request $request)
    {
        $code = $request->all();
        return $this->payment->getSelectOptionByObjectAccountingTypeCode($code['code']);
    }

    /**
     * Tạo phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function createPayment(StoreRequest $request)
    {
        $dataCreate = $request->all();
        return $this->payment->createPayment($dataCreate);
    }

    /**
     * Xoá phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function deletePayment(Request $request)
    {
        $id = $request->all()['payment_id'];
        return $this->payment->deletePayment($id);
    }

    /**
     * Lấy thông tin 1 phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function getDataById(Request $request)
    {
        $id = $request->all()['payment_id'];
        return $this->payment->getDataById($id);
    }

    /**
     * Trả về view popup chỉnh sửa phiếu chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->payment->dataViewEdit($request->all());
        return response()->json($data);
    }

    /**
     * Lưu thay đổi khi cập nhật phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function saveUpdate(UpdateRequest $request)
    {
            $id = $request->payment_id;
            $data = $request->all();
            return $this->payment->edit($data,$id);
    }

    /**
     * Hành động xem chi tiết phiếu chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        $data = $this->payment->dataViewdetail($request->all());
        return response()->json($data);
    }
    /**
     * View in bill
     *
     * @param Request $request
     * @return mixed
     */
    public function printBill(Request $request)
    {
        $data = $request->all();
        return $this->payment->printBill($data);
    }
    public function saveLogPrintBill(Request $request)
    {
        $data = $request->all();
        return $this->payment->saveLogPrintBill($data);
    }

    /**
     * Export excel ds phiếu thu
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelAction(Request $request)
    {
        return $this->payment->exportExcel($request->all());
    }
}