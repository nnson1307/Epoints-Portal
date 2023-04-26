<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Acceptance;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ticket\Http\Api\SendNotificationApi;
use Modules\Ticket\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Ticket\Models\AcceptanceTable;
use Modules\Ticket\Models\Customers;
use Modules\Ticket\Models\MaterialDetailTable;
use Modules\Ticket\Models\ProductChildTable;
use Modules\Ticket\Models\TicketAcceptanceIncurredTable;
use Modules\Ticket\Models\TicketAcceptanceTable;
use Modules\Ticket\Models\TicketFileTable;
use Modules\Ticket\Models\TicketOperaterTable;
use Modules\Ticket\Models\TicketProcessorTable;
use Modules\Ticket\Models\TicketTable;
use Modules\Ticket\Models\TicketHistoryTable;


class AcceptanceRepository implements AcceptanceRepositoryInterface
{
    /**
     * @var AcceptanceTable
     */
    protected $acceptance;
    protected $ticket;
    protected $materialDetail;
    protected $ticketHistory;
    protected $timestamps = true;

    public function __construct(AcceptanceTable $acceptance, TicketTable $ticket, MaterialDetailTable $materialDetail, TicketHistoryTable $ticketHistory)
    {
        $this->acceptance = $acceptance;
        $this->ticket = $ticket;
        $this->materialDetail = $materialDetail;
        $this->ticketHistory = $ticketHistory;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->acceptance->listAcceptance($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->acceptance->getAll();
    }

    public function getName()
    {
        return $this->acceptance->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->acceptance->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->acceptance->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->acceptance->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->acceptance->getItem($id);
    }

    /**
     * Lấy danh sách ticket chưa có biên bản nghiệm thu
     * @return mixed|void
     */
    public function getListTicketNotAcceptance($ticket_acceptance_id = null)
    {
        return $this->ticket->getListTicketNotAcceptance($ticket_acceptance_id);
    }

    /**
     * Lấy thông tin khi thay đổi ticket
     * @param $data
     * @return mixed|void
     */
    public function changeTicket($data)
    {
        try {
            //        Lấy thông tin khách hàng từ ticket
            $ticketDetail = $this->ticket->getDetailTicket($data['ticket_id']);

//        Lấy thông tin vật tư từ ticket

            $listProduct = $this->materialDetail->getListMaterialAcceptance($data['ticket_id']);

            $listFile = $this->getListFile($data['ticket_id']);


            $view = view('ticket::acceptance.append.list-table-product-material', [
                'listProduct' => $listProduct,
                'type' => isset($data['type']) ? $data['type'] : ''
            ])->render();

            $viewFile = view('ticket::acceptance.append.list-file', [
                'listFile' => $listFile,
            ])->render();

            return [
                'error' => false,
                'acceptance_title' => 'Biên bản nghiệm thu ticket ' . ($ticketDetail != null ? $ticketDetail['ticket_code'] : ''),
                'customer_id' => $ticketDetail != null ? $ticketDetail['customer_id'] : '',
                'customer_name' => $ticketDetail != null ? $ticketDetail['full_name'] . ($ticketDetail['phone1'] != '' ? ' - ' . $ticketDetail['phone1'] : '') : '',
                'viewProduct' => $view,
                'viewFile' => $viewFile,
                'countFile' => count($listFile),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('ticket::acceptance.list_material_error'),
            ];
        }
    }

    /**
     * Show popup thêm vật tư phát sinh
     * @return mixed|void
     */
    public function showPopupAddProduct($data)
    {
        try {
            $mProductChild = new ProductChildTable();
            $listMaterial = $mProductChild->getListProductInventory($data);
            $view = view('ticket::acceptance.popup.popup-add-material', [
                'listMaterial' => $listMaterial
            ])->render();
            return [
                'error' => false,
                'view' => $view,
                'message' => __('ticket::acceptance.show_popup_success'),
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => __('ticket::acceptance.show_popup_error'),
            ];
        }
    }

    /**
     * Lưu sản phẩm đã chọn vào danh sách
     * @param $data
     * @return mixed|void
     */
    public function addProductIncurredList($data)
    {
        try {
            $data = array_values($data);
            $view = view('ticket::acceptance.append.list-product-incurred', [
                'listProduct' => $data,
            ])->render();

            return [
                'error' => false,
                'view' => $view,
                'message' => __('ticket::acceptance.add_incurred_success'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('ticket::acceptance.add_incurred_error'),
            ];
        }
    }

    /**
     * Select lấy danh sách vật tư
     * @param $data
     * @return array|mixed
     * @throws \Throwable
     */
    public function listProductSelect($data)
    {
        try {
            $mProductChild = new ProductChildTable();
            $listMaterial = $mProductChild->getListProductInventory($data);
            $view = view('ticket::acceptance.append.option-select-product', [
                'listMaterial' => $listMaterial
            ])->render();
            return [
                'error' => false,
                'view' => $view,
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
            ];
        }
    }

    public function createAcceptance($data)
    {
        try {
            DB::beginTransaction();
            $mTicketAcceptance = new TicketAcceptanceTable();
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
            $mTicketMaterialDetail = new MaterialDetailTable();
            $mTicketFile = new TicketFileTable();
//            Lưu biên bản nghiệm thu

            $acceptance = [
                'ticket_acceptance_code' => $this->createdCode(),
                'ticket_id' => $data['ticket_id'],
                'title' => $data['title'],
                'customer_id' => $data['customer_id'],
                'status' => 'new',
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
//            Tạo biên bản nghiệm thu
            $idAcceptance = $mTicketAcceptance->createdTicketAcceptance($acceptance);

            $material = [];

//            Cập nhật vật tư
            if (isset($data['ticket_request_material_detail'])) {
                foreach ($data['ticket_request_material_detail'] as $key => $item) {
                    $dataMaterial = [];
                    $dataMaterial = [
                        'quantity_approve' => (float)$item['quantity_approve'],
                        'quantity_reality' => (float)$item['quantity_reality'],
                        'quantity_return' => (float)$item['quantity_approve'] - (float)$item['quantity_reality'],
                    ];

                    $mTicketMaterialDetail->edit($dataMaterial, $key);
                }
            }


//            Tạo vật tư phát sinh

            if (isset($data['incurred'])) {
                $dataIncurred = [];
                foreach ($data['incurred'] as $item) {
                    $dataIncurred[] = [
                        'ticket_acceptance_id' => $idAcceptance,
                        'product_id' => $item['product_id'],
                        'product_code' => $item['product_code'],
                        'product_name' => $item['product_name'],
                        'quantity' => $item['product_quantity'],
                        'unit_name' => $item['product_unit'],
                        'money' => str_replace(',', '', $item["product_money"]),
                        'status' => 'new',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ];
                }

                if (count($dataIncurred) != 0) {
                    $mTicketAcceptanceIncurred->createdTicketAcceptance($dataIncurred);
                }
            }

            $mTicketFile->removeFile($data['ticket_id']);

            if (isset($data['pathFile'])) {
                $arrFile = [];
                foreach ($data['pathFile'] as $item) {
                    $type = explode('.', $item['path']);
                    $last = end($type);
                    $arrFile[] = [
                        'ticket_id' => $data['ticket_id'],
                        'type' => in_array(strtolower($last), ['jpg', 'jpeg', 'png']) ? 'image' : 'file',
                        'path' => $item['path'],
                        'group' => 'acceptance',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                }

                if (count($arrFile) != 0) {
                    $mTicketFile->createFile($arrFile);
                }
            }

            DB::commit();

            $mNoti = new SendNotificationApi();
            $listCustomer = $this->getListStaff($data['ticket_id']);
            foreach ($listCustomer as $item) {
                $mNoti->sendStaffNotification([
                    'key' => 'acceptance_create',
                    'customer_id' => $item,
                    'object_id' => $idAcceptance
                ]);
            }
            $item = $mTicketAcceptance->getItem($idAcceptance);
            $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã tạo biên bản nghiệm thu ' . createATag(route('ticket.acceptance', ['search' => $item->ticket_acceptance_code]), $item->ticket_acceptance_code);
            $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has created acceptance ' . createATag(route('ticket.acceptance', ['search' => $item->ticket_acceptance_code]), $item->ticket_acceptance_code);
            $this->createHistory($note, $note_en, $item->ticket_id);

            return [
                'error' => false,
                'message' => __('ticket::acceptance.add_acceptance_success'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('ticket::acceptance.add_acceptance_error'),
            ];
        }
    }

    /**
     * Lấy danh sách nhân viên
     * @param $ticketId
     * @return array
     */
    public function getListStaff($ticketId)
    {
        $mOperater = new TicketOperaterTable();
        $mTicket = new TicketTable();
        $mProcessor = new TicketProcessorTable();

//        Lấy danh sách người chủ trì
        $getOperater = $mTicket->ticketDetailByTicket($ticketId);

        if ($getOperater != null && $getOperater['operate_by'] != null) {
            $listOperater = [$getOperater['operate_by']];
        } else {
            $listOperater = [];
        }
        $listProcessor = $mProcessor->getListProcessor($ticketId);
        if (count($listProcessor) != 0) {
            $listProcessor = collect($listProcessor)->pluck('staff_id');
        }

        $listArr = collect($listOperater)->merge($listProcessor)->toArray();
        $listArr = array_unique($listArr);

        return $listArr;
    }

//    Chỉnh sửa biên bản nghiệm thu
    public function editAcceptance($data)
    {
        try {
            DB::beginTransaction();
            $idAcceptance = $data['ticket_acceptance_id'];
            $mTicketAcceptance = new TicketAcceptanceTable();
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
            $mTicketMaterialDetail = new MaterialDetailTable();
            $mTicketFile = new TicketFileTable();
//            Lưu biên bản nghiệm thu

            $acceptance = [
                'title' => $data['title'],
                'sign_by' => isset($data['sign_by']) ? strip_tags($data['sign_by']) : '',
                'status' => isset($data['status']) ? strip_tags($data['status']) : '',
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            if (isset($data['sign_date'])) {
                $acceptance['sign_date'] = Carbon::createFromFormat('d/m/Y H:i', $data['sign_date'])->format('Y-m-d H:i:s');
            }
//            Chỉnh sửa biên bản nghiệm thu
            $mTicketAcceptance->editTicketAcceptance($acceptance, $idAcceptance);

            $material = [];

//            Cập nhật vật tư
            if (isset($data['ticket_request_material_detail'])) {
                foreach ($data['ticket_request_material_detail'] as $key => $item) {
                    $dataMaterial = [];
                    $dataMaterial = [
                        'quantity_approve' => (float)$item['quantity_approve'],
                        'quantity_reality' => (float)$item['quantity_reality'],
                        'quantity_return' => (float)$item['quantity_approve'] - (float)$item['quantity_reality'],
                    ];

                    $mTicketMaterialDetail->edit($dataMaterial, $key);
                }
            }


//            Tạo vật tư phát sinh
            $mTicketAcceptanceIncurred->deleteTicketAcceptance($idAcceptance);
            if (isset($data['incurred'])) {
                $dataIncurred = [];
                foreach ($data['incurred'] as $item) {
                    $dataIncurred[] = [
                        'ticket_acceptance_id' => $idAcceptance,
                        'product_id' => $item['product_id'],
                        'product_code' => $item['product_code'],
                        'product_name' => $item['product_name'],
                        'quantity' => $item['product_quantity'],
                        'unit_name' => $item['product_unit'],
                        'money' => str_replace(',', '', $item["product_money"]),
                        'status' => 'new',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ];
                }

                if (count($dataIncurred) != 0) {
                    $mTicketAcceptanceIncurred->createdTicketAcceptance($dataIncurred);
                }
            }

            $mTicketFile->removeFileAcceptance($data['ticket_id']);
            if (isset($data['pathFile'])) {
                $arrFile = [];
                foreach ($data['pathFile'] as $item) {
                    $type = explode('.', $item['path']);
                    $last = end($type);
                    $arrFile[] = [
                        'ticket_id' => $data['ticket_id'],
                        'type' => in_array(strtolower($last), ['jpg', 'jpeg', 'png']) ? 'image' : 'file',
                        'group' => 'acceptance',
                        'path' => $item['path'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                }

                if (count($arrFile) != 0) {
                    $mTicketFile->createFile($arrFile);
                }
            }

            DB::commit();

            $mNoti = new SendNotificationApi();
            $listCustomer = $this->getListStaff($data['ticket_id']);
            foreach ($listCustomer as $item) {
                $mNoti->sendStaffNotification([
                    'key' => 'acceptance_edit',
                    'customer_id' => $item,
                    'object_id' => $idAcceptance
                ]);
            }
            $item = $mTicketAcceptance->getItem($idAcceptance);
            $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã cập nhật biên bản nghiệm thu ' . createATag(route('ticket.acceptance', ['search' => $item->ticket_acceptance_code]), $item->ticket_acceptance_code);
            $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' has updated the acceptance ' . createATag(route('ticket.acceptance', ['search' => $item->ticket_acceptance_code]), $item->ticket_acceptance_code);
            $this->createHistory($note, $note_en, $item->ticket_id);

            return [
                'error' => false,
                'message' => __('ticket::acceptance.edit_acceptance_success'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('ticket::acceptance.edit_acceptance_error'),
            ];
        }
    }

//    Tạo mã
    public function createdCode()
    {
        $mTicketAcceptance = new TicketAcceptanceTable();
        $codeTicket = 'BBNT' . Carbon::now()->format('Ymd') . '_';

//        Lấy phiếu thu mới nhất
        $getTicketDetailCode = $mTicketAcceptance->getAcceptanceNew($codeTicket);
        if ($getTicketDetailCode == null) {
            return $codeTicket . '001';
        } else {
            $arr = explode($codeTicket, $getTicketDetailCode);
            $value = strval(intval($arr[1]) + 1);
            $zero_str = "";
            if (strlen($value) < 7) {
                for ($i = 0; $i < (3 - strlen($value)); $i++) {
                    $zero_str .= "0";
                }
            }

            return $codeTicket . $zero_str . $value;
        }

    }

//    Danh sách vật tư phát sinh
    public function listIncurred($ticket_acceptance_id)
    {
        $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
        return $mTicketAcceptanceIncurred->getListProduct($ticket_acceptance_id);
    }

    //    Danh sách vật tư phát sinh
    public function listIncurredByTicketId($ticket_id)
    {
        $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
        return $mTicketAcceptanceIncurred->listIncurredByTicketId($ticket_id);
    }

//    lấy danh sách file theo ticket
    public function getListFile($ticketId)
    {
        $mTicketFile = new TicketFileTable();
        return $mTicketFile->getTicketFile($ticketId, 'acceptance');
    }

//    Lấy danh sách khách hàng để search
    public function getListCustomerSelect()
    {
        $mCustomers = new Customers();
        return $mCustomers->getName();
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
}