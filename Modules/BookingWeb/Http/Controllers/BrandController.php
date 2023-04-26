<?php


namespace Modules\BookingWeb\Http\Controllers;


use Illuminate\Http\Request;
use Modules\BookingWeb\Repositories\Brand\BrandRepositoryInterface;

class BrandController extends Controller
{
    protected $brand;
    protected $display;
    public function __construct(BrandRepositoryInterface $brand)
    {
        $this->brand = $brand;
        $this->display = 10;
    }

    public function indexAction(Request $request) {

//        $brand = $this->brand->getListBrand();
        $display = $this->display;
        return view('bookingweb::brand.index', [
//            'brand' => $brand,
            'display' => $display
        ]);
    }

    public function getListBrandPage(Request $request){
        $param = $request->all();
        $brand = $this->brand->getListBrand($param)['Result']['Data'];
        $brandList = $brand['data'];
        $display = $this->display;
        $view = view('bookingweb::brand.list', [
            'brand' => $brandList,
            'page' => $brand,
            'display' => $display
        ])->render();

        return response()->json([
            'html' => $view
        ]);
    }
}