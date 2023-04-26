<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;

class ProductBranchPriceController extends Controller
{
    protected $productBranchPrice;
    protected $product;
    protected $productChild;
    protected $branch;
    protected $productCategory;

    public function __construct(
        ProductBranchPriceRepositoryInterface $productBranchPrice,
        ProductRepositoryInterface $product,
        BranchRepositoryInterface $branch,
        ProductChildRepositoryInterface $productChild,
        ProductCategoryRepositoryInterface $productCategory
    )
    {
        $this->productBranchPrice = $productBranchPrice;
        $this->productChild = $productChild;
        $this->product = $product;
        $this->branch = $branch;
        $this->productCategory = $productCategory;
    }

    public function indexAction()
    {
        //Lấy tất cả chi nhánh
        $branchList = $this->branch->getBranch();
        $arrBranch = [];
        if (count($branchList) > 0) {
            foreach ($branchList as $k => $v) {
                $arrBranch[$k] = 0;
            }
        }
        //Lấy ds product child
        $productChild = $this->productChild->getListChildOrderPaginate();
        //Load giá chi nhánh
        if (count($productChild->items()) > 0) {
            foreach ($productChild as $v) {
                $productBranchPriceList = $this->productBranchPrice->getProductBranchPrice($v['product_child_id'])->toArray();
                //Lấy product branch price
                $priceList = array_combine(
                    array_column($productBranchPriceList, 'branch_id'),
                    array_column($productBranchPriceList, 'new_price')
                );
                //Load price của tất cả chi nhánh
                $arrPrice = [];
                foreach ($arrBranch as $k1 => $v1) {
                    $arrPrice [] = isset($priceList[$k1]) ? $priceList[$k1] : $v1;
                }
                $v['branchPrice'] = $arrPrice;
            }
        }

        return view('admin::product-branch-prices.index', [
            'LIST' => $productChild,
            'FILTER' => $this->filters(),
            'BRANCH' => $branchList
        ]);
    }

    protected function filters()
    {
        $optionCate = $this->productCategory->getAll();
        $groupCate = (["" => __("Chọn nhóm sản phẩm")]) + $optionCate;
        return [
            'products$product_category_id' => [
                'data' => $groupCate
            ],
        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'products$product_category_id', 'created_at', 'search']);
        //Lấy tất cả chi nhánh
        $branchList = $this->branch->getBranch();

        $arrBranch = [];
        if (count($branchList) > 0) {
            foreach ($branchList as $k => $v) {
                $arrBranch[$k] = 0;
            }
        }
        //Lấy ds product child
        $productChild = $this->productChild->getListChildOrderPaginate($filter);
        //Load giá chi nhánh
        if (count($productChild->items()) > 0) {
            foreach ($productChild as $v) {
                $productBranchPriceList = $this->productBranchPrice->getProductBranchPrice($v['product_child_id'])->toArray();
                //Lấy product branch price
                $priceList = array_combine(
                    array_column($productBranchPriceList, 'branch_id'),
                    array_column($productBranchPriceList, 'new_price')
                );
                //Load price của tất cả chi nhánh
                $arrPrice = [];
                foreach ($arrBranch as $k1 => $v1) {
                    $arrPrice [] = isset($priceList[$k1]) ? $priceList[$k1] : $v1;
                }
                $v['branchPrice'] = $arrPrice;
            }
        }

        return view('admin::product-branch-prices.list', [
            'LIST' => $productChild,
            'FILTER' => $this->filters(),
            'BRANCH' => $branchList,
            'page' => $filter['page']
        ]);
    }

    /**
     * Trang cấu hình giá
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function configAction()
    {
        $branchList = $this->branch->getBranch();
        $productList = $this->product->getProduct();

        return view('admin::product-branch-prices.config', [
            'BRANCH_LIST' => $branchList,
            'PRODUCT_LIST' => $productList,
        ]);
    }

    public function listConfigAction(Request $request)
    {
        $branchId = $request->branchId;
        $productId = $request->product;
        $productBranchPriceList = $this->productBranchPrice->getProductBranchPriceByBranchId($branchId)->toArray();

        $branchList = $this->branch->getBranch([$request->branchId]);

        $arr = [];

        if (count($productBranchPriceList) > 0) {
            foreach ($productBranchPriceList as $v) {
                $v['old_price'] = number_format($v['old_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0);
                $v['new_price'] = number_format($v['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0);
                $arr [] = $v;
            }
        }

        $result = [$branchList, $arr];

        return response()->json($result);
    }

    public function submitConfigAction(Request $request)
    {
        $branchId = $request->branchId;
        $listProduct = $request->listProduct;

        foreach ($listProduct as $key => $value) {
            $this->productBranchPrice->editConfigPrice($value, $branchId);
        }

        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function changBranchAction(Request $request){
        $param = $request->all();
//        $branchList = $this->branch->getBranch([$param['branchId']]);
        $branchList = $this->branch->getBranch();

        $view = view('admin::product-branch-prices.partital.option',['list' => $branchList])->render();

        return response()->json([
            'error' => 0,
            'view' => $view
        ]);
    }

    public function listProductChildAction(Request $request)
    {
        $listProductId = $request->listProductId;
        $productChildList = $this->productChild->getProductChildByProductId($listProductId);
        $arr = [];

        if (count($productChildList) > 0) {
            foreach ($productChildList as $v) {
                $v['cost'] = number_format($v['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0);
                $v['price'] = number_format($v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0);
                $arr [] = $v;
            }
        }
        return response()->json($arr);
    }

    public function editAction($id)
    {
        $productItem = $this->productChild->getItem($id);
        $get = $this->productBranchPrice->getProductBanchPrice($id);
        $itemBranch = $this->productBranchPrice->getItem($id);
        $optionBranch = $this->branch->getBranch();

        $arrayBranch = [];
        $arrayBranchDB = [];
        foreach ($get as $item) {
            $arrayBranchDB[] = $item['branch_id'];
        }

        foreach ($optionBranch as $key => $value) {
            if (!in_array($key, $arrayBranchDB)) {
                $arrayBranch[] = $key;
            }

        }
        $branchWhereIn = $this->branch->searchWhereIn($arrayBranch);

        return view('admin::product-branch-prices.edit', [
            'item' => $productItem,
            'itemBranch' => $itemBranch,
            'optionBranch' => $optionBranch,
            'LIST' => $get,
            'FILTER' => $this->filters(),
            'branchWhereIn' => $branchWhereIn
        ]);
    }

    public function submitEditAction(Request $request)
    {
        $listBranch = $request->listBranch;

        $idProduct = $request->product_child_id;
        foreach ($listBranch as $item) {

            $isActived = ($item[4] == 'true') ? 1 : 0;
            $data = [
                'new_price' => str_replace(',', '', $item[3]),
                'is_actived' => $isActived,
            ];
            if ($item[0] != "0") {
                $this->productBranchPrice->edit($data, $item[0]);
            }else{
                if ($isActived == 1 || $item[3] != 0) {
                    $data2 = [
                        'branch_id' => $item[1],
                        'product_id' => $idProduct,
                        'old_price' => $item[2],
                        'new_price' => str_replace(',', '', $item[3]),
                        'is_actived' => $isActived,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->staff_id,
                        'updated_by' => Auth::user()->staff_id,
                        'product_code'=>$this->productChild->getItem($idProduct)['product_code']
                    ];
                    $this->productBranchPrice->add($data2);
                }
            }
        }

        return response()->json($listBranch[0]);
    }

    /**
     * Ajax lấy danh sách giá chi nhánh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listBranchAction(Request $request)
    {
        $serviceItem = $this->productChild->getItem($request->id);
        $get = $this->productBranchPrice->list([], $serviceItem->product_id)->toArray();

        return response()->json($get['data']);
    }

    public function filterAction(Request $request)
    {
        $keyword = $request->keyword;
        $productCategory = $request->productCategory;
//        $productChild = $this->productChild->getListChildOrder($keyword, $productCategory);
        $productChild = $this->productChild->getListChildOrderPaginate($request->all(), $productCategory);
        $arrPorductId = [];
        if ($productChild->toArray()['data'] != null) {
            $arrPorductId = collect($productChild->toArray()['data'])->keyBy('product_id');
        }
//        $productBranchPriceList = $this->productBranchPrice->getProductBranchPrice();
        $productBranchPriceList = $this->productBranchPrice->getProductBranchPrice($arrPorductId);
        $branchList = $this->branch->getBranch();
        $priceList = [];
        $result = [];

        foreach ($productChild as $key => $itemProductChild) {
            foreach ($branchList as $brachId => $branchName) {
                $priceList[$brachId] = 0;
                foreach ($productBranchPriceList as $item) {
                    if ($item['product_id'] == $itemProductChild['product_id']) {
                        if ($item['branch_id'] == $brachId) {
                            $priceList[$brachId] = $item['new_price'];

                        }
                    }
                }
            }
//            $result[] = [$itemProductChild, $priceList];
            $productChild[$key]['brand'] = $priceList;
        }
        $resultPaginate = collect($result)->forPage(1, 10);
        $content = view('admin::product-branch-prices.filter', [
            'BRANCH' => $branchList,
            'page' => 1,
//            'data' => $result,
            'data' => $productChild->toArray()['data'],
//            'LIST' => $resultPaginate,
            'LIST' => $productChild,
        ])->render();
        return $content;
    }

    public function pagingFilterAction(Request $request)
    {
        $keyword = $request->keyword;
        $productCategory = $request->productCategory;
        $page = $request->page;
//        $productChild = $this->productChild->getListChildOrder($keyword, $productCategory);
        $productChild = $this->productChild->getListChildOrderPaginate($request->all(), $productCategory);
        $arrPorductId = [];
        if ($productChild->toArray()['data'] != null) {
            $arrPorductId = collect($productChild->toArray()['data'])->keyBy('product_id');
        }


//        $productBranchPriceList = $this->productBranchPrice->getProductBranchPrice();
        $productBranchPriceList = $this->productBranchPrice->getProductBranchPrice($arrPorductId);
        $branchList = $this->branch->getBranch();
        $priceList = [];
        $result = [];

        foreach ($productChild as $key => $itemProductChild) {
            foreach ($branchList as $brachId => $branchName) {
                $priceList[$brachId] = 0;
                foreach ($productBranchPriceList as $item) {
                    if ($item['product_id'] == $itemProductChild['product_id']) {
                        if ($item['branch_id'] == $brachId) {
                            $priceList[$brachId] = $item['new_price'];
                        }
                    }
                }
            }
//            $result[] = [$itemProductChild, $priceList];
            $productChild[$key]['brand'] = $priceList;
        }
        $resultPaginate = collect($result)->forPage($page, 10);
        $content = view('admin::product-branch-prices.filter', [
            'BRANCH' => $branchList,
            'page' => $page,
//            'data' => $result,
            'data' => $productChild->toArray()['data'],
//            'LIST' => $resultPaginate,
            'LIST' => $productChild,
        ])->render();
        return $content;
    }
}