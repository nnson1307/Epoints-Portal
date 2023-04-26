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
use Modules\Ticket\Models\StaffQueueMapTable;
use Modules\Ticket\Repositories\QueueStaff\QueueStaffRepositoryInterface;
use Modules\Ticket\Repositories\Queue\QueueRepositoryInterface;
use Modules\Ticket\Repositories\RoleQueue\RoleQueueRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\Role\RoleRepositoryInterface;
use Modules\Ticket\Repositories\StaffQueueMap\StaffQueueMapRepositoryInterface;

class QueueStaffController extends Controller
{
    protected $queueStaff;
    protected $RoleQueue;
    protected $queue;
    protected $staff;
    protected $role;
    protected $staffQueueMap;

    public function __construct(
        QueueStaffRepositoryInterface    $queueStaff,
        RoleQueueRepositoryInterface     $RoleQueue,
        QueueRepositoryInterface         $queue,
        StaffRepositoryInterface         $staff,
        RoleRepositoryInterface          $role,
        StaffQueueMapRepositoryInterface $staffQueueMap

    )
    {
        $this->queueStaff = $queueStaff;
        $this->RoleQueue = $RoleQueue;
        $this->queue = $queue;
        $this->staff = $staff;
        $this->role = $role;
        $this->staffQueueMap = $staffQueueMap;
    }

    public function indexAction()
    {
        $currentStaff = $this->queueStaff->getStaff();
        $allStaff = $this->staff->getName();
        // loại bỏ nhân viên đã phân công rồi
        $staff = array_diff_key($allStaff, array_flip($currentStaff));

        return view('ticket::queueStaff.index', [
            'list' => $this->queueStaff->list(),
            'filter' => $this->filters(),
            'roleQueue' => $this->RoleQueue->list(),
            'queue' => $this->queue->getName(),
            'role' => $this->role->getName(),
            'staff' => $staff,
            'allStaff' => $allStaff,
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
        $filters = $request->only(['page', 'display', 'ticket_queue_id', 'ticket_role_queue_id', 'staff_id']);
        return view('ticket::queueStaff.list', [
                'list' => $this->queueStaff->list($filters),
                'filter' => $this->filters(),
                'roleQueue' => $this->RoleQueue->list(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $data = [
            'staff_id' => $request->staff_id,
            'ticket_role_queue_id' => $request->ticket_role_queue_id,
        ];

        //Lưu phân công nhân viên
        $id = $this->queueStaff->add($data);

        if (isset($request->ticket_queue_id) && count($request->ticket_queue_id) > 0) {
            foreach ($request->ticket_queue_id as $v) {
                $queueMap [] = [
                    'ticket_staff_queue_id' => $id,
                    'ticket_queue_id' => $v,
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }
        }

        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
        //Insert staff queue map
        $mStaffQueueMap->insert($queueMap);

        if ($id) {
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ticket_staff_queue_id;
            $item = $this->queueStaff->getItem($id);
            $info = $this->staff->getDetail($item->staff_id);

            $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
            //Lấy queue map
            $getQueueMap = $mStaffQueueMap->getQueueMap($id);

            $jsonString = [
                'ticket_staff_queue_id' => $id,
                'staff_id' => $item->staff_id,
                'ticket_queue_id' => $item->ticket_queue_id,
                'ticket_role_queue_id' => $item->ticket_role_queue_id,
                'info' => [
                    'full_name' => $info->full_name,
                    'email' => $info->email,
                    'phone' => $info->phone1,
                    'address' => $info->address,
                ],
                'queue_map' => $getQueueMap
            ];
            // dd($jsonString);
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ticket_staff_queue_id;
            $data = [
                'ticket_role_queue_id' => $request->ticket_role_queue_id,
            ];
            //Chỉnh sửa nhân viên theo queue
            $id_last = $this->queueStaff->edit($data, $id);

            $queueMap = [];

            if (isset($request->ticket_queue_id) && count($request->ticket_queue_id) > 0) {
                foreach ($request->ticket_queue_id as $v) {
                    $queueMap [] = [
                        'ticket_staff_queue_id' => $id,
                        'ticket_queue_id' => $v,
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
            //Xoá staff queue map
            $mStaffQueueMap->removeBy($id);
            //Insert staff queue map
            $mStaffQueueMap->insert($queueMap);

            if ($id_last) {
                return response()->json(['status' => 1]);
            }
            return response()->json(['status' => 0]);
        }
    }

    public function removeAction($id)
    {
        $this->queueStaff->remove($id);
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
        $this->queueStaff->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function getDetail(Request $request)
    {
        $id = $request->only(['staff_id']);
        $item = $this->staff->getDetail($id);
        $jsonString = [
            'full_name' => $item->full_name,
            'email' => $item->email,
            'phone' => $item->phone1,
            'address' => $item->address,
        ];
        return response()->json($jsonString);
    }
}