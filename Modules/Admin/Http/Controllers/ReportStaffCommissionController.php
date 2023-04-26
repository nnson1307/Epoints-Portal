<?php


namespace Modules\Admin\Http\Controllers;



use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;

class ReportStaffCommissionController extends Controller
{

    protected $branches;
    protected $order;
    protected $staff;
    protected $order_commission;

    public function __construct(
        StaffRepositoryInterface $staff,
        BranchRepositoryInterface $branch,
        OrderRepositoryInterface $order,
        OrderCommissionRepositoryInterface $order_commission
    ) {
        $this->branches = $branch;
        $this->order = $order;
        $this->staff = $staff;
        $this->order_commission = $order_commission;
    }

    /**
     * Page Báo cáo hoa hồng nhân viên
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $staff = $this->staff->getStaffOption();
        return view('admin::report.report-staff-commission.index', [
            'branch' => $branch,
            'staff' => $staff
        ]);
    }

    public function loadChartAction(Request $request)
    {
        $staff = $this->staff->getStaffOptionWithMoney();
        $order_commission = $this->order_commission->reportStaffCommission($request->time);
        $arr_commission = collect($order_commission)->toArray();
        foreach ($arr_commission as $key => $value) {
            $staff[$value['staff_id']]['money'] += $value['staff_money'];
        }
        $keys = array_column($staff, 'money');
        array_multisort($keys, SORT_DESC, $staff);
        
        if ($request->numberStaff != null) {
            $staff = array_splice($staff, 0, intval($request->numberStaff));
        }
        $arr_name = [];
        $arr_money = [];
        $total_money = 0;
        foreach ($staff as $item) {
            $arr_name[] = $item['name'];
            $arr_money[] = $item['money'];
            $total_money += $item['money'];
        }

        return response()->json([
            'list' => $arr_name,
            'seriesData' => $arr_money,
            'totalMoney' => $total_money,
            'countList' => count($staff)
        ]);
    }
}