<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Refund;

use DB;
use Carbon\Carbon;
use Modules\Ticket\Models\TicketRefundTable;
use Modules\Ticket\Models\QueueStaffTable;
use Modules\Ticket\Models\TicketTable;
use Modules\Ticket\Models\MaterialDetailTable;
use Modules\Ticket\Models\TicketRefundMapTable;
use Modules\Ticket\Models\TicketRefundFileTable;
use Modules\Ticket\Models\TicketFileTable;
use Modules\Ticket\Models\TicketAcceptanceIncurredTable;
use Modules\Ticket\Models\StaffTable;
use Modules\Ticket\Models\TicketRefundItemTable;
use Modules\Ticket\Models\InventoryInputsTable;
use Modules\Ticket\Models\InventoryInputDetailsTable;
use Modules\Ticket\Models\PaymentTable;

//use Modules\Ticket\Models\ProductInventoryTable2;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;


use App\Http\Middleware\S3UploadsRedirect;
use Illuminate\Support\Facades\Storage;

class RefundRepository implements RefundRepositoryInterface
{
    /**
     * @var TicketRefundTable
     */
    protected $refund;
    protected $s3Disk;
    protected $refund_map;
    protected $ticket_refund_item;
    protected $code;
    protected $timestamps = true;

    public function __construct(
        TicketRefundTable $refund,
        S3UploadsRedirect $_s3,
        TicketRefundMapTable $refund_map,
        TicketRefundFileTable $refund_file,
        StaffTable $staff,
        TicketRefundItemTable $ticket_refund_item,
        CodeGeneratorRepositoryInterface $code
    )
    {
        $this->refund = $refund;
        $this->s3Disk = $_s3;
        $this->refund_map = $refund_map;
        $this->refund_file = $refund_file;
        $this->staff = $staff;
        $this->ticket_refund_item = $ticket_refund_item;
        $this->code = $code;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        $filters = array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        });
        return $this->refund->listRefund($filters);
    }

    public function getName()
    {
        return $this->refund->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->refund->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->refund->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->refund->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->refund->getItem($id);
    }

    /*
     *  get queue name by staff id
     */

    public function loadQueueByStaff($params)
    {
        try {
            $staff_id = $params['staff_id'];
            $mQueueStaffTable = new QueueStaffTable;
            $queue_name = $mQueueStaffTable->getTicketQueueIdByStaffId($staff_id);
            return [
                'status' => 1,
                'queue_name' => isset($queue_name->queue_name) ? $queue_name->queue_name : '',
                'message' => __('Load thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 0,
                'message' => __('Hiện tại có lỗi xảy ra'),
            ];
        }
    }

    /* lấy danh sách trạng thái vật tư có 6 trạng thái */
    public function filters()
    {
        return [
            '' => __('Chọn trạng thái'),
            'D' => __('Bản nháp'),
            'W' => __('Chờ duyệt'),
            'WF' => __('Chờ hồ sơ'),
            'A' => __('Đã duyệt'),
            'C' => __('Hoàn tất'),
            'R' => __('Từ chối'),
        ];
    }

    public function loadListApprove()
    {
        try {
            return DB::table('staffs')->select('staffs.staff_id', 'staffs.full_name')
                ->join('map_role_group_staff as p1', 'staffs.staff_id', 'p1.staff_id')
                ->leftJoin('ticket_role as p2', 'p1.role_group_id', 'p2.role_group_id')
                ->where('p2.is_approve_refund', 1)->get()->pluck("full_name", "staff_id")->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function addView($id)
    {
        $mTicket = new TicketTable;
        $ticketRefundList = $mTicket->ticketRefundList($id);
        $item = $this->refund->getItem($id);
        if (isset($item->status) && in_array($item->status, ['D']) && $item->created_by == \Auth::id()) {
            $ticketRefundMapList = $this->refund->getTicketRefundMap($id);
            $html = '';
            foreach ($ticketRefundMapList as $ticket_id) {
                $data = $this->loadTicketRefundDetail($ticket_id, true, $id);
                $html .= $data['html'];
            }
            return [
                'statusList' => $this->filters(),
                'item' => $item,
                'ticketRefundList' => $ticketRefundList,
                'ticketRefundMapList' => $ticketRefundMapList,
                'html' => $html,
                'check_edit' => true,
            ];
        }
        return [
            'item' => $item
        ];
    }

    public function editView($id)
    {
        $mTicket = new TicketTable;
        $item = $this->refund->getItem($id);
        if (isset($item->status) && in_array($item->status, ['D', 'WF']) && $item->created_by == \Auth::id()) {
            $ticketRefundMapList = $this->refund->getTicketRefundMap($id);
            $html = '';
            foreach ($ticketRefundMapList as $ticket_id) {
                $data = $this->loadTicketRefundDetail($ticket_id, true, $id);
                $html .= $data['html'];
            }
            return [
                'statusList' => $this->filters(),
                'ticketRefundMapList' => $ticketRefundMapList,
                'item' => $item,
                'html' => $html,
            ];
        }
        return [];
    }

    public function detailView($id)
    {
        $mTicket = new TicketTable;
        $item = $this->refund->getItem($id);
        $receive_data = [];
        $payment_data = [];
        if (isset($item->status) && $item->status == 'C') {
            $mInventoryInputsDetailTable = new InventoryInputsTable;
            $receive_data = $mInventoryInputsDetailTable->getItemByRefundID($id);
            if(isset($receive_data->object_id) && $receive_data->object_id){

            }else{
                $receive_data = null;
            }
            $mPaymentTable = new PaymentTable;
            $payment_data = $mPaymentTable->getItemByRefundID($id);
        }
        $ticketRefundMapList = $this->refund->getTicketRefundMap($id);
        $html = '';
        foreach ($ticketRefundMapList as $ticket_id) {
            if (isset($item->status) && $item->status == 'C') {
                $data = $this->loadTicketRefundDetailApprove($ticket_id, false, $id);
            } else {
                $data = $this->loadTicketRefundDetail($ticket_id, false, $id);
            }
            $html .= $data['html'];
        }
        if (isset($item->status) && in_array($item->status, ['C', 'A'])) {
            $resultRefundMaterial = $this->ticket_refund_item->getResultRefundMaterial($id);
            $resultRefundAcceptanceIncurred = $this->ticket_refund_item->getResultRefundIncurred($id);
            $html_result_refund = view('ticket::refund.content.table_result_refund', [
                'resultRefundMaterial' => $resultRefundMaterial,
                'resultRefundAcceptanceIncurred' => $resultRefundAcceptanceIncurred
            ])->render();

            $html = $html . $html_result_refund;
        }
        return [
            'statusList' => $this->filters(),
            'ticketRefundMapList' => $ticketRefundMapList,
            'item' => $item,
            'html' => $html,
            'receive_data' => $receive_data,
            'payment_data' => $payment_data,
        ];
    }

    public function approveView($id)
    {
        $mTicket = new TicketTable;
        $item = $this->refund->getItem($id);
        if (isset($item->status) && $item->status == 'W' && $item->approve_id == \Auth::id()) {
            $ticketRefundMapList = $this->refund->getTicketRefundMap($id);
            $html = '';
            foreach ($ticketRefundMapList as $ticket_id) {
                $data = $this->loadTicketRefundDetailApprove($ticket_id, true, $id);
                $html .= $data['html'];
            }
            $resultRefundMaterial = $this->ticket_refund_item->getResultRefundMaterial($id);
            $resultRefundAcceptanceIncurred = $this->ticket_refund_item->getResultRefundIncurred($id);

            $html_result_refund = view('ticket::refund.content.table_result_refund', [
                'resultRefundMaterial' => $resultRefundMaterial,
                'resultRefundAcceptanceIncurred' => $resultRefundAcceptanceIncurred,

            ])->render();

            $html = $html . $html_result_refund;
            return [
                'statusList' => $this->filters(),
                'ticketRefundMapList' => $ticketRefundMapList,
                'item' => $item,
                'html' => $html,
            ];
        }
        return [];


    }

    public function loadTicketRefundDetail($ticket_id, $check_edit = false, $id = null, $is_detail = false)
    {
        // try{
        $mTicket = new TicketTable;
        $ticketItem = $mTicket->getItem($ticket_id);
        if ($is_detail) {
            $materialListRefund = $this->ticket_refund_item->getListMaterialRefund($ticket_id);
            $acceptanceIncurred = $this->ticket_refund_item->getListTicketAcceptanceIncurred($ticket_id);
        } else {
            $mMaterialDetail = new MaterialDetailTable;
            $materialListRefund = $mMaterialDetail->getListMaterialRefund($ticket_id);
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable;
            $acceptanceIncurred = $mTicketAcceptanceIncurred->getListTicketAcceptanceIncurred($ticket_id);
//            $materialListRefund = $this->ticket_refund_item->getListMaterialRefund($ticket_id);
//            $acceptanceIncurred = $this->ticket_refund_item->getListTicketAcceptanceIncurred($ticket_id);
        }

        // dd($materialListRefund,$acceptanceIncurred);
        $mTicketFile = new TicketFileTable;
        $file['refund'] = $this->refund_file->getFileRefundByTicketId($ticket_id, $id);
        $file['acceptance'] = $mTicketFile->getFileRefundByTicketId($ticket_id);

        $html = view('ticket::refund.content.ticket_refund_card', [
            'materialListRefund' => $materialListRefund,
            'acceptanceIncurred' => $acceptanceIncurred,
            'ticketItem' => $ticketItem,
            'file' => $file,
            'total_money' => 0,
            'check_edit' => $check_edit,
        ])->render();

        return [
            'error' => 0,
            'html' => $html,
            'message' => __('Load thành công'),
        ];
        // }catch (\Exception $e){
        //     return [
        //         'error' => 1,
        //         'html' => '',
        //         'message' => __('Hiện tại có lỗi xảy ra'),
        //     ];
        // }
    }

    public function loadTicketRefundDetailApprove($ticket_id, $check_edit = true, $id = null)
    {
        $mTicket = new TicketTable;
        $ticketItem = $mTicket->getItem($ticket_id);
        $materialListRefund = $this->ticket_refund_item->getListMaterialRefund($ticket_id);
        $acceptanceIncurred = $this->ticket_refund_item->getListTicketAcceptanceIncurred($ticket_id);

        $mTicketFile = new TicketFileTable;
        $file['refund'] = $this->refund_file->getFileRefundByTicketId($ticket_id, $id);
        $file['acceptance'] = $mTicketFile->getFileRefundByTicketId($ticket_id);

        // $ticket_refund_item_list  = $this->ticket_refund_item->getRefundItemByRefundId($id);
        // $ticket_refund_item_list = array_column($ticket_refund_item_list,null,'ticket_refund_item_id');


        $html = view('ticket::refund.content.ticket_refund_card_approve', [
            'materialListRefund' => $materialListRefund,
            'acceptanceIncurred' => $acceptanceIncurred,
            'ticketItem' => $ticketItem,
            'file' => $file,
            'total_money' => 0,
            'check_edit' => $check_edit,
        ])->render();

        return [
            'error' => 0,
            'html' => $html,
            'message' => __('Load thành công'),
        ];
    }

    public function addAction($params)
    {
        $refund_data = [
            'code' => $this->generateRefundCode(),
            'staff_id' => $params['staff_id'],
            'approve_id' => $params['approve_id'],
            'status' => isset($params['status']) ? $params['status'] : 'D',
            'created_by' => \Auth::id(),
            'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
        ];
        $refund_id = $this->refund->add($refund_data);
        if ($refund_id) {
            return [
                'error' => 0,
                'refund_id' => $refund_id,
                'message' => __('Thêm thành công'),
            ];
        }
    }

    public function showApproveItem($params)
    {
        $params['item'] = $this->ticket_refund_item->getItem($params['id']);
        $html = view('ticket::refund.popup.approve_popup', $params)->render();
        return [
            'error' => 0,
            'html' => $html,
            'message' => __('Show modal thành công'),
        ];
    }

    public function updateApproveItem($params)
    {
        $id = $params['id'];
        unset($params['id']);
        $params['money'] = isset($params['money']) ? $params['money'] : 0;
        $params['quantity'] = isset($params['quantity']) ? $params['quantity'] : 0;
        $params['money'] = str_replace(',', '', $params['money']);
        $params['quantity'] = str_replace(',', '', $params['quantity']);
        if ($this->ticket_refund_item->edit($params, $id)) {
            return [
                'error' => 0,
                'message' => __('Update thành công'),
            ];
        }
        return;
    }

    public function submitEditAction($id, $params)
    {
        $mess = __('Lưu thành công');
        $ticket_refund_item_list = [];
        if (isset($params['ticket_id']) && count($params['ticket_id'])) {
            $array_map_list = $this->refund_map->getRefundMapByTicketRefundId($id);
            if ($array_map_list) {
                foreach ($array_map_list as $ticket_refund_map_id) {
                    $this->ticket_refund_item->removeByTicketReFundMapId($ticket_refund_map_id);
                    $this->refund_file->removeByRefundMapId($ticket_refund_map_id);
                }
            }
            $this->refund_map->removeByRefundId($id);
            $arr_map = [];
            $arr_file = [];
            $count_type_refund_item = 0;
            foreach ($params['ticket_id'] as $v) {
                $arr_map = [
                    "ticket_refund_id" => $id,
                    "ticket_id" => $v,
                    "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                    "created_by" => \Auth::id(),
                ];
                if (isset($params[$v]['refund']) && count($params[$v]['refund']) > 0 && ($params['status'] != 'D')) {
                    $refund_map_id = $this->refund_map->add($arr_map);
                    if ($refund_map_id) {
                        foreach ($params[$v]['refund'] as $path_file) {
                            $arr_refund_file = [
                                "ticket_refund_map_id" => $refund_map_id,
                                "ticket_id" => $v,
                                "path_file" => $path_file,
                                "type" => 'refund',
                                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                                "created_by" => \Auth::id(),
                            ];
                            $this->refund_file->add($arr_refund_file);
                        }
                        if (isset($params[$v]['acceptance']) && count($params[$v]['acceptance']) > 0) {
                            foreach ($params[$v]['acceptance'] as $path_file) {
                                $arr_acceptance_file = [
                                    "ticket_refund_map_id" => $refund_map_id,
                                    "ticket_id" => $v,
                                    "path_file" => $path_file,
                                    "type" => 'acceptance',
                                    "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                                    "created_by" => \Auth::id(),
                                ];
                                $this->refund_file->add($arr_acceptance_file);
                            }
                        }
                    }
                } elseif (($params['status'] == 'D')) {
                    $refund_map_id = $this->refund_map->add($arr_map);
                    if (isset($params[$v]['refund']) && count($params[$v]['refund']) > 0 && $refund_map_id) {
                        foreach ($params[$v]['refund'] as $path_file) {
                            $arr_refund_file = [
                                "ticket_refund_map_id" => $refund_map_id,
                                "ticket_id" => $v,
                                "path_file" => $path_file,
                                "type" => 'refund',
                                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                                "created_by" => \Auth::id(),
                            ];
                            $this->refund_file->add($arr_refund_file);
                        }
                    }
                    if (isset($params[$v]['acceptance']) && count($params[$v]['acceptance']) > 0) {
                        foreach ($params[$v]['acceptance'] as $path_file) {
                            $arr_acceptance_file = [
                                "ticket_refund_map_id" => $refund_map_id,
                                "ticket_id" => $v,
                                "path_file" => $path_file,
                                "type" => 'acceptance',
                                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                                "created_by" => \Auth::id(),
                            ];
                            $this->refund_file->add($arr_acceptance_file);
                        }
                    }
                } else {
                    return [
                        'error' => 1,
                        'message' => __('Bạn chưa upload file hồ sơ chứng từ'),
                    ];
                }
                if (isset($params['product'][$v]['product_id']) && count($params['product'][$v]['product_id']) > 0) {
                    foreach ($params['product'][$v]['product_id'] as $product_id) {
                        $ticket_refund_item_list = [
                            "ticket_refund_map_id" => $refund_map_id,
                            "ticket_id" => $v,
                            "type" => $params['product_item'][$v][$product_id]['type'], # nếu type A thì product_id, I thì id vật tư phát sinh
                            "obj_id" => $params['product_item'][$v][$product_id]['obj_id'], # nếu type A thì product_id, I thì id vật tư phát sinh
                            "quantity" => $params['product_item'][$v][$product_id]['quantity'],
                            "money" => $params['product_item'][$v][$product_id]['money'],
                            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "created_by" => \Auth::id(),
                        ];
                        if ($params['product_item'][$v][$product_id]['ticket_id'] == $v) {
                            $refund_item = $this->ticket_refund_item->add($ticket_refund_item_list);
                        }

                    }
                }
            }

            $refund_id = $this->refund->edit(
                [
                    "status" => $params['status'],
                    "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                    "updated_by" => \Auth::id(),
                ], $id);
            return [
                'error' => 0,
                'message' => $mess,
            ];
        }
        if (isset($params['status']) && $params['status']) {
            if ((isset($params['check_null_item']) && $params['check_null_item'] == 1) && empty($params['product'])) {
                return [
                    'error' => 1,
                    'message' => __('Ticket trống'),
                ];
            }
            $data = [
                "status" => $params['status'],
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_by" => \Auth::id(),
            ];
            $this->refund->edit($data, $id);
            if ($params['status'] == 'C') {
                $mInventoryInputsTable = new InventoryInputsTable;
                $mInventoryInputDetailsTable = new InventoryInputDetailsTable;
                $mPaymentTable = new PaymentTable;
                $code = 'NK_' . date("Y") . date("m") . date("d") . $mInventoryInputsTable->count();
                $listItem = $this->ticket_refund_item->getRefundItemByRefundId($id);

                $dataInventoryInput = [
                    'warehouse_id' => 0,
                    'pi_code' => $code,
                    'status' => 'new',
                    'type' => 'normal', # nhap kho thuong normal
                    'note' => '',
                    'user_recived' => '',
                    'date_recived' => '',
                    'object_id' => $id,
                    'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                    'created_by' => \Auth::id(),
                ];
                if (count($listItem)) {
                    $check_quantity_inventory_input = 0;
                    foreach ($listItem as $item) {
                        if ($item['type'] == 'A' && $item['quantity'] > 0) {
                            $check_quantity_inventory_input += $item['quantity'];
                        }
                    }
                    $total_amount = 0;
                    if ($check_quantity_inventory_input != 0) {
                        $inventory_input_id = $mInventoryInputsTable->add($dataInventoryInput);
                    }
                    // phiếu nhập
                    foreach ($listItem as $item) {
                        if ($item['type'] == 'A' && $item['quantity'] > 0) {
                            $product_list = [
                                'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                                'created_by' => \Auth::id(),
                                'inventory_input_id' => $inventory_input_id,
                                'product_code' => $item['product_code'],
                                'quantity' => $item['quantity'],
                                'current_price' => $item['money'],
                                'total' => $item['money'] * $item['quantity'],
                            ];
                            if ($check_quantity_inventory_input != 0) {
                                $list_detail[] = $mInventoryInputDetailsTable->add($product_list);
                            }
//                            $mProductInventory = new ProductInventoryTable2;
//                            $mProductInventory->plusQuantityProduct($product_list['product_code'],$item['quantity']);
                        } elseif ($item['type'] == 'I') {
                            $total_amount += $item['money'];
                        }
                    }
                    // phiếu chi
                    if ($total_amount) {
                        $payment_code_old = $mPaymentTable->getPaymentMaxId();
                        $max_id_old = $payment_code_old != null ?
                            substr($payment_code_old['payment_code'], 9, strlen($payment_code_old['payment_code'])) : 0;
                        //Lay thong tin
                        $item = $this->refund->getItem($id);
                        $staff_id = $item->staff_id;
                        //Lay thong tin
                        $infoStaff = $this->staff->getItem($staff_id);

                        $max_id_new = (int)$max_id_old + 1;
                        $currentDay = (new \DateTime())->format('d');
                        $currentMonth = (new \DateTime())->format('m');
                        $currentYear = (new \DateTime())->format('Y');
                        $payment_code_new = 'P' . $currentDay . $currentMonth . $currentYear . $max_id_new;

                        $mPaymentTable->createPayment([
                            'payment_code' => $payment_code_new,
                            'branch_code' => Auth()->user()->branch_id,
                            'total_amount' => $total_amount,
                            'status' => 'new',
                            'note' => 'Chi trả tiền vật tư phát sinh cho nhân viên ' . $infoStaff->full_name,
                            'object_accounting_type_code' => 'OAT_EMPLOYEE',
                            'accounting_id' => $staff_id,
                            'accounting_name' => $infoStaff->full_name,
                            'payment_type' => 6,
                            'document_code' => 'refund_id_' . $id,
                            'payment_method' => 'CASH',
                            'created_by' => Auth()->id(),
                        ]);


                        // ******************
                    }
                }


            }
            return [
                'error' => 0,
                'message' => $mess,
            ];
        }

    }


    public function generateRefundCode()
    {
        $type_ticket = 'PHU';
        $time = date("Ymd");
        $last_id = DB::table('ticket_refund')->whereDate('created_at', Carbon::today()->format('Y-m-d'))->count();
        $last_id = sprintf("%03d", ($last_id));
        return $type_ticket . '_' . $time . '_' . $last_id;
    }

    public function removeAction($id)
    {
        try {
            if ($this->refund->remove($id)) {
                return true;
            }
            return false;

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * upload file
     * @param $data
     * @return mixed|void
     */
    public function uploadFile($input)
    {
        try {
            if ($input['file'] != null) {
                $fileName = $this->uploadImageS3($input['file'], '.');
                $file_name_custom = $this->fileNameCustom($fileName);
                return [
                    'error' => false,
                    'file' => $fileName,
                    'file_name_custom' => $file_name_custom,
                ];
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [
                'error' => true,
                'message' => 'Tải file thất bại'
            ];
        }
    }

    function fileNameCustom($file_name)
    {
        $arr = explode("/", $file_name);
        $arr = array_reverse($arr);
        $file_name = $arr[0];
        return $file_name;
    }

    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageS3($file, $link)
    {
        // dd(file_get_contents($file));
        $time = Carbon::now();
        $idTenant = session()->get('idTenant');
        $to = $idTenant . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';
        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $this->stripVN($file->getClientOriginalName());
        Storage::disk('public')->put($to . $file_name, file_get_contents($file), 'public');
        //Lấy real path trên s3
        return $this->s3Disk->getRealPath($to . $file_name);
    }

    function stripVN($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
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
            [
                "active" => 1,
                "placeholder" => __("Nhập thông tin tìm kiếm"),
                "type" => "text",
                "class" => "form-control",
                "name" => "search",
                "id" => "search",
                "data" => "",
                "nameConfig" => __("Thông tin tìm kiếm"),
            ],
            [
                "active" => 1,
                "placeholder" => __("Chọn nhân viên hoàn ứng"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "staff_id",
                "id" => "staff_id",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Nhân viên hoàn ứng"),
            ],
            [
                "active" => 1,
                "placeholder" => __("Chọn trạng thái"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "status",
                "id" => "status",
                "data" => $this->filters(),
                "nameConfig" => __("Trạng thái"),
            ],
            [
                "active" => 1,
                "placeholder" => __("Chọn người tạo"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "created_by",
                "id" => "created_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người tạo"),
            ],
            [
                "active" => 1,
                "placeholder" => __("Chọn ngày tạo"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "created_at",
                "id" => "created_at",
                "data" => "",
                "nameConfig" => __("Ngày tạo"),
            ],
        ];
        return $data;
    }

    // hiển thị cấu hình table
    public function showColumn()
    {
        $data = [
            [
                "name" => "#",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("ID"),
                "column_name" => "count",
                "type" => "label"
            ],
            [
                "name" => "",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Chức năng"),
                "type" => "function"
            ],
            [
                "name" => __("Mã phiếu hoàn ứng"),
                "class" => "view-refund",
                "active" => 1,
                "nameConfig" => __("Mã phiếu hoàn ứng"),
                "column_name" => "code",
                "type" => "link",
                "attribute" => [],
            ],
            [
                "name" => __("Nhân viên hoàn ứng"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Nhân viên hoàn ứng"),
                "column_name" => "staff_id",
                "type" => "label"
            ],
            [
                "name" => __("Người duyệt"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người duyệt"),
                "column_name" => "approve_id",
                "type" => "label"
            ],
            [
                "name" => __("Người tạo"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người tạo"),
                "column_name" => "created_by",
                "type" => "label"
            ],
            [
                "name" => __("Ngày tạo"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Ngày tạo"),
                "column_name" => "created_at",
                "type" => "label"
            ],
            [
                "name" => __("Người cập nhật"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Người cập nhật"),
                "column_name" => "updated_by",
                "type" => "label"
            ],
            [
                "name" => __("Ngày cập nhật"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày cập nhật"),
                "column_name" => "updated_at",
                "type" => "label"
            ],
            [
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
                        màu sắc trạng thái (có 5 màu success ,danger , primary, warning ,second,info)
                */
                "option" => [
                    'D' => [
                        'name' => __('Bản nháp'),
                        'color' => 'success'
                    ],
                    'W' => [
                        'name' => __('Chờ duyệt'),
                        'color' => 'warning'
                    ],
                    'WF' => [
                        'name' => __('Chờ hồ sơ'),
                        'color' => 'metal'
                    ],
                    'A' => [
                        'name' => __('Đã duyệt'),
                        'color' => 'primary'
                    ],
                    'R' => [
                        'name' => __('Từ chối'),
                        'color' => 'danger'
                    ],
                    'C' => [
                        'name' => __('Hoàn tất'),
                        'color' => 'info'
                    ],
                ],
                // thuộc tính khác
                "attribute" => [
                    "style" => 'width:80%',
                ]
            ],

        ];
        return $data;
    }

}