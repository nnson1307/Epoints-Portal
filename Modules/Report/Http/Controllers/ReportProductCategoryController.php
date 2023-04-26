<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 4:45 PM
 */

namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Models\ProductCategoryTable;
use Modules\Report\Repository\ProductCategory\ReportProductCategoryRepoInterface;

class ReportProductCategoryController extends Controller
{
    protected $reportProductCategory;

    public function __construct(ReportProductCategoryRepoInterface $reportProductCategory)
    {
        $this->reportProductCategory = $reportProductCategory;
    }

    /**
     * View báo cáo loại sản phẩm
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $mProductCate = new ProductCategoryTable();
        $optionProductCate = $mProductCate->getOption();
        return view('report::product-category.index',[
            'productCategory' => $optionProductCate
        ]);
    }

    /**
     * Load chart báo cáo sản phẩm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadChartAction(Request $request)
    {
        $data = $this->reportProductCategory->loadChart($request->all());

        return response()->json($data);
    }
}