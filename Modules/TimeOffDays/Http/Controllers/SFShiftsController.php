<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepositoryInterface;
use Carbon\Carbon;
use Validator;

class SFShiftsController extends Controller
{

    protected $repo;
    protected $timOffDaysShift;
    public function __construct(
        SFShiftsRepositoryInterface $repo, TimeOffDaysShiftsRepositoryInterface $timOffDaysShift)
    {
        $this->repo = $repo;
        $this->timOffDaysShift = $timOffDaysShift;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {
        $params = $request->all();
        $result = $this->repo->getLists($params);
        $data = [];
        if($result)
        {
            $index = 0;
            foreach($result as $key => $item)
            {
                if(isset($request->time_off_days_id)){
                    $data[$key]['time_working_staff_id'] = $item['time_working_staff_id'];
                    $workingDay = Carbon::createFromFormat('Y-m-d',$item['working_day'] ? $item['working_day'] : '00-00-00')->format('d/m');
                    $data[$key]['shift_name'] = str_replace('Ca', 'Ca nghỉ', $item['shift_name'] ) .' - ' .$workingDay;
                    if($item['time_off_days_id'] > 0){
                        $data[$key]['selected'] = true;
                    }else {
                        $data[$key]['selected'] = false;
                    }
                }else {
                    // $infoShift = $this->timOffDaysShift->checkExist($item['time_working_staff_id'], $request->time_off_type_id);
                    $data[$key]['time_working_staff_id'] = $item['time_working_staff_id'];
                        $workingDay = Carbon::createFromFormat('Y-m-d',$item['working_day'] ? $item['working_day'] : '00-00-00')->format('d/m');
                        $data[$key]['shift_name'] = str_replace('Ca', 'Ca nghỉ', $item['shift_name'] ) .' - ' .$workingDay;
                        if($index == 0){
                            $data[$key]['selected'] = true;
                        }else {
                            $data[$key]['selected'] = false;
                        }
                }
                
                $index++;
            }
        }
        return response()->json($data);
    }


}
