<?php

namespace Modules\Warranty\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warranty\Http\Requests\WarrantyPackage\StoreRequest;
use Modules\Warranty\Repository\WarrantyPackage\WarrantyPackageRepoInterface;

class WarrantyPackageController extends Controller
{
    protected $warrantyPackage;

    public function __construct(WarrantyPackageRepoInterface $warrantyPackage)
    {
        $this->warrantyPackage = $warrantyPackage;
    }

    public function index()
    {
        $data = $this->warrantyPackage->list();
        return view('warranty::warranty-package.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
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
        $data = $this->warrantyPackage->list($filter);
        return view('warranty::warranty-package.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm phiếu bảo hành
     *
     * @return array
     */
    public function create()
    {
        $data = $this->warrantyPackage->dataViewCreate();
        return view('warranty::warranty-package.add');
    }

    /**
     * Thêm gói bảo hành
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->warrantyPackage->store($data);
    }

    /**
     * View chỉnh sửa
     *
     * @param $warrantyPackageId
     * @return array
     */
    public function edit($warrantyPackageId)
    {
        $getData = $this->warrantyPackage->dataViewEdit($warrantyPackageId);
        return view('warranty::warranty-package.edit', [
            'data' => $getData['data'],
            'dataDetail' => $getData['dataDetail'],
            'listResult' => $getData['listResult'],
        ]);
    }

    /**
     * Cập nhật phiếu bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->all();
        return $this->warrantyPackage->update($data);
    }

    /**
     * Xoá gói bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->warrantyPackage->delete($data);
    }

    /**
     * Cập nhật trạng thái bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatus(Request $request)
    {
        $data = $request->all();
        return $this->warrantyPackage->updateStatus($data);
    }

    public function show($warrantyPackageId)
    {
        $getData = $this->warrantyPackage->dataViewDetail($warrantyPackageId);
        return view('warranty::warranty-package.detail', [
            'data' => $getData['data'],
            'dataDetail' => $getData['dataDetail'],
            'listResult' => $getData['listResult'],
        ]);
    }

    /**
     * Show popup sp/dv/thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupAction(Request $request)
    {
        $data = $this->warrantyPackage->showPopup($request->all());

        return response()->json($data);
    }

    /**
     * Ajax phân trang, filter product
     *
     * @param Request $request
     * @return mixed
     */
    public function listProductAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_keyword',
            'products$product_category_id'
        ]);

        return $this->warrantyPackage->listProduct($filter);
    }

    /**
     * Ajax phân trang, filter service
     *
     * @param Request $request
     * @return mixed
     */
    public function listServiceAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search', 'services$service_category_id']);

        return $this->warrantyPackage->listService($filter);
    }

    /**
     * Ajax filter, phân trang service card
     *
     * @param Request $request
     * @return mixed
     */
    public function listServiceCardAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_keyword', 'service_cards$service_card_group_id']);

        return $this->warrantyPackage->listServiceCard($filter);
    }

    /**
     * Chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAllAction(Request $request)
    {
        $data = $this->warrantyPackage->chooseAll($request->all());

        return response()->json($data);
    }

    /**
     * Chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAction(Request $request)
    {
        $data = $this->warrantyPackage->choose($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAllAction(Request $request)
    {
        $data = $this->warrantyPackage->unChooseAll($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAction(Request $request)
    {
        $data = $this->warrantyPackage->unChoose($request->all());

        return response()->json($data);
    }

    /**
     * Submit chọn sp, dv, thẻ dv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitChooseAction(Request $request)
    {
        $data = $this->warrantyPackage->submitChoose($request->all());

        return response()->json($data);
    }

    /**
     * Phân trang ds discount sp, dv, thẻ dv
     *
     * @param Request $request
     * @return mixed
     */
    public function listDiscountAction(Request $request)
    {
        $filter = $request->only(['page', 'display']);

        return $this->warrantyPackage->listDiscount($filter);
    }

    /**
     * Phân trang ds discount sp, dv, thẻ dv cho view chi tiết
     *
     * @param Request $request
     * @return mixed
     */
    public function listDiscountDetailAction(Request $request)
    {
        $filter = $request->only(['page', 'display']);

        return $this->warrantyPackage->listDiscountDetail($filter);
    }

    /**
     * Xóa dòng table sp, dv, thẻ db
     *
     * @param Request $request
     * @return mixed
     */
    public function removeTrAction(Request $request)
    {
        $data = $this->warrantyPackage->removeTr($request->all());

        return response()->json($data);
    }
}