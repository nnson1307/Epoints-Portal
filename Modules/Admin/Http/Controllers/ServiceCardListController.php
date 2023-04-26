<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Validator;
use View;

class ServiceCardListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $card_list;
    private $code;
    private $service_card;
    protected $branch;

    public function __construct(
        ServiceCardRepositoryInterface $cardRepository,
        ServiceCardListRepositoryInterface $cardListRepository,
        CodeGeneratorRepositoryInterface $codeGeneratorRepository,
        BranchRepositoryInterface $branch
    )
    {
        $this->card_list = $cardListRepository;
        $this->code = $codeGeneratorRepository;
        $this->service_card = $cardRepository;
        $this->branch = $branch;
    }

    public function indexAction()
    {
        $result = $this->listServiceCardListAll();
        $branchList = $this->branch->getBranch();
        $result2 = collect($result)->forPage(1, 10);
        return view('admin::service-card-list.index', [
            'LIST' => $result2,
            'FILTER' => $this->filters(),
            'BRANCH' => $branchList,
            'page' => 1,
            'data'=>$result
        ]);
    }

    protected function filters()
    {
        return [
            'service_cards$service_card_type' => [
                "data" => [
                    '' => __('Chọn loại thẻ'),
                    "money" => __("Sản phẩm"),
                    "service" => __("Dịch vụ")
                ]
            ]
        ];
    }

    public function listAction(Request $request)
    {
//        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'service_cards$service_card_type', 'branches$branch_id']);
//        $List = $this->card_list->list($filter);
//        return view('admin::service-card-list.list', ['LIST' => $List]);
    }

    public function getCardServicePrice(Request $request)
    {
        $service_card = $request->input('service_card');
        $oSelect = $this->service_card->getServiceCardInfo($service_card);

        return response()->json(number_format($oSelect->price));
    }

    public function createAction(Request $request)
    {
        $branch = new BranchTable();
        $service_card = new ServiceCard();

        $mBranch = $branch->getName();
        $mServiceCard = $service_card->getName();
        unset($mBranch[""]);
//        dd($mBranch);
        return view("admin::service-card-list.create", [
            "_branch" => $mBranch,
            "_service_card" => $mServiceCard
        ]);
    }

    public function submitCreateAction(Request $request)
    {
        $params = $request->except("_token");


        $validator = Validator::make($params, [
            "service_card_id" => "required",
            "quantity" => "required|integer",
            "code" => "required"
        ], [
            "required" => __(":attribute bắt buộc phải nhập"),
            "code.required" => __("Mã thẻ dịch vụ chưa có"),
            "integer" => __(":attribute phải là số"),
        ]);
        $validator->setAttributeNames([
            "service_card_id" => __("Thẻ dịch vụ"),
            "quantity" => __("Số lượng"),
            "code" => __("Mã thẻ dịch vụ")
        ]);
//        dd($validator->fails());

        if ($validator->fails()) {
//            dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }
//        dd($params);
        try {

            \DB::beginTransaction();
            if ($params["action"] == "save") {
                foreach ($params["code"] as $code) {
                    $this->card_list->add([
                        "branch_id" => $params["branch_id"],
                        "service_card_id" => $params["service_card_id"],
                        "code" => $code,
                        'is_actived' => 1
                    ]);
                }
            } elseif ($params["action"] == "save-and-print") {

            }

            \DB::commit();

            return redirect()->route("admin.service-card-list")->with("status", __("Đã thêm thành công"));
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with("error", __("Đã có lỗi khi thêm thẻ in dịch vụ"));
        }


    }

    public function getCodeAction(Request $request)
    {
        $quantity = $request->input("quantity");

        $arr_result = [];

        for ($i = 0; $i < $quantity; $i++) {
            $code = $this->code->generateCardListCode();
            while (array_search($code, $arr_result)) {
                $code = $this->code->generateCardListCode();
            }
            $arr_result[] = $code;
        }

//        dd($arr_result);
        $html = View::make("admin::service-card-list.inc.table-create-code", ["LIST" => $arr_result])->render();
        return response()->json(["html" => $html]);
//        return response()->json();
    }

    public function detailAction(Request $request, $id)
    {
        $card_detail = $this->card_list->getServiceCardListDetail($id);
        return view("admin::service-card-list.detail", [
            "card_detail" => $card_detail
        ]);
    }

    public function getUnuseCardList(Request $request)
    {
        $params = $request->all();

        $list = $this->card_list->getUnuseCard($params["service_card_id"], $params["branch_id"]);

        $html = View::make("admin::service-card-list.inc.table-unuse", ["LIST" => $list])->render();
        return response()->json(["html" => $html]);
    }

    public function getInuseCardList(Request $request)
    {
        $params = $request->all();

        $list = $this->card_list->getInuseCard($params);
        return View::make("admin::service-card-list.inc.table-inuse", ["LIST" => $list, "params" => $params])->render();
    }

    public function listServiceCardListAll()
    {
        //Danh sách chi nhánh.
        $branchList = $this->branch->getBranch();
        //Danh sách thẻ in.
        $service_card_list = $this->card_list->getAll();
        $getAllServiceCardList = $this->card_list->getAllServiceCardList();
        //Kết quả.
        $result = [];
        foreach ($service_card_list as $item) {
            //Biến lưu thẻ in.
            $serviceCardList = [];
            $serviceCardList['cardListId'] = $item['service_card_id'];
            $serviceCardList['cardListName'] = $item['name'];
            $serviceCardList['price'] = (int)$item['price'];
            $serviceCardList['serviceCardType'] = $item['service_card_type'];
            $serviceCardList['total'] = $item['card_count'];
            foreach ($branchList as $branchId => $branchName) {
                $serviceCardList['branch'][$branchId] = 0;
            }
            foreach ($serviceCardList['branch'] as $id => $value) {
                foreach ($getAllServiceCardList as $serviceCard) {
                    if ($item['service_card_id'] == $serviceCard['service_card_id'] && $id == $serviceCard['branch_id']) {
                        $serviceCardList['branch'][$id] += 1;
                    }
                }
            }
            $result[] = $serviceCardList;
        }
        return $result;
    }

    //Phân trang (tất cả sản phẩm).
    public function pagingAction(Request $request)
    {
        $page = $request->page;
        //Danh sách chi nhánh.
        $branchList = $this->branch->getBranch();
        $data = $this->listServiceCardListAll();
        $result = collect($data)->forPage($page, 10);
        $contents = view('admin::service-card-list.paging', [
            'data' => $data,
            'LIST' => $result,
            'BRANCH' => $branchList,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function searchCardList($result, $keyWord, $type)
    {
        if ($keyWord != null && $type != null) {
            foreach ($result as $key => $value) {
                if ($value['serviceCardType'] != $type || strpos($value['cardListName'], $keyWord) === false) {
                    unset($result[$key]);
                }
            }
        } else if ($type != null && $keyWord == null) {
            foreach ($result as $key => $value) {
                if ($value['serviceCardType'] != $type) {
                    unset($result[$key]);
                }
            }
        } else if ($type == null && $keyWord != null) {
            foreach ($result as $key => $value) {
                if (strpos($value['cardListName'], $keyWord) === false) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

    //Filter
    public function filterAction(Request $request)
    {
        $keyWord = $request->keyWord;
        $type = $request->type;
        $result = $this->listServiceCardListAll();
        $branchList = $this->branch->getBranch();
        $data=$this->searchCardList($result, $keyWord, $type);
        $data2=[];
        $display=10;
        foreach ($data as $key=>$value){
            $data2[]=  $value;
        }
        $res = collect($data2)->forPage(1, $display);
        $view = view('admin::service-card-list.filter', [
            'LIST' => $res,
            'BRANCH' => $branchList,
            'page' => 1,
            'display'=>$display,
            'data'=>$data
        ]);
        return $view;
    }

    //Phân trang (khi filter).
    public function pagingResultFilterAction(Request $request)
    {
        $page = $request->page;
        $keyWord = $request->keyWord;
        $type = $request->type;

        //Danh sách chi nhánh.
        $branchList = $this->branch->getBranch();
        //Tất cả service card
        $allServiceCard = $this->listServiceCardListAll();
        //Service card phù hợp với điều kiện.
        $data=$this->searchCardList($allServiceCard, $keyWord, $type);
        //reset lại key của mảng service card phù hợp với điều kiện.
        $data2=[];
        foreach ($data as $key=>$value){
            $data2[]=  $value;
        }
        //Kết quả trả về theo trang.
        $result = collect($data2)->forPage($page, 10);
        $contents = view('admin::service-card-list.filter-paging', [
            'data' => $data2,
            'LIST' => $result,
            'BRANCH' => $branchList,
            'page' => $page
        ])->render();
        return $contents;
    }
}
