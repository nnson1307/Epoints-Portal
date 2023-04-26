<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\Receipt\StoreRequest;
use Modules\Payment\Http\Requests\Receipt\UpdateRequest;
use Modules\Payment\Repositories\Receipt\ReceiptRepoInterface;

class ReceiptController extends Controller
{
    protected $receipt;
    public function __construct(ReceiptRepoInterface $receipt) {
        $this->receipt = $receipt;
    }
    // view index
    public function index()
    {
        $data = $this->receipt->list();
        return view('payment::receipt.index', [
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
        $listStatus = ([
            '' => __('Trạng thái'),
            'unpaid' => __('Chưa thanh toán'),
            'part-paid' => __('Thanh toán một phần'),
            'paid' => __('Đã thanh toán'),
            'cancel' => __('Hủy'),
            'fail' => __('Lỗi'),
        ]);
        return [
            'receipts$status' => [
                'data' => $listStatus
            ],
        ];
    }

    /**
     * Render view list
     *
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
            'receipts$status'
        ]);
        $data = $this->receipt->list($filter);
        return view('payment::receipt.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm phiếu thu
     *
     * @return array
     */
    public function add()
    {
        $data = $this->receipt->dataViewCreate();
        return view('payment::receipt.add', $data);
    }

    /**
     * Thêm mới phiếu thu
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->receipt->store($data);
    }

    /**
     * View chỉnh sửa phiếu thu
     *
     * @param $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->receipt->dataViewEdit($id);
        return view('payment::receipt.edit', $data);
    }

    /**
     * Cập nhật phiếu thu
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->receipt->update($data);
    }

    /**
     * Xoá phiếu thu
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->receipt->delete($data);
    }

    /**
     * Load option đối tượng theo loại
     *
     * @param Request $request
     * @return mixed
     */
    public function loadOptionObjectAccounting(Request $request)
    {
        $data = $request->all();
        return $this->receipt->loadOptionObjectAccounting($data);
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
        return $this->receipt->printBill($data);
    }

    /**
     * Save log print bill
     *
     * @param Request $request
     * @return mixed
     */
    public function saveLogPrintBill(Request $request)
    {
        $data = $request->all();
        return $this->receipt->saveLogPrintBill($data);
    }

    /**
     * Chi tiết phiếu thu
     *
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $data = $this->receipt->dataViewDetail($id);

        return view('payment::receipt.detail', $data);
    }

    /**
     * Export excel ds phiếu thu
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelAction(Request $request)
    {
        return $this->receipt->exportExcel($request->all());
    }
}