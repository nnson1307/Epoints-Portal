<?php

namespace Modules\Contract\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Contract\Http\Requests\Vat\StoreRequest;
use Modules\Contract\Http\Requests\Vat\UpdateRequest;
use Modules\Contract\Repositories\Vat\VatRepoInterface;

class VatController extends Controller
{
    protected $vat;

    public function __construct(
        VatRepoInterface $vat
    ) {
        $this->vat = $vat;
    }

    /**
     * View danh sách VAT
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        //Lấy data DS VAT
        $data = $this->vat->getList();

        return view('contract::vat.index', [
            'LIST' => $data['list']
        ]);
    }

    public function listAction(Request $request)
    {
        //Lấy data DS VAT
        $data = $this->vat->getList($request->all());

        return view('contract::vat.list', [
            'LIST' => $data['list']
        ]);
    }

    /**
     * Show pop thêm VAT
     *
     * @return JsonResponse
     */
    public function showPopCreateAction()
    {
        $html = \View::make('contract::vat.create')->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm VAT
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->vat->store($request->all());

        return response()->json($data);
    }

    public function showPopEditAction(Request $request)
    {
        //Lấy data VAT
        $data = $this->vat->getDataViewEdit($request->all());

        $html = \View::make('contract::vat.edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa VAT
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->vat->update($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi trạng thái VAT
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->vat->changeStatus($request->all());

        return response()->json($data);
    }
}