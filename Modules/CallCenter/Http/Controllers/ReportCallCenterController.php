<?php
namespace Modules\CallCenter\Http\Controllers;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CallCenter\Repositories\CallCenter\CallCenterRepoInterface;
use Modules\CallCenter\Models\CustomerRequestAttributeTable;
use Carbon\Carbon;

class ReportCallCenterController extends Controller
{
    protected $callCenter;

    public function __construct(
        CallCenterRepoInterface $callCenter
    )
    {
        $this->callCenter = $callCenter;
    }

    /**
     * Danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {  
        return view('call-center::report-call-center.index', []);
    }

    /**
     * Lấy tổng theo ngày trong tháng
     */
    public function getChartByMonth(Request $request){
        $data = $this->callCenter->getTotalByMonth($request->months, $request->years);
        return response()->json($data);
    }

    /**
     * Lấy tổng theo nhân viên
     */
    public function getChartStaffByMonth(Request $request){
        $data = $this->callCenter->getTotalStaffByMonth($request->months, $request->years);
        return response()->json($data);
    }
}