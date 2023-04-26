<?php


namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\PaymentType\StoreRequest;
use Modules\Payment\Http\Requests\PaymentType\UpdateRequest;
use Modules\Payment\Repositories\PaymentType\PaymentTypeRepositoryInterface;

class PaymentTypeController extends Controller
{
    protected $paymentType;

    public function __construct(PaymentTypeRepositoryInterface $paymentType) {
        $this->paymentType = $paymentType;
    }

    /**
     * Thêm nhanh loại phiếu chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeQuicklyAction(Request $request)
    {
        $data = $this->paymentType->storeQuickly($request->all());

        return response()->json($data);
    }

    /**
     * Danh sách loại thanh toán
     *
     * @return array
     */
    public function index()
    {
        $data = $this->paymentType->list();
        return view('payment::payment-type.index', [
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
        $data = $this->paymentType->list($filter);

        return view('payment::payment-type.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    public function create()
    {
        return view('payment::payment-type.add');
    }

    /**
     * Thêm mới loại phiếu chi
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->paymentType->store($data);
    }

    /**
     * View chinh sua loại phiếu chi
     *
     * @param $id
     * @return array
     */
    public function edit($id)
    {
        $data = $this->paymentType->dataViewEdit($id);
        return view('payment::payment-type.edit', $data);
    }

    /**
     * Cập nhật loại phiếu chi
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->paymentType->update($data);
    }

    /**
     * xoá loại phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $data = $request->all();
        return $this->paymentType->destroy($data);
    }

    /**
     * cập nhật trạng thái loại phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatus(Request $request)
    {
        $data = $request->all();
        return $this->paymentType->changeStatus($data);
    }
}