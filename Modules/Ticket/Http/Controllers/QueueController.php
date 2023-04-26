<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\Ticket\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Ticket\Repositories\Queue\QueueRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Models\DepartmentTable;


class QueueController extends Controller
{
    protected $queue;
    protected $staff;
    protected $department;


    public function __construct(
        QueueRepositoryInterface $queue,
        StaffRepositoryInterface $staff,
        DepartmentTable $department
    )
    {
        $this->queue = $queue;
        $this->staff = $staff;
        $this->department = $department;
    }

    public function indexAction()
    {
        return view('ticket::queue.index', [
            'list' => $this->queue->list(),
            'filter' => $this->filters(),
            'staff' => $this->staff->listStaff(),
            'department' => $this->department->getOption(),
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
        $filters = $request->only(['page', 'display', 'search', 'search_keyword', 'is_actived','created_at','updated_by','created_by']);
        return view('ticket::queue.list', [
                'list' => $this->queue->list($filters),
                'filter' => $this->filters(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $queue_name = $request->queue_name;
        $email = $request->email;
        $description = $request->description;
        $checkExistEmail = $this->queue->checkExistEmail($email);
        if ($checkExistEmail != null) {
            return response()->json(['status' => 0]);
        } else {
            $data = [
                'queue_name' => $queue_name,
                'email' => $email,
                'description' => $description,
                'department_id' => $request->department_id,
                'is_actived' => $request->isActived,
                'created_by' => Auth::id(),
            ];
            $id = $this->queue->add($data);
            if($id == -2) {
                // kiểm tra cùng tên cùng phòng ban
                return response()->json(['status' => 2]);
            }
            if($id){
                return response()->json(['status' => 1]);
            }
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->queueId;
            $item = $this->queue->getItem($id);
            $jsonString = [
                'ticket_queue_id' => $id,
                'queue_name' => $item->queue_name,
                'email' => $item->email,
                'description' => $item->description,
                'department_id' => $item->department_id,
                'is_actived' => $item->is_actived,
                'updated_by' => Auth::id(),
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $queue_name = $request->queue_name;
            $email = $request->email;
            $description = $request->description;
            $checkExistEmail = $this->queue->checkExistEmail($email,$id);
            if ($checkExistEmail == null) {
                $data = [
                    'email' => $request->email,
                    'queue_name' => $request->queue_name,
                    'description' => $request->description,
                    'department_id' => $request->department_id,
                    'updated_by' => Auth::id(),
                ];
                $exists = $this->queue->edit($data, $id);
                if($exists == 2){
                    return response()->json(['status' => 2]);
                }
                if($exists){
                    // kiểm tra cùng tên cùng phòng ban
                    return response()->json(['status' => 1]); 
                }
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->queue->remove($id);
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
        $data['updated_by'] =  Auth::id();
        $this->queue->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

}