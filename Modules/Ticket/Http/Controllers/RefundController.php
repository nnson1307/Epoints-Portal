<?php

namespace Modules\Ticket\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Ticket\Http\Requests\Refund\StoreRequest;
use Modules\Ticket\Http\Requests\Refund\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Ticket\Http\Api\SendNotificationApi;
use Modules\Ticket\Repositories\Refund\RefundRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Illuminate\Support\Facades\Cookie;

class RefundController extends Controller
{
    protected $refund;
    protected $staff;
    protected $listRefund;
    // protected $refundDetail;
    
    public function __construct(
        RefundRepositoryInterface $refund,
        StaffRepositoryInterface $staff
    )
    {
        $this->refund = $refund;
        $this->staff = $staff;
    }

    public function indexAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search','staff_id','approve_id','status','created_at','created_by','updated_at','updated_by']);
        return view('ticket::refund.index', [
            'list' => $this->refund->list($filters),
            'page' => isset($filters['page']) ? $filters['page'] : 1,
            'searchConfig' => $this->searchColumn(),
            'showColumn' => $this->showColumn(),
            'params' => $filters,
            'staffList' => $this->staff->getName(),
            'listApproveStaff' => $this->refund->loadListApprove(),
        ]);
    }

    /**
     * Lấy tên queue theo staff_id
     * @param Request $request
     */
    public function loadQueueByStaff(Request $request)
    {
        $params = $request->all();
        $data = $this->refund->loadQueueByStaff($params);
        return response()->json($data);
    }

    public function addView($id = null)
    {
        $data = $this->refund->addView($id);
        if($data['item']->status != 'D'){
            return redirect()->route('ticket.refund.edit-view', $id);
        }
        return view('ticket::refund.add', $data);
    }
    
    // FUNCTION RETURN VIEW EDIT
    public function editView($id = null,Request $request)
    {
        $data = $this->refund->editView($id);
        if($data == []){
            return redirect()->route('ticket.refund');
        }
        return view('ticket::refund.edit', $data);
    }
    
    // FUNCTION RETURN VIEW EDIT
    public function detailView($id = null,Request $request)
    {
        $data = $this->refund->detailView($id);
        return view('ticket::refund.detail', $data);
    }

    public function approveView($id = null,Request $request)
    {
        $data = $this->refund->approveView($id);
        if($data == []){
            return redirect()->route('ticket.refund');
        }
        return view('ticket::refund.approve', $data);
    }

    public function addAction(StoreRequest $request)
    {
        $params = $request->all();
        $data = $this->refund->addAction($params);
        return response()->json($data);
    }

    public function getListStaff($ticketId){
        return $this->ticket->getListStaff($ticketId);
    }

    public function loadTicketRefundDetail($id,Request $request)
    {
        $check_edit = false;
        if($request->check_edit == true){
            $check_edit = true;
        }
        $data = $this->refund->loadTicketRefundDetail($id,$check_edit);
        return response()->json($data);
    }

    public function submitEditAction($id = null,Request $request)
    {
        $params = $request->all();
        $data = $this->refund->submitEditAction($id,$params);
        return response()->json($data);
    }

    public function updateApproveItem(Request $request)
    {
        $params = $request->all();
        $data = $this->refund->updateApproveItem($params);
        return response()->json($data);
    }
    
    public function showApproveItem(Request $request)
    {
        $params = $request->all();
        $data = $this->refund->showApproveItem($params);
        return response()->json($data);
    }

    public function removeAction($id)
    {
        $data = $this->refund->removeAction($id);
        if($data){
            return redirect()->route('ticket.refund')->with('remove_action', 'success');
        }
        return redirect()->route('ticket.refund')->with('remove_action', 'danger');
    }

    /**
     * Upload files
     * @param Request $request
     */
    public function uploadFile(Request $request)
    {
        $params = $request->all();
        $data = $this->refund->uploadFile($params);
        return response()->json($data);
    }

    // ajax lưu cấu hình tìm kiếm + table
    public function saveConfig(Request $request)
    {
        $data = [
            'route_name' => 'ticket.refund.save-config',
            'search' => $request->search,
            'column' => $request->column,
        ];
        $data = serialize($data);
        Cookie::queue('refund_token', $data, 3600);
        return response()->json(['status' => 1,'data'=> $data]);
    }

    // hiển thị cấu hình tìm kiếm
    public function searchColumn()
    {
        return $this->refund->searchColumn();
    }

     // hiển thị cấu hình table
    public function showColumn()
    {
        return $this->refund->showColumn();
    }
}