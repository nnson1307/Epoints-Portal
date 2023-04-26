<?php

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\Customers;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardGroup\ServiceCardGroupRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;
use Modules\Admin\Repositories\UploadImage\UploadImageRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ServiceCardController extends Controller
{
    protected $service_card;
    protected $serviceCardGroup;
    protected $service;
    protected $uploadImage;
    protected $codeGenerator;
    protected $service_card_list;
    protected $staff;
    protected $customer;
    protected $branch;
    protected $orderDetail;
    protected $customerServiceCard;
    protected $service2;
    protected $customer2;

    public function __construct(
        ServiceCardRepositoryInterface $cardRepository,
        UploadImageRepositoryInterface $uploadImage,
        CodeGeneratorRepositoryInterface $codeGeneratorRepository,
        ServiceCardListRepositoryInterface $cardListRepository,
        OrderDetailRepositoryInterface $orderDetail,
        CustomerServiceCardRepositoryInterface $customerServiceCard,
        ServiceCardGroupRepositoryInterface $serviceCardGroup,
        ServiceRepositoryInterface $service2,
        CustomerRepository $customer2

    )
    {
        $this->service_card = $cardRepository;
        $this->uploadImage = $uploadImage;
        $this->codeGenerator = $codeGeneratorRepository;
        $this->service_card_list = $cardListRepository;
        $this->orderDetail = $orderDetail;
        $this->customerServiceCard = $customerServiceCard;
        $this->service2 = $service2;
        $this->customer2 = $customer2;


        $this->serviceCardGroup = $serviceCardGroup;
        $this->service = new \Modules\Admin\Models\Service();
        $this->staff = new StaffsTable();
        $this->customer = new Customers();
        $this->branch = new BranchTable();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexAction()
    {
        $branchList = $this->branch->getBranch();
        $dataAllBranch = $this->listServiceCardAll();
        $result = collect($dataAllBranch)->forPage(1, 10);
        return view('admin::service-card.index', [
            'FILTER' => $this->filters(),
            'BRANCH' => $branchList,
            'data' => $dataAllBranch,
            'page' => 1,
            'LIST' => $result,
        ]);
    }

    protected function filters()
    {
        $group = $this->serviceCardGroup->getAllName();
        $arr = $group->pluck("name", "service_card_group_id")->toArray();
        $group = (["" => __("Chọn nhóm")]) + $arr;
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
            'service_card_type' => [
                "data" => [
                    '' => __('Chọn loại thẻ'),
                    "money" => __("Thẻ tiền"),
                    "service" => __("Dịch vụ")
                ]
            ],
            'service_cards$service_card_group_id' => [
                "data" => $group
            ]
        ];
    }

    public function listAction(Request $request)
    {
//        $filter = $request->only(['page', 'display', 'search_keyword', 'is_actived', 'service_card_type', 'service_cards$service_card_group_id', 'created_at']);
//        $List = $this->service_card->list($filter);
//        return view('admin::service-card.list', ['LIST' => $List]);
    }

    /*
     * create
     *
     * */

    public function createAction(Request $request)
    {

        $group = ['' => __('Chọn nhóm thẻ')] + $this->serviceCardGroup->getOption();
//        $service = $this->service->getServiceName();
//        $service = array_merge(["" => "Chọn dịch vụ"], $service->pluck("service_name", "service_id")->toArray());
        $service =  $this->service2->getServiceOption();
        $code_group = $this->codeGenerator->generateServiceCardCode(reset($group));
        return view("admin::service-card.create", [
            "_group_card" => $group,
            "_service" => $service,
            "_code_group" => $code_group
        ]);

    }

    /**
     * Thêm thẻ dịch vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitCreateAction(Request $request)
    {
        $cardGroup = $request->cardGroup;
        $name = $request->name;
        $price = $request->price;
        $serviceId = $request->serviceId;
        $money = $request->money;
        $typeDateUsing = $request->typeDateUsing;
        $dateUsing = $request->dateUsing;
        $numberUsing = $request->numberUsing;
        $description = $request->description;
        $image = $request->image;
        $typeService = $request->typeService;
        $code = $this->codeGenerator->generateServiceCardCode('');
        $isSurcharge = $request->is_surcharge;
        if ($request->type_refer_commission == 'percent') {
            if ($request->refer_commission_percent > 100) {
                return response()->json([
                    'error_refer_commission' => 1,
                    'message' => __('Hoa hồng người giới thiệu không hợp lệ')
                ]);
            }
        }
        if ($request->type_refer_commission == 'money') {
            if ($request->refer_commission_value > str_replace(',', '', $price)) {
                return response()->json([
                    'error_refer_commission' => 1,
                    'message' => __('Hoa hồng người giới thiệu vươt quá giá thẻ')
                ]);
            }
        }
        if ($request->type_staff_commission == 'percent') {
            if ($request->staff_commission_percent > 100) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng nhân viên phục vụ không hợp lệ')
                ]);
            }
        }
        if ($request->type_staff_commission == 'money') {
            if ($request->staff_commission_value > str_replace(',', '', $price)) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng nhân viên phục vụ vượt quá giá thẻ')
                ]);
            }
        }
        // hoa hong cho deal
        if ($request->type_deal_commission == 'percent') {
            if ($request->deal_commission_percent > 100) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng cho deal không hợp lệ')
                ]);
            }
        }
        if ($request->type_deal_commission == 'money') {
            if ($request->deal_commission_value > str_replace(',', '', $price)) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng cho deal vượt quá giá thẻ')
                ]);
            }
        }
        $data = [
            'service_card_group_id' => $cardGroup,
            'name' => $name,
            'slug' => str_slug($name),
            'service_card_type' => $typeService,
            'number_using' => $numberUsing,
            'price' => str_replace(',', '', $price),
            'image' => $image,
            'created_by' => Auth::id(),
            'code' => $code,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'type_refer_commission' => $request->type_refer_commission,
            'refer_commission_value' => $request->type_refer_commission == 'money' ? $request->refer_commission_value : $request->refer_commission_percent,
            'type_staff_commission' => $request->type_staff_commission,
            'staff_commission_value' => $request->type_staff_commission == 'money' ? $request->staff_commission_value : $request->staff_commission_percent,
            'type_deal_commission' => $request->type_deal_commission,
            'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent,
            'is_surcharge' => $isSurcharge,
            'is_remind' => $request->is_remind,
            'remind_value' => $request->is_remind == 1 ? $request->remind_value : null
        ];
        if ($typeService == 'service') {
            if (isset($serviceId)) {
                $data['service_id'] = intval($serviceId);
            } else {
                $data['service_id'] = 0;
            }
        } else {
            $data['money'] = str_replace(',', '', $money);
        }
        if ($typeDateUsing == 'week') {
            $data['date_using'] = str_replace('.', '', $dateUsing) * 7;
        } else if ($typeDateUsing == 'month') {
            $data['date_using'] = str_replace('.', '', $dateUsing) * 30;
        } else if ($typeDateUsing == 'year') {
            $data['date_using'] = str_replace('.', '', $dateUsing) * 365;
        } else {
            $data['date_using'] = str_replace('.', '', $dateUsing);
        }
        if ($image != null) {
            $data["image"] = $image;
        }
        $this->service_card->add($data);
        return response()->json(['error' => 0]);

    }

    /*
     *
     * edit
     * */

    public function editAction(Request $request, $id)
    {
        $card = $this->service_card->getServiceCardInfo($id);
        $type = '';
        $size = '';
        $width = '';
        $height = '';
        if ($card != null) {
            $card['refer_commission_value'] = !empty($card['refer_commission_value']) ? $card['refer_commission_value'] : 0;
            $card['staff_commission_value'] = !empty($card['staff_commission_value']) ? $card['staff_commission_value'] : 0;

            $groupCard = $this->serviceCardGroup->getOption();
            $service = $this->service2->getServiceOption();
            if ($card->image != null && $card->image != "" && Storage::disk('public')->exists($card->image)) {
                $getimagesize = getimagesizefromstring(Storage::disk('public')->get($card->image));
                $type = strtoupper(substr($card->image, strrpos($card->image, '.') + 1));
                $width = $getimagesize[0];
                $height = $getimagesize[1];
                $size = (int)round(Storage::disk('public')->size($card->image) / 1024);
            }
            return view("admin::service-card.edit", [
                "_group_card" => $groupCard,
                "_service" => $service,
                "_card" => $card,
                'type' => $type,
                'size' => $size,
                'width' => $width,
                'height' => $height,
            ]);
        } else {
            return redirect()->route('admin.service-card');
        }

    }

    /**
     * Chỉnh sửa thẻ dịch vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $cardGroup = $request->cardGroup;
        $name = $request->name;
        $price = $request->price;
        $serviceId = $request->serviceId;
        $money = $request->money;
        $typeDateUsing = $request->typeDateUsing;
        $dateUsing = $request->dateUsing;
        $numberUsing = $request->numberUsing;
        $description = $request->description;
        $image = $request->image;
        $typeService = $request->typeService;
        $oldImage = $request->oldImage;
        $isSurcharge = $request->is_surcharge;
        if ($request->type_refer_commission == 'percent') {
            if ($request->refer_commission_percent > 100) {
                return response()->json([
                    'error_refer_commission' => 1,
                    'message' => __('Hoa hồng người giới thiệu không hợp lệ')
                ]);
            }
        }
        if ($request->type_refer_commission == 'money') {
            if ($request->refer_commission_value > str_replace(',', '', $price)) {
                return response()->json([
                    'error_refer_commission' => 1,
                    'message' => __('Hoa hồng người giới thiệu vươt quá giá thẻ')
                ]);
            }
        }
        if ($request->type_staff_commission == 'percent') {
            if ($request->staff_commission_percent > 100) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng nhân viên phục vụ không hợp lệ')
                ]);
            }
        }
        if ($request->type_staff_commission == 'money') {
            if ($request->staff_commission_value > str_replace(',', '', $price)) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng nhân viên phục vụ vượt quá giá thẻ')
                ]);
            }
        }
        // hoa hong cho deal
        if ($request->type_deal_commission == 'percent') {
            if ($request->deal_commission_percent > 100) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng cho deal không hợp lệ')
                ]);
            }
        }
        if ($request->type_deal_commission == 'money') {
            if ($request->deal_commission_value > str_replace(',', '', $price)) {
                return response()->json([
                    'error_staff_commission' => 1,
                    'message' => __('Hoa hồng cho deal vượt quá giá thẻ')
                ]);
            }
        }
        $data = [
            'service_card_group_id' => $cardGroup,
            'name' => $name,
            'slug' => str_slug($name),
            'service_card_type' => $typeService,
            'number_using' => $numberUsing,
            'price' => str_replace(',', '', $price),
            'image' => $oldImage,
            'created_by' => Auth::id(),
            'description' => $description,
            'updated_at' => date('Y-m-d H:i:s'),
            'type_refer_commission' => $request->type_refer_commission,
            'refer_commission_value' => $request->type_refer_commission == 'money' ? $request->refer_commission_value : $request->refer_commission_percent,
            'type_staff_commission' => $request->type_staff_commission,
            'staff_commission_value' => $request->type_staff_commission == 'money' ? $request->staff_commission_value : $request->staff_commission_percent,
            'type_deal_commission' => $request->type_deal_commission,
            'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent,
            'is_surcharge' => $isSurcharge,
            'is_remind' => $request->is_remind,
            'remind_value' => $request->is_remind == 1 ? $request->remind_value : null
        ];

        if ($typeService == 'service') {
            $data['service_id'] = intval($serviceId);
        } else {
            $data['money'] = str_replace(',', '', $money);
        }
        if ($typeDateUsing == 'week') {
            $data['date_using'] = str_replace('.', '', $dateUsing) * 7;
        } else if ($typeDateUsing == 'month') {
            $data['date_using'] = str_replace('.', '', $dateUsing) * 30;
        } else if ($typeDateUsing == 'year') {
            $data['date_using'] = str_replace('.', '', $dateUsing) * 365;
        } else {
            $data['date_using'] = str_replace('.', '', $dateUsing);
        }

        if ($image != null) {
            $data["image"] = $image;
            if (Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $edit = $this->service_card->edit($id, $data);
        if ($edit == 1) {
            return response()->json(['error' => 0]);
        } else {
            return response()->json(['error' => 1]);
        }

    }

    public function deleteAction($id)
    {
        try {
            $this->service_card->delete($id);
            return response()->json([
                'error' => 0,
                'message' => __('Xóa thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 1,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function getDetailAction($id)
    {

        $serviceCardDetail = $this->service_card->detail($id);
        $branch = $this->branch->getName();
        $staff = $this->staff->getName();
        $customers = $this->customer->getName();
        $getServiceCardByAllBranch = $this->orderDetail->getServiceCardByAllBranch(null, null, null, null, $id);
        $arrayAllServiceCard = [];
        foreach ($getServiceCardByAllBranch as $key => $value) {
            $arrayCodeServiceCard = explode(',', $value['service_code']);
            foreach ($arrayCodeServiceCard as $key2 => $value2) {
                $isActive = 0;
                if ($this->customerServiceCard->getCardByCode($value2) != null) {
                    $isActive = 1;
                }
                $arrayAllServiceCard[] = [
                    'code' => $value2,
                    'customer' => $value['full_name'],
                    'branch' => $value['branch_name'],
                    'createdAt' => date('d/m/Y', strtotime($value['created_at'])),
                    'isActived'=>$isActive
                ];
            }
        }

        $arrayServiceCardPaginate = collect($arrayAllServiceCard)->forPage(1, 10);


        $listServiceCardUsed = $this->customerServiceCard->getServiceCardUsed($id);
        $arrayAllCardUsed = collect($listServiceCardUsed)->forPage(1, 10);

        return view("admin::service-card.detail", [
            "serviceCard" => $serviceCardDetail,
            "arrayAllServiceCard" => $arrayServiceCardPaginate,
            "listServiceCardUsed" => $listServiceCardUsed,
            "page" => 1,
            "data" => $arrayAllServiceCard,
            "idCard" => $id,
            "dataCardUsed" => $listServiceCardUsed,
            "arrayAllCardUsed" => $arrayAllCardUsed,
        ]);
    }


    public function listDetailAction(Request $request)
    {

        $filter = $request->only(['service_card_id', 'page', 'display', 'search_type', 'search_keyword', 'is_actived', 'staff', 'customer', 'branch', 'actived_date', 'created_at']);
        var_dump($filter);
        $card_list = $this->service_card_list->getAllByServiceCard($filter["service_card_id"], $filter);
        return view('admin::service-card.inc.table-card-list', ['LIST' => $card_list]);
    }

    public function getServiceTypeTemplate(Request $request)
    {
        $type = $request->input("service_type");
        $arr = [];

        if ($request->has("service_card_id")) {
            $id = $request->input("service_card_id");
            $card = $this->service_card->getServiceCardInfo($id);

            $arr = [
                "_card" => $card
            ];
        }
//        dd($arr);

        if ($type == "money") {
            $html = View::make("admin::service-card.inc.money-type", $arr)->render();
        } else {
            $service = $this->service->getServiceName();
            $service = ["" => "Chọn dịch vụ"] + $service->pluck("service_name", "service_id")->toArray();
            $arr["_service"] = $service;
            $html = View::make("admin::service-card.inc.service-type", $arr)->render();
        }
        return response()->json(["html" => $html]);
    }

    public function addNewServiceCardGroup(Request $request)
    {
        $params = $request->only("nameGroup", "description");
        $check = $this->serviceCardGroup->checkName(null, $params['nameGroup']);
        if ($check == null) {
            $data = ['name' => $params['nameGroup'], 'description' => $params['description']];
            $id = $this->serviceCardGroup->add($data);
            return response()->json(["id" => $id, "name" => $params["nameGroup"], "error" => 0]);
        } else {
            return response()->json(["error" => 1]);
        }

    }

    public function uploadsImageAction(Request $request)
    {
        if ($request->hasFile("image_file")) {
            return $this->uploadImage->uploadSingleFile($request->file("image_file"));
        } else {
            return response()->json(["status" => "error"]);
        }
    }

    public function deleteUploadAction(Request $request)
    {
        return $this->uploadImage->deleteTempImage($request->input("file"));
    }

    private function listServiceCardAll()
    {
        //Kết quả.
        $result = [];
        //Danh sách chi nhánh.
        $branchList = $this->branch->getBranch();
        $serviceCard = $this->service_card->getAllServiceCard();
        $dataSelect = $this->orderDetail->getServiceCardByAllBranch();

        foreach ($serviceCard as $key => $value) {
            $arrayServiceCard = [];
            $arrayServiceCard['id'] = $value['service_card_id'];
            $arrayServiceCard['name'] = $value['name'];
            $arrayServiceCard['price'] = $value['price'];
            $arrayServiceCard['type'] = $value['service_card_type'];
            $arrayServiceCard['quantity'] = 0;
            $arrayServiceCard['image'] = $value['image'];
            $arrayServiceCard['is_actived'] = $value['is_actived'];
            $arrayServiceCard['is_surcharge'] = $value['is_surcharge'];
            foreach ($branchList as $key2 => $value2) {
                $arrayServiceCard['branch'][$value2['branch_id']] = 0;
            }
            //Tính tổng số lượng đã bán của thẻ dịch vụ.
            foreach ($dataSelect as $key3 => $value3) {
                if ($value['service_card_id'] == $value3['object_id']) {
                    $arrayServiceCard['quantity'] += $value3['quantity'];
                }
            }

            foreach ($arrayServiceCard['branch'] as $key4 => $value4) {
                foreach ($dataSelect as $key5 => $value5) {
                    if ($value['service_card_id'] == $value5['object_id'] && $key4 == $value5['branch_id']) {
                        $arrayServiceCard['branch'][$key4] += $value5['quantity'];
                    }
                }
            }
            $result[] = $arrayServiceCard;
        }

        return $result;
    }

    //Phân trang (tất cả thẻ dịch vụ).
    public function pagingAction(Request $request)
    {
        $page = $request->page;
        //Danh sách chi nhánh.
        $branchList = $this->branch->getBranch();
        $data = $this->listServiceCardAll();

        $result = collect($data)->forPage($page, 10);
        $contents = view('admin::service-card.paging', [
            'data' => $data,
            'LIST' => $result,
            'BRANCH' => $branchList,
            'page' => $page
        ])->render();
        return $contents;
    }

    private function listServiceCardFilter($keyWord, $status, $cardType, $cardGroup)
    {
        //Kết quả.
        $result = [];
        //Danh sách chi nhánh.
        $branchList = $this->branch->getBranch();
        $serviceCard = $this->service_card->filter($keyWord, $status, $cardType, $cardGroup);

        $dataSelect = $this->orderDetail->getServiceCardByAllBranch($keyWord, $status, $cardType, $cardGroup);
        foreach ($serviceCard as $key => $value) {
            $arrayServiceCard = [];
            $arrayServiceCard['id'] = $value['service_card_id'];
            $arrayServiceCard['name'] = $value['card_name'];
            $arrayServiceCard['price'] = $value['price'];
            $arrayServiceCard['type'] = $value['service_card_type'];
            $arrayServiceCard['quantity'] = 0;
            $arrayServiceCard['image'] = $value['image'];
            $arrayServiceCard['is_actived'] = $value['is_actived'];
            foreach ($branchList as $key2 => $value2) {
                $arrayServiceCard['branch'][$value2['branch_id']] = 0;
            }
            //Tính tổng số lượng đã bán của thẻ dịch vụ.
            foreach ($dataSelect as $key3 => $value3) {
                if ($value['service_card_id'] == $value3['object_id']) {
                    $arrayServiceCard['quantity'] += $value3['quantity'];
                }
            }
            foreach ($arrayServiceCard['branch'] as $key4 => $value4) {
                foreach ($dataSelect as $key5 => $value5) {
                    if ($value['service_card_id'] == $value5['object_id'] && $key4 == $value5['branch_id']) {
                        $arrayServiceCard['branch'][$key4] += $value5['quantity'];
                    }
                }
            }
            $result[] = $arrayServiceCard;
        }

        return $result;
    }

    public function filterAction(Request $request)
    {
        $keyWord = $request->keyWord;
        $status = $request->status;
        $cardType = $request->cardType;
        $cardGroup = $request->cardGroup;
        $dataSelect = $this->listServiceCardFilter($keyWord, $status, $cardType, $cardGroup);

        $result = collect($dataSelect)->forPage(1, 10);
        $branchList = $this->branch->getBranch();
        $contents = view('admin::service-card.filter', [
            'data' => $dataSelect,
            'LIST' => $result,
            'BRANCH' => $branchList,
            'page' => 1
        ])->render();
        return $contents;
    }

    //Phân trang (khi filter).
    public function pagingResultFilterAction(Request $request)
    {
        $page = $request->page;
        $keyWord = $request->keyWord;
        $status = $request->status;
        $cardType = $request->cardType;
        $cardGroup = $request->cardGroup;
        $branchList = $this->branch->getBranch();
        $dataSelect = $this->listServiceCardFilter($keyWord, $status, $cardType, $cardGroup);
        $result = collect($dataSelect)->forPage($page, 10);
        $contents = view('admin::service-card.filter-paging', [
            'data' => $dataSelect,
            'LIST' => $result,
            'BRANCH' => $branchList,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function pagingDetailAllServiceCard(Request $request)
    {
        $page = $request->page;
        $id = $request->idCard;

        $getServiceCardByAllBranch = $this->orderDetail->getServiceCardByAllBranch(null, null, null, null, $id);

        $arrayAllServiceCard = [];
        foreach ($getServiceCardByAllBranch as $key => $value) {
            $arrayCodeServiceCard = explode(',', $value['service_code']);
            foreach ($arrayCodeServiceCard as $key2 => $value2) {
                $isActive = 0;
                if ($this->customerServiceCard->getCardByCode($value2) != null) {
                    $isActive = 1;
                }
                $arrayAllServiceCard[] = [
                    'code' => $value2,
                    'customer' => $value['full_name'],
                    'branch' => $value['branch_name'],
                    'createdAt' => date('d/m/Y', strtotime($value['created_at'])),
                    'isActived'=>$isActive
                ];
            }
        }

        $arrayServiceCardPaginate = collect($arrayAllServiceCard)->forPage($page, 10);

        $contents = view('admin::service-card.paging-detail', [
            'arrayAllServiceCard' => $arrayAllServiceCard,
            'page' => $page,
            'arrayServiceCardPaginate' => $arrayServiceCardPaginate
        ])->render();
        return $contents;
    }

    public function uploadAvatar(Request $request)
    {
        if ($request->image != null) {
            $path = TEMP_PATH . '/' . $request->image;
            Storage::disk('public')->delete($path);
        }
        $time = Carbon::now();
        // Requesting the file from the form
        $image = $request->file('file');
        if ($image != null) {
            // Getting the extension of the file
            $extension = $image->getClientOriginalExtension();
            $filename = time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . '_' . str_random(10) . "." . $extension;
            // This is our upload main function, storing the image in the storage that named 'public'
            $upload_success = $image->storeAs(TEMP_PATH, $filename, 'public');
            // If the upload is successful, return the name of directory/filename of the upload.
            if ($upload_success) {
                return response()->json($filename, 200);
            } // Else, return error 400
            else {
                return response()->json('error', 400);
            }
        }
    }

    public function checkNameAction(Request $request)
    {
        $name = $request->name;
        $groupId = $request->groupId;
        $id = $request->id;
        $check = $this->service_card->checkName($name, intval($id), $groupId);
        if ($check != null) {
            return response()->json(['error' => 1]);
        } else {
            return response()->json(['error' => 0]);
        }
    }

    private function transferTempfileToAdminfile($path, $imgName)
    {
        Storage::disk('public')->makeDirectory(SERVICE_CARD_PATH);
        $new_path = SERVICE_CARD_PATH . $imgName;
        Storage::disk('public')->move($path, $new_path);
        return $new_path;
    }

    //Thẻ đã bán.

    //Thẻ dịch vụ đã bán.
    public function serviceCardSold()
    {
        $serviceCardSold = $this->service_card->getServiceCardSold('service');
        $result = $this->calculateServiceCardSold($serviceCardSold);
        $branch = $this->branch->getBranch();
        $staff = $this->staff->getStaffOption();
        $resultPaginate = collect($result)->forPage(1, 10);

        return view("admin::service-card.sold.service-card.service-card", [
            'LIST' => $resultPaginate,
            'branch' => $branch,
            'staff' => $staff,
            'page' => 1,
            'data' => $result,
        ]);
    }

    //Thẻ tiền đã bán.
    public function serviceMoneySold()
    {
        $moneyCardSold = $this->service_card->getServiceCardSold('money');
        $result = $this->calculateServiceCardSold($moneyCardSold);

        $branch = $this->branch->getBranch();
        $staff = $this->staff->getStaffOption();
        $resultPaginate = collect($result)->forPage(1, 10);
        return view("admin::service-card.sold.money-card.money-card", [
            'LIST' => $resultPaginate,
            'branch' => $branch,
            'staff' => $staff,
            'page' => 1,
            'data' => $result,
        ]);
    }

    private function calculateServiceCardSold($serviceCardSold)
    {
        $arrayServiceCard = [];
        //Lấy các mã thẻ trong đơn hàng.
        foreach ($serviceCardSold as $key => $value) {
            foreach (explode(',', $value['service_code']) as $key2 => $value2) {
                $arrayTemp = [];
                if ($value2 != '') {
                    $arrayTemp['card_code'] = $value2;
                    $arrayTemp['staff_sold_id'] = $value['staff_id'];
                    $arrayTemp['customer_id'] = $value['customer_id'];
                    $arrayTemp['is_actived'] = 0;
                    $arrayTemp['customer_actived'] = null;
                    $arrayTemp['staff_actived'] = null;
                    $arrayTemp['actived_date'] = null;
                    $arrayTemp['branch_id'] = $value['branch_id'];
                    $arrayTemp['service_card_name'] = $value['service_card_name'];
                    $arrayServiceCard[] = $arrayTemp;
                }
            }
        }

        $result = [];

        foreach ($arrayServiceCard as $key => $value) {
            $dataSelect = $this->customerServiceCard->getCardActiveByCode($value['card_code']);
            if (!empty($dataSelect)) {
                $arrayTemp = [];
                $arrayTemp['card_code'] = $value['card_code'];
                $arrayTemp['is_deleted'] = $dataSelect['is_deleted'];
                $arrayTemp['note'] = $dataSelect['note'];
                $arrayTemp['service_card_name'] = $value['service_card_name'];
                $arrayTemp['is_reserve'] = $dataSelect['is_reserve'];
                $arrayTemp['is_use'] = 1;
                $customer = $this->customer2->getItem($value['customer_id']);
                if ($customer != null) {
                    $arrayTemp['customer_pay'] = $customer->full_name;
                } else {
                    $arrayTemp['customer_pay'] = null;
                }
                $staff = $this->staff->getItem($value['staff_sold_id']);

                if ($staff != null) {
                    $arrayTemp['staff_sold'] = $staff->full_name;
                } else {
                    $arrayTemp['staff_sold'] = null;
                }

                if (isset($dataSelect['actived_date']) &&  $dataSelect['actived_date'] != null) {
                    $arrayTemp['is_actived'] = 1;
                    $arrayTemp['actived_date'] = $dataSelect->actived_date;
                    $customer = $this->customer2->getItem($dataSelect->customer_id);
                    if ($customer != null) {
                        $arrayTemp['customer_actived'] = $customer->full_name;
                    } else {
                        $arrayTemp['customer_actived'] = null;
                    }
                    $staff2 = $this->staff->getItem($dataSelect->created_by);
                    if ($staff2 != null) {
                        $arrayTemp['staff_actived'] = $staff2->full_name;
                    } else {
                        $arrayTemp['staff_actived'] = null;
                    }

                } else {
                    $arrayTemp['is_actived'] = 0;
                    $arrayTemp['actived_date'] = null;
                    $arrayTemp['customer_actived'] = null;
                    $arrayTemp['staff_actived'] = null;
                }
                $branch = $this->branch->getItem($value['branch_id']);
                if ($branch != null) {
                    $arrayTemp['branch'] = $branch->branch_name;
                } else {
                    $arrayTemp['branch'] = null;
                }

                //Kiểm tra có hạn sử dụng thẻ ko
                if ($dataSelect['expired_date'] != null &&
                    Carbon::createFromFormat('Y-m-d H:i:s', $dataSelect['expired_date'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                    $arrayTemp['is_use'] = 0;
                }

                // check điều kiện bảo lưu
                $arrayTemp['showButtonReserve'] = 1;

                $check = $this->validateCard($dataSelect);
                if ($check['error'] == true) {
                    $arrayTemp['showButtonReserve'] = 0;
                } else {
                    if ($dataSelect['is_reserve'] == 1) {
                        $arrayTemp['showButtonReserve'] = 0;
                    }
                }
                $result[] = $arrayTemp;
            } else {
                $arrayTemp = [];
                $arrayTemp['card_code'] = $value['card_code'];
                $arrayTemp['is_deleted'] = 0;
                $arrayTemp['note'] = null;
                $arrayTemp['service_card_name'] = $value['service_card_name'];
                $arrayTemp['is_use'] = 0;
                $customer = $this->customer2->getItem($value['customer_id']);
                if ($customer != null) {
                    $arrayTemp['customer_pay'] = $customer->full_name;
                } else {
                    $arrayTemp['customer_pay'] = null;
                }
                $staff = $this->staff->getItem($value['staff_sold_id']);

                if ($staff != null) {
                    $arrayTemp['staff_sold'] = $staff->full_name;
                } else {
                    $arrayTemp['staff_sold'] = null;
                }
                $arrayTemp['is_actived'] = 0;
                $arrayTemp['actived_date'] = null;
                $arrayTemp['customer_actived'] = null;
                $arrayTemp['staff_actived'] = null;

                $branch = $this->branch->getItem($value['branch_id']);
                if ($branch != null) {
                    $arrayTemp['branch'] = $branch->branch_name;
                } else {
                    $arrayTemp['branch'] = null;
                }
                $result[] = $arrayTemp;
            }
        }
        return $result;
    }

    //Lọc thẻ đã bán.
    public function filterCardSoldAction(Request $request)
    {
        $cardType = $request->cardType;
        $keyWord = $request->keyWord;
        $status = $request->status;
        $branch = $request->branch;
        $staff = $request->staff;
        $time = $request->time;
        $result = array_values($this->calculateFilterCardSold($cardType, $keyWord, $status, $branch, $staff, $time));
        $list = collect($result)->forPage(1, 10);
        $contents = view('admin::service-card.sold.service-card.filter', [
            'LIST' => $list,
            'page' => 1,
            'data' => $result,
            'cardType' => $cardType,
        ])->render();
        return $contents;
    }

    //Hàm tính kết quả filter thẻ đã bán.
    private function calculateFilterCardSold($cardType, $keyWord, $status, $branch, $staff, $time)
    {
        $startTime = null;
        $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $serviceCardSold = $this->service_card->getServiceCardSold($cardType);

        $result = $this->calculateServiceCardSold($serviceCardSold);
//        $search = $this->customerServiceCard->filterCardSold($cardType, $keyWord, $branch, $staff, $startTime, $endTime)->toArray();
        $search = $this->service_card_list->filterCardSold($cardType, $keyWord, $status, $branch, $staff, $startTime, $endTime);
        if ($search != null) {
            foreach ($result as $key => $value) {
                $flag = 0;
                foreach ($search as $key2 => $value2) {
                    if ($value2['card_code'] == $value['card_code']) {
                        $flag = 1;
                    }
                }
                if ($flag == 0) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

    /**
     * Chi tiết thẻ dv đã bán
     *
     * @param $type
     * @param $code
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function detailCardSold($type, $code)
    {
        if ($type == 'service') {
            $detail = $this->customerServiceCard->getDetailCardSold($code);
            // Lấy ảnh theo từng dòng
            foreach ($detail as $value) {
                $listImage = $this->service_card->getImageServiceCardSold($code, $value['order_code'])->toArray();
                $value['listImage'] = $listImage;
            }
            //Chi tiết thẻ dv
            $detailCardSold = $this->customerServiceCard->getCardByCode($code);
            $detailCardSoldUnActived = $this->service_card_list->getDetailByCode($code);
            if ($detailCardSoldUnActived == null) {
                if ($detail == null) {
                    if ($detailCardSold == null) {
                        return redirect()->route('admin.service-card.sold.service-card');
                    } else {
                        return view('admin::service-card.sold.service-card.detail', [
                            'code' => $code,
                            'detailCardSold' => $detailCardSold,
                        ]);
                    }
                } else {
                    return view('admin::service-card.sold.service-card.detail', [
                        'code' => $code,
                        'LIST' => $detail,
                        'page' => 0,
                        'detailCardSold' => $detailCardSold,
                    ]);
                }
            } else {
                return view('admin::service-card.sold.service-card.detail', [
                    'code' => $code,
                    'detailCardSold' => $detailCardSoldUnActived,
                ]);
            }
        } else {
            $detailCardSoldUnActived = $this->service_card_list->getDetailByCode($code);
            $detailCardSold = $this->customerServiceCard->getCardByCode($code);
            if ($detailCardSoldUnActived == null) {
                if ($detailCardSold == null) {
                    return redirect()->route('admin.service-card.sold.service-money');
                } else {
                    return view('admin::service-card.sold.money-card.detail', [
                        'code' => $code,
                        'detailCardSold' => $detailCardSold,
                    ]);
                }
            } else {
                return view('admin::service-card.sold.money-card.detail', [
                    'code' => $code,
                    'detailCardSold' => $detailCardSoldUnActived,
                ]);
            }
        }

    }

    /**
     * Form chỉnh sửa thẻ dịch vụ đã bán
     *
     * @param string $type
     * @param string $code
     * @return Response
     */
    public function editCardSold($type, $code)
    {
        if ($type == 'service') {
            $detail = $this->customerServiceCard->getDetailCardSold($code);

            $detailCardSold = $this->customerServiceCard->getCardByCode($code);
            $detailCardSoldUnActived = $this->service_card_list->getDetailByCode($code);
            if ($detailCardSoldUnActived == null) {
                if ($detail == null) {
                    if ($detailCardSold == null) {
                        return redirect()->route('admin.service-card.sold.service-card');
                    } else {
                        return view('admin::service-card.sold.service-card.edit', [
                            'code' => $code,
                            'detailCardSold' => $detailCardSold,
                        ]);
                    }
                } else {
                    return view('admin::service-card.sold.service-card.edit', [
                        'code' => $code,
                        'LIST' => $detail,
                        'page' => 0,
                        'detailCardSold' => $detailCardSold,
                    ]);
                }
            } else {
                return view('admin::service-card.sold.service-card.edit', [
                    'code' => $code,
                    'detailCardSold' => $detailCardSoldUnActived,
                ]);
            }
        } else {
            $detailCardSoldUnActived = $this->service_card_list->getDetailByCode($code);
            $detailCardSold = $this->customerServiceCard->getCardByCode($code);
            if ($detailCardSoldUnActived == null) {
                if ($detailCardSold == null) {
                    return redirect()->route('admin.service-card.sold.service-money');
                } else {
                    return view('admin::service-card.sold.money-card.edit', [
                        'code' => $code,
                        'detailCardSold' => $detailCardSold,
                    ]);
                }
            } else {
                return view('admin::service-card.sold.money-card.edit', [
                    'code' => $code,
                    'detailCardSold' => $detailCardSoldUnActived,
                ]);
            }
        }
    }

    /**
     * Cập nhật thông tin thẻ dịch vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCardSold(Request $request)
    {
        if (in_array('admin.service-card.sold.edit',session('routeList'))) {
            $validator = Validator::make($request->all(), [
                'note' => 'required',
                'count_using' => 'nullable|numeric',
                'number_using' => 'nullable|numeric',
            ], [
                'note.required' => __('Vui lòng nhập ghi chú'),
                'count_using.numeric' => __('Vui lòng chỉ nhập số'),
                'number_using.numeric' => __('Vui lòng chỉ nhập số'),
            ]);
            if ($validator->fails()) {
                return back()->with('errors', $validator->errors()->toArray());
            }

            $data = $request->all();
            $errors = [];

            if (! isset($data['not_limit'])) {
                $data['count_using'] = ($data['count_using']) ?? 0;
                $data['number_using'] = ($data['number_using']) ?? 0;

                if (($data['number_using'] - $data['count_using'] < 0)) {
                    $errors['count_using'][] = __('Số lần sử dụng phải ít hơn số thẻ dịch vụ');
                }

                if ($data['expired_date'] == null) {
                    $errors['expired_date'][] = __('Vui lòng chọn hạn sử dụng');
                }

            }

            if (count($errors) > 0) {
                return back()->withInput()->with('errors', $errors);
            }

            $result = $this->customerServiceCard->editCardSold($data);

            if (!$result['error']) {
                return redirect()->route('admin.service-card.sold.service-card');
            }
        }

        return back()->withInput()->with('failed', __('Cập nhật thất bại'));
    }

    public function listActionDetailCardSold(Request $request)
    {
        $detail = $this->customerServiceCard->getDetailCardSold($request->code,
            ['page' => $request->page, 'display' => $request->display]);
        // Lấy ảnh theo từng dòng
        foreach ($detail as $value) {
            $listImage = $this->service_card->getImageServiceCardSold($request->code, $value['order_code'])->toArray();
            $value['listImage'] = $listImage;
        }
        $contents = view('admin::service-card.sold.service-card.list-detail', [
            'LIST' => $detail,
            'page' => $request->page - 1
        ])->render();
        return $contents;
    }

    //Phân trang (tất cả thẻ đã bán).
    public function pagingCardSolAction(Request $request)
    {
        $page = intval($request->page);
        $cardType = $request->cardType;
        $serviceCardSold = $this->service_card->getServiceCardSold($cardType);

        $result = $this->calculateServiceCardSold($serviceCardSold);
        $list = collect($result)->forPage($page, 10);
        $contents = view('admin::service-card.sold.list-paging', [
            'data' => $result,
            'LIST' => $list,
            'page' => $page,
            'cardType' => $cardType,
        ])->render();
        return $contents;
    }

    public function pagingCardSoldFilter(Request $request)
    {
        $cardType = $request->cardType;
        $keyWord = $request->keyWord;
        $status = $request->status;
        $branch = $request->branch;
        $staff = $request->staff;
        $time = $request->time;
        $page = intval($request->page);
        $data = array_values($this->calculateFilterCardSold($cardType, $keyWord, $status, $branch, $staff, $time));
        $list = collect($data)->forPage($page, 10);
        $contents = view('admin::service-card.sold.service-card.filter', [
            'LIST' => $list,
            'page' => $page,
            'data' => $data,
            'cardType' => $cardType,
        ])->render();
        return $contents;
    }

    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_actived'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->service_card->edit($params['id'], $data);
        return response()->json([
            'status' => 0,
            'messages' => __('Trạng thái đã được cập nhật')
        ]);
    }
    public function changeStatusSurcharge(Request $request)
    {
        $params = $request->all();
        $data['is_surcharge'] = $params['action'];
        $this->service_card->edit($params['id'], $data);
        return response()->json([
            'status' => 0,
            'messages' => __('Phụ thu đã được cập nhật')
        ]);
    }

    public function pagingDetailServiceCardUsed(Request $request)
    {
        $page = $request->page;
        $id = $request->idCard;


        $listServiceCardUsed = $this->customerServiceCard->getServiceCardUsed($id);
        $arrayCardUsedPaginate = collect($listServiceCardUsed)->forPage($page, 10);

        $contents = view('admin::service-card.paging-detail-card-used', [
            'listServiceCardUsed' => $listServiceCardUsed,
            'page' => $page,
            'arrayCardUsedPaginate' => $arrayCardUsedPaginate
        ])->render();
        return $contents;
    }

    /**
     * Lưu ảnh trước khi điều trị, sau khi điều trị (thẻ dịch vụ đã bán)
     *
     * @param Request $request
     * @return mixed
     */
    public function saveImageServiceCardSold(Request $request)
    {
        $input = $request->all();
        return $this->service_card->saveImageServiceCardSold($input);
    }

    /**
     * Lấy hình ảnh theo input cho view carousel
     *
     * @param Request $request
     * @return mixed
     */
    public function getImageForCarousel(Request $request)
    {
        $input = $request->all();
        return $this->service_card->getImageForCarousel($input);
    }

    /**
     * Bảo lưu thẻ liệu trình (thẻ dịch vụ đã bán)
     *
     * @param Request $request
     * @return mixed
     */
    public function reserveServiceCard(Request $request)
    {
        return $this->service_card->reserveServiceCard($request->all());
    }

    /**
     * Mở bảo lưu thẻ liệu trình (không bảo lưu nữa)
     *
     * @param Request $request
     * @return mixed
     */
    public function openReserveServiceCard(Request $request)
    {
        return $this->service_card->openReserveServiceCard($request->all());
    }

    /**
     * Kiểm trả thẻ còn sử dụng được không
     *
     * @param $infoCard
     * @return array|false[]
     */
    private function validateCard($infoCard)
    {
        //Kiểm tra thẻ đã kích hoạt chưa
        if ($infoCard['is_actived'] == 0) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình chưa được kích hoạt')
            ];
        }
        //Kiểm tra hạn sử dụng
        $dataNow = Carbon::now()->format('Y-m-d');
        $dateExpired = Carbon::parse($infoCard['expired_date'])->format('Y-m-d');
        if ($infoCard['expired_date'] != null && $dataNow >= $dateExpired) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình hết hạn sử dụng')
            ];
        }
        //Kiểm tra số lần sử dụng
        if ($infoCard['number_using'] != 0 && $infoCard['number_using'] <= $infoCard['count_using']) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình hết số lần sử dụng')
            ];
        }

        return [
            'error' => false
        ];
    }

    /**
     * Modal cộng dồn thẻ liệu trình
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modalAccrualSCSold(Request $request)
    {
        $data = $this->service_card->modalAccrualSCSold($request->all());
        return response()->json($data);
    }

    /**
     * submit cộng dồn thẻ liệu trình
     *
     * @param Request $request
     * @return mixed
     */
    public function submitAccrualSCSold(Request $request)
    {
        return $this->service_card->submitAccrualSCSold($request->all());
    }
}

