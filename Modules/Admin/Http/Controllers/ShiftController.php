<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\Shift\ShiftRepositoryInterface;

class ShiftController extends Controller
{
    protected $shift;
    protected $code;

    public function __construct(
        ShiftRepositoryInterface $shift,
        CodeGeneratorRepositoryInterface $code
    )
    {
        $this->shift = $shift;
        $this->code = $code;
    }

    public function indexAction()
    {
        $orderSourceList = $this->shift->list();
        return view('admin::shift.index', [
            'LIST' => $orderSourceList,
            'FILTER' => $this->filters()
        ]);
    }

    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $orderSourceList = $this->shift->list($filters);
        return view('admin::shift.list', [
                'LIST' => $orderSourceList,
                'FILTER' => $this->filters()
            ]
        );
    }

    public function addAction(Request $request)
    {
        $code = $this->code->generateCodeRandom("CA");
        $startTime = $request->startTime;
        $endTime = $request->endTime;
        $checkExistDelete = $this->shift->checkExist($startTime, $endTime, 1);
        $checkExistUnDelete = $this->shift->checkExist($startTime, $endTime, 0);
        if ($checkExistUnDelete != null) {
            return response()->json(['status' => 0]);
        } else {
            if ($checkExistDelete != null) {
                $this->shift->edit(['is_deleted' => 0], $checkExistDelete->shift_id);
                return response()->json(['status' => 1]);
            } else {
                if ($checkExistUnDelete == null && $checkExistDelete == null) {
                    $data = [
                        'shift_code' => $code,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_actived' => $request->isActived,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ];
                    $id = $this->shift->add($data);
                    $this->shift->edit(['shift_code' => $this->code->codeDMY('CA',$id)],$id);
                    return response()->json(['status' => 1]);
                }
            }
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->shiftId;
            $item = $this->shift->getItem($id);
            $startTime = Carbon::createFromFormat('H:i:s', $item->start_time)->format('H:i');
            $endTime = Carbon::createFromFormat('H:i:s', $item->end_time)->format('H:i');
            $jsonString = [
                'shift_id' => $id,
                'shift_code' => $item->shift_code,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_actived' => $item->is_actived,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $shiftCode = $request->shiftCode;
            $startTime = $request->startTime;
            $endTime = $request->endTimes;
            $testIsDeleted = $this->shift->checkExist($startTime, $endTime, 1);
            $testEdit = $this->shift->testEdit($id, $startTime, $endTime);
            if ($request->parameter == 0) {
                if ($testIsDeleted != null) {
                    //Tồn tại ca làm việc trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($testEdit == null) {
                        $data = [
                            'start_time' => $request->startTime,
                            'end_time' => $request->endTimes,
                            'is_actived' => $request->isActived,
                            'updated_by' => Auth::id(),
                        ];
                        $this->shift->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else {
                        return response()->json(['status' => 0]);
                    }
                }
            } else {
                //Kích hoạt lại ca làm việc.
                $this->shift->edit(['is_deleted' => 0], $testIsDeleted->shift_id);
                return response()->json(['status' => 3]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->shift->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->shift->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}