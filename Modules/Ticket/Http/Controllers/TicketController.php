<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\Ticket\Http\Controllers;

use App\Jobs\FunctionSendNotify;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Contract\Models\ContractPartnerTable;
use Modules\Contract\Models\ContractTable;
use Modules\Ticket\Http\Api\SendNotificationApi;
use Modules\Ticket\Models\OrderDetailTable;
use Modules\Ticket\Models\OrderTable;
use Modules\Ticket\Models\QueueStaffTable;
use Modules\Ticket\Models\StaffQueueMapTable;
use Modules\Ticket\Models\TicketConfigTable;
use Modules\Ticket\Models\TicketOperaterTable;
use Modules\Ticket\Models\TicketProcessorTable;
use Modules\Ticket\Models\TicketTable;
use Modules\Ticket\Models\TicketHistoryTable;
use Modules\Ticket\Repositories\Ticket\TicketRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Ticket\Repositories\TicketStatus\TicketStatusRepositoryInterface;
use Modules\Ticket\Repositories\Request\RequestRepositoryInterface;
use Modules\Ticket\Repositories\RequestGroup\RequestGroupRepositoryInterface;
use Modules\Ticket\Repositories\Queue\QueueRepositoryInterface;
use Modules\Ticket\Repositories\Upload\UploadRepositoryInterface;
use Modules\Ticket\Repositories\TicketFile\TicketFileRepositoryInterface;
use Modules\Ticket\Repositories\TicketProcessor\TicketProcessorRepositoryInterface;
use Modules\Ticket\Repositories\QueueStaff\QueueStaffRepositoryInterface;
use Modules\Ticket\Repositories\TicketRating\TicketRatingRepositoryInterface;
use Modules\Ticket\Repositories\Material\MaterialRepositoryInterface;
use Modules\Ticket\Repositories\Acceptance\AcceptanceRepositoryInterface;
use Modules\Ticket\Models\Customers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Modules\Ticket\Models\ProductInventoryTable;
use Modules\Ticket\Models\WarehousesTable;


class TicketController extends Controller
{
    protected $ticket;
    protected $staff;
    protected $provinces;
    protected $ticketStatus;
    protected $requests;
    protected $requestGroup;
    protected $queue;
    protected $upload;
    protected $ticketFile;
    protected $ticketProcessor;
    protected $material;
    protected $ticketRating;
    protected $listMaterial;
    protected $queueStaff;
    protected $ticketHistory;
    protected $acceptance;

    public function __construct(
        TicketRepositoryInterface          $ticket,
        StaffRepositoryInterface           $staff,
        ProvinceRepositoryInterface        $provinces,
        TicketStatusRepositoryInterface    $ticketStatus,
        RequestRepositoryInterface         $requests,
        RequestGroupRepositoryInterface    $requestGroup,
        QueueRepositoryInterface           $queue,
        UploadRepositoryInterface          $upload,
        TicketFileRepositoryInterface      $ticketFile,
        TicketProcessorRepositoryInterface $ticketProcessor,
        TicketRatingRepositoryInterface    $ticketRating,
        MaterialRepositoryInterface        $material,
        QueueStaffRepositoryInterface      $queueStaff,
        AcceptanceRepositoryInterface      $acceptance,
        TicketHistoryTable                 $ticketHistory
    )
    {
        $this->ticket = $ticket;
        $this->staff = $staff;
        $this->province = $provinces;
        $this->ticketStatus = $ticketStatus;
        $this->requests = $requests;
        $this->requestGroup = $requestGroup;
        $this->queue = $queue;
        $this->upload = $upload;
        $this->ticketFile = $ticketFile;
        $this->ticketProcessor = $ticketProcessor;
        // $this->ticketOperater = $ticketOperater;
        // $this->ticketQueueMap = $ticketQueueMap;
        $this->ticketRating = $ticketRating;
        $this->material = $material;
        $this->listMaterial = new ProductInventoryTable;
        $this->queueStaff = $queueStaff;
        $this->ticketHistory = $ticketHistory;
        $this->acceptance = $acceptance;
    }

    public function dashboard()
    {
        $arrQueueStaff = [];

        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);

        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        $filters['arr_queue_staff'] = $arrQueueStaff;

        /*
            1 => Mới
            2 => Đang xử lý
            3 => Hoàn tất
            4 => Đóng
            5 => Huỷ
            6 => Reopen
            7 => Quá hạn
        */
        // lấy số lượng qua status
        $expiredTicket = $this->ticket->getTicketByStatus([7], $filters);
        $newTicket = $this->ticket->getTicketByStatus([1], $filters);
        $inprocessTicket = $this->ticket->getTicketByStatus([2], $filters);
        $total = $expiredTicket + $newTicket + $inprocessTicket;
        // chuyển số lượng qua phần trăm 
        $number_after = 1; # làm tròn
        $expiredTicketPercent = $expiredTicket != 0 ? round(100 / $total * $expiredTicket, $number_after) : 0;
        $newTicketPercent = $newTicket != 0 ? round(100 / $total * $newTicket, $number_after) : 0;
        $inprocessTicketPercent = $inprocessTicket != 0 ? round(100 / $total * $inprocessTicket, $number_after) : 0;
        $ticketDashboad = [
            'total' => $total,
            'expiredTicket' => $expiredTicket,
            'newTicket' => $newTicket,
            'inprocessTicket' => $inprocessTicket,
            'expiredTicketPercent' => $expiredTicketPercent,
            'newTicketPercent' => $newTicketPercent,
            'inprocessTicketPercent' => $inprocessTicketPercent,
        ];
        // lấy số lượng ticket cá nhân
        $myTicketPer = $this->ticket->getNumberTicketAssignMe();
        $ticketCreateByMePer = $this->ticket->getNumberTicketCreatedByMe();
        // Đang xử lý (Mới, đang xử lý, hoàn tất, quá hạn)
        $inprocessTicketPer = $this->ticket->getTicketByStatus([1, 2], $filters);

        $ticketPersonal = [
            'myTicket' => $myTicketPer,
            'ticketCreateByMe' => $ticketCreateByMePer,
            'inprocessTicket' => $inprocessTicketPer,
        ];
        // lấy danh sách ticket chưa hoàn thành
        $ticketInprocessList = $this->ticket->getTicketProcessingList();
        $ticketProcessingListExpired = $this->ticket->getTicketProcessingListExpired();
//        dd($ticketProcessingListExpired,$ticketInprocessList);
        $ticketInprocessList = $this->groupArrayByKey($ticketInprocessList, 'queue_process_id');
        $ticketProcessingListExpired = $this->groupArrayByKey($ticketProcessingListExpired, 'queue_process_id');
        foreach ($ticketInprocessList as $key => $value) {
            $ticketInprocessList[$key] = array_column($value, null, 'ticket_status_id');
        }
        foreach ($ticketProcessingListExpired as $key => $value) {
            $ticketProcessingListExpired[$key] = array_column($value, null, 'ticket_status_id');
        }
        foreach ($ticketProcessingListExpired as $key => $value) {
            $count_ticket_processing_expired = 0;
            foreach ($value as $ticket_status_id => $data) {
                if ($data['queue_process_id'] == $key) {
                    $count_ticket_processing_expired += $data['count'];
                    $ticketInprocessList[$key][7] = $data;
                    $ticketInprocessList[$key][7]['count'] = $count_ticket_processing_expired;
                }
            }

        }
//        dd($ticketInprocessList,$ticketProcessingListExpired);
        // lấy danh sách ticket chưa assign
        $ticketUnAssignList = [];
        // lấy danh sách tên queue
        $listQueueName = $this->queue->getName();
        $listStatusUnAssign = [1, 2, 3, 7];
        foreach ($listQueueName as $queue_process_id => $name_queue) {
            if ($this->ticket->getTicketUnAssign($queue_process_id)) {
                $arrayTicketUnAssign = $this->ticket->getTicketUnAssign($queue_process_id);
                if ($arrayTicketUnAssign) {
                    $ticketUnAssignList[$queue_process_id] = $arrayTicketUnAssign;
                }
            }
        }
        return view('ticket::ticket.dasboard', [
            'ticketDashboad' => $ticketDashboad,
            'ticketPersonal' => $ticketPersonal,
            'ticketInprocessList' => $ticketInprocessList,
            'listQueueName' => $listQueueName,
            'ticketUnAssignList' => $ticketUnAssignList,
        ]);
    }

    public function indexAction(Request $request)
    {
        session()->forget('array_filter');
        $arrQueueStaff = [];

        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
        $mTicketConfig = app()->get(TicketConfigTable::class);
        $mQueueStaff = app()->get(QueueStaffTable::class);

        //Lấy thông tin quyền hạn nhân viên
        $infoStaffQueue = $mQueueStaff->getTicketQueueIdByStaffId(Auth()->id());
        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        //Lấy cấu hình nhận ticket củng queue
        $isAcceptTicketSameQueue = $mTicketConfig->getConfig('is_accept_ticket_same_queue')['ticket_config_value'];

        $filters = $request->all();
        $filters['ticket_index'] = 1;
        $filters['arr_queue_staff'] = $arrQueueStaff;

        //Danh sách ticket
        $list = $this->ticket->getTicketList($filters);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                $isEdit = 0;

                if (($v['process_by'] != null && in_array(Auth()->id(), explode(",", $v['process_by']))) || $v['created_by'] == Auth()->id() || $v['operate_by'] == Auth()->id()) {
                    $isEdit = 1;
                }

                $v['is_edit'] = $isEdit;
            }
        }

        return view('ticket::ticket.index', [
            'list' => $list,
            'filter' => $this->filters(),
            'ticketStatusList' => $this->ticketStatus->list(),
            'staff' => $this->staff->getName(),
            'optionProvince' => $this->province->getOptionProvince(),
            'requests' => $this->requests->getName(),
            'requestGroup' => $this->requestGroup->getAll(),
            'queue' => $this->queue->getName(),
            'searchConfig' => $this->searchColumn(),
            'showColumn' => $this->showColumn(),
            'params' => $filters,
            'status_role_array' => $this->check_status_role(),
            'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
            'arrQueueStaff' => $arrQueueStaff,
            'infoStaffQueue' => $infoStaffQueue
        ]);
    }

    public function myTicket(Request $request)
    {
        $filter = $request->all();
        $my_ticket = 0;
        if (isset($filter['tab']) && $filter['tab'] == 'my_ticket') {
            $my_ticket = 1;
        }
        $filterCreated["created_by"] = \Auth::id();
        $filterAssign["processor_by"] = \Auth::id();
        //Lấy cấu hình nhận ticket củng queue
        $mTicketConfig = app()->get(TicketConfigTable::class);
        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
        // $mQueueStaff = app()->get(QueueStaffTable::class);
        $isAcceptTicketSameQueue = $mTicketConfig->getConfig('is_accept_ticket_same_queue')['ticket_config_value'];
        $arrQueueStaff = [];
        //Lấy thông tin quyền hạn nhân viên
        // $infoStaffQueue = $mQueueStaff->getTicketQueueIdByStaffId(Auth()->id());
        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }
        return view('ticket::ticket.my_ticket', [
            'listCreated' => view('ticket::ticket.list', [
                    'list' => $this->ticket->getTicketCreatedByMe($filterCreated),
                    'filter' => $this->filters(),
                    'showColumn' => $this->showColumnMyTicket(),
                    'status_role_array' => $this->check_status_role(),
                    'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
                    'arrQueueStaff' => $arrQueueStaff
                ]
            ),
            'listAssign' => view('ticket::ticket.list', [
                    'list' => $this->ticket->getTicketAssignMe($filterAssign),
                    'filter' => $this->filters(),
                    'showColumn' => $this->showColumnMyTicket(),
                    'status_role_array' => $this->check_status_role(),
                    'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
                    'arrQueueStaff' => $arrQueueStaff
                ]
            ),
            'filter' => $this->filters(),
            'ticketStatusList' => $this->ticketStatus->list(),
            'staff' => $this->staff->getName(),
            'optionProvince' => $this->province->getOptionProvince(),
            'requests' => $this->requests->getAll(),
            'requestGroup' => $this->requestGroup->getAll(),
            'queue' => $this->queue->getName(),
            'searchConfig' => $this->searchColumn(),
            'showColumn' => $this->showColumn(),
            'tab' => $my_ticket,
            'params' => $filter,
            
            'status_role_array' => $this->check_status_role()
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

    protected function statusMaterialItem()
    {
        return [
            'new' => __('Mới'),
            'approve' => __('Đã duyệt'),
            'cancel' => __('Từ chối'),
        ];
    }

    public function listAction(Request $request)
    {
        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
        $mTicketConfig = app()->get(TicketConfigTable::class);
        $mQueueStaff = app()->get(QueueStaffTable::class);

        $arrQueueStaff = [];
       
        //Lấy thông tin quyền hạn nhân viên
        $infoStaffQueue = $mQueueStaff->getTicketQueueIdByStaffId(Auth()->id());
        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        //Lấy cấu hình nhận ticket củng queue
        $isAcceptTicketSameQueue = $mTicketConfig->getConfig('is_accept_ticket_same_queue')['ticket_config_value'];

        $filters = $request->only([
            'page',
            'display',
            'search',
            'ticket_type',
            'ticket_issue_id',
            'issule_level',
            'localtion_id',
            'queue_process_id',
            'staff_notification_id',
            'ticket_status_id',
            'date_issue',
            'date_estimated',
            'date_request',
            'processor_by',
            'ticket_index',
            'operate_by',
            'created_by',
            'priority',
            'handle_by',
            'sort_priority',
            'date_expected',
        ]);

        if (isset($request->filters) && $request->filters) {
            $filters = array_merge($filters, $request->filters);
        }

        $filters['arr_queue_staff'] = $arrQueueStaff;

        session()->put('array_filter', $filters);

        //Danh sách ticket
        $list = $this->ticket->getTicketList($filters);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                $isEdit = 0;

                if (($v['process_by'] != null && in_array(Auth()->id(), explode(",", $v['process_by']))) || $v['created_by'] == Auth()->id() || $v['operate_by'] == Auth()->id()) {
                    $isEdit = 1;
                }

                $v['is_edit'] = $isEdit;
            }
        }

        return view('ticket::ticket.list', [
                'list' => $list,
                'filter' => $this->filters(),
                'searchConfig' => $this->searchColumn(),
                'showColumn' => $this->showColumn(),
                'page' => $filters['page'],
                'status_role_array' => $this->check_status_role(),
                'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
                'arrQueueStaff' => $arrQueueStaff,
                'infoStaffQueue' => $infoStaffQueue
            ]
        );
    }

    //  danh sách auto load ticket của tôi
    public function listMyTicket(Request $request)
    {
       
        $filters = $request->only([
            'page',
            'display',
            'search',
            'ticket_type',
            'ticket_issue_id',
            'issule_level',
            'localtion_id',
            'queue_process_id',
            'staff_notification_id',
            'ticket_status_id',
            'date_issue',
            'date_estimated',
            'date_request',
            'ticket_index',
            'operate_by',
            'created_by',
            'priority',
            'handle_by',
            'sort_priority',
            'date_expected',
        ]);
        $filters["processor_by"] = \Auth::id();
         //Lấy cấu hình nhận ticket củng queue
         $mTicketConfig = app()->get(TicketConfigTable::class);
         $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
         // $mQueueStaff = app()->get(QueueStaffTable::class);
         $isAcceptTicketSameQueue = $mTicketConfig->getConfig('is_accept_ticket_same_queue')['ticket_config_value'];
         $arrQueueStaff = [];
         //Lấy thông tin quyền hạn nhân viên
         // $infoStaffQueue = $mQueueStaff->getTicketQueueIdByStaffId(Auth()->id());
         //Lấy queue của nhân viên
         $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());
 
         if (count($getQueue) > 0) {
             foreach ($getQueue as $v) {
                 $arrQueueStaff [] = $v['ticket_queue_id'];
             }
         }
        return view('ticket::ticket.list', [
//                'list' => $this->ticket->getTicketList($filters),
                'list' => $this->ticket->getTicketAssignMe($filters),
                'filter' => $this->filters(),
                'showColumn' => $this->showColumnMyTicket(),
                'page' => $filters['page'],
                'status_role_array' => $this->check_status_role(),
                'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
                'arrQueueStaff' => $arrQueueStaff
            ]
        );
    }

    //  danh sách auto load ticket của tôi
    public function listTicketCreated(Request $request)
    {
          //Lấy cấu hình nhận ticket củng queue
          $mTicketConfig = app()->get(TicketConfigTable::class);
          $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
          // $mQueueStaff = app()->get(QueueStaffTable::class);
          $isAcceptTicketSameQueue = $mTicketConfig->getConfig('is_accept_ticket_same_queue')['ticket_config_value'];
          $arrQueueStaff = [];
          //Lấy thông tin quyền hạn nhân viên
          // $infoStaffQueue = $mQueueStaff->getTicketQueueIdByStaffId(Auth()->id());
          //Lấy queue của nhân viên
          $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());
  
          if (count($getQueue) > 0) {
              foreach ($getQueue as $v) {
                  $arrQueueStaff [] = $v['ticket_queue_id'];
              }
          }
        $filters = $request->only([
            'page',
            'display',
            'search',
            'ticket_type',
            'ticket_issue_id',
            'issule_level',
            'localtion_id',
            'queue_process_id',
            'staff_notification_id',
            'ticket_status_id',
            'date_issue',
            'date_estimated',
            'date_request',
            'processor_by',
            'ticket_index',
            'operate_by',
            'created_by',
            'priority',
            'handle_by',
            'sort_priority',
            'date_expected',
        ]);
        $filters["created_by"] = \Auth::id();
        return view('ticket::ticket.list', [
                'list' => $this->ticket->getTicketList($filters),
                'filter' => $this->filters(),
                'showColumn' => $this->showColumnMyTicket(),
                'page' => $filters['page'],
                'status_role_array' => $this->check_status_role(),
                'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
                'arrQueueStaff' => $arrQueueStaff
            ]
        );
    }

    public function getRequestOption(Request $request)
    {
        $ticket_type_id = $request->ticket_type_id;
        $ticket_issue_id = $request->ticket_issue_id;
        $queue_process_id = $request->queue_process_id;
        $ticket_id = $request->ticket_id;
        $html = '';
        $data = [];
        if ($ticket_type_id) {
            $request_list = $this->requestGroup->getItem($ticket_type_id);
            $data = $request_list->issue_group->toArray();
            $html = '<option value="">' . __('Chọn yêu cầu') . '</option>';
            if ($data) {
                foreach ($data as $key => $value) {
                    $html .= '<option value="' . $value['ticket_issue_id'] . '">' . $value['name'] . '</option>';
                }
            }
            return response()->json(['status' => 1, 'html' => $html]);

        } elseif ($ticket_issue_id) {
            $request_list = $this->requests->getItem($ticket_issue_id);
            return response()->json(['status' => 1, 'level' => $request_list->level]);
        } elseif ($queue_process_id) {
            // load nhân viên chủ trì + nhân viên xử lý theo queue
            $request_list['operate_by'] = '';
            $request_list['processor'] = '';
            $operater_option = $this->queueStaff->getQueueOption($queue_process_id, 2);
            $processor_option = $this->queueStaff->getQueueOption($queue_process_id, 1);
            if ($operater_option) {
                $request_list['operate_by'] .= '<option value="">' . __('Chọn nhân viên chủ trì') . '</option>';
                foreach ($operater_option as $staff_info) {
                    $request_list['operate_by'] .= '<option value="' . $staff_info->staff_id . '">' . $staff_info->staff->full_name . '</option>';
                }
            }
            if ($processor_option) {
                $request_list['processor'] .= '';
                foreach ($processor_option as $staff_info) {
                    $request_list['processor'] .= '<option value="' . $staff_info->staff_id . '">' . $staff_info->staff->full_name . '</option>';
                }
            }
            return response()->json(['status' => 1, 'data' => $request_list]);
        }
        if ($ticket_id) {
            // load nhân viên chủ trì + nhân viên xử lý theo queue
            $ticket = $this->ticket->getItem($ticket_id);
            $queue_process_id = $ticket->queue_process_id;
            $ticket_issue_id = $ticket->ticket_issue_id;
            $request_list['operate_by'] = '';
            $request_list['processor'] = '';
            $operater_option = $this->queueStaff->getQueueOption($queue_process_id, 2);
            $processor_option = $this->queueStaff->getQueueOption($queue_process_id, 1);
            if ($operater_option) {
                foreach ($operater_option as $staff_info) {
                    $selected = '';
                    if ($ticket->operate_by == $staff_info->staff_id) {
                        $selected = ' selected';
                    }
                    $request_list['operate_by'] .= '<option value="' . $staff_info->staff_id . '"' . ($selected) . '>' . (isset($staff_info->staff->full_name) ? $staff_info->staff->full_name : '') . '</option>';
                }
            }
            if ($processor_option) {
                // danh sách
                $list_processor = \DB::table('ticket_processor')->where('ticket_id', $ticket_id)->pluck('process_by')->toArray();
                foreach ($processor_option as $staff_info) {
                    $selected = '';
                    if (in_array($staff_info->staff_id, $list_processor)) {
                        $selected = ' selected';
                    }
                    $request_list['processor'] .= '<option value="' . $staff_info->staff_id . '"' . ($selected) . '>' . (isset($staff_info->staff->full_name) ? $staff_info->staff->full_name : '') . '</option>';
                }
            }
            if ($ticket_issue_id) {
                $request_list['ticket_issue_id'] = $ticket_issue_id;
            }
            return response()->json(['status' => 1, 'data' => $request_list, 'ticket_status' => $ticket->ticket_status_id]);
        }
    }

    public function addAction($id = null, Request $request)
    {
        $params = $request->all();

        $contractPartnerId = "";
        $contractId = "";
        $check_done_status = "";
        $mContractPartner = new ContractPartnerTable();
        if (isset($params['contract'])) {
            $contractId = $params['contract'];
            $dataPartner = $mContractPartner->getPartnerByContract($contractId);
            $contractPartnerId = $dataPartner['partner_object_id'];
        }
        $customer = new Customers;
        $item = $this->ticket->getItem($id);
        if (isset($item->ticket_status_id) && in_array($item->ticket_status_id, [3, 4])) {
            if ($item->ticket_status_id == 3 && $item->created_by == \Auth::id()) {
                $check_done_status = ' disabled';
            } elseif ($this->checkRoleStatus($item->ticket_status_id)) {
                return redirect()->route('ticket');
            } else {
                return redirect()->route('ticket.detail', $id);
            }
        }

        if ($item == null) {
            $item = [
                "ticket_id" => "",
                "localtion_id" => "",
                "ticket_issue_id" => "",
                "ticket_type" => "",
                "issule_level" => "",
                "priority" => "",
                "title" => "",
                "description" => "",
                "customer_id" => "",
                "ticket_issue_group_id" => "",
                "customer_address" => "",
                "staff_notification_id" => "",
                "date_issue" => "",
                "date_estimated" => "",
                "date_expected" => "",
                "date_request" => "",
                "queue_process_id" => "",
                "operate_by" => "",
                "handle_by" => "",
                "image" => "",
                "ticket_code" => "",
                "ticket_status_id" => "",
                "created_at" => "",
            ];
        }
        $filters['get_option'] = 1;
        $checkRoleEdit = true;
        $listAcceptance = [];
        if ($id != null) {
            $listAcceptance = $this->ticket->getListAcceptance($id);
            $checkRoleEdit = $this->checkRoleEdit($id);
        }
        $checkRoleMaterialStatus = [2, 4];
        $checkRoleAcceptanceStatus = [3, 4];

        $infoCustomer = null;

        if (isset($request->customer_id) && $request->customer_id != null) {
            $mCustomer = app()->get(Customers::class);
            //Lấy thông tin KH
            $infoCustomer = $mCustomer->getItem($request->customer_id);
        }

        $dataOrder = null;
        $textOrderDetail = null;

        if (isset($params['order_id']) && $params['order_id'] != null) {
            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);

            //Lấy thông tin đơn hàng
            $dataOrder = $infoOrder = $mOrder->orderInfo($params['order_id']);

            if ($dataOrder['customer_type'] == 'bussiness') {
                $dataOrder['date_finish'] = Carbon::parse($dataOrder['created_at'])->addMonths(1)->format('Y-m-d H:i:s');
            } else {
                $dataOrder['date_finish'] = Carbon::parse($dataOrder['created_at'])->addDays(1)->format('Y-m-d H:i:s');
            }
            //Lấy thông tin chi tiết đơn hàng
            $getOrderDetail = $mOrderDetail->orderDetail($params['order_id']);

            if (count($getOrderDetail) > 0) {
                foreach ($getOrderDetail as $k => $v) {
                    $textOrderDetail .= $v['object_name'];

                    if ($k + 1 < count($getOrderDetail)) {
                        $textOrderDetail .= ', ';
                    }
                }
            }
        }

        return view('ticket::ticket.edit', [
            'staff' => $this->staff->getName(),
            'optionProvince' => $this->province->getOptionProvince(),
            'requests' => $this->requests->getName(),
            'requestGroup' => $this->requestGroup->getName(),
            'customer' => $customer->getFullOption(),
            'getAdress' => $customer->getAdress(),
            'queue' => $this->queue->getName(),
            'item' => $item,
            'listTicket' => $this->ticket->getTicketCode(),
            'listMaterial' => $this->listMaterial->getListProductInventory($filters),
            'statusMaterialItem' => $this->statusMaterialItem(),
            'filter' => $this->statusMaterialItem(),
            'listAcceptance' => $listAcceptance,
            'checkRoleMaterialStatus' => $checkRoleMaterialStatus,
            'checkRoleAcceptanceStatus' => $checkRoleAcceptanceStatus,
            'checkRoleEdit' => $checkRoleEdit,
            'ticketStatusList' => $this->ticketStatus->getName(),
            'contractPartnerId' => $contractPartnerId,
            'contractId' => $contractId,
            'check_done_status' => $check_done_status,
            'listIncurred' => $this->acceptance->listIncurredByTicketId($id),
            'listWarehouses' => $this->getWarehousesOption(),
            'id' => $id,
            'infoCustomer' => $infoCustomer,
            'infoOrder' => $dataOrder,
            'textOrderDetail' => $textOrderDetail
        ]);
    }

    public function submitAction(Request $request)
    {
        $id_check = $request->ticket_id;
        $image = '';
        if ($request->image && $request->image != 'undefined') {
            $image = $request->image;
        }
        $request_process_time = $this->requests->getItem($request->ticket_issue_id)->process_time;
        // thời gian bắt buộc hoàn thành lấy từ ngày tạo + thời gian xử lý của yêu cầu created_at
        $date_expected = Carbon::now()->addHour($request_process_time);
        $date_request = $request->date_request;
        if ($date_request != null) {
            $date_request = Carbon::createFromFormat("d/m/Y H:i", $request->date_request)->format("Y-m-d H:i:s");
        }
        if ($request->created_at) {
            $date_expected = Carbon::parse($request->created_at)->addHour($request_process_time);
        }
        $data = [
            "localtion_id" => $request->localtion_id,
            "ticket_issue_id" => $request->ticket_issue_id,
            "ticket_type" => $request->ticket_type,
            "issule_level" => $request->issule_level,
            "date_estimated" => $request->date_estimated ? Carbon::createFromFormat("d/m/Y H:i", $request->date_estimated)->format("Y-m-d H:i:s") : null,
            "priority" => $request->priority,
            "title" => $request->title,
            "description" => $request->description,
            "customer_id" => $request->customer_id,
            "ticket_issue_group_id" => $request->ticket_issue_group_id,
            "customer_address" => $request->customer_address,
            "staff_notification_id" => $request->staff_notification_id != null ? $request->staff_notification_id : Auth::id(),
            "date_issue" => Carbon::createFromFormat("d/m/Y H:i", $request->date_issue)->format("Y-m-d H:i:s"),
            "date_expected" => $date_expected,
            "date_request" => $date_request,
            "queue_process_id" => $request->queue_process_id,
            "operate_by" => $request->operate_by,
            // "image" => $image,
            "ticket_status_id" => isset($request->ticket_status_id) ? $request->ticket_status_id : 1,
            "updated_by" => Auth::id()
        ];
        if ($id_check) {
            if (isset($request->fileTicket) && count($request->fileTicket)) {
                $this->ticketFile->removeFile($id_check, 'ticket');
                foreach ($request->fileTicket as $v) {
                    $arrfileTicket = [
                        "ticket_id" => $id_check,
                        "type" => "file",
                        "path" => $v,
                        'group' => 'ticket',
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "created_by" => Auth::id()
                    ];
                    //Thêm file kèm theo
                    $this->ticketFile->add($arrfileTicket);
                }
            }
            if (isset($request->image) && $request->image) {
                $this->ticketFile->removeFile($id_check, 'image');
                foreach ($request->image as $v) {
                    $arrImageTicket = [
                        "ticket_id" => $id_check,
                        "type" => "image",
                        "group" => "image",
                        "path" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "created_by" => Auth::id(),
                    ];
                    //Thêm image kèm theo
                    $this->ticketFile->add($arrImageTicket);
                }
            }
            if (isset($request->processor) && ($request->processor)) {
                $this->ticketProcessor->removeFile($id_check);
                foreach ($request->processor as $v) {
                    $arrProcessor = [
                        "ticket_id" => $id_check,
                        "name" => "",
                        "process_by" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "created_by" => Auth::id(),
                    ];
                    //Thêm người xử lý
                    $this->ticketProcessor->add($arrProcessor);
                }
            }
            $item = $this->ticket->getItem($id_check);

            $finish = 0;
            $close = 0;
            if ($data['ticket_status_id'] != $item['ticket_status_id']) {
                if ($data['ticket_status_id'] == 3) {
                    $finish = 1;
                }

                if ($data['ticket_status_id'] == 4) {
                    $close = 1;
                }
            }

            $data['updated_by'] = Auth::id();
            if ($request->ticket_status_id == TICKET_STATUS_REOPEN) {
                $data['count_opened'] = $item->count_opened + 1;
            }

            if (isset($request->ticket_status_id) && $request->ticket_status_id == 2) {
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã chuyển trạng thái ticket sang đang xử lý';
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has changed the ticket status to processing';
                $this->createHistory($note, $note_en, $id_check);
            }
            if (isset($request->ticket_status_id) && $request->ticket_status_id == 3) {
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã hoàn tất ticket';
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has completed the ticket';
                $this->createHistory($note, $note_en, $id_check);
            }
            if (isset($request->ticket_status_id) && $request->ticket_status_id == 4) {
                $data['date_finished'] = Carbon::now()->format("Y-m-d H:i:s");
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã đóng ticket';
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' closed the ticket';
                $this->createHistory($note, $note_en, $id_check);
            }
            if (isset($request->ticket_status_id) && $request->ticket_status_id == 5) {
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã hủy ticket';
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' canceled the ticket';
                $this->createHistory($note, $note_en, $id_check);
            }
            if (isset($request->ticket_status_id) && $request->ticket_status_id == 6) {
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã chuyển trạng thái ticket sang reopen';
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has changed ticket status to reopen';
                $this->createHistory($note, $note_en, $id_check);
            }
            //Chỉnh sửa ticket
            $this->ticket->edit($data, $id_check);

//            if ($this->ticket->edit($data, $id_check)) {
//                return response()->json(['status' => 1]);
//            }

            $mNoti = new SendNotificationApi();
            $listCustomer = $this->getListStaff($id_check);
            foreach ($listCustomer as $itemCustomer) {
                $keyNoti = '';
                if ($finish == 1 || $close == 1) {
                    if ($finish == 1) {
                        if ($itemCustomer == $item['operate_by']) {
                            $keyNoti = 'ticket_finish_operater';
                        } else {
                            $keyNoti = 'ticket_finish_processor';
                        }
                    }

                    if ($close == 1) {
                        if ($itemCustomer == $item['operate_by']) {
                            $keyNoti = 'ticket_close_operater';
                        } else {
                            $keyNoti = 'ticket_close_processor';
                        }
                    }

                    $mNoti->sendStaffNotification([
                        'key' => $keyNoti,
                        'customer_id' => $itemCustomer,
                        'object_id' => $id_check
                    ]);
                } else {
                    $mNoti->sendStaffNotification([
                        'key' => 'ticket_edit',
                        'customer_id' => $itemCustomer,
                        'object_id' => $id_check
                    ]);
                }
            }
            if ($finish == 1) {
                $mNoti->sendStaffNotification([
                    'key' => 'ticket_finish_processor',
                    'customer_id' => $item['created_by'],
                    'object_id' => $id_check
                ]);
            }
            return response()->json(['status' => 1]);
//            return response()->json(['status' => 0]);
        } else {
            $ticket_code = $this->generateTicketCode();
            $data['ticket_code'] = $ticket_code;
            $data['created_by'] = Auth::id();
            //Thêm ticket
            $id_check = $this->ticket->add($data);

            $mContract = new ContractTable();

            if (isset($request->contract_id) && $request->contract_id != '') {
                $mContract->edit([
                    'ticket_code' => $ticket_code
                ], $request->contract_id);
            }

            if ($id_check) {
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã tạo ticket';
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . 'created a ticket';
                $this->createHistory($note, $note_en, $id_check);

                if (isset($request->fileTicket) && count($request->fileTicket)) {
                    $this->ticketFile->removeFile($id_check, 'ticket');
                    foreach ($request->fileTicket as $v) {
                        $arrfileTicket = [
                            "ticket_id" => $id_check,
                            "type" => "file",
                            "group" => "ticket",
                            "path" => $v,
                            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "updated_by" => Auth::id(),
                        ];
                        //Thêm file kèm theo
                        $this->ticketFile->add($arrfileTicket);
                    }
                }
                if (isset($request->image) && $request->image) {
                    $this->ticketFile->removeFile($id_check, 'image');
                    foreach ($request->image as $v) {
                        $arrfileTicket = [
                            "ticket_id" => $id_check,
                            "type" => "image",
                            "group" => "image",
                            "path" => $v,
                            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "created_by" => Auth::id(),
                            "updated_at" => Carbon::now(),
                            "updated_by" => Auth::id()
                        ];
                        //Thêm image kèm theo
                        $this->ticketFile->add($arrfileTicket);
                    }
                }
                if (isset($request->processor) && ($request->processor)) {
                    $this->ticketProcessor->removeFile($id_check);
                    foreach ($request->processor as $v) {
                        $arrProcessor = [
                            "ticket_id" => $id_check,
                            "name" => "",
                            "process_by" => $v,
                            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "updated_by" => Auth::id(),
                        ];
                        //Thêm người xử lý
                        $this->ticketProcessor->add($arrProcessor);
                    }
                }

                $mNoti = new SendNotificationApi();
                $listCustomer = $this->getListStaff($id_check);
                foreach ($listCustomer as $item) {
                    $mNoti->sendStaffNotification([
                        'key' => $data['operate_by'] == $item ? 'ticket_operater' : 'ticket_assign',
                        'customer_id' => $item,
                        'object_id' => $id_check
                    ]);
                }
                if (isset($request->ticket_status_id) && $request->ticket_status_id == 1) {
                    $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . __(' đã tạo ticket');
                    $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . 'created a ticket';
                    $this->createHistory($note, $note_en, $id_check);
                }

                if (isset($request->operate_by) && $request->operate_by != null) {
                    //Bắn zns cho nhân viên chủ trì
                    FunctionSendNotify::dispatch([
                        'type' => SEND_ZNS_CUSTOMER,
                        'key' => 'create_ticket_user_support',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_check,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }

                return response()->json(['status' => 1]);
            }
            return response()->json(['status' => 0]);
        }
    }

    public function getListStaff($ticketId)
    {
        return $this->ticket->getListStaff($ticketId);
    }

    public function detailAction(Request $request, $id = 0)
    {
        $customer = new Customers;
        $item = $this->ticket->getItem($id);
        if ($item == null) {
            $item = [
                "ticket_id" => "",
                "localtion_id" => "",
                "ticket_issue_id" => "",
                "ticket_type" => "",
                "issule_level" => "",
                "priority" => "",
                "title" => "",
                "description" => "",
                "customer_id" => "",
                "ticket_issue_group_id" => "",
                "customer_address" => "",
                "staff_notification_id" => "",
                "date_issue" => "",
                "date_estimated" => "",
                "date_expected" => "",
                "queue_process_id" => "",
                "operate_by" => "",
                "handle_by" => "",
                "image" => "",
            ];
        }
        $filters['get_option'] = 1;
        $listAcceptance = $this->ticket->getListAcceptance($id);
        $checkRoleEdit = $this->checkRoleEdit($id);
        $checkRoleMaterialStatus = [2, 4];
        $checkRoleAcceptanceStatus = [3, 4];

        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);
        $mTicketConfig = app()->get(TicketConfigTable::class);
        $mQueueStaff = app()->get(QueueStaffTable::class);

        //Lấy thông tin quyền hạn nhân viên
        $infoStaffQueue = $mQueueStaff->getTicketQueueIdByStaffId(Auth()->id());
        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        $arrQueueStaff = [];

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        //Lấy cấu hình nhận ticket củng queue
        $isAcceptTicketSameQueue = $mTicketConfig->getConfig('is_accept_ticket_same_queue')['ticket_config_value'];

        $isEdit = 0;

        if (($item['process_by'] != null && in_array(Auth()->id(), explode(",", $item['process_by']))) || $item['created_by'] == Auth()->id() || $item['operate_by'] == Auth()->id()) {
            $isEdit = 1;
        }
        return view('ticket::ticket.detail', [
            'staff' => $this->staff->getName(),
            'optionProvince' => $this->province->getOptionProvince(),
            'requests' => $this->requests->getName(),
            'requestGroup' => $this->requestGroup->getAll(),
            'customer' => $customer->getName(),
            'queue' => $this->queue->getName(),
            'item' => $item,
            'listTicket' => $this->ticket->getTicketCode(),
            'listMaterial' => $this->listMaterial->getListProductInventory($filters),
            'statusMaterialItem' => $this->statusMaterialItem(),
            'filter' => $this->statusMaterialItem(),
            'listAcceptance' => $listAcceptance,
            'checkRoleMaterialStatus' => $checkRoleMaterialStatus,
            'checkRoleAcceptanceStatus' => $checkRoleAcceptanceStatus,
            'checkRoleEdit' => $checkRoleEdit,
            'ticketStatusList' => $this->ticketStatus->getName(),
            'listIncurred' => $this->acceptance->listIncurredByTicketId($id),
            'listWarehouses' => $this->getWarehousesOption(),
            'isAcceptTicketSameQueue' => $isAcceptTicketSameQueue,
            'arrQueueStaff' => $arrQueueStaff,
            'isEdit' => $isEdit,
            'infoStaffQueue' => $infoStaffQueue
        ]);
    }

    public function removeAction($id)
    {
        $this->ticket->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function rating(Request $request)
    {
        $id = $request->ticket_id;
        if ($id) {
            $arrRating = [
                "ticket_id" => $id,
                "point" => $request->point,
                "description" => $request->description,
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "created_by" => Auth::id(),
            ];
            $this->ticketRating->removeFile($id);
            if ($this->ticketRating->add($arrRating)) {
                $mNoti = new SendNotificationApi();
                $listCustomer = $this->getListStaff($id);

                foreach ($listCustomer as $itemStaff) {
                    $mNoti->sendStaffNotification([
                        'key' => 'ticket_rating',
                        'customer_id' => $itemStaff,
                        'object_id' => $id
                    ]);
                }
                return response()->json(['status' => 1]);
            }
            return response()->json(['status' => 0]);
        }
    }

    public function saveConfig(Request $request)
    {
        $data = [
            'route_name' => 'ticket',
            'search' => $request->search,
            'column' => $request->column,
        ];
        $data = serialize($data);
        Cookie::queue('ticket_token', $data, 3600);
        return response()->json(['status' => 1, 'data' => $data]);
    }

    public function report(Request $request)
    {
        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);

        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        $arrQueueStaff = [];

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        $filters['arr_queue_staff'] = $arrQueueStaff;

        $quantity_ticket = [
            "total" => $this->ticket->getTicketByStatus(array(1, 2, 3, 4, 5, 6, 7), $filters),
            "new" => $this->ticket->getTicketByStatus(array(1), $filters),
            "inprocess" => $this->ticket->getTicketByStatus(array(2), $filters),
            "done" => $this->ticket->getTicketByStatus(array(3), $filters),
            "close" => $this->ticket->getTicketByStatus(array(4), $filters),
            "cancle" => $this->ticket->getTicketByStatus(array(5), $filters),
            "overtime" => $this->ticket->getTicketByStatus(array(7), $filters),
            "reopen" => $this->ticket->getTicketByStatus(array(6), $filters),
        ];
        return view('ticket::ticket.report.report', [
            'staff' => $this->staff->getName(),
            'ticketStatus' => $this->ticketStatus->getName(),
            'queue' => $this->queue->getName(),
            'requestGroup' => $this->requestGroup->getName(),
            'quantity_ticket' => $quantity_ticket,
        ]);
    }

    public function getChart(Request $request)
    {
        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);

        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        $arrQueueStaff = [];

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        $time = $request->time;
        $filters['created_by'] = $request->staff_id;
        $filters['ticket_status_id'] = $request->ticket_status_id;
        $filters['queue_process_id'] = $request->queue_process_id;
        $filters['ticket_type'] = $request->ticket_issue_group_id;
        $filters['arr_queue_staff'] = $arrQueueStaff;

        // Array days
        $arrayDate = [];
        $arr_filter = explode(" - ", $time);
        if ($time) {
            $filters['time'] = $time;
            $filters['created_at'] = $time;
        } else {
            $time = [];
        }
        if ($request->page) {
            $filters['page'] = $request->page;
            $table = $this->getTableReport($filters);
            return response()->json(['table' => $table]);
        }
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $arrayCategories = $this->createDateRangeArray($startTime, $endTime, 'd/m/Y');
        // Data series
        $list_status = $this->ticketStatus->getName();

        $status = $this->ticket->dataSeries($filters);
//        if ($status) {
//            $status = array_column($status, null, 'ticket_status_id');
//        } else {
//            $status = [];
//        }

        $quantity = [];

        $series = [];
        $list_status [7] = __('Quá hạn');
        $list_status [8] = __('Tổng');

        foreach ($list_status as $key => $val) {

            $dataChild = [
                "visible" => false,
                "name" => $val,
                "data" => []
            ];
            if (in_array($key, [6, 7, 8])) {
                $dataChild['visible'] = true;
            }
            if ($key == 8) {
                $dataChild['index'] = -2;
                $quantity[$key] = $this->ticket->getTicketByStatus(array(1, 2, 3, 4, 5), $filters);
            } else {
                $quantity[$key] = $this->ticket->getTicketByStatus(array($key), $filters);
            }
            foreach ($arrayCategories as $date) {
                $count = 0;
                foreach ($status as $v) {
                    $filters_overtime = $filters;
                    $filters_overtime['created_filter'] = $date;
                    if ($v['created_group'] == $date) {
                        if ($key != 8) {
                            $count = $this->ticket->getTicketByStatus(array($key), $filters_overtime);
                        } elseif ($key == 8) {
                            $count = $this->ticket->getTicketByStatus(array(1, 2, 3, 4, 5), $filters_overtime);
                        }
                    }
                }

                $dataChild['data'] [] = [
                    $date,
                    $count
                ];
            }

            $series [] = $dataChild;
        }

        $quantity['total'] = array_sum($quantity);
        $series = json_decode(json_encode($series), false);
        $table = $this->getTableReport($request->all());

        return response()->json([
            'arrayCategories' => $arrayCategories,
            'dataSeries' => $series,
            'quantity' => $quantity,
            'table' => $table
        ]);
    }

    public function getTableReport($filters)
    {
        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);

        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        $arrQueueStaff = [];

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        if (!isset($filters['page'])) {
            $filters['page'] = 1;
        }

        $filters['arr_queue_staff'] = $arrQueueStaff;

        return view('ticket::ticket.report.list_report', [
            'list' => $this->ticket->getTicketList($filters),
            'page' => $filters['page']
        ])->render();
    }

    public function listReportKpi($filters)
    {
        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);

        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        $arrQueueStaff = [];

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        if (!isset($filters['page'])) {
            $filters['page'] = 1;
        }

        $filters['arr_queue_staff'] = $arrQueueStaff;

        return view('ticket::ticket.report.list_report_kpi', [
            'list' => $this->ticket->getKPITicketTable($filters),
            'page' => $filters['page']
        ])->render();
    }

    public function reportKPI(Request $request)
    {
        return view('ticket::ticket.report.report_kpi', [
            'staff' => $this->staff->getName(),
            'ticketStatus' => $this->ticketStatus->getName(),
            'queue' => $this->queue->getName(),
            'requestGroup' => $this->requestGroup->getName(),
        ]);
    }

    public function getChartKPI(Request $request)
    {
        $time = $request->time;
        $filters['process_by'] = $request->staff_id;
        $filters['ticket_status_id'] = $request->ticket_status_id;
        $filters['queue_process_id'] = $request->queue_process_id;
        $filters['ticket_type'] = $request->ticket_issue_group_id;
        // Array days
        $arrayDate = [];
        $arr_filter = explode(" - ", $time);
        if ($time) {
            $filters['time'] = $time;
//            $filters['created_at'] = $time;
        } else {
            $time = [];
        }
        if ($request->page) {
            $filters['page'] = $request->page;
            $table = $this->getTableReport($filters);
            return response()->json(['table' => $table]);
        }
        // Data series
        $kpi = $this->ticket->getKPITicket($filters);

        if ($kpi) {
            $kpi = array_column($kpi, null, 'process_by');
        } else {
            $kpi = [];
        }
        $table = $this->listReportKpi($filters);
        $staff = $this->staff->getName();
        $issue = [];// list ticket xử lý sự cố
        $enforce = []; // list ticket triển khai
        $arrayCategories = []; // danh sách nhân viên
        $arrayLine = []; // danh sách đánh giá
        foreach ($kpi as $key => $value) {
            $arrayCategories[] = $staff[$key];
            $arrayLine[] = $value['avg_point'] != null ? $value['avg_point'] : 0;
            $filters['process_by'] = $key;
            $filters['ticket_type'] = 1;
            $issue[] = $this->ticket->countTicketByProcessor($filters);
            $filters['ticket_type'] = 2;
            $enforce[] = $this->ticket->countTicketByProcessor($filters);
        }

        $data = [
            'arrayCategories' => array_values($arrayCategories),
            'issue' => $issue,
            'enforce' => $enforce,
            'arrayLine' => $arrayLine,
        ];
        return response()->json([
            'data' => $data,
            'table' => $table
        ]);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->ticket->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    /**
     * Upload files
     * @param Request $request
     */
    public function uploadFile(Request $request)
    {
        $param = $request->all();
        $data = $this->ticket->uploadFile($param);
        return response()->json($data);
    }

    public function generateTicketCode()
    {
        $type_ticket = 'TKTK';
        $time = date("Ymd");
        $last_id = DB::table('ticket')->latest('ticket_id')->first();
        if ($last_id) {
            $last_id = $last_id->ticket_id;
        } else {
            $last_id = 0;
        }
        $last_id = sprintf("%03d", ($last_id + 1));
        return $type_ticket . '_' . $time . '_' . $last_id;
    }

    public function uploadImageAction(Request $request)
    {
        $data = $this->upload->uploadImage($request->all());

        return response()->json($data);
    }

    public function createHistory($note_vi = "", $note_en = "", $ticketId)
    {
        // Tạo lịch sử
        $history_data = [
            "ticket_id" => $ticketId,
            "note_en" => $note_en,
            "note_vi" => $note_vi,
            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
            "created_by" => Auth::id(),
        ];
        $this->ticketHistory->add($history_data);
    }

    //function upload image
    // public function uploadAction(Request $request)
    // {
    //     $this->validate($request, [
    //         "file_ticket" => "mimes:jpg,jpeg,png,gif,xlsx,doc,docx,ppt,pptx|max:10000"
    //     ], [
    //         "file_ticket.mimes" => __('File không đúng định dạng'),
    //         "file_ticket.max" => __('File quá lớn')
    //     ]);
    //     if ($request->file('file') != null) {
    //         $file = $this->uploadImageTemp($request->file('file'));
    //         return response()->json(["file" => $file, "success" => "1"]);
    //     }
    // }

    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_ticket." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH . "/" . $file_name, file_get_contents($file));
        return $file_name;
    }

    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = TICKET_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(TICKET_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    //function delete image
    public function deleteTempFileAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

    // hiển thị cấu hình tìm kiếm
    public function searchColumn()
    {
        /*
         Có 3 loại:
            - text
            - datepicker
            - select2 
        */

        // return data search

        $data = [
            1 => [
                "active" => 1,
                "placeholder" => __("Nhập thông tin tìm kiếm"),
                "type" => "text",
                "class" => "form-control",
                "name" => "search",
                "id" => "search",
                "data" => "",
                "nameConfig" => __("Thông tin tìm kiếm"),
            ],
            2 => [
                "active" => 1,
                "placeholder" => __("Chọn loại yêu cầu"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "ticket_type",
                "id" => "ticket_type",
                "data" => getTypeTicket(),
                "nameConfig" => __("Loại yêu cầu"),
            ],
            3 => [
                "active" => 1,
                "placeholder" => __("Chọn yêu cầu"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "ticket_issue_id",
                "id" => "ticket_issue_id",
                "data" => $this->requests->getName(),
                "nameConfig" => __("Yêu cầu"),
            ],
            4 => [
                "active" => 1,
                "placeholder" => __("Chọn độ ưu tiên"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "sort_priority",
                "id" => "sort_priority",
                "data" => [
                    "" => "Mặc định",
                    "DESC" => "Từ thấp tới cao",
                    "ASC" => "Từ cao tới thấp",
                ],
                "nameConfig" => __("Độ ưu tiên"),
            ],
            5 => [
                "active" => 1,
                "placeholder" => __("Chọn cấp độ yêu cầu"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "issule_level",
                "id" => "issule_level",
                "data" => levelIssue(),
                "nameConfig" => __("Cấp độ yêu cầu"),
            ],
            6 => [
                "active" => 0,
                "placeholder" => __("Chọn thành phố"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "localtion_id",
                "id" => "localtion_id",
                "data" => $this->province->getOptionProvince(),
                "nameConfig" => __("Thành phố"),
            ],
            7 => [
                "active" => 1,
                "placeholder" => __("Chọn queue"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "queue_process_id",
                "id" => "queue_process_id",
                "data" => $this->queue->getName(),
                "nameConfig" => __("Queue"),
            ],
            8 => [
                "active" => 1,
                "placeholder" => __("Chọn nhân viên thông báo"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "staff_notification_id",
                "id" => "staff_notification_id",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Nhân viên thông báo"),
            ],
            9 => [
                "active" => 1,
                "placeholder" => __("Chọn trạng thái"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "ticket_status_id",
                "id" => "ticket_status_id",
                "data" => $this->ticketStatus->getName(),
                "nameConfig" => __("Trạng thái"),
            ],
            10 => [
                "active" => 1,
                "placeholder" => __("Thời gian phát sinh"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "date_issue",
                "id" => "date_issue",
                "data" => "",
                "nameConfig" => __("Thời gian phát sinh"),
            ],
            11 => [
                "active" => 1,
                "placeholder" => __("Thời gian bắt buộc hoàn thành"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "date_expected",
                "id" => "date_expected",
                "data" => "",
                "nameConfig" => __("Thời gian bắt buộc hoàn thành"),
            ],
            12 => [
                "active" => 1,
                "placeholder" => __("Chọn nhân viên xử lý"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "handle_by",
                "id" => "handle_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Nhân viên xử lý"),
            ],
//            13 => [
//                "active" => 1,
//                "placeholder" => __("Thời gian tạo"),
//                "type" => "daterange_picker",
//                "class" => "form-control m-input daterange-picker",
//                "name" => "created_at",
//                "id" => "created_at",
//                "data" => "",
//                "nameConfig" => __("Thời gian tạo"),
//            ],
        ];
        $config = Cookie::get('ticket_token');
        if ($config) {
            $config = unserialize($config);
            foreach ($data as $key => $value) {
                if (!in_array($key, $config['search'])) {
                    $data[$key]['active'] = 0;
                } else {
                    $data[$key]['active'] = 1;
                }
            }
        }
        return $data;
    }

    // hiển thị cấu hình table
    public function showColumn()
    {
        $data = [
            1 => [
                "name" => "#",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("ID"),
                "column_name" => "count",
                "type" => "label"
            ],
            2 => [
                "name" => "",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Chức năng"),
                "type" => "function"
            ],
            3 => [
                "name" => __("Mã Ticket"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Mã Ticket"),
                "view_detail" => 1,
                "type" => "link",
                "column_name" => "ticket_code",
            ],
            4 => [
                "name" => __("Tiêu đề"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiêu đề"),
                "view_detail" => 0,
                "type" => "label",
                "column_name" => "title",
            ],
            5 => [
                "name" => __("Độ ưu tiên"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Độ ưu tiên"),
                "column_name" => "priority",
                "type" => "label"
            ],
            6 => [
                "name" => __("Loại yêu cầu"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Loại yêu cầu"),
                "type" => "label",
                "column_name" => "ticket_type",
            ],
            7 => [
                "name" => __("Yêu cầu"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Yêu cầu"),
                "type" => "label",
                "column_name" => "ticket_issue_id",
            ],
            8 => [
                "name" => __("Cấp độ yêu cầu"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Cấp độ yêu cầu"),
                "type" => "label",
                "column_name" => "issule_level",
            ],
            9 => [
                "name" => __("Thời gian phát sinh"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Thời gian phát sinh"),
                "type" => "label",
                "column_name" => "date_issue",
            ],
            10 => [
                "name" => __("Thời gian bắt buộc hoàn thành"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Thời gian bắt buộc hoàn thành"),
                "type" => "label",
                "column_name" => "date_expected",
            ],
            11 => [
                "name" => __("Queue"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Queue"),
                "type" => "label",
                "column_name" => "queue_process_id",

            ],
            12 => [
                "name" => __("Nhân viên chủ trì"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Nhân viên chủ trì"),
                "type" => "label",
                "column_name" => "operate_by",
            ],
            13 => [
                "name" => __("Đánh giá"),
                "class" => "text-center text-nowrap",
                "active" => 1,
                "nameConfig" => __("Đánh giá"),
                "type" => "label",
                "column_name" => "star",
                "attribute" => [
                    "white-space" => "nowrap"
                ]
            ],
            14 => [
                "name" => __("Trạng thái"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "column_name" => "ticket_status_id",
                "type" => "status",
                "option" => [
                    '1' => [
                        'name' => __('Mới'),
                        'color' => 'success'
                    ],
                    '2' => [
                        'name' => __('Đang xử lý'),
                        'color' => 'warning'
                    ],
                    '3' => [
                        'name' => __('Hoàn tất'),
                        'color' => 'info'
                    ],
                    '4' => [
                        'name' => __('Đóng'),
                        'color' => 'focus'
                    ],
                    '5' => [
                        'name' => __('Hủy'),
                        'color' => 'danger'
                    ],
                    '6' => [
                        'name' => __('Reopen'),
                        'color' => 'primary'
                    ],
                    '7' => [
                        'name' => __('Quá hạn'),
                        'color' => 'metal'
                    ],
                ],
                // thuộc tính khác
                // "attribute" => [
                //     "style" => 'width:80%',
                // ]
            ],

        ];
        $config = Cookie::get('ticket_token');
        if ($config) {
            $config = unserialize($config);
            foreach ($data as $key => $value) {
                if (!in_array($key, $config['column'])) {
                    $data[$key]['active'] = 0;
                }
            }
        }
        return $data;
    }

    // ticket của tôi
    public function showColumnMyTicket()
    {
        $data = [
            1 => [
                "name" => "#",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("ID"),
                "column_name" => "count",
                "type" => "label"
            ],
            2 => [
                "name" => "",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Chức năng"),
                "type" => "function"
            ],
            3 => [
                "name" => __("Mã Ticket"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Mã Ticket"),
                "view_detail" => 1,
                "type" => "link",
                "column_name" => "ticket_code",
            ],
            4 => [
                "name" => __("Tiêu đề"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiêu đề"),
                "view_detail" => 0,
                "type" => "label",
                "column_name" => "title",
            ],
            5 => [
                "name" => __("Độ ưu tiên"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Độ ưu tiên"),
                "column_name" => "priority",
                "type" => "label"
            ],
            6 => [
                "name" => __("Loại yêu cầu"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Loại yêu cầu"),
                "type" => "label",
                "column_name" => "ticket_type",
            ],
            7 => [
                "name" => __("Yêu cầu"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Yêu cầu"),
                "type" => "label",
                "column_name" => "ticket_issue_id",
            ],
            8 => [
                "name" => __("Cấp độ yêu cầu"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Cấp độ yêu cầu"),
                "type" => "label",
                "column_name" => "issule_level",
            ],
            9 => [
                "name" => __("Thời gian phát sinh"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Thời gian phát sinh"),
                "type" => "label",
                "column_name" => "date_issue",
            ],
            10 => [
                "name" => __("Thời gian bắt buộc hoàn thành"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Thời gian bắt buộc hoàn thành"),
                "type" => "label",
                "column_name" => "date_expected",
            ],
            11 => [
                "name" => __("Queue"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Queue"),
                "type" => "label",
                "column_name" => "queue_process_id",

            ],
            12 => [
                "name" => __("Nhân viên chủ trì"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Nhân viên chủ trì"),
                "type" => "label",
                "column_name" => "operate_by",
            ],
            13 => [
                "name" => __("Đánh giá"),
                "class" => "text-center text-nowrap",
                "active" => 1,
                "nameConfig" => __("Đánh giá"),
                "type" => "label",
                "column_name" => "star",
                "attribute" => [
                    "white-space" => "nowrap"
                ]
            ],
            14 => [
                "name" => __("Trạng thái"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "column_name" => "ticket_status_id",
                "type" => "status",
                "option" => [
                    '1' => [
                        'name' => __('Mới'),
                        'color' => 'success'
                    ],
                    '2' => [
                        'name' => __('Đang xử lý'),
                        'color' => 'warning'
                    ],
                    '3' => [
                        'name' => __('Hoàn tất'),
                        'color' => 'info'
                    ],
                    '4' => [
                        'name' => __('Đóng'),
                        'color' => 'focus'
                    ],
                    '5' => [
                        'name' => __('Hủy'),
                        'color' => 'danger'
                    ],
                    '6' => [
                        'name' => __('Reopen'),
                        'color' => 'primary'
                    ],
                    '7' => [
                        'name' => __('Quá hạn'),
                        'color' => 'metal'
                    ],
                ],
                // thuộc tính khác
                // "attribute" => [
                //     "style" => 'width:80%',
                // ]    
            ],

        ];

        return $data;
    }

    function groupArrayByKey($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    function createDateRangeArray($strDateFrom, $strDateTo, $format = 'Y-m-d')
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = [];

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date($format, $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date($format, $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function checkRoleEdit($ticket_id)
    {
        $item = $this->ticket->getItem($ticket_id);
        // kiểm tra trạng thái
        // $item->ticket_status_id;
        return 1;
    }

    protected function getWarehousesOption()
    {
        $mWarehouses = new WarehousesTable;
        return $mWarehouses->getOption();
    }

    // Phân quyền trạng thái
    public function check_status_role()
    {
        return DB::table('ticket_role_status_map')->select('ticket_role_status_map.ticket_status_id')
            ->join('ticket_role as p2', 'p2.ticket_role_id', 'ticket_role_status_map.ticket_role_id')
            ->join('map_role_group_staff as p3', 'p3.role_group_id', 'p2.role_group_id')
            ->where('p3.staff_id', Auth::id())->get()->pluck("ticket_status_id")->toArray();
    }

    // Nếu không có quyền chỉnh sửa trạng thái mà vào chỉnh sửa sẽ redirect ra index ticket
    public function checkRoleStatus($status)
    {
        if (in_array($status, $this->check_status_role())) {
            return false;
        }
        return true;
    }

    /**
     * Export excel ticket
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelAction(Request $request)
    {
        return $this->ticket->exportExcel($request->all());
    }

    /**
     * Load vị trí của ticket
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadLocationAction(Request $request)
    {
        //Lấy dữ liệu vị trí ticket
        $dataLocation = $this->ticket->loadLocation($request->ticket_id);

        $html = \View::make('ticket::ticket.tab-location', [
            'dataLocation' => $dataLocation
        ])->render();

        return response()->json([
            'html' => $html,
            'dataLocation' => $dataLocation
        ]);
    }
    /*
    *Thêm phần comment cho ticket
    *Hieupc
    */

    /**
     * lấy danh sách bình luận
     * @param Request $request
     */
    public function getListComment(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ticket_id;
            $listComment = $this->ticket->getListComment($id);
            $html = \View::make('ticket::ticket.append.list-ticket-comment', [
                'listComment' => $listComment,
            ])->render();
            return response()->json([
                'html' => $html

            ]);
        }
    }

    /**
     * Thêm bình luận
     * @param Request $request
     */
    public function addComment(Request $request)
    {
        $param = $request->all();
        $data = $this->ticket->addComment($param);
        return response()->json($data);
    }

    /**
     * hiển thị form comment
     * @param Request $request
     */
    public function showFormComment(Request $request)
    {
        $param = $request->all();
        $data = $this->ticket->showFormComment($param);
        return response()->json($data);
    }
}