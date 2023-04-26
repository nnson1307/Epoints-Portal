<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/8/2020
 * Time: 9:37 AM
 */

namespace Modules\Delivery\Http\Controllers;


use Illuminate\Http\Request;

use Modules\Delivery\Http\Requests\UserCarrier\StoreRequest;
use Modules\Delivery\Http\Requests\UserCarrier\UpdateRequest;
use Modules\Delivery\Repositories\UserCarrier\UserCarrierRepoInterface;

class UserCarrierController extends Controller
{
    protected $userCarrier;

    public function __construct(
        UserCarrierRepoInterface $userCarrier
    ) {
        $this->userCarrier = $userCarrier;
    }

    /**
     * Ds nhân viên giao hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->userCarrier->getList();

        return view('delivery::user-carrier.index', [
            'LIST' => $data,
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

        return [

        ];
    }

    /**
     * Ajax ds nv giao hàng
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
            'created_at',
        ]);

        $data = $this->userCarrier->getList($filter);

        return view('delivery::user-carrier.list', [
            'LIST' => $data,
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm nv giao hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        return view('delivery::user-carrier.create');
    }

    /**
     * Thêm nv giao hàng
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->userCarrier->store($request->all());

        return response()->json($data);
    }

    /**
     * View chỉnh sửa nv giao hàng
     *
     * @param $userCarrierId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($userCarrierId)
    {
        $data = $this->userCarrier->getInfo($userCarrierId);

        return view('delivery::user-carrier.edit', $data);
    }

    /**
     * Chỉnh sửa nv giao hàng
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->userCarrier->update($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi trạng thái
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->userCarrier->changeStatus($request->all());

        return response()->json($data);
    }

    /**
     * Xóa nv giao hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->userCarrier->destroy($request->all());

        return response()->json($data);
    }
}