<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 17:05
 */

namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\ContractSpend\StoreRequest;
use Modules\Contract\Http\Requests\ContractSpend\UpdateRequest;
use Modules\Contract\Repositories\ContractSpend\ContractSpendRepoInterface;

class ContractSpendController extends Controller
{
    protected $contractSpend;

    public function __construct(
        ContractSpendRepoInterface $contractSpend
    ) {
        $this->contractSpend = $contractSpend;
    }

    /**
     * Danh sách đợt chi
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        //Lấy data đợt thu
        $data = $this->contractSpend->list($request->all());

        return view('contract::contract.inc.contract-spend.list', [
            'LIST' => $data['list'],
            'page' => (int) ($request->all()['page'] ?? 1)
        ]);
    }

    /**
     * Show modal thêm đợt chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalCreateAction(Request $request)
    {
        $data = $this->contractSpend->getDataCreate($request->all());

        $html = \View::make('contract::contract.pop.contract-spend.create', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm đợt chi
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->contractSpend->store($request->all());
    }

    /**
     * Show modal chỉnh sửa đợt chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalEditAction(Request $request)
    {
        $data = $this->contractSpend->getDataEdit($request->all());

        $html = \View::make('contract::contract.pop.contract-spend.edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa đợt chi
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->contractSpend->update($request->all());
    }

    /**
     * Show modal xoá đợt chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalRemoveAction(Request $request)
    {
        $html = \View::make('contract::contract.pop.contract-spend.remove', [
            'contract_spend_id' => $request->contract_spend_id
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Xoá đợt chi
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->contractSpend->destroy($request->all());
    }
}