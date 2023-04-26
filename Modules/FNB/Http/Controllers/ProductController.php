<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\FNB\Http\Requests\Promotion\UpdateRequest;
use Modules\FNB\Models\ProductChildTable;
use Modules\FNB\Repositories\Product\ProductRepositoryInterface;
use Modules\FNB\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\FNB\Repositories\ProductTopping\ProductToppingRepositoryInterface;
use Modules\FNB\Repositories\PromotionMaster\PromotionMasterRepositoryInterface;

class ProductController extends Controller
{
    private $product;

    public function __construct(ProductRepositoryInterface $product)
    {
        $this->product = $product;
    }

    /**
     * Giao diện chỉnh sửa tên tiếng anh của promotion
     */
    public function edit($productId){
        $data = $this->product->getDetail($productId);

        return view('fnb::product.edit', ['product'=> $data,'id' => $productId]);
    }

    /**
     * Luu thông tin tiếng anh
     * @param Request $request
     */
    public function update(Request $request){
        $param = $request->all();
        $data = $this->product->update($param);
        return \response()->json($data);
    }

    /**
     * Kiểm tra tên tiếng anh
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNameAction(Request $request){
        $param = $request->all();
        $data = $this->product->checkNameAction($param);
        return \response()->json($data);
    }

    public function addTopping($id){
        if (session()->has('list-topping')){
            session()->forget('list-topping');
        }
        $rProductTopping = app()->get(ProductToppingRepositoryInterface::class);
        $data = $this->product->getDetail($id);
        if ($data == null) {
            return redirect()->route('admin.product');
        }

        $listTopping = $rProductTopping->getAllTopping($id);
        $listTopping = collect($listTopping)->keyBy('product_child_id')->toArray();

        session()->put('list-topping',$listTopping);

        $linkBack = redirect()->back();

        return view('fnb::product.add-topping', [
            'id' => $id,
            'data' => $data,
            'listTopping' => $listTopping,
            'linkBack' => $linkBack
        ]);
    }

    /**
     * Lấy danh sách topping đã được thêm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListTopping(Request $request){
        $rProductTopping = app()->get(ProductToppingRepositoryInterface::class);
        $param = $request->all();
        $data = $rProductTopping->getAllTopping($param['product_id']);
        return \response()->json($data);
    }

    /**
     * Lưu topping
     * @param Request $request
     */
    public function storeTopping(Request $request){
        $param = $request->all();
        $rProductTopping = app()->get(ProductToppingRepositoryInterface::class);
        $data = $rProductTopping->storeTopping($param);
        return \response()->json($data);
    }

    /**
     * Thêm topping vào session
     * @param Request $request
     */
    public function addToppingSession(Request $request){
        $param = $request->all();
        $rProductTopping = app()->get(ProductToppingRepositoryInterface::class);
        $data = $rProductTopping->addToppingSession($param);
        return \response()->json($data);
    }

    /**
     * Xóa topping ra khỏi session
     * @param Request $request
     */
    public function removeToppingSession(Request $request){
        $param = $request->all();
        $rProductTopping = app()->get(ProductToppingRepositoryInterface::class);
        $data = $rProductTopping->removeToppingSession($param);
        return \response()->json($data);
    }

    public function getListProductChild(Request $request){
        $param = $request->all();
        $rProductChild = app()->get(ProductChildRepositoryInterface::class);
        $data = $rProductChild->getListProductChild($param);
        return response()->json($data);
    }
}
