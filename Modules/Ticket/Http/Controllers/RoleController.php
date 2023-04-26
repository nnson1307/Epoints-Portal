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
use Modules\Ticket\Repositories\Role\RoleRepositoryInterface;
use Modules\Ticket\Repositories\TicketStatus\TicketStatusRepositoryInterface;
use Modules\Ticket\Repositories\TicketRoleStatusMap\TicketRoleStatusMapRepositoryInterface;
use Modules\Ticket\Repositories\TicketAction\TicketActionRepositoryInterface;
use Modules\Ticket\Repositories\TicketRoleActionMap\TicketRoleActionMapRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\RoleGroup\RoleGroupRepositoryInterface;

class RoleController extends Controller
{
    protected $role;
    protected $ticketStatus;
    protected $ticketRoleStatusMap;
    protected $ticketAction;
    protected $ticketRoleActionMap;
    protected $staff;
    protected $roleGroup;

    public function __construct(
        RoleRepositoryInterface $role,
        TicketStatusRepositoryInterface $ticketStatus,
        TicketRoleStatusMapRepositoryInterface $ticketRoleStatusMap,
        TicketActionRepositoryInterface $ticketAction,
        TicketRoleActionMapRepositoryInterface $ticketRoleActionMap,
        StaffRepositoryInterface $staff,
        RoleGroupRepositoryInterface $roleGroup
    )
    {
        $this->role = $role;
        $this->ticketStatus = $ticketStatus;
        $this->ticketRoleStatusMap = $ticketRoleStatusMap;
        $this->ticketAction = $ticketAction;
        $this->ticketRoleActionMap = $ticketRoleActionMap;
        $this->staff = $staff;
        $this->roleGroup = $roleGroup;
    }

    public function indexAction()
    {
        $allRoleGroup = $this->roleGroup->getName();
        $roleHasUsed = $this->role->getRoleGroupId();
        $allRoleGroup = array_diff_key($allRoleGroup, array_flip($roleHasUsed));

        return view('ticket::role.index', [
            'list' => $this->role->list(),
            'filter' => $this->filters(),
            'ticketStatusList' => $this->ticketStatus->list(),
            'ticketAction' => $this->ticketAction->getName(),
            'staff' => $this->staff->listStaff(),
            'roleGroup' => $allRoleGroup,
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
        $filters = $request->only(['page', 'display', 'search', 'search_keyword','created_at','updated_by','created_by']);
        $orderSourceList = $this->role->list($filters);
        return view('ticket::role.list', [
                'list' => $orderSourceList,
                'filter' => $this->filters(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        // $role_name = $request->role_name;
        $role_group_id = $request->role_group_id;
        $description = $request->description;
        $status = $request->status;
        $action = $request->action;
        $is_approve_refund = $request->is_approve_refund;
       
        $data = [
            // 'role_name' => $role_name,
            'role_group_id' => $role_group_id,
            'description' => $description,
            'is_approve_refund' => $is_approve_refund,
            'created_by' => Auth::id(),
        ];
        $id = $this->role->add($data);
        if($id){
            if($status != null){
                foreach($status as $value){
                    $arr_status = [
                        'ticket_role_id' => $id,
                        'ticket_status_id' => $value,
                        'allow' => 1,
                        'created_by' => Auth::id(),
                    ];
                    $this->ticketRoleStatusMap->add($arr_status);
                }
            }
            if($action != null){
                // foreach($action as $value){
                $arr_action = [
                    'ticket_role_id' => $id,
                    'ticket_action_value' => $action,
                    'allow' => 1,
                    'created_by' => Auth::id(),
                ];
                $this->ticketRoleActionMap->add($arr_action);
                // }
            }
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);
            
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->roleId;
            $item = $this->role->getItem($id);
            $jsonString = [
                'ticket_role_id' => $id,
                // 'role_name' => $item->role_name,$this->table.'.is_approve_refund',
                'role_group_id' => $item->role_group_id,
                'is_approve_refund' => $item->is_approve_refund,
                'option' => '<option value="'.$item->role_group_id.'" class="option-tmp">'.$item->name.'</option>',
                'description' => $item->description,
                'role_action' => json_decode(json_encode($item->ticketRoleActionMap),true),
                'role_status' => json_decode(json_encode($item->TicketRoleStatusMap),true),
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $role_group_id = $request->role_group_id;
            $description = $request->description;
            $status = $request->status;
            $action = $request->action;
            $is_approve_refund = $request->is_approve_refund;
            if ($request->parameter == 0) {
                $data = [
                    'role_group_id' => $request->role_group_id,
                    'description' => $request->description,
                    'is_approve_refund' => $request->is_approve_refund,
                    'updated_by' => Auth::id(),
                ];
                if($this->role->edit($data, $id)){
                    if($status != null){
                        $this->ticketRoleStatusMap->removeByRole($id);
                        foreach($status as $value){
                            $arr_status = [
                                'ticket_role_id' => $id,
                                'ticket_status_id' => $value,
                                'allow' => 1,
                                'created_by' => Auth::id(),
                            ];
                            $this->ticketRoleStatusMap->add($arr_status);
                        }
                    }
                    if($action != null){
                        $this->ticketRoleActionMap->removeByRole($id);
                        // foreach($action as $value){
                            $arr_action = [
                                'ticket_role_id' => $id,
                                'ticket_action_value' => $action,
                                'allow' => 1,
                                'created_by' => Auth::id(),
                            ];
                            $this->ticketRoleActionMap->add($arr_action);
                        // }
                    }
                }
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->role->remove($id);
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
        $this->role->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}