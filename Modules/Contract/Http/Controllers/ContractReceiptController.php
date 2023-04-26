<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/09/2021
 * Time: 14:55
 */

namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\ContractReceipt\StoreRequest;
use Modules\Contract\Http\Requests\ContractReceipt\UpdateRequest;
use Modules\Contract\Repositories\ContractReceipt\ContractReceiptRepoInterface;

class ContractReceiptController extends Controller
{
    protected $contractReceipt;

    public function __construct(
        ContractReceiptRepoInterface $contractReceipt
    ) {
        $this->contractReceipt = $contractReceipt;
    }

    /**
     * Danh sách đợt thu
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        //Lấy data đợt thu
        $data = $this->contractReceipt->list($request->all());

        return view('contract::contract.inc.contract-receipt.list', [
            'LIST' => $data['list'],
            'page' => (int) ($request->all()['page'] ?? 1)
        ]);
    }

    /**
     * Show modal thêm đợt thu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalCreateAction(Request $request)
    {
        $data = $this->contractReceipt->getDataCreate($request->all());

        $html = \View::make('contract::contract.pop.contract-receipt.create', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm đợt thu
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->contractReceipt->store($request->all());
    }

    /**
     * Show modal chỉnh sửa đợt thu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalEditAction(Request $request)
    {
        $data = $this->contractReceipt->getDataEdit($request->all());

        $html = \View::make('contract::contract.pop.contract-receipt.edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa đợt thu
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->contractReceipt->update($request->all());
    }

    /**
     * Show modal xoá đợt thu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalRemoveAction(Request $request)
    {
        $html = \View::make('contract::contract.pop.contract-receipt.remove', [
            'contract_receipt_id' => $request->contract_receipt_id
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Xoá đợt thu
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->contractReceipt->destroy($request->all());
    }
}