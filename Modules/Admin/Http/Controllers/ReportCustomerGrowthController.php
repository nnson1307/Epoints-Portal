<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/28/2018
 * Time: 10:41 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;

class ReportCustomerGrowthController extends Controller
{
    protected $customer;
    protected $branch;

    public function __construct(CustomerRepository $customers, BranchRepositoryInterface $branches)
    {
        $this->customer = $customers;
        $this->branch = $branches;
    }

    public function indexAction()
    {
        $year = date('Y');
        $optionBranch = $this->branch->getBranch();
        return view('admin::report.report-customer-growth.index', [
            'optionBranch' => $optionBranch,
            'year' => $year
        ]);
    }

    public function loadReportAction()
    {

        $yearNow = date('Y');
        //Lấy tổng số KH
        $total_customer = $this->customer->totalCustomer($yearNow);
        //Lấy số KH đã tạo trong ngày hôm nay
        $total_now = $this->customer->totalCustomerNow($yearNow);
        return response()->json([
            'total_customer' => $total_customer[0],
            'total_now' => $total_now[0],
            'year' => $yearNow
        ]);

    }

    public function filterYearBranch(Request $request)
    {
        $year = $request->year;
        $branch_id = $request->branch_id;
        //Lấy tổng số KH theo year trở về trước
        $total_year_branch = $this->customer->filterCustomerYearBranch($year, $branch_id);
        //Lấy tổng KH mới đã tạo trong năm (theo year)
        $total_now_year_branch = $this->customer->filterNowCustomerBranch($year, $branch_id);
        $branch_name = $this->branch->getItem($branch_id);
        $name = [];
        if ($branch_name != null) {
            $name = $branch_name['branch_name'];
        }
        return response()->json([
            'total_year_branch' => $total_year_branch[0],
            'total_now_year_branch' => $total_now_year_branch[0],
            'branch_name' => $name,
            'year' => $year
        ]);
    }

    public function filterTimeToTime(Request $request)
    {
        $time = $request->time;
        $branch_id = $request->branch_id;
        //Lấy tổng số KH trong khoảng thời gian trở về trước
        $total_time_branch = $this->customer->filterTimeToTime($time, $branch_id);
        //Lấy tổng số KH trong khoảng thời gian
        $total_now_branch=$this->customer->filterTimeNow($time,$branch_id);
        $branch_name = $this->branch->getItem($branch_id);
        $name = [];
        if ($branch_name != null) {
            $name = $branch_name['branch_name'];
        }
        return response()->json([
            'total_time_branch' => $total_time_branch[0],
            'total_now_branch'=>$total_now_branch[0],
            'branch_name' => $name,
            'time'=>$time
        ]);
    }
}