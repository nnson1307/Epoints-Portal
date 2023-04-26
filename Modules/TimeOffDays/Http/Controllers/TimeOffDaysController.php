<?php

namespace Modules\TimeOffDays\Http\Controllers;

use App;
use App\Jobs\FunctionSendNotify;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepositoryInterface;
use Modules\TimeOffDays\Repositories\Staffs\StaffsRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepositoryInterface;
use Modules\TimeOffDays\Http\Requests\TimeOffDaysRequestForm;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotal\TimeOffDaysTotalRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysFiles\TimeOffDaysFilesRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotalLog\TimeOffDaysTotalLogRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffTypeOption\TimeOffTypeOptionRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class TimeOffDaysController extends Controller
{

    protected $repo;
    protected $staffs;
    protected $timeOffType;
    protected $timeOffDaysTotal;
    protected $timeOffDaysLog;
    protected $timeOffDaysConfigApprove;
    protected $timeOffDaysFiles;
    protected $timeOffDaysShifts;
    protected $timeOffDaysTotalLog;
    protected $timeOffTypeOption;
    protected $timeWorkingStaff;
    
    public function __construct(
        TimeOffDaysRepositoryInterface $repo,
        TimeOffTypeRepositoryInterface $timeOffType,
        TimeOffDaysTotalRepositoryInterface $timeOffDaysTotal,
        TimeOffDaysLogRepositoryInterface $timeOffDaysLog,
        TimeOffDaysShiftsRepositoryInterface $timeOffDaysShifts,
        TimeOffDaysFilesRepositoryInterface $timeOffDaysFiles,
        TimeOffDaysConfigApproveRepositoryInterface $timeOffDaysConfigApprove,
        TimeOffDaysTotalLogRepositoryInterface $timeOffDaysTotalLog,
        StaffsRepositoryInterface $staffs,
        TimeOffTypeOptionRepositoryInterface $timeOffTypeOption,
        TimeWorkingStaffsRepositoryInterface $timeWorkingStaff
        )
    {
        $this->repo = $repo;
        $this->staffs = $staffs;
        $this->timeOffType = $timeOffType;
        $this->timeOffDaysTotal = $timeOffDaysTotal;
        $this->timeOffDaysLog = $timeOffDaysLog;
        $this->timeOffDaysShifts = $timeOffDaysShifts;
        $this->timeOffDaysFiles = $timeOffDaysFiles;
        $this->timeOffDaysConfigApprove = $timeOffDaysConfigApprove;
        $this->timeOffDaysTotalLog = $timeOffDaysTotalLog;
        $this->timeOffTypeOption = $timeOffTypeOption;
        $this->timeWorkingStaff = $timeWorkingStaff;
    }

    public function generationTimeOffDay(){
        $this->createDaysOff();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        
        $data = $this->repo->getList([]);
   
        return view('timeoffdays::timeoffdays.index', [
            'LIST' => $data,
            'FILTER' => $this->filters(),
            'param' => $request->all()
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->only([
            'page',
            'time_off_type_id',
            'staff_id_level1',
            'is_approve',
            'staff_id',
            'created_at'
        ]);
        // if (isset($request->filters) && $request->filters) {
        //     $filters = array_merge($filters, $request->filters);
        // }
  
        $data = $this->repo->getList($filters);

        return view('timeoffdays::timeoffdays.list', [
                'LIST' => $data,
                'FILTER' => $this->filters(),
                'page' => $filters['page'],
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $timeOffTypeList = $this->timeOffType->getAll();
        $daysOffTime =  $this->repo->getOptionDaysOffTime();
        $arrDepartment =  $this->repo->getOptionDepartment();
        
        return view('timeoffdays::timeoffdays.create',[
            'timeOffTypeList' => $timeOffTypeList,
            'daysOffTime' => $daysOffTime,
            'arrDepartment' => $arrDepartment,
            'staffApprove' => [],
        ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            
                $data['time_off_type_id']    = (int)$request->time_off_type_id ?? 0;
                $data['time_off_days_start'] = Carbon::createFromFormat("d/m/Y", $request->time_off_days_start)->format("Y-m-d") ?? '';
                $data['time_off_days_end']   = Carbon::createFromFormat("d/m/Y", $request->time_off_days_end)->format("Y-m-d") ?? '';
                $data['time_off_days_time']  = $request->time_off_days_time ?? '';
                $data['time_off_note']       = $request->time_off_note ?? '';
                $data['date_type_select']    = $request->select_type_date ?? 'one-day';
                $data['staff_id_level1']     = null;
                $data['staff_id_level2']     = null;
                $data['staff_id_level3']     = null;

                //check có chọn ca chưa
                if(!isset($request->time_off_days_shift) || count($request->time_off_days_shift) == 0){
                    return response()->json(['error' => true, 'message' => __('Chưa chọn ca nghĩ')]);
                }
                
                //Check giới hạn ngày phép
                $infoDaysTotal = $this->timeOffDaysTotal->checkValidTotal(Auth()->id(), $request->time_off_type_id);
                if(isset($infoDaysTotal)){
                    if($infoDaysTotal['time_off_days_number'] != -1){
                        if($infoDaysTotal['time_off_days_number'] < count($request->time_off_days_shift)){
                            return response()->json(['error' => true, 'message' => __('Số ngày phép vượt quá giới hạn cho phép')]);
                        }
                    }
                }
                
                $data['staff_id']            = Auth()->id();
                $data['created_by']          = Auth()->id();
                $data['created_at']          = Carbon::now()->format("Y-m-d H:i:s");
                $data['updated_at']          = Carbon::now()->format("Y-m-d H:i:s");
                $id = $this->repo->add($data);
                if ($id) {
                    if(isset($request->time_off_days_files) && count($request->time_off_days_files)){
                        foreach($request->time_off_days_files as $item){
                            $input['time_off_days_id'] = $id;
                            $input['time_off_days_files_name'] = $item['path'];
                            $result = $this->timeOffDaysFiles->add($input);
                        }
                    }
                    if(isset($request->time_off_days_shift)&& count($request->time_off_days_shift)){
                        foreach($request->time_off_days_shift as $item){
                            //Đánh dấu ngày công đã được tạo đơn phép
                            $this->timeWorkingStaff->edit(['time_off_days_id' => $id], (int)$item);
                            
                            //insert ca xin nghĩ
                            $dataShift = [
                                "time_off_days_id" => $id,
                                "time_working_staff_id" => (int)$item,
                                "is_approve" => null,
                                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                                "created_by" => Auth()->id(),
                                "updated_by" => Auth()->id(),
                                "created_days" => Carbon::now()->day,
                                "created_months" => Carbon::now()->month,
                                "created_years" => Carbon::now()->year,
                                "time_off_type_id" => $request->time_off_type_id,
                                "staff_id" => Auth()->id()
                            ];
                            $this->timeOffDaysShifts->add($dataShift);
                        }
                    }
                
                    $param['time_off_days_id'] =  $id;
                    $param['time_off_days_action'] = 'created';
                    $param['time_off_days_title'] = 'Tạo đơn phép';
                    $param['time_off_days_content'] = 'Tạo đơn phép';
                    $this->timeOffDaysLog->add($param);

                    //Cập nhật lại số lượng ngày phép
                    if(isset($infoDaysTotal)){
                        if($infoDaysTotal['time_off_days_number'] != -1){
                            $daysTotal = $infoDaysTotal['time_off_days_number'] - count($request->time_off_days_shift);
                            $this->timeOffDaysTotal->edit(['time_off_days_number' => $daysTotal], $infoDaysTotal['time_off_days_total_id'], Auth()->id());
                        }
                    }
                   //Lấy thông tin loại đơn
                   $detailType = $this->timeOffType->getDetail($data['time_off_type_id']);
                   if(isset($detailType)){
                        $arrStaff = [];
                        if($detailType['direct_management_approve'] == 1){
                            $infoApproveLevel1 = $this->staffs->getDetailApproveLevel1(Auth()->user()->department_id);
                            if(isset($infoApproveLevel1)){
                                $arrStaff[] = $infoApproveLevel1['staff_id'];
                            }
                        }
                        if(isset($detailType['staff_id_approve_level2'])){
                            foreach (json_decode($detailType['staff_id_approve_level2']) as $value) {
                                $arrStaff[] = $value;
                            }
                        }
                        if(isset($detailType['staff_id_approve_level3'])){
                            foreach (json_decode($detailType['staff_id_approve_level3']) as $value) {
                                $arrStaff[] = $value;
                            }
                        }
                        // else {
                        //     if(isset($detailType['staff_id_approve_level2'])){
                        //         $arrStaff = json_decode($detailType['staff_id_approve_level2']);
                        //     }else {
                        //         if(isset($detailType['staff_id_approve_level3'])){
                        //             $arrStaff = json_decode($detailType['staff_id_approve_level3']);
                        //         }
                        //     }
                            
                        // }
                        try {
                            if(count($arrStaff) > 0){
                                foreach ($arrStaff as $value) {
                                    App\Jobs\FunctionSendNotify::dispatch([
                                        'type' => SEND_NOTIFY_STAFF,
                                        'key' => 'time_off_days_waiting', //Key nào mình muốn gửi thì config
                                        'customer_id' => null, //Này ko có thì để rỗng
                                        'object_id' => $id, //Đối tượng ăn theo key
                                        'branch_id' => Auth()->user()->branch_id,
                                        'tenant_id' => session()->get('idTenant'),
                                        'staff_id' => $value,
                                        'model' => json_encode(["time_off_days_id" => $id, "is_personal" => 0]),
                                        'content' => 'Xin ' . $detailType['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end']
                                    ]);
                                }
                            }
                        } catch (Exception $ex) {
                            Log::error($ex->getMessage());
                        }
                   }
                    
                    return response()->json(['error' => false, 'message' => __('Tạo đơn phép thành công')]);
                }
            
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => __('Tạo đơn phép thất bại'). ':' . $e->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
    
        $data = $this->repo->getDetail($id);
       
        $inputStaff = array($data['staff_id_level1'], $data['staff_id_level2'], $data['staff_id_level3']);

        $input['time_off_days_id'] = $id;
        $log = $this->timeOffDaysLog->getLists($input);
        $staff = $this->staffs->getListById($inputStaff);
      
        return view('timeoffdays::timeoffdays.show', [
            'data' => $data,
            'log' => $log,
            'staff' => $staff
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function remove($id)
    {
        $data = $this->repo->getDetail($id);
        $input['is_deleted'] = 1;
        $result = $this->repo->edit($input, $id);

        if ($result) {
            $this->timeOffDaysFiles->remove($id);
            $this->timeOffDaysShifts->remove($id);

            $param['time_off_days_id'] = $id;
            $param['time_off_days_action'] = 'deleted';
            $param['time_off_days_title'] = 'Xóa đơn phép';
            $param['time_off_days_content'] = 'Bạn vừa xóa đơn phép';
            $this->timeOffDaysLog->add($param);

            //Cập nhật lại số lượng ngày phép
            $this->updateTotalDaysOff($data['staff_id'], $data['time_off_type_id'], 'bonus');
            return redirect()->route("timeoffdays.mylist")->with("status", __('Đã xóa thành công'));
        }
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function approve(Request $request)
    {
        try {
            $data = $this->repo->getDetail($request->time_off_days_id);
            $authId = Auth()->id();
            $data['staff_id_approve_level1'] = null;
            $dataUpdate['is_approve'] = null;
           
            if(isset($data) && $data['direct_management_approve'] == 1){
              
                $infoApproveLevel1 = $this->staffs->getDetailApproveLevel1($data['department_id']);
                if(isset($infoApproveLevel1)){
                    $data['staff_id_approve_level1'] = $infoApproveLevel1['staff_id'];
                    if($authId == $infoApproveLevel1['staff_id'] ){
                        $dataUpdate['staff_id_level1'] = $authId;
                        $dataUpdate['is_approve_level1'] = 1;
                        $data['is_approve_level1'] = 1;
                        $data['staff_id_level1'] = $authId;
                        
                    }
                }
            }
            if(isset($data['staff_id_approve_level2']) && in_array($authId, json_decode($data['staff_id_approve_level2']))){
                $dataUpdate['staff_id_level2'] = $authId;
                $dataUpdate['is_approve_level2'] = 1;
                $data['is_approve_level2'] = 1;
                $data['staff_id_level2'] = $authId;
            }
            if(isset($data['staff_id_approve_level3']) && in_array($authId, json_decode($data['staff_id_approve_level3']))){
                $dataUpdate['staff_id_level3'] = $authId;
                $dataUpdate['is_approve_level3'] = 1;
                $data['is_approve_level3'] = 1;
                $data['staff_id_level3'] = $authId;
            }
            if($data['staff_id_approve_level1'] == $data['staff_id_level1'] && in_array($data['staff_id_level2'], json_decode($data['staff_id_approve_level2']) ?? []) && in_array($data['staff_id_level3'], json_decode($data['staff_id_approve_level3'] ?? []))){
                $dataUpdate['is_approve'] = 1;
            }
            $result = $this->repo->edit($dataUpdate, $request->time_off_days_id);
            if ($result) {
                $param['time_off_days_id'] = $request->time_off_days_id;
                $param['time_off_days_action'] = 'update';
                $param['time_off_days_title'] = 'Duyệt đơn phép';
                $param['time_off_days_content'] = 'Bạn vừa duyệt đơn phép';
                $this->timeOffDaysLog->add($param);
                
                if($dataUpdate['is_approve'] == 1){
                    $lstShiftDaysOff = $this->timeOffDaysShifts->getListsByDaysOff($request->time_off_days_id);
                    if(count($lstShiftDaysOff) > 0){
                        foreach ($lstShiftDaysOff as $obj) {
                            $this->timeOffDaysShifts->edit(['is_approve' => 1], $obj['time_off_days_shift_id']);
                            switch ($obj['time_off_type_code']) {
                                case '017':
                                    //Xin đi trễ
                                    $this->timeWorkingStaff->edit(
                                        ['is_approve_late' => 1, 'approve_late_by' =>  $authId], $obj['time_working_staff_id']
                                    );
                                    break;
                                case '018':
                                    //Xin về sớm
                                    $this->timeWorkingStaff->edit(
                                        ['is_approve_soon' => 1 , 'approve_soon_by' => $authId], $obj['time_working_staff_id']
                                    );
                                    break;
                                default:
                                    $this->timeWorkingStaff->edit(
                                        ['is_deducted' => $obj['is_deducted']], $obj['time_working_staff_id']
                                    );
                                    break;
                            }
                        }
                    }
                    
                }
                // if($data['is_approve_level1'] == 1){
                //     if(isset($data['staff_id_approve_level2']) && in_array($authId, json_decode($data['staff_id_approve_level2']))){
                //         $arrStaff = [];
                //         if(isset($detailType['staff_id_approve_level2'])){
                //             $arrStaff = json_decode($detailType['staff_id_approve_level2']);
                //         }
                //         if(count($arrStaff) > 0){
                //             foreach ($arrStaff as $value) {
                //                 App\Jobs\FunctionSendNotify::dispatch([
                //                     'type' => SEND_NOTIFY_STAFF,
                //                     'key' => 'time_off_days_waiting', //Key nào mình muốn gửi thì config
                //                     'customer_id' => null, //Này ko có thì để rỗng
                //                     'object_id' => $request->time_off_days_id, //Đối tượng ăn theo key
                //                     'branch_id' => Auth()->user()->branch_id,
                //                     'tenant_id' => session()->get('idTenant'),
                //                     'staff_id' => $value,
                //                     'model' => json_encode(["time_off_days_id" => $request->time_off_days_id, "is_personal" => 0]),
                //                     'content' => 'Xin ' . $data['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end']
                //                 ]);
                //             }
                //         }
                //     }
                // }
                // if($data['is_approve_level2'] == 1){
                //     if(isset($data['staff_id_approve_level3']) && in_array($authId, json_decode($data['staff_id_approve_level3']))){
                //         $arrStaff = [];
                //         if(isset($detailType['staff_id_approve_level3'])){
                //             $arrStaff = json_decode($detailType['staff_id_approve_level3']);
                //         }
                //         if(count($arrStaff) > 0){
                //             foreach ($arrStaff as $value) {
                //                 App\Jobs\FunctionSendNotify::dispatch([
                //                     'type' => SEND_NOTIFY_STAFF,
                //                     'key' => 'time_off_days_waiting', //Key nào mình muốn gửi thì config
                //                     'customer_id' => null, //Này ko có thì để rỗng
                //                     'object_id' => $request->time_off_days_id, //Đối tượng ăn theo key
                //                     'branch_id' => Auth()->user()->branch_id,
                //                     'tenant_id' => session()->get('idTenant'),
                //                     'model' => json_encode(["time_off_days_id" => $request->time_off_days_id, "is_personal" => 0]),
                //                     'staff_id' => $value,
                //                     'content' => 'Xin ' . $data['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end']
                //                 ]);
                //             }
                //         }
                //     }
                // }
                if($dataUpdate['is_approve'] = 1){
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_STAFF,
                        'key' => 'time_off_days_approved', //Key nào mình muốn gửi thì config
                        'customer_id' => null, //Này ko có thì để rỗng
                        'object_id' => $request->time_off_days_id, //Đối tượng ăn theo key
                        'branch_id' => Auth()->user()->branch_id,
                        'tenant_id' => session()->get('idTenant'),
                        'staff_id' => $data['staff_id'],
                        'model' => json_encode(["time_off_days_id" => $request->time_off_days_id, "is_personal" => 1]),
                        'content' => 'Đơn xin ' . $data['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end'] . ' đã được phê duyệt'
                    ]);
                }
            }
            
            return response()->json(['error' => false, 'message' => __('Duyệt đơn phép thành công')]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => __('Duyệt đơn phép thất bại') . $e->getMessage()]);
        }
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function unApprove(Request $request)
    {
        try {
            $id = $request->time_off_days_id;
            $data = $this->repo->getDetail($request->time_off_days_id);
            $authId = Auth()->id();
            $data['staff_id_approve_level1'] = null;
            $dataUpdate['is_approve'] = null;
           
            if(isset($data) && $data['direct_management_approve'] == 1){
              
                $infoApproveLevel1 = $this->staffs->getDetailApproveLevel1($data['department_id']);
                if(isset($infoApproveLevel1)){
                    $data['staff_id_approve_level1'] = $infoApproveLevel1['staff_id'];
                    if($authId == $infoApproveLevel1['staff_id'] ){
                        $dataUpdate['staff_id_level1'] = $authId;
                        $dataUpdate['is_approve_level1'] = 0;
                        $data['is_approve_level1'] = 0;
                        $data['staff_id_level1'] = $authId;
                        
                    }
                }
            }
            if(isset($data['staff_id_approve_level2']) && in_array($authId, json_decode($data['staff_id_approve_level2']))){
                $dataUpdate['staff_id_level2'] = $authId;
                $dataUpdate['is_approve_level2'] = 0;
                $data['is_approve_level2'] = 0;
                $data['staff_id_level2'] = $authId;
            }
            if(isset($data['staff_id_approve_level3']) && in_array($authId, json_decode($data['staff_id_approve_level3']))){
                $dataUpdate['staff_id_level3'] = $authId;
                $dataUpdate['is_approve_level3'] = 0;
                $data['is_approve_level3'] = 0;
                $data['staff_id_level3'] = $authId;
            }
            
            $dataUpdate['is_approve'] = 0;
            $result = $this->repo->edit($dataUpdate, $request->time_off_days_id);
            if ($result) {
                $param['time_off_days_id'] = $id;
                $param['time_off_days_action'] = 'cancel';
                $param['time_off_days_title'] = 'Từ chối đơn phép';
                $param['time_off_days_content'] = 'Bạn vừa từ chối đơn phép';
                $this->timeOffDaysLog->add($param);
                
                //cập nhật bảng ca xin nghĩ
                $lstShiftDaysOff = $this->timeOffDaysShifts->getListsByDaysOff($request->time_off_days_id);
                if(count($lstShiftDaysOff) > 0){
                    foreach ($lstShiftDaysOff as $obj) {
                        $this->timeOffDaysShifts->edit(['is_approve' => 0], $obj['time_off_days_shift_id']);
                        switch ($obj['time_off_type_code']) {
                            case '017':
                                //Xin đi trễ
                                $this->timeWorkingStaff->edit(
                                    ['is_approve_late' => 0, 'approve_late_by' =>  $authId], $obj['time_working_staff_id']
                                );
                                break;
                            case '018':
                                //Xin về sớm
                                $this->timeWorkingStaff->edit(
                                    ['is_approve_soon' => 0 , 'approve_soon_by' => $authId], $obj['time_working_staff_id']
                                );
                                break;
                            default:
                                $this->timeWorkingStaff->edit(
                                    ['is_deducted' => 1], $obj['time_working_staff_id']
                                );
                                break;
                        }
                    }
                }

                //tính lại số lần xin nghĩ
                $this->updateTotalDaysOff($data['staff_id'], $data['time_off_type_id'], 'bonus');

                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_STAFF,
                    'key' => 'time_off_days_not_approved', //Key nào mình muốn gửi thì config
                    'customer_id' => null, //Này ko có thì để rỗng
                    'object_id' => $request->time_off_days_id, //Đối tượng ăn theo key
                    'branch_id' => Auth()->user()->branch_id,
                    'tenant_id' => session()->get('idTenant'),
                    'staff_id' => $data['staff_id'],
                    'model' => json_encode(["time_off_days_id" => $request->time_off_days_id, "is_personal" => 0]),
                    'content' => 'Đơn xin ' . $data['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end'] . ' đã không được chấp nhận'
                ]);
            }
            return response()->json(['error' => false, 'message' => __('Từ chối đơn phép thành công')]);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => __('Từ chối đơn phép thất bại')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = $this->repo->getDetail($id);

        $timeOffTypeList = $this->timeOffType->getAll();

        $staffApprove = $this->timeOffTypeOption->getListsStaffApprove($data['time_off_type_id']);
        $daysOffTime =  $this->repo->getOptionDaysOffTime();
        return view('timeoffdays::timeoffdays.edit',[
            'timeOffTypeList' => $timeOffTypeList,
            'staffApprove' => $staffApprove,
            'data' => $data,
            'daysOffTime' => $daysOffTime
        ]
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        try {
            $id = $request->time_off_days_id ?? 0;
            $data['time_off_type_id']    = (int)$request->time_off_type_id ?? 0;
            $data['time_off_days_start'] = Carbon::createFromFormat("d/m/Y", $request->time_off_days_start)->format("Y-m-d") ?? '';
            $data['time_off_days_end']   = Carbon::createFromFormat("d/m/Y", $request->time_off_days_end)->format("Y-m-d") ?? '';
            $data['time_off_days_time']  = $request->time_off_days_time ?? '';
            $data['time_off_note']       = $request->time_off_note ?? '';
            $data['date_type_select']    = $request->select_type_date ?? 'one-day';
           
            $data['staff_id']            = Auth()->id();
            $data['created_by']          = Auth()->id();
            $data['created_at']          = Carbon::now()->format("Y-m-d H:i:s");
            $data['updated_at']          = Carbon::now()->format("Y-m-d H:i:s");
            
            $result = $this->repo->edit($data, $id);
            if ($result) {
                $this->timeOffDaysFiles->remove($id);
                if(isset($request->time_off_days_files) && count($request->time_off_days_files)){
                    foreach($request->time_off_days_files as $item){
                        $input['time_off_days_id'] = $id;
                        $input['time_off_days_files_name'] = $item['path'];
                        $result = $this->timeOffDaysFiles->add($input);
                    }
                }
                
                $this->timeWorkingStaff->removeTimeOffDay($id);
                $this->timeOffDaysShifts->remove($id);
                if(isset($request->time_off_days_shift)&& count($request->time_off_days_shift)){
                    foreach($request->time_off_days_shift as $item){
                        //Đánh dấu ngày công đã được tạo đơn phép
                        $this->timeWorkingStaff->edit(['time_off_days_id' => $id], (int)$item);

                         //insert ca xin nghĩ
                         $dataShift = [
                            "time_off_days_id" => $id,
                            "time_working_staff_id" => (int)$item,
                            "is_approve" => null,
                            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            "created_days" => Carbon::now()->day,
                            "created_months" => Carbon::now()->month,
                            "created_years" => Carbon::now()->year,
                            "time_off_type_id" => $request->time_off_type_id,
                            "staff_id" => Auth()->id()
                        ];
                        $this->timeOffDaysShifts->add($dataShift);
                    }
                }
            
                $param['time_off_days_id'] =  $id;
                $param['time_off_days_action'] = 'update';
                $param['time_off_days_id'] = 'Chỉnh sửa đơn phép';
                $param['time_off_days_id'] = 'Bạn vừa chỉnh sửa đơn phép';
                $this->timeOffDaysLog->add($param);
                return response()->json(['error' => false, 'message' => __('Chỉnh sửa đơn phép thành công')]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => __('Chỉnh sửa đơn phép thất bại') . $e->getMessage()]);
        }
    }

    protected function filters()
    {

        $staffList = $this->staffs->getAll();
        $timeOffTypeList = $this->timeOffType->getAll();
        
        $arr = [];
        foreach ($staffList as $item) {
            $arr[$item['staff_id']] = $item['full_name'];
        }

        $arrType = [];
        foreach ($timeOffTypeList as $item) {
            $arrType[$item['time_off_type_id']] = $item['time_off_type_name'];
        }
        $selectstaffListApprove = (['' => __('Chọn người duyệt')]) + $arr;
        $selectstaffList = (['' => __('Chọn nhân viên')]) + $arr;
        $timeOffTypeList = (['' => __('Chọn loại phép')]) + $arrType;
        return [
            'time_off_days$time_off_type_id' => [
                'data' => $timeOffTypeList
            ],
            'time_off_days$staff_id_approve' => [
                'data' => $selectstaffListApprove
            ],
            'time_off_days$is_approve' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Chấp nhận'),
                    0 => __('Từ chối'),
                    null => __('Chờ duyệt')
                ]
                ],
            'time_off_days$staff_id' => [
                'data' => $selectstaffList
            ],
            
        ];
    }


     /**
     * tổng ngày phép
     * @return Response
     */
    public function total(Request $request)
    {
        try {
            $now = Carbon::now();
            $month = $now->month;
            $id = $request->input('time_off_type_id') ?? 1;
            $detailType = $this->timeOffType->getDetail($id);
            $isDay = 1;
            $authId = Auth()->id();
            $totalDaysUsed = -1;
            if($detailType['total_number'] != -1){
                $infoDaysUsed = $this->timeOffDaysShifts->getNumberDaysOff(
                    [
                        'staff_id' => $authId,
                        'time_off_type_id' => $id,
                        'month' => Carbon::now()->month,
                        'years' => Carbon::now()->year,
                        'month_reset' => $detailType['month_reset']
                    ]
                );
              
                $totalDaysUsed = $infoDaysUsed->total;
            }
            if(isset($detailType) && $detailType['time_off_type_code'] == '017'){
                $data = array(
                    [
                        'key' => 'Tổng số lần xin đi trễ', 
                        'value' => $totalDaysUsed == -1 ? 'Không giới hạn' : $totalDaysUsed.' lần', 
                    ],
                    [
                        'key' => 'Giới hạn đi trễ', 
                        'value' => $detailType['total_number'] == -1 ? 'Không giới hạn' : $detailType['total_number'], 
                    ]
                );
            }elseif($detailType['time_off_type_code'] == '018'){
                $data = array(
                    [
                        'key' => 'Tổng số lần xin về sớm', 
                        'value' => $totalDaysUsed == -1 ? 'Không giới hạn' : $totalDaysUsed.' lần', 
                    ],
                    [
                        'key' => 'Giới hạn về sớm', 
                        'value' => $detailType['total_number'] == -1 ? 'Không giới hạn' : $detailType['total_number'], 
                    ]
                );
            }else{
                $data = array(
                    [
                        'key' => 'Quỹ phép khả dụng', 
                        'value' => $totalDaysUsed == -1 ? 'Không giới hạn' :$totalDaysUsed.' ngày', 
                    ],
                    [
                        'key' => 'Quỹ phép năm ('. Carbon::now()->year . ')', 
                        'value' => $detailType['total_number'] == -1 ? 'Không giới hạn' : $detailType['total_number'], 
                    ]
                );
            }

            $result['total'] = $data;
            $result['detail'] = $detailType;
            return response()->json($result);

        } catch (\Exception $ex) {

            return response()->json($ex->getMessage());

        }
    }

    /**
     * Duyệt ngày phép
     * @return Response
     */
    protected function isApproveAction($data)
    {
        if( ($data['is_approve_level1'] == 1 && $data['staff_id_level1']  != null)
            && ($data['is_approve_level2'] == 1  && $data['staff_id_level2']  != null)
            && ($data['is_approve_level3'] == 1 && $data['staff_id_level3']  != null))
        {
            return 1;
        }elseif($data['is_approve_level1'] == 0 
            || $data['is_approve_level2'] == 0 
            || $data['is_approve_level3'] == 0)
        {
            
            return 0;    
        }
        return null;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function mylist(Request $request)
    {
        $filters = $request->only([
            'page',
            'time_off_type_id',
            'staff_id_level1',
            'is_approve',
            'staff_id',
            'created_at'
        ]);
       
        $filters['staff_id'] = Auth()->id();
        $data = $this->repo->getList($filters);
        $staffList = $this->staffs->getAll();
        $timeOffTypeList = $this->timeOffType->getAll();
        $arr = [];
        foreach ($staffList as $item) {
            $arr[$item['staff_id']] = $item['full_name'];
        }
        $arrType = [];
        foreach ($timeOffTypeList as $item) {
            $arrType[$item['time_off_type_id']] = $item['time_off_type_name'];
        }
        $timeOffTypeList = (['' => __('Chọn loại phép')]) + $arrType;
        $datafilters =  [
            'time_off_days$time_off_type_id' => [
                'data' => $timeOffTypeList
            ],
     
            'time_off_days$is_approve' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Chấp nhận'),
                    0 => __('Từ chối'),
                    null => __('Chờ duyệt')
                ]
                ],
        ];
   
        return view('timeoffdays::timeoffdays.myindex', [
            'LIST' => $data,
            'FILTER' => $datafilters,
            'param' => $request->all()
        ]);
    }


    /**
     * Check ngày phép hợp lệ
     * @return Response
     */
    protected function checkDateApproveAction($params)
    {
        $timeOffTypeDetail = $this->timeOffType->getDetail($params['time_off_type_id']);
        $timeOffHolidaysNumber = $timeOffTypeDetail['time_off_holidays_number'];
      
        if($timeOffHolidaysNumber != 0){
            $dayStart = Carbon::createFromFormat("d/m/Y", $params['time_off_days_start'] )->format("Y-m-d");
            $dayEnd = Carbon::createFromFormat("d/m/Y", $params['time_off_days_end'] )->format("Y-m-d");
            
            if($dayEnd - $dayStart >= $timeOffHolidaysNumber){
                return 0;
            }else{
                return 1;
            }
        }else{
            return 1;
        }
    }
    
    public function getStaffApprove(Request $request){
        $staffApprove = $this->timeOffType->getListsStaffApprove($request->time_off_type_id);
        $html = \View::make('timeoffdays::timeoffdays.list_staff_approve', ['staffApprove'  => $staffApprove])->render();
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Duyệt ngày phép
     * @return Response
     */
    protected function checkApproveDone($data)
    {
        if ($data['is_approve_level1'] == 1 && $data['staff_id_level2'] == null && $data['staff_id_level3'] == null) {
            return 1;
        }
        if ($data['is_approve_level2'] == 1 && $data['staff_id_level3'] == null) {
            return 1;
        }
        if ($data['is_approve_level3'] == 1) {
            return 1;
        }
        return 0;
    }

    /**
     * Cập nhật lại tổng ngày phép
     */
    protected function updateTotalDaysOff($staffId, $timeOffTypeId, $type){
        //Cập nhật lại số lượng ngày phép
        $infoDaysTotal = $this->timeOffDaysTotal->checkValidTotal($staffId, $timeOffTypeId);
        if(isset($infoDaysTotal)){
            if($infoDaysTotal['time_off_days_number'] != -1){
                $daysTotal = $infoDaysTotal['time_off_days_number'];
                if($type == 'minus'){
                    $daysTotal = $daysTotal - 1;
                }else {
                    $daysTotal = $daysTotal + 1;
                }
                $this->timeOffDaysTotal->edit(['time_off_days_number' => $daysTotal], $staffId, $timeOffTypeId);
            }
        }
    }

    public function createDaysOff()
    {
        //
        $list = DB::table('staffs')
            ->select('staff_id')
            ->where('is_actived', 1)
            ->where('is_deleted', 0)->get();

        foreach ($list as $item) {
           
            $listDaysOffType = DB::table('time_off_type')
            ->where('time_off_type_parent_id', '!=' , 0)
            ->where('is_status', '=' , 1)
            ->select('total_number','time_off_type_id')->get();
            foreach ($listDaysOffType as $itemType) {
                DB::table('time_off_days_total')
                ->insert(
                    [
                        'time_off_type_id' => $itemType->time_off_type_id,
                        'staff_id' => $item->staff_id,
                        'time_off_days_number' => $itemType->total_number,
                        'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                        'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                    ]
                );
            } 
        }
    }
}