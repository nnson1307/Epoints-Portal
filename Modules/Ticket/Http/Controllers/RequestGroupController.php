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
use Modules\Ticket\Repositories\RequestGroup\RequestGroupRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;

class RequestGroupController extends Controller
{
    protected $requestGroup;
    protected $staff;

    public function __construct(
        RequestGroupRepositoryInterface $requestGroup,
        StaffRepositoryInterface $staff
    )
    {
        $this->requestGroup = $requestGroup;
        $this->staff = $staff;
    }

    public function indexAction()
    {
        return view('ticket::requestGroup.index', [
            'list' => $this->requestGroup->list(),
            'filter' => $this->filters(),
            'filterType' => getTypeTicket(),
            'staff' => $this->staff->listStaff(),
        ]);
    }

    protected function filters()
    {
        return [
            'is_active' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'search_keyword', 'is_active','created_by','created_at','type']);
        $orderSourceList = $this->requestGroup->list($filters);
        return view('ticket::requestGroup.list', [
                'list' => $orderSourceList,
                'filter' => $this->filters(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $group_request_name = $request->group_request_name;
        $description = $request->description;
        // $type = $request->type;
            $data = [
                'name' => $group_request_name,
                'description' => $description,
                'type' => ($request->type != null)?$request->type != null:'I',
                'is_active' => $request->isActived,
                'created_by' => Auth::id(),
            ];
            $id = $this->requestGroup->add($data);
            if($id){
                return response()->json(['status' => 1]);
            }else{
                return response()->json(['status' => 0]);
            }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->group_requestId;
            $item = $this->requestGroup->getItem($id);
            $jsonString = [
                'ticket_issue_group_id' => $id,
                'group_request_name' => $item->name,
                'description' => $item->description,
                'is_active' => $item->is_active,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $description = $request->description;
            $data = [
                'description' => $request->description,
                'is_active' => $request->isActived,
                'updated_by' => Auth::id(),
            ];
            if ($this->requestGroup->edit($data, $id)) {
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->requestGroup->remove($id);
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
        $this->requestGroup->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}