<?php

namespace Modules\Warranty\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warranty\Repository\ReportRepairCost\ReportRepairCostRepoInterface;

class ReportRepairCostController extends Controller
{
    protected $repairCost;
    public function __construct(ReportRepairCostRepoInterface $repairCost)
    {
        $this->repairCost = $repairCost;
    }

    /**
     * view index
     *
     * @return array
     */
    public function index()
    {
        $data = $this->repairCost->dataViewIndex();
        return view('warranty::report-repair-cost.index', [
            'branch' => $data['optionBranch']
        ]);
    }

    /**
     * Filter
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->repairCost->filterAction($request->all());
    }
}