<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffTypeOption\TimeOffTypeOptionRepositoryInterface;
use Modules\TimeOffDays\Repositories\StaffTitle\StaffTitleRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepositoryInterface;
use Modules\TimeOffDays\Repositories\Staffs\StaffsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

class TimeOffTypeController extends Controller
{

    protected $repo;
    protected $timeOffTypeOption;
    protected $staffTitle;
    protected $timeOffDays;
    protected $staffs;

    public function __construct(
        TimeOffTypeRepositoryInterface $repo,
        StaffTitleRepositoryInterface $staffTitle,
        TimeOffTypeOptionRepositoryInterface $timeOffTypeOption,
        TimeOffDaysRepositoryInterface $timeOffDays,
        StaffsRepositoryInterface $staffs)
    {
        $this->repo = $repo;
        $this->timeOffTypeOption = $timeOffTypeOption;
        $this->staffTitle = $staffTitle;
        $this->timeOffDays = $timeOffDays;
        $this->staffs = $staffs;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
       
        $result = $this->repo->getList([]);
        return view('timeoffdays::timeofftype.index', [
            'LIST' => $result,
            'FILTER' => [],
            'param' => $request->all()
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->only([
            'page',
        ]);

       
        if (isset($request->filters) && $request->filters) {
            $filters = array_merge($filters, $request->filters);
        }
  
        $data = $this->repo->getList($filters);

        return view('timeoffdays::timeofftype.list', [
                'LIST' => $data,
                'FILTER' => [],
                'page' => $filters['page'],
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $code)
    {

        $params = $request->all();
        $code = $params['code'];
        $dataDetail = $this->repo->getDetail($code);
        $arrDepartment = $this->timeOffDays->getOptionDepartment();
        if(isset($dataDetail['staff_id_approve_level2'])){
            $staffInfo = $this->staffs->getListStaffApproveInfo(json_decode($dataDetail['staff_id_approve_level2']));
            if(isset($staffInfo)){
                $dataDetail['department_approve2'] = $staffInfo[0]['department_id'];
            }
        }
        if(isset($dataDetail['staff_id_approve_level3'])){
            $staffInfo = $this->staffs->getListStaffApproveInfo(json_decode($dataDetail['staff_id_approve_level3']));
            if(isset($staffInfo)){
                $dataDetail['department_approve3'] = $staffInfo[0]['department_id'];
            }
        }
        $view = 'timeoffdays::timeofftype.popup-edit';
        $html = \View::make($view, [
            'code'            => $code,
            'dataDetail'    => $dataDetail,
            'title'         => $dataDetail['time_off_type_name'],
            'arrDepartment' => $arrDepartment,
            'day'           => 10
        ])->render();
        return response()->json(['html' => $html]);
    }


    public function update(Request $request)
    {
        try {
            $params = $request->only([
                'time_off_type_code',
                'require_create_time_off_before',
                'limit_number_time_off_by_year',
                'limit_number_time_off_by_create',
                'number_day_auto_approve',
                'approve_level_1',
                'approve_level_2',
                'approve_level_3',
                'request_time_off_from_1_3',
                'request_time_off_from_3_5',
                'request_time_off_over_5'
            ]);
            $timeOffTypeCode = $params['time_off_type_code'];
            $approve_level_2 = null;
            $approve_level_3 = null;
            if(isset($params['approve_level_2'])){
                foreach ($params['approve_level_2'] as $value) {
                    $approve_level_2[] = (int)$value;
                }
            }
            if(isset($params['approve_level_3'])){
                foreach ($params['approve_level_3'] as $value) {
                    $approve_level_3[] = (int)$value;
                }
            }
            $dataUpdate = [
                'direct_management_approve' => $params['approve_level_1'],
                'staff_id_approve_level2' => $approve_level_2 ? json_encode($approve_level_2) : null,
                'staff_id_approve_level3' => $approve_level_3 ? json_encode($approve_level_3) : null,
            ];
            $this->repo->edit($dataUpdate,  $timeOffTypeCode);
            return response()->json(['status' => 1]);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => 0]);
        }
       
    }

    public function getListStaff(Request $request){
        $data = $this->staffs->getListStaffDepartment($request->department_id);
        return response()->json($data);
    }
}
