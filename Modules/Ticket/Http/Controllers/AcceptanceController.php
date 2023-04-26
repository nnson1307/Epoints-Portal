<?php

namespace Modules\Ticket\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Ticket\Repositories\Acceptance\AcceptanceRepositoryInterface;
#use Modules\Ticket\Repositories\AcceptanceDetail\AcceptanceDetailRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\Facades\Cookie;
use Modules\Ticket\Models\ProductInventoryTable;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

class AcceptanceController extends Controller
{
    protected $acceptance;
    protected $staff;
    protected $ticket;
    protected $listAcceptance;
    protected $acceptanceDetail;
    
    public function __construct(
        AcceptanceRepositoryInterface $acceptance,
        // AcceptanceDetailRepositoryInterface $acceptanceDetail,
        StaffRepositoryInterface $staff,
        TicketRepositoryInterface $ticket
    )
    {
        $this->acceptance = $acceptance;
        // $this->materialDetail = $materialDetail;
        $this->staff = $staff;
        $this->ticket = $ticket;
        $this->listAcceptance = new ProductInventoryTable;
    }

    public function indexAction(Request $request)
    {
        #lấy danh sách vật tư dạng option,
        $filters['get_option'] = 1;
        return view('ticket::acceptance.index', [
            'list' => $this->acceptance->list(),
            'filter' => $this->filters(),
            'statusMaterialItem' => $this->statusacceptanceItem(),
            'staff' => $this->staff->getName(),
            'searchConfig' => $this->searchColumn(),
            'showColumn' => $this->showColumn(),
            'listTicket' => $this->ticket->getTicketCode(),
            'listAcceptance' => $this->listAcceptance->getListProductInventory($filters),
        ]);
    }

    // lấy danh sách trạng thái vật tư có 3 trạng thái
    protected function filters()
    {
        return [
            '' => __('Chọn trạng thái'),
            'new' => __('Mới'),
            'approve' => __('Chấp nhận'),
            'cancel' => __('Hủy'),
        ];
    }

    protected function statusAcceptanceItem()
    {
        return [
            'new' => __('Mới'),
            'approve' => __('Duyệt'),
            'cancel' => __('Từ chối'),
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->all();
        return view('ticket::acceptance.list', [
                'list' => $this->acceptance->list($filters),
                'filter' => $this->filters(),
                'showColumn' => $this->showColumn(),
                'page' => $filters['page']
            ]
        );
    }

    // ajax lưu cấu hình tìm kiếm + table
    public function saveConfig(Request $request)
    {
        $data = [
            'route_name' => 'ticket.acceptance.save-config',
            'search' => $request->search,
            'column' => $request->column,
        ];
        $data = serialize($data);
        Cookie::queue('acceptance_token', $data, 3600);
        return response()->json(['status' => 1,'data'=> $data]);
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
            1 =>[
                "active" => 1,
                "placeholder" => __("Nhập thông tin tìm kiếm"),
                "type" => "text",
                "class" => "form-control",
                "name" => "search",
                "id" => "search",
                "data" => "",
                "nameConfig" => __("Thông tin tìm kiếm"),
            ],
            2 =>[
                "active" => 1,
                "placeholder" => __("Nhập mã ticket"),
                "type" => "text",
                "class" => "form-control",
                "name" => "ticket_code",
                "id" => "ticket_code",
                "data" => "",
                "nameConfig" => __("Nhập mã ticket"),
            ],
            3 =>[
                "active" => 1,
                "placeholder" => __("Chọn trạng thái"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "status",
                "id" => "status",
                "data" => [
                    'new' => __('Mới'),
                    'approve' => __('Đã ký')
                ],
                "nameConfig" => __("Chọn trạng thái"),
            ],
            4 =>[
                "active" => 1,
                "placeholder" => __("Chọn khách hàng"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "customer_id",
                "id" => "customer_id",
                "data" => $this->acceptance->getListCustomerSelect(),
                "nameConfig" => __("Chọn khách hàng"),
            ],
            5 =>[
                "active" => 1,
                "placeholder" => __("Chọn người tạo"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "created_by",
                "id" => "created_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Chọn người tạo"),
            ],
            6 =>[
                "active" => 1,
                "placeholder" => __("Nhập người ký"),
                "type" => "text",
                "class" => "form-control",
                "name" => "sign_by",
                "id" => "sign_by",
                "data" => "",
                "nameConfig" => __("Nhập người ký"),
            ],
            7 =>[
                "active" => 1,
                "placeholder" => __("Chọn ngày ký"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "sign_date",
                "id" => "sign_date",
                "data" => "",
                "nameConfig" => __("Chọn ngày ký"),
            ]
        ];
        // kiểm tra cookie thanh search + column
        $config = Cookie::get('acceptance_token');
        if($config){
            $config = unserialize($config);
            foreach($data as $key => $value){
                if ($config['search'] != null){
                    if(!in_array($key,$config['search'])){
                        $data[$key]['active'] = 0;
                    }
                }
            }
        }
        return $data;
    }

     // hiển thị cấu hình table
    public function showColumn()
    {
        $data = [
            1 =>[
                "name" => "#",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("#"),
                "column_name" => "count",
                "type" => "label",
                "view_detail" => 0
            ],
            2 =>[
                "name" => __("Mã biên bản"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Mã biên bản"),
                "column_name" => "ticket_acceptance_code",
                "type" => "label",
                "view_detail" => 1
            ],
            3 =>[
                "name" => __("Tên biên bản"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tên biên bản"),
                "column_name" => "title",
                "type" => "label",
                "view_detail" => 0
            ],
            4 =>[
                "name" => __("Mã ticket"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Mã ticket"),
                "column_name" => "ticket_code",
                "type" => "label",
                "view_detail" => 0
            ],
            5 =>[
                "name" => __("Khách hàng"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Khách hàng"),
                "column_name" => "customer_name",
                "type" => "label",
                "view_detail" => 0
            ],
            6 =>[
                "name" => __("Người tạo"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người tạo"),
                "column_name" => "created_name",
                "type" => "label",
                "view_detail" => 0
            ],
            7 =>[
                "name" => __("Người ký"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người ký"),
                "column_name" => "sign_by",
                "type" => "label",
                "view_detail" => 0
            ],
            8 =>[
                "name" => __("Ngày ký"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày ký"),
                "column_name" => "sign_date",
                "type" => "label",
                "view_detail" => 0
            ],
            9 =>[
                "name" => __("Thời gian cập nhật"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Thời gian cập nhật"),
                "column_name" => "updated_at",
                "type" => "label",
                "view_detail" => 0
            ],
            10 =>[
                "name" => __("Trạng thái"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "column_name" => "status",
                "type" => "status",
                "view_detail" => 0,
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
                        'name' => __('Đã ký'),
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
        $config = Cookie::get('acceptance_token');
        if($config){
            $config = unserialize($config);
            foreach($data as $key => $value){
                if ($config['search'] != null){
                    if(!in_array($key,$config['column'])){
                        $data[$key]['active'] = 0;
                    }
                }
            }
        }
        return $data;
    }

//    Giao diện biên bản nghiệm thu
    public function addAction($idTicket = null){
        $listTicket = $this->acceptance->getListTicketNotAcceptance();
        $listFile = $this->acceptance->getListFile($idTicket);
        return view('ticket::acceptance.add', [
            'listTicket' => $listTicket,
            'idTicket' => $idTicket,
            'listFile' => $listFile
        ]);
    }

//    Giao diện chỉnh sửa
    public function editAction($id){
        $detailAcceptance = $this->acceptance->getItem($id);
        if (in_array($detailAcceptance['status'],['approve','cancel'])){
            return redirect()->route('ticket.acceptance');
        }
        $listIncurred = $this->acceptance->listIncurred($id);
        $listTicket = $this->acceptance->getListTicketNotAcceptance($id);
        $listFile = $this->acceptance->getListFile($detailAcceptance['ticket_id']);
        return view('ticket::acceptance.edit', [
            'listTicket' => $listTicket,
            'detailAcceptance' => $detailAcceptance,
            'listIncurred' => $listIncurred,
            'listFile' => $listFile
        ]);
    }

//    Giao diện chỉnh sửa
    public function detailAction($id){
        $detailAcceptance = $this->acceptance->getItem($id);
        $listIncurred = $this->acceptance->listIncurred($id);
        $listTicket = $this->acceptance->getListTicketNotAcceptance($id);
        $listFile = $this->acceptance->getListFile($detailAcceptance['ticket_id']);
        return view('ticket::acceptance.detail', [
            'listTicket' => $listTicket,
            'detailAcceptance' => $detailAcceptance,
            'listIncurred' => $listIncurred,
            'listFile' => $listFile
        ]);
    }

    /**
     * Lấy tin theo ticket
     * @param Request $request
     */
    public function changeTicket(Request $request){
        $param = $request->all();
        $changeTicket = $this->acceptance->changeTicket($param);
        return response()->json($changeTicket);
    }

    /**
     * Show popup add thêm vật tư phát sinh
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupAddProduct(Request $request){
        $param = $request->all();
        $showPopupAddProduct = $this->acceptance->showPopupAddProduct($param);
        return response()->json($showPopupAddProduct);
    }

    /**
     * Lưu sản phẩm đã chọn vào danh sách
     * @param Request $request
     */
    public function addProductIncurredList(Request $request){
        $param = $request->all();
        $addProductIncurredList = $this->acceptance->addProductIncurredList($param);
        return response()->json($addProductIncurredList);
    }

    /**
     * Select lấy danh sách vật tư
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProductSelect(Request $request){
        $param = $request->all();
        $listProductSelect = $this->acceptance->listProductSelect($param);
        return response()->json($listProductSelect);
    }

    /**
     * Tạo biên bản nghiệm thu
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAcceptance(Request $request){
        $param = $request->all();
        $createAcceptance = $this->acceptance->createAcceptance($param);
        return response()->json($createAcceptance);
    }

    /**
     * Chỉnh sửa biên bản nghiệm thu
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAcceptance(Request $request){
        $param = $request->all();
        $editAcceptance = $this->acceptance->editAcceptance($param);
        return response()->json($editAcceptance);
    }
}