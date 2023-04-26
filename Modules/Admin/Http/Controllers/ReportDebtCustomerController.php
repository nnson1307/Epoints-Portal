<?php


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;

class ReportDebtCustomerController extends Controller
{
    protected $branch;
    protected $customer;

    public function __construct(
        BranchRepositoryInterface $branch,
        CustomerRepositoryInterface $customer
    ) {
        $this->branch = $branch;
        $this->customer = $customer;
    }

    public function indexAction()
    {
        $optionBranch = $this->branch->getBranchOption();

        return view('admin::report.report-debt-by-customer.index',[
            'optionBranch' => $optionBranch
        ]);
    }

    public function loadChartCustomerAction(Request $request)
    {

    }
}