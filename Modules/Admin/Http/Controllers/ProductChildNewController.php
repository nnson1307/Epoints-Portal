<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ProductChildNew\UpdateRequest;
use Modules\Admin\Models\ProductChildCustomDefineTable;
use Modules\Admin\Repositories\ProductChildNew\ProductChildNewRepositoryInterface;

class ProductChildNewController extends Controller
{
    protected $productChild;

    public function __construct(ProductChildNewRepositoryInterface $productChild)
    {
        $this->productChild = $productChild;
    }

    protected function filters()
    {
        return [];
    }

    public function index()
    {
        $data = $this->productChild->list();
        return view('admin::product-child-new.index', [
            'LIST' => $data,
            'FILTER' => $this->filters()
        ]);
    }

    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
        ]);
        $data = $this->productChild->list($filter);
        return view('admin::product-child-new.list', [
            'LIST' => $data,
            'page' => $filter['page']
        ]);
    }

    /**
     * Màn hình chỉnh sửa sản phẩm con
     *
     * @param $id
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $data = $this->productChild->dataViewEdit($id);
        return view('admin::product-child-new.edit', $data);
    }

    /**
     * Cập nhật sản phẩm con
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->productChild->updateAction($request->all());
    }

    /**
     * Màn hình chi tiết sản phẩm con
     *
     * @param $id
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $data = $this->productChild->dataViewEdit($id);
        return view('admin::product-child-new.detail', $data);
    }

    /**
     * Cập nhật trạng thái cho is_active, is_display
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatus(Request $request)
    {
        return $this->productChild->updateStatus($request->all());
    }

    /**
     * Lấy danh sách sản phẩm tồn kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListInventory(Request $request)
    {
        $data = $this->productChild->getListInventory($request->all());
        return response()->json($data);
    }

    /**
     * hiển thị popup serial tồn kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupSerial(Request $request)
    {
        $data = $this->productChild->showPopupSerial($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách serial tồn kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListSerialPopup(Request $request)
    {
        $data = $this->productChild->getListSerialPopup($request->all());
        return response()->json($data);
    }
}