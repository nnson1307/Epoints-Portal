<?php

namespace Modules\Ticket\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Ticket\Http\Api\SendNotificationApi;
use Modules\Ticket\Repositories\Material\MaterialRepositoryInterface;
use Modules\Ticket\Repositories\MaterialDetail\MaterialDetailRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\Facades\Cookie;
use Modules\Ticket\Models\ProductInventoryTable;
use Modules\Ticket\Models\ProductInventoryTable2;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Modules\Ticket\Models\TicketHistoryTable;
use Modules\Ticket\Models\WarehousesTable;


class MaterialController extends Controller
{
    protected $material;
    protected $staff;
    protected $ticket;
    protected $listMaterial;
    protected $listMaterial2;
    protected $materialDetail;
    protected $ticketHistory;

    public function __construct(
        MaterialRepositoryInterface $material,
        MaterialDetailRepositoryInterface $materialDetail,
        StaffRepositoryInterface $staff,
        TicketRepositoryInterface $ticket,
        ProductInventoryTable $listMaterial,
        ProductInventoryTable2 $listMaterial2,
        TicketHistoryTable $ticketHistory
    )
    {
        $this->material = $material;
        $this->materialDetail = $materialDetail;
        $this->staff = $staff;
        $this->ticket = $ticket;
        $this->listMaterial = $listMaterial;
        $this->listMaterial2 = $listMaterial2;
        $this->ticketHistory = $ticketHistory;
    }

    public function indexAction(Request $request)
    {
        $param = $request->all();
        $ticket_request_material_id = isset($param['ticket_request_material_id']) ? $param['ticket_request_material_id'] : null;
        #lấy danh sách vật tư dạng option,
        $filters = $request->only(['page', 'display', 'search', 'search_keyword', 'proposer_by', 'approved_by', 'status', 'proposer_date', 'description']);
        return view('ticket::material.index', [
            'list' => $this->material->list($filters),
            'filter' => $this->filters(),
            'statusMaterialItem' => $this->statusMaterialItem(),
            'staff' => $this->staff->getName(),
            'searchConfig' => $this->searchColumn(),
            'showColumn' => $this->showColumn(),
            'listTicket' => $this->ticket->getTicketCode(),
            'listTicketAll' => $this->ticket->getName(),
            'listMaterial' => $this->listMaterial->getListProductInventory(['get_option' => 1]),
            'listWarehouses' => $this->getWarehousesOption(),
            'ticket_request_material_id' => $ticket_request_material_id
        ]);
    }

    // lấy danh sách trạng thái vật tư có 3 trạng thái
    protected function filters()
    {
        return [
            '' => __('Chọn trạng thái'),
            'new' => __('Mới'),
            'approve' => __('Đã duyệt'),
            'cancel' => __('Hủy'),
        ];
    }

    protected function getWarehousesOption()
    {
        $mWarehouses = new WarehousesTable;
        return $mWarehouses->getOption();
    }

    protected function statusMaterialItem($filters = [])
    {
        $status = [
            'new' => __('Mới'),
            'approve' => __('Đã duyệt'),
            'cancel' => __('Từ chối'),
        ];
        if (isset($filters['status'])) {
            return $status[$filters['status']];
        }
        return $status;
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'search_keyword', 'proposer_by', 'approved_by', 'status', 'proposer_date', 'description']);
        return view('ticket::material.list', [
                'list' => $this->material->list($filters),
                'filter' => $this->filters(),
                'searchConfig' => $this->searchColumn(),
                'showColumn' => $this->showColumn(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $params = $request->all();
        $code = $this->generateMaterialCode();
        $material_data = [
            'ticket_id' => $request->ticket_code,
            'description' => $request->description,
            'proposer_by' => \Auth::id(),
            'ticket_request_material_code' => $code,
            'proposer_date' => Carbon::now()->format("Y-m-d H:i:s"),
            'status' => 'new',
            'created_by' => \Auth::id(),
            'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
            'updated_by' => Auth::id()
        ];
        $warehouse = $params['warehouse'];
        unset($params['warehouse']);
        unset($params['ticket_code']);
        unset($params['description']);
        unset($params['warehouse_id']);
        unset($params['material']);
        // dd($warehouse);

        if (empty($params)) {
            return response()->json(['status' => 2]);
        }
        $material_id = $this->material->add($material_data);
        if ($material_id) {
            // tạo lịch sử
            $item = $this->material->getItem($material_id);
            $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã tạo phiếu yêu cầu vật tư ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
            $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has created a requisition for supplies ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
            $this->createHistory($note,$note_en, $item->ticket_id);
            foreach ($params as $key => $value) {
                if ($value != null) {
                    $warehouse_id = isset($warehouse[$key]) ? $warehouse[$key] : '';
                    $product_inventory = \DB::table('product_inventorys')->select('product_inventory_id')->where('warehouse_id', $warehouse_id)->where('product_id', $key)->first();
                    $product_inventory_id = isset($product_inventory->product_inventory_id) ? $product_inventory->product_inventory_id : '';
                    $material_data_detail = [
                        'ticket_request_material_id' => $material_id,
                        'product_id' => $key,
                        'warehouse_id' => $warehouse_id,
                        'product_inventory_id' => $product_inventory_id,
                        'quantity' => $value,
                        'quantity_approve' => $value,
                        'status' => 'new',
                        'created_by' => \Auth::id(),
                        'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                    ];
                    $id = $this->materialDetail->add($material_data_detail);
                }
            }

            $mNoti = new SendNotificationApi();
            $listCustomer = $this->getListStaff($request->ticket_code);
            foreach ($listCustomer as $item) {
                $mNoti->sendStaffNotification([
                    'key' => 'request_material_create',
                    'customer_id' => $item,
                    'object_id' => $material_id
                ]);
            }

            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0]);

    }

    public function getListStaff($ticketId)
    {
        return $this->ticket->getListStaff($ticketId);
    }


    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ticket_request_material_id;
            $item = $this->material->getItem($id);
            $itemDetail = $this->materialDetail->getItemByMaterialId($id);
            // dd($item,$itemDetail);
            if ($itemDetail != null) {
                $itemDetail = $itemDetail->toArray();
            }
            $jsonString = [];
            if ($item) {
                $jsonString = [
                    'ticket_request_material_id' => $id,
                    'ticket_request_material_code' => $item->ticket_request_material_code,
                    'ticket_id' => $item->ticket_id,
                    'description' => $item->description,
                    'proposer_by' => isset($item->proposer->full_name) ? $item->proposer->full_name : __('Không xác định'),
                    'proposer_date' => Carbon::parse($item->proposer_date)->format('d/m/Y H:i'),
                    'status' => $item->status,
                    'material_detail' => $itemDetail
                ];
            }

            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $params = $request->all();
            $material_data = [
                'description' => $request->description,
                // 'approved_by' => \Auth::id(),
                // 'approved_date' => null,
                // 'status' => $request->status_material,
                'updated_by' => \Auth::id(),
                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
            ];
            $status_all = 'new';
            $item = $this->material->getItem($params['ticket_request_material_id']);
            if (isset($params['status_material']) && $params['status_material'] == 'approve') {
                $material_data['approved_by'] = \Auth::id();
                $material_data['approved_date'] = Carbon::now()->format("Y-m-d H:i:s");
                $material_data['status'] = $request->status_material;
                $status_all = 'approve';
                // tạo lịch sử
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã duyệt phiếu yêu cầu vật tư ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' approved the material requisition form ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $this->createHistory($note,$note_en, $item->ticket_id);
            } elseif (isset($params['status_material']) && $params['status_material'] == 'cancel') {
                $material_data['status'] = $request->status_material;
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã hủy phiếu yêu cầu vật tư ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has canceled the request for supplies ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $this->createHistory($note,$note_en, $item->ticket_id);
                $status_all = 'cancel';
            }
            if (!isset($params['status'])) {
                return response()->json(['status' => 2]);
            }
            if ($this->material->edit($material_data, $params['ticket_request_material_id'])) {
                $material_id = $params['ticket_request_material_id'];
                $this->materialDetail->removeByMaterialId($material_id);
                if (isset($params['status'])) {
                    foreach ($params['status'] as $key => $value) {
                        if (isset($params['ticket_request_material_detail_id'][$key])) {
                            $material_data_detail = [
                                'quantity' => $params[$key],#số lượng duyệt
                                'status' => $params['status'][$key],
                                'updated_by' => \Auth::id(),
                                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                            ];
                            if ($this->materialDetail->edit($material_data_detail, $params['ticket_request_material_detail_id'][$key])) {
                                if($params['status'][$key] == 'approve' || $status_all =='approve'){
                                    $materialItem = $this->materialDetail->getItem($params['ticket_request_material_detail_id'][$key]);
                                    $product_inventory_id = $materialItem->product_inventory_id;
                                    $quantity_minus = $materialItem->quantity_approve;
                                    $inventoryItem = $this->listMaterial2->getItem($product_inventory_id);
                                    $data_inventory['quantity'] = $inventoryItem->quantity - $quantity_minus;
                                    $this->listMaterial2->edit($data_inventory, $product_inventory_id);
                                }
                            }
                        } else {
                            $warehouse = $params['warehouse'];
                            $warehouse_id = isset($warehouse[$key]) ? $warehouse[$key] : '';
                            $product_inventory = \DB::table('product_inventorys')->select('product_inventory_id')->where('warehouse_id', $warehouse_id)->where('product_id', $key)->first();
                            $product_inventory_id = isset($product_inventory->product_inventory_id) ? $product_inventory->product_inventory_id : '';

                            $material_data_detail['ticket_request_material_id'] = $params['ticket_request_material_id'];
                            $material_data_detail['product_id'] = $key;
                            $material_data_detail['warehouse_id'] = $warehouse_id;
                            $material_data_detail['product_inventory_id'] = $product_inventory_id;
                            $material_data_detail['quantity'] = $params[$key];
                            $material_data_detail['status'] = $status_all;
                            $material_data_detail['created_at'] = Carbon::now()->format("Y-m-d H:i:s");
                            $material_data_detail['created_by'] = \Auth::id();
                            $this->materialDetail->add($material_data_detail);
                            if($params['status'][$key] == 'approve' || $status_all =='approve'){
                                $materialItem = $this->materialDetail->getItem($params['ticket_request_material_detail_id'][$key]);
                                $product_inventory_id = $materialItem->product_inventory_id;
                                $quantity_minus = $materialItem->quantity_approve;
                                $inventoryItem = $this->listMaterial2->getItem($product_inventory_id);
                                $data_inventory['quantity'] = $inventoryItem->quantity - $quantity_minus;
                                $this->listMaterial2->edit($data_inventory, $product_inventory_id);
                            }
                        }
                    }
                    return response()->json(['status' => 1]);
                }
            }
            return response()->json(['status' => 0]);
        }
    }

    public function approvedAction(Request $request)
    {
        if ($request->ajax()) {
            $params = $request->all();
            $material_data = [
                'description' => $request->description,
                // 'approved_by' => \Auth::id(),
                // 'approved_date' => null,
                // 'status' => $request->status_material,
                'updated_by' => \Auth::id(),
                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
            ];
            $item = $this->material->getItem($params['ticket_request_material_id']);
            if (isset($params['status_material']) && $params['status_material'] == 'approve') {
                $material_data['approved_by'] = \Auth::id();
                $material_data['approved_date'] = Carbon::now()->format("Y-m-d H:i:s");
                $material_data['status'] = $request->status_material;
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã duyệt phiếu yêu cầu vật tư ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' approved the material requisition form ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $this->createHistory($note,$note_en, $item->ticket_id);
            } elseif (isset($params['status_material']) && $params['status_material'] == 'cancel') {
                $material_data['status'] = $request->status_material;
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã hủy phiếu yêu cầu vật tư ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has canceled the request for supplies ' . createATag(route('ticket.material', ['search' => $item->ticket_request_material_code]), $item->ticket_request_material_code);
                $this->createHistory($note,$note_en, $item->ticket_id);
            }
            // dd($request->all());
            if (!isset($params['status'])) {
                if (!isset($params['warehouse'])) {
                    return response()->json(['status' => 2]);
                }
            }
            if ($this->material->edit($material_data, $params['ticket_request_material_id'])) {
                $material_id = $params['ticket_request_material_id'];
                // $this->materialDetail->removeByMaterialId($material_id);
                if (isset($params['status'])) {
                    foreach ($params['status'] as $key => $value) {
                        if (isset($params['ticket_request_material_detail_id'][$key])) {
                            if(isset($params['status_material']) && $params['status_material'] == 'approve'){
                                $params['status'][$key] = 'approve';
                            }elseif (isset($params['status_material']) && $params['status_material'] == 'cancel'){
                                $params['status'][$key] = 'cancel';
                            }
                            $material_data_detail = [
                                'quantity_approve' => $params[$key],#số lượng duyệt
                                'status' => $params['status'][$key],
                                'updated_by' => \Auth::id(),
                                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                            ];

                            if ($this->materialDetail->edit($material_data_detail, $params['ticket_request_material_detail_id'][$key])) {
                                if($params['status'][$key] == 'approve' || $params['status_material'] == 'approve'){
                                    $materialItem = $this->materialDetail->getItem($params['ticket_request_material_detail_id'][$key]);
                                    $product_inventory_id = $materialItem->product_inventory_id;
                                    $quantity_minus = $materialItem->quantity_approve;
                                    $inventoryItem = $this->listMaterial2->getItem($product_inventory_id);
                                    $data_inventory['quantity'] = $inventoryItem->quantity - $quantity_minus;
                                    $this->listMaterial2->edit($data_inventory, $product_inventory_id);
                                }
                            }
                        } else {
                            // them vat tu thay the
                            $warehouse = $params['warehouse'];
                            $warehouse_id = isset($warehouse[$key]) ? $warehouse[$key] : '';
                            $product_inventory = \DB::table('product_inventorys')->select('product_inventory_id')->where('warehouse_id', $warehouse_id)->where('product_id', $key)->first();
                            $product_inventory_id = isset($product_inventory->product_inventory_id) ? $product_inventory->product_inventory_id : '';

                            $material_data_detail['ticket_request_material_id'] = $params['ticket_request_material_id'];
                            $material_data_detail['product_id'] = $key;
                            $material_data_detail['warehouse_id'] = $warehouse_id;
                            $material_data_detail['product_inventory_id'] = $product_inventory_id;
                            $material_data_detail['quantity_approve'] = $params[$key];
                            $material_data_detail['quantity'] = $params[$key];
                            $material_data_detail['status'] = 'approve';
                            $material_data_detail['created_at'] = Carbon::now()->format("Y-m-d H:i:s");
                            $material_data_detail['created_by'] = \Auth::id();
                            if ($this->materialDetail->add($material_data_detail)) {
                                $inventoryItem = $this->listMaterial2->getItem($product_inventory_id);
                                $data_inventory['quantity'] = $inventoryItem->quantity - $params[$key];
                                $this->listMaterial2->edit($data_inventory, $product_inventory_id);
                            }
                        }
                    }
                }

                $mNoti = new SendNotificationApi();
                $listCustomer = $this->getListStaff($item['ticket_id']);
                $keyNotiStaff = '';
                if (isset($params['status_material']) && $params['status_material'] == 'approve') {
                    $keyNotiStaff = 'request_material_approve';
                }

                if (isset($params['status_material']) && $params['status_material'] == 'cancel') {
                    $keyNotiStaff = 'request_material_reject';
                }

                if ($keyNotiStaff != ''){
                    foreach ($listCustomer as $itemCustomer) {
                        $mNoti->sendStaffNotification([
                            'key' => $keyNotiStaff,
                            'customer_id' => $itemCustomer,
                            'object_id' => $params['ticket_request_material_id']
                        ]);
                    }
                }

//                Duyệt phiếu gửi cho người duyệt
                if ($keyNotiStaff == 'request_material_approve'){
//                    Lấy danh sách nhân viên duyệt phiếu
                    $mMapRoleGroupStaff = app()->get(MapRoleGroupStaffTable::class);
                    $listStaffApprove = $mMapRoleGroupStaff->getListStaffApproveTicket();
                    foreach ($listStaffApprove as $itemStaffApprove){
                        $mNoti->sendStaffNotification([
                            'key' => $keyNotiStaff,
                            'customer_id' => $itemStaffApprove['staff_id'],
                            'object_id' => $params['ticket_request_material_id']
                        ]);
                    }
                }

//                Từ chối gửi cho người tạo phiếu
                if ($keyNotiStaff == 'request_material_reject'){
                    $mNoti->sendStaffNotification([
                        'key' => $keyNotiStaff,
                        'customer_id' => $item['created_by'],
                        'object_id' => $params['ticket_request_material_id']
                    ]);
                }

                return response()->json(['status' => 1]);
            }
            return response()->json(['status' => 0]);
        }
    }

    // lấy danh sách phiếu vật tư của 1 ticket
    public function getListMaterialByTicketId(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ticket_id;
            $item = $this->material->getItemByTicketId($id);
            $jsonString = [];
            $material_list = [];
            $material_list_detail = [];
            if ($item) {
                foreach ($item as $material) {
                    $material_list[] = [
                        'ticket_request_material_id' => $material->ticket_request_material_id,
                        'ticket_request_material_code' => $material->ticket_request_material_code,
                        'proposer_by' => isset($material->proposer->full_name) ? $material->proposer->full_name : '',
                        'proposer_date' => Carbon::parse($material->proposer_date)->format('d/m/Y H:i'),
                        'approved_by' => isset($material->approved->full_name) ? $material->approved->full_name : '',
                        'approved_date' => Carbon::parse($material->approved_date)->format('d/m/Y H:i'),
                        'description' => $material->description,
                        'status' => $this->statusMaterialItem(['status' => $material->status]),
                        'status_id' => $material->status,
                    ];
                }
                $item_detail = $this->materialDetail->getListMaterialByTicketId($id);
                if ($item_detail) {
                    foreach ($item_detail as $material_detail) {
                        $quantity_approve = $material_detail->quantity_approve?$material_detail->quantity_approve:0;
                        $quantity_reality = $material_detail->quantity_reality;
                        $quantity_return = $material_detail->quantity_return;
                        $quantity = $material_detail->quantity;
                        $material_list_detail[] = [
                            'product_code' => $material_detail->product_code,
                            'product_name' => $material_detail->product_name,
                            'quantity' => $quantity,
                            'quantity_approve' => $quantity_approve,
                            'quantity_reality' => $quantity_reality,
                            'quantity_return' => $quantity_return,
                            'status' => $this->statusMaterialItem(['status' => $material_detail->status]),
                            'status_id' => $material->status,
                        ];
                    }
                }
                $jsonString = [
                    'material_list' => $material_list,
                    'material_list_detail' => $material_list_detail,
                    'success' => 1
                ];
            }
            return response()->json($jsonString);
        }
    }

    //  lấy danh sách tất cả vật tư của 1 ticket
    public function getListMaterialDetailByTicketId(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ticket_id;
            $item = $this->material->getMaterialDetailByTicketId($id);
            $jsonString = [];
            if ($item) {
                foreach ($item as $material) {
                    $material_list[] = [
                        'product_code' => $material->product_code,
                        'product_name' => $material->product_name,
                        'quantity' => ($material->quantity) ? $material->quantity : 0,
                        'quantity_approve' => ($material->quantity_approve) ? $material->quantity_approve : 0,
                        'quantity_reality' => ($material->quantity_reality) ? $material->quantity_reality : 0,
                        'quantity_return' => ($material->quantity_return) ? $material->quantity_return : 0,
                        'status' => $this->statusMaterialItem(['status' => $material->status]),
                    ];
                }
                $jsonString = [
                    'material_list' => $material_list,
                    'success' => 1
                ];
            }
            return response()->json($jsonString);
        }
    }

    public function createHistory($note_vi = "",$note_en = "", $ticketId)
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

    // lấy danh sách vật tư tồn kho & chi tiết vật tư
    public function getDetailMaterial(Request $request)
    {
        $filters['product_id'] = $request->only('product_id');
        $filters['warehouse_id'] = $request->only('warehouse_id');
        $data = $this->listMaterial->getListProductInventory($filters);
        return response()->json(['data' => $data, 'status' => 1]);
    }

    // lấy danh sách vật tư tồn kho & chi tiết vật tư
    public function getProductInWarehouse(Request $request)
    {
        $filters['warehouse_id'] = $request->only('warehouse_id');
        $data = $this->listMaterial->getListProductInventory($filters);
        $html = "<option value=''>" . __('Chọn vật tư cần đề xuất') . "</option>";
        if (count($data)) {
            foreach ($data as $key => $value) {
                $html .= "<option value='" . $key . "'>" . $value . "</option>";
            }
        }
        return response()->json(['html' => $html, 'status' => 1]);
    }

    public function removeAction($id)
    {
        try {
            if ($this->material->remove($id)) {
                $detail = $this->material->getItem($id);
                $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã xóa phiếu yêu cầu vật tư ' . createATag(route('ticket.material', ['search' => $detail->ticket_request_material_code]), $detail->ticket_request_material_code);
                $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has deleted the request for supplies ' . createATag(route('ticket.material', ['search' => $detail->ticket_request_material_code]), $detail->ticket_request_material_code);
                $this->createHistory($note,$note_en, $detail->ticket_id);

                $mNoti = new SendNotificationApi();
                $listCustomer = $this->getListStaff($detail['ticket_id']);
                foreach ($listCustomer as $item) {
                    $mNoti->sendStaffNotification([
                        'key' => 'request_material_remove',
                        'customer_id' => $item,
                        'object_id' => $id
                    ]);
                }

                return response()->json([
                    'error' => 0,
                    'message' => 'Remove success'
                ]);
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function parseExcel(Request $request)
    {
        $data = [];
        if ($request->hasFile('import_file')) {
            $file = $request->file('import_file');
            if (isset($file)) {
                $typeFileExcel = $file->getClientOriginalExtension();
                if ($typeFileExcel == "xlsx") {
                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($file);
                    foreach ($reader->getSheetIterator() as $sheet) {
                        foreach ($sheet->getRowIterator() as $key => $row) {
                            if ($key > 1) {
                                // check số lượng tồn tại trong kho theo mã + tên + số lượng
                                if ($row[1] != '' && $row[2] != '' && $row[3] != '' && $row[3] != 0 && is_numeric($row[3])) {
                                    $filters = [
                                        "product_code" => $row[1],
                                        "product_child_name" => $row[2],
                                        "quantity" => $row[3],
                                        "warehouse_name" => $row[4],
                                    ];
                                    if ($this->listMaterial->checkProductExist($filters)) {
                                        $data[$key] = $this->listMaterial->checkProductExist($filters)->toArray();
                                        $data[$key]["quantity_current"] = $row[3];
                                    }
                                }

                            }
                        }
                    }
                    $reader->close();
                }
                $data = array_column($data, null, 'product_id');
                return response()->json([
                    'status' => 1,
                    'data' => $data
                ]);
            }
        }
    }

    public function generateMaterialCode()
    {
        $type_ticket = 'YCVT';
        $time = date("Ymd");
        $last_id = DB::table('ticket_request_material')->whereDate('created_at', Carbon::today()->format('Y-m-d'))->count();

        $last_id = sprintf("%03d", ($last_id));
        return $type_ticket . '_' . $time . '_' . $last_id;
    }

    // ajax lưu cấu hình tìm kiếm + table
    public function saveConfig(Request $request)
    {
        $data = [
            'route_name' => 'ticket.material.save-config',
            'search' => $request->search,
            'column' => $request->column,
        ];
        $data = serialize($data);
        Cookie::queue('material_token', $data, 3600);
        return response()->json(['status' => 1, 'data' => $data]);
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
                "placeholder" => __("Chọn người đề xuất"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "proposer_by",
                "id" => "proposer_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người đề xuất"),
            ],
            3 => [
                "active" => 1,
                "placeholder" => __("Chọn người duyệt"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "approved_by",
                "id" => "approved_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người duyệt"),
            ],
            4 => [
                "active" => 1,
                "placeholder" => __("Thời gian đề xuất"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "proposer_date",
                "id" => "proposer_date",
                "data" => "",
                "nameConfig" => __("Thời gian đề xuất"),
            ],
            5 => [
                "active" => 1,
                "placeholder" => __("Chọn trạng thái"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "status",
                "id" => "status",
                "data" => $this->filters(),
                "nameConfig" => __("Trạng thái"),
            ],
        ];
        // kiểm tra cookie thanh search + column
        $config = Cookie::get('material_token');
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
                "name" => __("Mã phiếu yêu cầu"),
                "class" => "view-material",
                "active" => 1,
                "nameConfig" => __("Mã phiếu yêu cầu"),
                "column_name" => "ticket_request_material_code",
                "type" => "link",
                "attribute" => [],
            ],
            4 => [
                "name" => __("Mã Ticket"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Mã Ticket"),
                "column_name" => "ticket_id",
                "type" => "label"
            ],

            5 => [
                "name" => __("Người đề xuất"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người đề xuất"),
                "column_name" => "proposer_by",
                "type" => "label"
            ],
            6 => [
                "name" => __("Thời gian đề xuất"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Thời gian đề xuất"),
                "column_name" => "proposer_date",
                "type" => "label"
            ],
            7 => [
                "name" => __("Người duyệt"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Người duyệt"),
                "column_name" => "approved_by",
                "type" => "label"
            ],
            8 => [
                "name" => __("Thời gian duyệt"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Thời gian duyệt"),
                "column_name" => "approved_date",
                "type" => "label"
            ],
            9 => [
                "name" => __("Trạng thái"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "column_name" => "status",
                "type" => "status",
                /* 
                    - dạng enum
                    + truyền biến vào key của option là giá trị
                    + value là tên hiển thị và
                        màu sắc trạng thái (có 5 màu success ,danger , primary, warning ,second)
                */
                "option" => [
                    'new' => [
                        'name' => __('Mới'),
                        'color' => 'success'
                    ],
                    'approve' => [
                        'name' => __('Đã duyệt'),
                        'color' => 'primary'
                    ],
                    'cancel' => [
                        'name' => __('Hủy'),
                        'color' => 'danger'
                    ],
                ],
                // thuộc tính khác
                "attribute" => [
                    "style" => 'width:80%',
                ]
            ],

        ];
        $config = Cookie::get('material_token');
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
}