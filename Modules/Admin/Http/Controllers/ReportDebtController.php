<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;

class ReportDebtController extends Controller
{
    protected $customer_debt;
    protected $branch;

    public function __construct(
        CustomerDebtRepositoryInterface $customer_debt,
        BranchRepositoryInterface $branch
    ) {
        $this->customer_debt = $customer_debt;
        $this->branch = $branch;
    }

    /**
     * View báo cáo công nợ theo chi nhánh
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction()
    {
        $optionBranch = $this->branch->getBranchOption();

        return view('admin::report.report-debt-by-branch.index', [
            'optionBranch' => $optionBranch
        ]);
    }

    /**
     * Báo cáo công nợ theo chi nhánh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadChartBranchAction(Request $request)
    {
        $report_debt = $this->customer_debt->reportDebtAll($request->branch, $request->time);
        $total_all = count($report_debt);
        $amount_all = collect($report_debt)->sum('amount');
        $total_paid = 0;
        $amount_paid = 0;
        $total_unpaid = 0;
        $amount_unpaid = 0;
        $total_cancel = 0;
        $amount_cancel = 0;
        $arr_branch_id = [];
        $arr_branch_name = [];
        $amount_branch = [];

        //Lấy tên chi nhánh
        foreach ($report_debt as $item) {
            $arr_branch_id[] = $item['branch_id'];
            $arr_branch_name[] = $item['branch_name'];
        }
        foreach ($report_debt as $item) {
            if ($item['status'] == 'paid') {
                $total_paid++;
                $amount_paid += $item['amount_paid'];
            }
            if ($item['status'] == 'part-paid') {
                $total_paid++;
                $amount_paid += $item['amount_paid'];
            }
            if($item['status'] == 'unpaid') {
                $total_unpaid++;
                $amount_unpaid += $item['amount'] - $item['amount_paid'];
            }
            if ($item['amount_paid'] > 0
                && $item['amount'] > $item['amount_paid']
                && $item['status'] != 'cancel'
            ) {
                $total_unpaid++;
                $amount_unpaid += $item['amount'] - $item['amount_paid'];
            }
            if ($item['status'] == 'cancel') {
                $total_cancel++;
                $amount_cancel += $item['amount'];
            }
//            $amount_unpaid += $item['amount'] - $item['amount_paid'];
        }
        //Lấy tiền theo chi nhánh
        foreach (array_unique($arr_branch_id) as $branch) {
            $branch = $this->customer_debt->reportDebtAll($branch, $request->time);
            $amount_branch[] = collect($branch)->sum('amount_paid');
        }

        return response()->json([
            'total_all' => $total_all,
            'amount_all' => $amount_all,
            'total_paid' => $total_paid,
            'amount_paid' => $amount_paid,
            'total_unpaid' => $total_unpaid,
            'amount_unpaid' => $amount_unpaid,
            'total_cancel' => $total_cancel,
            'amount_cancel' => $amount_cancel,
            'branch_name' => $arr_branch_name,
            'amount_branch' => $amount_branch
        ]);
    }
}