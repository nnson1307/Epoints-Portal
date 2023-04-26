<?php

namespace Modules\Estimate\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Estimate\Repositories\EstimateBranchTime\EstimateBranchTimeRepoInterface;

class EstimateController extends Controller
{
    protected $estimate;
    /**
     * WEEK_TYPE: cấu hình theo tuần
     * MONTH_TYPE: cấu hình theo tháng
     * column 'type' trong table 'estimate_branch_time'
     */
    const WEEK_TYPE = 'W';
    const MONTH_TYPE = 'M';


    public function __construct(EstimateBranchTimeRepoInterface $estimate)
    {
        $this->estimate = $estimate;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($id)
    {
        $years  = $this->estimate->getYearsEstimate($id);
       
        return view('estimate::quota-estimate.index', ['branchId' => $id, 'years' => $years]);
    }

    /**
     * Lấy danh sách cấu hình theo tuần
     * @param $request
     * @return view
     */
    public function getListWeekEstimate(Request $request)
    {
        $requestData = $request->all();
        $data  = $this->estimate->getEstimateList(self::WEEK_TYPE, $requestData['year'], $requestData['branch_id']);
        return view('estimate::quota-estimate.list', ['data' => $data]);
    }

    /**
     * Lấy danh sách cấu hình theo tháng
     * @param $request
     * @return view
     */
    public function getListMonthEstimate(Request $request)
    {
        $requestData = $request->all();
        $data  = $this->estimate->getEstimateList(self::MONTH_TYPE, $requestData['year'], $requestData['branch_id']);
        return view('estimate::quota-estimate.list', ['data' => $data]);
    }

    /**
     * Insert cấu hình vào db
     * @param $request
     */
    public function addQuotaEstimate(Request $request)
    {
        // Kiểm tra nếu không chọn tuần hoặc tháng
       
        if (!$request->is_approve_week && !$request->is_approve_month) {
            return response()->json([
                'status' => false,
                'message' => __('Phải chọn ít nhất 1 trong 2 cấu hình tuần hoặc tháng')
            ]);
        }

        // Nếu có chọn tuần
        if ($request->is_approve_week) {
            $weekForm = (int)$request->week['select_from'];
            $weekTo = (int)$request->week['select_to']; 
           
            if ($weekForm > $weekTo) {
                return response()->json([
                    'status' => false,
                    'message' => __('Tuần kết thúc phải lớn hơn tuần bắt đầu')
                ]);
            }
            $this->addWeekQuotaEstimate($request);
        }

        // Nếu có chọn tháng
        if ($request->is_approve_month) {
            $monthForm = (int)$request->month['select_from'];
            $monthTo = (int)$request->month['select_to'];
    
            if ($monthForm > $monthTo) {
                return response()->json([
                    'status' => false,
                    'message' => __('Tháng kết thúc phải lớn hơn tháng bắt đầu')
                ]);
            }
            
            $this->addMonthQuotaEstimate($request);
        }

        return response()->json([
            'status' => true,
            'message' => __('Thêm thành công')
        ]);
    }

    /**
     * Chỉnh sửa cấu hình vào db
     * @param $request
     */
    public function editQuotaEstimate(Request $request)
    {
        $dataUpdate = [];
        // Nếu có chọn tuần
        if ($request->is_approve_week) {
            $dataUpdate = [
                'estimate_time' => str_replace(",","",$request->week['estimate_time']),
                'estimate_money' => str_replace(",","",$request->week['estimate_money'])
            ];
        }
        if ($request->is_approve_month) {
            $dataUpdate = [
                'estimate_time' => str_replace(",","",$request->month['estimate_time']),
                'estimate_money' => str_replace(",","",$request->month['estimate_money']),
            ];
        }

        $idReturn = $this->estimate->editEstimate($request->estimate_branch_time_id, $dataUpdate);
        if($idReturn > 0){
            return response()->json([
                'status' => true,
                'message' => __('Thêm thành công')
            ]);
        }else {
            return response()->json([
                'status' => false,
                'message' => __('Thêm thất bại')
            ]);
        }
        
    }

    /**
     * Hàm insert cấu hình theo tuần vào db
     * @param $request
     * @return true
     */
    public function addWeekQuotaEstimate($request)
    {
      
        $weekForm = (int)$request->week['select_from'];
        $weekTo = (int)$request->week['select_to']; 
    
        // Loop từ tuần bắt đầu đến tuần kết thúc và thêm cấu hình từng tuần vào db
        for ($i = $weekForm; $i <= $weekTo; $i++) {

            // chuẩn bị data insert vào db 
            $condition = [
                'branch_id' => $request->branch_id,
                'type' => self::WEEK_TYPE,
                'week' => $i,
                'year' => Carbon::now()->year
            ];

            $dataUpdate = [
                'estimate_time' => str_replace(",","",$request->week['estimate_time']),
                'estimate_money' => str_replace(",","",$request->week['estimate_money'])
            ];
           
            // Nếu có chọn nhưng không nhập cấu hình thì bỏ qua
            if (empty($request->week['estimate_time']) || empty($request->week['estimate_money'])) {
                break;
            }

            // update vào db
            $this->estimate->updateOrCreateEstimate($condition, $dataUpdate);
            
        }
    }

    /**
     * Hàm insert cấu hình theo tháng vào db
     * @param $request
     * @return true
     */
    public function addMonthQuotaEstimate($request)
    {
      
        $monthForm = (int)$request->month['select_from'];
        $monthTo = (int)$request->month['select_to'];
        // Loop từ tháng bắt đầu đến tháng kết thúc và thêm cấu hình từng tháng vào db
        for ($i = $monthForm; $i <= $monthTo; $i++) {

            // chuẩn bị data insert vào db 
            $condition = [
                'branch_id' => $request->branch_id,
                'type' => self::MONTH_TYPE,
                'month' => $i,
                'year' => Carbon::now()->year
            ];

            $dataUpdate = [
                'estimate_time' => str_replace(",","",$request->month['estimate_time']),
                'estimate_money' => str_replace(",","",$request->month['estimate_money']),
            ];
            try {
                $this->estimate->updateOrCreateEstimate($condition, $dataUpdate);
            } catch (Exception $e) {
               var_dump('Caught exception: ',  $e->getMessage(), "\n") ;die;
            }
        }
    }

    public function showModalEdit(Request $request)
    {
        $requestData = $request->all();
        if ($request->ajax()) {
            $html = \View::make('estimate::quota-estimate.popup.edit', [
                'branchId' => $request->branch,
                'type' => $request->type,
                'time' => $request->time,
                'money' => $request->money,
                'content' => $request->content,
                'id' =>$request->id,
            ])->render();
            return response()->json([
                'html' => $html
            ]);
        }
    }
    public function showModalAdd(Request $request)
    {
        $requestData = $request->all();
        if ($request->ajax()) {
            $html = \View::make('estimate::quota-estimate.popup.add', [
                'branchId' => $request->branch
            ])->render();
            return response()->json([
                'html' => $html
            ]);
        }
    }
}