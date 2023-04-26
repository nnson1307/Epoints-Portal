<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 10:37 AM
 */

namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Models\ProductChildTable;
use Modules\Report\Models\ProductTable;
use Modules\Report\Repository\Product\ReportProductRepoInterface;

class ReportProductController extends Controller
{
    protected $reportProduct;

    public function __construct(ReportProductRepoInterface $reportProduct)
    {
        $this->reportProduct = $reportProduct;
    }

    /**
     * View báo cáo sản phẩm
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $mProduct = new \Modules\Admin\Models\ProductChildTable();
        $optionProduct = $mProduct->getProductChildOption();
        return view('report::product.index',[
            'product' => $optionProduct
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
        $data = $this->reportProduct->loadChart($request->all());

        return response()->json($data);
    }

    /**
     * Export tổng báo cáo sản phẩm
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->reportProduct->exportExcelTotal($request->all());
    }
}