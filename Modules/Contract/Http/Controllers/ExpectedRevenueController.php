<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 15:56
 */

namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\ExpectedRevenue\StoreRequest;
use Modules\Contract\Http\Requests\ExpectedRevenue\UpdateRequest;
use Modules\Contract\Repositories\ExpectedRevenue\ExpectedRevenueRepoInterface;

class ExpectedRevenueController extends Controller
{
    protected $expectedRevenue;

    public function __construct(
        ExpectedRevenueRepoInterface $expectedRevenue
    ) {
        $this->expectedRevenue = $expectedRevenue;
    }

    /**
     * Danh sách dự kiến thu - chi
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        //Lấy data ds thu - chi
        $data = $this->expectedRevenue->listRevenue($request->all());

        return view('contract::contract.inc.expected-receipt.list', [
            'LIST' => $data['list'],
            'page' => (int) ($request->all()['page'] ?? 1)
        ]);
    }

    /**
     * Show modal thêm dự kiến thu - chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalCreateAction(Request $request)
    {
        $data = $this->expectedRevenue->getDataViewCreate($request->all());

        $html = \View::make('contract::contract.pop.expected-receipt.create-expected-receipt', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm dự kiến thu - chi
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->expectedRevenue->store($request->all());
    }

    /**
     * Show modal chỉnh sửa dự kiến thu - chi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalEditAction(Request $request)
    {
        $data = $this->expectedRevenue->getDataViewEdit($request->all());

        $html = \View::make('contract::contract.pop.expected-receipt.edit-expected-receipt', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa dự kiến thu - chi
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->expectedRevenue->update($request->all());
    }

    /**
     * Xoá dự kiến thu - chi
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->expectedRevenue->destroy($request->all());
    }
}