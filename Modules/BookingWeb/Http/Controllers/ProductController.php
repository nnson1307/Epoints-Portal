<?php


namespace Modules\BookingWeb\Http\Controllers;


use Illuminate\Http\Request;
use Modules\BookingWeb\Repositories\Booking\BookingRepositoryInterface;
use Modules\BookingWeb\Repositories\Product\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $booking;
    protected $product;
    protected $display;
    public function __construct(BookingRepositoryInterface $booking , ProductRepositoryInterface $product)
    {
        $this->booking = $booking;
        $this->product = $product;
        $this->display = 12;
    }

    public function indexAction(Request $request){
        $product = $this->product->list()['Result']['Data'];
        unset($product['service_category']);
        $display = $this->display;
        if ($request->isMethod('post')) {
                return view('bookingweb::product.index', [
                    'product' => $product,
                    'product_category_id' => $request->product_category_id,
                    'display' => $display
                ]);
        }
        return view('bookingweb::product.index', [
            'product' => $product,
            'display' => $display
        ]);
    }

    //    Lấy danh sách product theo group
    public function getProductGroup(Request $request){
        $param = $request->all();
        $listProduct = $this->product->getProduct($param)['Result']['Data'];
        $listProductByGroup = $listProduct['data'];
        $display = $this->display;
        $view = view('bookingweb::product.list', [
            'product' => $listProductByGroup,
            'page' => $listProduct,
            'display' => $display
        ])->render();

        return response()->json([
            'html' => $view
        ]);
    }

    public function getProductDetail($id , Request $request){
        $product['product_id'] = $id;
        $getProductDetailGroup = $this->product->getProductDetailGroup($product)['Result']['Data'];
        $display = $this->display;
        return view('bookingweb::product.detail', [
            'productDetail' => $getProductDetailGroup,
            'display' => $display
        ]);
    }
}