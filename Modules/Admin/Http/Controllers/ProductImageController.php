<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/3/2018
 * Time: 2:39 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductImage\ProductImageRepositoryInterface;

class ProductImageController extends Controller
{
    protected $productImage;
    protected $product;

    public function __construct(ProductImageRepositoryInterface $productImage,
                                ProductRepositoryInterface $product)
    {
        $this->productImage = $productImage;
        $this->product = $product;
    }

    public function indexAction()
    {
        $productImageList = $this->productImage->list();
        $productList = $this->product->getOption();
        return view('admin::product-image.index', [
            'LIST' => $productImageList,
            'PRODUCT' => $productList
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword']);
        $productImageList = $this->productImage->list($filters);
        return view('admin::product-image.list', ['LIST' => $productImageList]);
    }

    public function removeAction($id)
    {
        $this->productImage->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'product_id' => $request->productId,
                'name' => $request->name,
                'type' => $request->type,
                'created_by' => Auth::id()
            ];
            $this->productImage->add($data);
            $message = ['message' => '', 'close' => $request->close];
        } else {
            $message = ['message' => __('Thêm hình ảnh sản phẩm thất bại')];
        }
        return response()->json($message);
    }

    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $item = $this->productImage->getItem($id);
            $data = [
                'product_id' => $item->product_id,
                'name' => $item->name,
                'type' => $item->type,
                'id' => $id
            ];
            return response()->json($data);
        }
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $data=[
            'product_id'=>$request->productId,
            'name'=>$request->name,
            'type'=>$request->type,

        ];
        $this->productImage->edit($data,$id);
    }
}