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
use Modules\Ticket\Repositories\Request\RequestRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\RequestGroup\RequestGroupRepositoryInterface;

class RequestsController extends Controller
{
    protected $requests;
    protected $groupRequest;
    protected $staff;

    public function __construct(
        RequestRepositoryInterface $requests,
        StaffRepositoryInterface $staff,
        RequestGroupRepositoryInterface $groupRequest
    )
    {
        $this->requests = $requests;
        $this->staff = $staff;
        $this->groupRequest = $groupRequest;
    }

    public function indexAction()
    {
        return view('ticket::request.index', [
            'list' => $this->requests->list(),
            'filter' => $this->filters(),
            'filterType' => getTypeTicket(),
            'staff' => $this->staff->listStaff(),
            'groupRequest' => $this->groupRequest->list(),
        ]);
    }

    protected function filters()
    {
        return [
            '' => __('Chọn trạng thái'),
            1 => __('Hoạt động'),
            0 => __('Tạm ngưng')
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search','level', 'search_keyword', 'is_active','ticket_issue_group_id','created_at','created_by','process_time']);
        return view('ticket::request.list', [
                'list' => $this->requests->list($filters),
                'filter' => $this->filters(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $request_name = $request->request_name;
        $description = $request->description;
        $ticket_issue_group_id = $request->ticket_issue_group_id;
        $process_time = $request->process_time;
        $level = $request->level;
        $data = [
            'name' => strip_tags($request_name),
            'level' => $level,
            'ticket_issue_group_id' => $ticket_issue_group_id,
            'process_time' => $process_time,
            'description' => $description,
            'is_active' => $request->isActived,
            'created_by' => Auth::id(),
        ];
        $id = $this->requests->add($data);
        if ($id) {
            return response()->json(['status' => 1]);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->requestId;
            $item = $this->requests->getItem($id);
            $jsonString = [
                'ticket_issue_id' => $item->ticket_issue_id,
                'request_name' => strip_tags($item->name),
                'level' => $item->level,
                'ticket_issue_group_id' => $item->ticket_issue_group_id,
                'process_time' => $item->process_time,
                'description' => $item->description,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $request_name = $request->request_name;
            $description = $request->description;
            $level = $request->level;
            $process_time = $request->process_time;
            $data = [
                'name' => $request_name,
                'level' => $level,
                // 'ticket_issue_group_id' => $ticket_issue_group_id,
                'process_time' => $process_time,
                'description' => $description,
                'updated_by' => Auth::id(),
            ];
            if($this->requests->edit($data, $id)){
                return response()->json(['status' => 1]);
            }else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->requests->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_active'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->requests->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}