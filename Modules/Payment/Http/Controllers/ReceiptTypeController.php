<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\ReceiptType\StoreRequest;
use Modules\Payment\Http\Requests\ReceiptType\UpdateRequest;
use Modules\Payment\Repositories\ReceiptType\ReceiptTypeRepoInterface;

class ReceiptTypeController extends Controller
{
    protected $receiptType;
    public function __construct(ReceiptTypeRepoInterface $receiptType) {
        $this->receiptType = $receiptType;
    }

    /**
     * Danh sách loại phiếu thu
     *
     * @return array
     */
    public function index()
    {
        $data = $this->receiptType->list();
        return view('payment::receipt-type.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    protected function filters()
    {
        return [

        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
        ]);
        $data = $this->receiptType->list($filter);

        return view('payment::receipt-type.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    public function create()
    {
        return view('payment::receipt-type.add');
    }

    /**
     * Thêm mới loại phiếu thu
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->receiptType->store($data);
    }

    /**
     * View chinh sua
     *
     * @param $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->receiptType->dataViewEdit($id);
        return view('payment::receipt-type.edit', $data);
    }

    /**
     * Cập nhật loại phiếu thu
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->receiptType->update($data);
    }

    /**
     * xoá loại phiếu thu
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $data = $request->all();
        return $this->receiptType->destroy($data);
    }

    /**
     * cập nhật trạng thái
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatus(Request $request)
    {
        $data = $request->all();
        return $this->receiptType->changeStatus($data);
    }
}