<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:39 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductModel\ProductModelRepositoryInterface;

class ProductChildController extends Controller
{
    protected $productChild;
    protected $productCategory;
    protected $productModel;
    protected $branch;

    public function __construct(
        ProductChildRepositoryInterface $productChild,
        ProductCategoryRepositoryInterface $productCategory,
        ProductModelRepositoryInterface $productModel,
        BranchRepositoryInterface $branch
    )
    {
        $this->productChild = $productChild;
        $this->productCategory = $productCategory;
        $this->productModel = $productModel;
        $this->branch = $branch;
    }

    /**
     * Quản lý product childs.
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        session()->put('type_tab', 'new');
        $productCategoryList = $this->productCategory->getAll();
        $productModelList = $this->productModel->getAll();
        $branch = $this->branch->getBranch();
        $getListCondition = $this->productChild->getListCondition();
        $getListTags = $this->productChild->getListTags();

        $getListProductSuggestConfig = $this->productChild->getListProductSuggestConfig();

        return view('admin::product-child.index', [
            'productCategoryList' => $productCategoryList,
            'productModelList' => $productModelList,
            'branch' => $branch,
            'getListCondition' => $getListCondition,
            'getListTags' => $getListTags,
            'getListProductSuggestConfig' => $getListProductSuggestConfig
        ]);
    }

    /**
     * Danh sách product childs theo tab
     * @param Request $request
     *
     * @return string
     * @throws \Throwable
     */
    public function listTab(Request $request)
    {
        $filters = $request->all();
        if (!isset($filters['type_tab'])) {
            $tabCurrent = $request->session()->get('type_tab');
            $filters['type_tab'] = $tabCurrent;
        }

        $typeTab = isset($filters['type_tab']) ? $filters['type_tab'] : '';
        $list = $this->productChild->listTab($filters);
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['display'] ?? PAGING_ITEM_PER_PAGE);
        $stt = ($page - 1) * $display + 1;
        $contents = view('admin::product-child.list.list', [
            'LIST' => $list,
            'stt' => $stt,
            'typeTab' => $typeTab,
        ])->render();
        return $contents;
    }

    /**
     * Option product child để thêm với vào 3 tab: Mới, giảm giá, bán chạy.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionAddTab(Request $request)
    {
        $filters['type_tab'] = $request->type_tab;
        $filters['display'] = 10000;
        $listNotIn = $this->productChild->listTab($filters);
        $option = $this->productChild->getOptionAddTab($listNotIn, $filters);
        $contents = view('admin::product-child.popup.add-new-best-seller', [
            'option' => $option,
            'type_tab' => $filters['type_tab'],
        ])->render();
        return $contents;
    }

    /**
     * Chọn sản phẩm.
     * @param Request $request
     *
     * @return mixed
     */
    public function selectedProductChild(Request $request)
    {
        $id = $request->id;
        $detail = $this->productChild->selectedProductChild($id);
        return response()->json($detail);
    }

    /**
     * Thêm cấu hình sản phẩm thương mại.
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddProductChild(Request $request)
    {
        $params = $request->all();
        $result = $this->productChild->submitAddProductChild($params);
        return response()->json($result);
    }

    /**
     * Đang ở tab nào để khi phân trang biết loại nào mà phân trang.
     * @param Request $request
     */
    public function tabCurrent(Request $request)
    {
        $typeTab = $request->type_tab;
        $request->session()->put('type_tab', $typeTab);
    }

    /**
     * Remove product child.
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeList(Request $request)
    {
        $params = $request->all();
        $result = $this->productChild->removeList($params);
        return response()->json($result);
    }

    /**
     * Thêm điều kiện cấu hình sản phẩm gợi ý
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addConditionSuggest(Request $request){
        $params = $request->all();
        $result = $this->productChild->addConditionSuggest($params);
        return response()->json($result);
    }

    /**
     * Insert cấu hình sản phẩm gợi ý
     * @param Request $request
     */
    public function insertConditionSuggest(Request $request){
        $params = $request->all();
        $result = $this->productChild->insertConditionSuggest($params);
        return response()->json($result);
    }
}