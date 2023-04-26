<?php

namespace Modules\Report\Http\Controllers;

use App\Exports\ExportFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\OrderTable;
use Modules\Report\Repository\RevenueByProduct\RevenueByProductRepoInterface;

class ReportRevenueByProductController extends Controller
{
    protected $revenueByProduct;
    public function __construct(RevenueByProductRepoInterface $revenueByProduct)
    {
        $this->revenueByProduct = $revenueByProduct;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $data = $this->revenueByProduct->dataViewIndex();
        return view('report::revenue-by-product.index', [
            'branch' => $data['optionBranch']
        ]);
    }

    /**
     * filter time, branch, number staff
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->revenueByProduct->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart by product
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueByProduct->listDetail($request->all());

        return view('report::revenue-by-product.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail product
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->revenueByProduct->exportExcelTotal($request->all());
    }

    /**
     * Export detail list product
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueByProduct->exportExcelDetail($request->all());
    }
}