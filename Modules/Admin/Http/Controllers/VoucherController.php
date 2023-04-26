<?php

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\MemberLevelTable;
use Modules\Admin\Models\ProductCategoryTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceCategoryTable;
use Modules\Admin\Models\ServiceTable;
use Modules\Admin\Repositories\Voucher\VoucherRepositoryInterface;
use Validator;

class VoucherController extends Controller
{
    private $voucher;
    private $branch;

    public function __construct(VoucherRepositoryInterface $voucherRepository)
    {
        $this->voucher = $voucherRepository;

        $this->branch = new BranchTable();
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexAction()
    {
        $voucher = $this->voucher->list();
        return view('admin::voucher.index', [
            'LIST' => $voucher,
            'FILTER' => $this->filters()
        ]);
    }

    protected function filters()
    {

        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Kích hoạt'),
                    0 => __('Chưa kích hoạt')
                ]
            ],
            'type' => [
                'data' => [
                    '' => __('Chọn loại giảm giá'),
                    "sale_percent" => __('Theo phần trăm'),
                    "sale_cash" => __('Theo tiền')
                ]
            ],
            'object_type' => [
                'data' => [
                    '' => __('Chọn hình thức'),
                    "service" => __('Theo dịch vụ'),
                    "service_card" => __('Theo thẻ dịch vụ'),
                    "product" => __("Theo sản phẩm")
                ]
            ],
        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived', 'object_type', 'type']);
        $List = $this->voucher->list($filter);
        return view('admin::voucher.list', ['LIST' => $List]);
    }

    public function createAction(Request $request)
    {
        $branch = $this->branch->getName();

        $mServiceCate = new ServiceCategoryTable();
        $mProductCategory = new ProductCategoryTable();
        $mMemberLevel = new MemberLevelTable();
        $mCustomerGroup = new CustomerGroupTable();

        $productCategory = $mProductCategory->getAll()->pluck("category_name", "product_category_id")->toArray();

        $productCategory = ["" => __("Tất cả")] + $productCategory;
        $service_cate = $mServiceCate->getName();

        $service_card_type = ["" => __("Tất cả"), "money" => __("Tiền"), "service" => __("Dịch vụ")];

        $memberLevel = $mMemberLevel->getOptionMemberLevel();
        $customerGroup = $mCustomerGroup->getOption();

        return view("admin::voucher.create", [
            "branch" => $branch,
            "product_cate" => $productCategory,
            "service_cate" => $service_cate,
            "service_card_type" => $service_card_type,
            "member_level" => $memberLevel,
            "customer_group" => $customerGroup
        ]);
    }

    public function submitCreateAction(Request $request)
    {
        $params = $request->except(["_token", "service_type", "product_type", "service_card_type"]);

        $validator = Validator::make($params, [
            "voucher_title" => "required|max:200",
            "point" => "required|integer|min:0",
            "code" => "required|string|unique:vouchers,code," . $params["code"] . ",code",
//            "cash" => ['regex:/(?=.*\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|0)?(\.\d{1,2})?$/', "required_without_all:percent"],
            "percent" => "required_without_all:cash",
            "expire_date" => "required",
            "description" => 'max:200',
            "member_level_apply" => "required",
            "quota" => "required|integer|min:0",
            "number_of_using" => "required|integer|min:0",
            "background_color" => 'max:200',
            "text_color" => 'max:200',
            "content_color" => 'max:200',
        ], [
            "required" => ":attribute ".__("bắt buộc phải nhập"),
            "required_without_all" => ":attribute ".__("bắt buộc phải nhập"),
            "string" => ":attribute ".__("phải là kí tự chữ"),
            "integer" => ":attribute phải là số",
//            "cash.regex" => ":attribute phải là số",
//            "max_price.regex" => ":attribute phải là số",
//            "required_price.regex" => ":attribute phải là số",
            "max" => ":attribute ".__("tối đa 200 kí tự"),
            "min" => ":attribute ".__("tối thiểu là 0")
        ]);
        $validator->setAttributeNames([
            "voucher_title" => __("Tiêu đề"),
            "point" => __("Điểm đổi voucher"),
            "code" => __("Mã Voucher"),
            "cash" => __("Giá trị giảm"),
            "percent" => __("Giá trị giảm"),
            "max_price" => __("Tiền giảm tối đa"),
            "required_price" => __("Giá trị đơn hàng tối thiểu"),
            "quota" => __("Hạn mức sử dụng"),
            "number_of_using" => __("Hạn mức 1 khách hàng sử dụng"),
            "branch_id" => __('Chi nhánh'),
            "expire_date" => __("Ngày hết hạn"),
            "description" => __("Mô tả ngắn"),
            "member_level_apply" => __("Cấp độ áp dụng"),
            "background_color" => __('Màu nền'),
            "text_color" => __('Màu chữ'),
            "content_color" => __('Màu nội dung'),
        ]);

        if ($validator->fails()) {
            if ($params["object_type"] == "product") {
                $mProduct = new ProductTable();
                $list = $mProduct->getProductInId($params["product_id"])->pluck("productName", "productId")->toArray();
            } elseif ($params["object_type"] == "service") {
                $mService = new ServiceTable();
                $list = $mService->getServiceInId($params["service_id"])->pluck("service_name", "service_id")->toArray();
            } elseif ($params["object_type"] == "service_card") {
                $mService = new ServiceCard();
                $list = $mService->getServiceCardInId($params["service_card_id"])->pluck("card_name", "service_card_id")->toArray();
            } else {
                $list = "";
            }

            return redirect()->back()->withErrors($validator)->withInput()->with("list", $list);
        }


        $user = \Auth::guard()->user();
        // $params["max_price"] = intval(str_replace(',', '', $params["max_price"]));
        // $params["required_price"] = intval(str_replace(',', '', $params["required_price"]));
        $params["expire_date"] = Carbon::createFromFormat("d/m/Y", $params['expire_date'])->format('Y-m-d');

        if (isset($params["cash"])) {
            // $params["cash"] = intval(str_replace(',', '', $params["cash"]));
        }

        if ($params["object_type"] != "all") {
            if ($params["object_type"] == "product") {
                $obj_arr = $params["product_id"];
            } elseif ($params["object_type"] == "service_card") {
                $obj_arr = $params["service_card_id"];
            } elseif ($params["object_type"] == "service") {
                $obj_arr = $params["service_id"];
            }

            if (isset($obj_arr)) {
                $value = implode(",", $obj_arr);
                $params["object_type_id"] = $value;
            }

            unset($params["product_id"], $params["service_card_id"], $params["service_id"]);
        } else {
            $params["is_all"] = 1;
        }
        $params["total_use"] = 0;
        $params["created_by"] = $user->staff_id;
        $params["updated_by"] = $user->staff_id;
        if (isset($params["is_actived"]) && $params["is_actived"] == "on") {
            $params["is_actived"] = 1;
        } else {
            $params["is_actived"] = 0;
        }
        if ($params["branch_id"] != null) {
            $params["branch_id"] = implode(",", $params["branch_id"]);
        }
        $params['slug'] = str_slug($params["code"]);
        if (isset($params["sale_special"]) && $params["sale_special"] == "on") {
            $params["sale_special"] = 1;
        } else {
            $params["sale_special"] = 0;
        }
        if (isset($params["using_by_guest"]) && $params["using_by_guest"] == "on") {
            $params["using_by_guest"] = 1;
        } else {
            $params["using_by_guest"] = 0;
        }
        if (isset($params['member_level_apply'])) {
            $value = implode(",", $params['member_level_apply']);
            $params["member_level_apply"] = $value;
        }
        if (isset($params['customer_group_apply'])) {
            $value = implode(",", $params['customer_group_apply']);
            $params["customer_group_apply"] = $value;
        }

//        if ($params['voucher_img'] != null) {
//            $params['voucher_img'] = $params['voucher_img'];
//        }
        $params['number_of_using'] = str_replace(',', '', isset($params['number_of_using']) ? $params['number_of_using'] : 0);
        $params['max_price'] = str_replace(',', '', isset($params['max_price']) ? $params['max_price'] : 0);
        $params['cash'] = str_replace(',', '', isset($params['cash']) ? $params['cash'] : 0);
        $params['percent'] = str_replace(',', '', isset($params['percent']) ? $params['percent'] : 0);
        $params['required_price'] = str_replace(',', '', isset($params['required_price']) ? $params['required_price'] : 0);

        if (isset($params['percent']) && $params['percent'] > 100) {
            return redirect()->back()->with("error", __('Giá trị giảm không quá 100 phầm trăm'));
        }

        try {

            $this->voucher->add($params);
//            if ($params['type_add'] == 1) {
//                return redirect()->route("admin.voucher.create")->with("errorsss", __('Loi'));
//            } else {
//                return redirect()->route("admin.voucher")->with("status", __("Đã thêm thành công"));
//            }
            if ($params['type_add'] == 1) {
                return redirect()->route("admin.voucher")->with("status", __('Lưu thông tin thành công'));
            } else {
                return redirect()->route("admin.voucher")->with("status", __("Đã thêm thành công"));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function editAction(Request $request, $id)
    {
        //Lấy thông tin voucher
        $voucher = $this->voucher->detail($id);

        if ($voucher->total_use > 0) {
            return redirect()->back()->with("error", __("Mã giảm giá đang được sử dụng, không thể thay đổi"));
        }

        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCard();
        $mServiceCate = new ServiceCategoryTable();
        $mProductCategory = new ProductCategoryTable();
        $mMemberLevel = new MemberLevelTable();
        $mCustomerGroup = new CustomerGroupTable();

        $arr_id = null;
        $arr_branch_id = null;
        $branch = $this->branch->getName();
        $list = ["" => __("Tất cả")];

        if ($voucher->object_type_id != null) {
            $arr_id = explode(",", $voucher->object_type_id);
            if ($voucher->object_type == "product") {
                $mProductCategory = new ProductCategoryTable();
                $list = $mProduct->getProductChildInId($arr_id)->pluck("productName", "productId")->toArray();
            }
            if ($voucher->object_type == "service") {
                $list = $mService->getServiceInId($arr_id)->pluck("service_name", "service_id")->toArray();
            }
            if ($voucher->object_type == "service_card") {
                $list = $mServiceCard->getServiceCardInId($arr_id)->pluck("card_name", "service_card_id")->toArray();
            }
        }

        if ($voucher->branch_id != null) {
            $arr_branch_id = explode(",", $voucher->branch_id);
        }
        $productCategory = $mProductCategory->getAll()->pluck("category_name", "product_category_id")->toArray();

        $productCategory = ["" => __("Tất cả")] + $productCategory;
        $service_cate = $mServiceCate->getName();

        $service_card_type = ["" => __("Tất cả"), "money" => __("Tiền"), "service" => __("Dịch vụ")];
        $memberLevel = $mMemberLevel->getOptionMemberLevel();
        $customerGroup = $mCustomerGroup->getOption();


        return view("admin::voucher.edit", [
            "voucher" => $voucher,
            "object" => $arr_id,
            "list" => $list,
            "branch_selected" => $arr_branch_id,
            "branch" => $branch,
            "product_cate" => $productCategory,
            "service_cate" => $service_cate,
            "service_card_type" => $service_card_type,
//            "LIST"=>$list,
//            "product_cate"=>isset($productCategory) ? $productCategory:null,
//            "service_cate"=>isset($service_cate) ? $service_cate :null,
            "member_level" => $memberLevel,
            "customer_group" => $customerGroup
        ]);
    }

    public function submitEditAction(Request $request)
    {
        $params = $request->except(["_token", "search_keyword", "service_type", "product_type", "service_card_type"]);

        $validator = Validator::make($params, [
            "voucher_title" => "required|max:200",
            "point" => "required|integer|min:0",
            "code" => "required|string|unique:vouchers,code," . $params["code"] . ",code",
//            "cash" => ['regex:/(?=.*\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|0)?(\.\d{1,2})?$/', "required_without_all:percent"],
            "percent" => "required_without_all:cash",
//            "max_price" => ['regex:/(?=.*\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|0)?(\.\d{1,2})?$/'],
//            "required_price" => ['regex:/(?=.*\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|0)?(\.\d{1,2})?$/'],
            "quota" => "required|integer|min:0",
            "number_of_using" => "required|integer|min:0",
            "expire_date" => "required",
            "description" => 'max:200',
            "member_level_apply" => "required",
            "background_color" => 'max:200',
            "text_color" => 'max:200',
            "content_color" => 'max:200',
        ], [
            "required" => ":attribute ".__("bắt buộc phải nhập"),
            "required_without_all" => ":attribute ".__("bắt buộc phải nhập"),
            "string" => ":attribute ".__("phải là kí tự chữ"),
            "integer" => ":attribute ".__("phải là số"),
//            "cash.regex" => ":attribute phải là số",
//            "max_price.regex" => ":attribute phải là số",
//            "required_price.regex" => ":attribute phải là số",
            "max" => ':attribute '.__("tối đa 200 kí tự"),
            "min" => ':attribute '.__("tối thiểu là 0"),
            "background_color" => __('Màu nền'),
            "text_color" => __('Màu chữ'),
            "content_color" => __('Màu nội dung'),
        ]);
        $validator->setAttributeNames([
            "voucher_title" => __("Tiêu đề"),
            "point" => __("Điểm đổi voucher"),
            "code" => __("Mã Voucher"),
            "cash" => __("Giá trị giảm"),
            "percent" => __("Giá trị giảm"),
            "max_price" => __("Tiền giảm tối đa"),
            "required_price" => __("Giá trị đơn hàng tối thiểu"),
            "quota" => __("Hạn mức sử dụng"),
            "number_of_using" => __("Hạn mức 1 khách hàng sử dụng"),
            "branch_id" => __('Chi nhánh'),
            "expire_date" => __("Ngày hết hạn"),
            "description" => __("Mô tả ngắn"),
            "member_level_apply" => __("Cấp độ áp dụng")
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $voucher = $this->voucher->detail($params["voucher_id"]);
        if ($voucher->total_use > 0) {
            return redirect()->route("admin.voucher")->with("error", __("Mã giảm giá đang được sử dụng, không thể thay đổi"));
        }

        $user = \Auth::guard()->user();

        // $params["max_price"] = intval(str_replace(',', '', $params["max_price"]));
        // $params["required_price"] = intval(str_replace(',', '', $params["required_price"]));
        $params["expire_date"] = Carbon::createFromFormat("d/m/Y", $params['expire_date'])->format('Y-m-d');

        if (isset($params["cash"])) {
            // $params["cash"] = intval(str_replace(',', '', $params["cash"]));

        }

        if ($params["object_type"] != "all") {
            if ($params["object_type"] == "product") {
                $obj_arr = $params["product_id"];
            } elseif ($params["object_type"] == "service_card") {
                $obj_arr = $params["service_card_id"];
            } elseif ($params["object_type"] == "service") {
                $obj_arr = $params["service_id"];
            }

            if (isset($obj_arr)) {
                $value = implode(",", $obj_arr);
                $params["object_type_id"] = $value;
            }

            unset($params["product_id"], $params["service_card_id"], $params["service_id"]);
        } else {
            $params["is_all"] = 1;
        }

        if (isset($params["is_actived"]) && $params["is_actived"] == "on") {
            $params["is_actived"] = 1;
        } else {
            $params["is_actived"] = 0;
        }
        $params["total_use"] = 0;
        $params["created_by"] = $user->staff_id;
        $params["updated_by"] = $user->staff_id;
        if ($params["branch_id"] != null) {
            $params["branch_id"] = implode(",", $params["branch_id"]);
        }
        $id = $params["voucher_id"];
        unset($params["voucher_id"]);
        $params['slug'] = str_slug($params["code"]);
        if (isset($params["sale_special"]) && $params["sale_special"] == "on") {
            $params["sale_special"] = 1;
        } else {
            $params["sale_special"] = 0;
        }
        if (isset($params["using_by_guest"]) && $params["using_by_guest"] == "on") {
            $params["using_by_guest"] = 1;
        } else {
            $params["using_by_guest"] = 0;
        }
        if (isset($params['member_level_apply'])) {
            $value = implode(",", $params['member_level_apply']);
            $params["member_level_apply"] = $value;
        }

        if (isset($params['customer_group_apply'])) {
            $value = implode(",", $params['customer_group_apply']);
            $params["customer_group_apply"] = $value;
        }

        if ($params['voucher_img'] != null) {
            $params['voucher_img'] = $params['voucher_img'];
        } else {
            $params['voucher_img'] = $params['img_old'];
        }
        unset($params['files'], $params['img_old']);

        $params['number_of_using'] = str_replace(',', '', isset($params['number_of_using']) ? $params['number_of_using'] : 0);

        $params['max_price'] = str_replace(',', '', isset($params['max_price']) ? $params['max_price'] : 0);
        $params['cash'] = str_replace(',', '', isset($params['cash']) ? $params['cash'] : 0);
        $params['percent'] = str_replace(',', '', isset($params['percent']) ? $params['percent'] : 0);
        $params['required_price'] = str_replace(',', '', isset($params['required_price']) ? $params['required_price'] :0);

        if (isset($params['percent']) && $params['percent'] > 100) {
            return redirect()->back()->with("error", __('Giá trị giảm không quá 100 phầm trăm'));
        }

        try {
            $this->voucher->edit($id, $params);
            return redirect()->route("admin.voucher.edit", $id)->with('statusss', 'success');
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function getObjectByType(Request $request)
    {
        $param = $request->all();

        if ($param["type"] == "product") {
            $mProduct = new ProductTable();
            $mProductCategory = new ProductCategoryTable();
            $product = $mProduct->getAll();
            $productCategory = $mProductCategory->getAll()->pluck("category_name", "product_category_id")->toArray();
//            dd($product);
            $productCategory = ["" => __("Tất cả")] + $productCategory;
            return \View::make("admin::voucher.inc.table-product", ["LIST" => $product, "product_cate" => $productCategory]);
        }
        if ($param["type"] == "service") {
            $mService = new ServiceTable();
            $mServiceCate = new ServiceCategoryTable();

            $service_cate = $mServiceCate->getName();
            $service = $mService->getAll();
            return \View::make("admin::voucher.inc.table-service", ["LIST" => $service, "service_cate" => $service_cate]);
        }
        if ($param["type"] == "service_card") {
            $mServiceCard = new ServiceCard();
            $serviceCard = $mServiceCard->getAll();
            return \View::make("admin::voucher.inc.table-service-card", ["LIST" => $serviceCard]);
        } else {
            return "";
        }
    }

    public function filterObjectByType(Request $request)
    {
        $params = $request->all();
        if ($params["type"] == "product") {
            $mProduct = new ProductChildTable();
            $product = $mProduct->getAllVoucher($params)->toArray();

            return response()->json([
                "list" => $product
            ]);
        }
        if ($params["type"] == "service") {
            $mService = new ServiceTable();
            $service = $mService->getAll($params)->toArray();

            return response()->json([
                "list" => $service
            ]);
        }
        if ($params["type"] == "service_card") {
            $mServiceCard = new ServiceCard();
            $serviceCard = $mServiceCard->getAll($params)->toArray();
            return response()->json([
                "list" => $serviceCard
            ]);
        }
    }
    
    public function deleteAction($id)
    {  
        try {
            $this->voucher->delete($id);
            
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

    public function detailAction($id)
    {
        $voucher = $this->voucher->detail($id);
        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCard();
        $object = null;
        $branch = null;

        if ($voucher->object_type_id != null) {
            $arr_id = explode(",", $voucher->object_type_id);
            if ($voucher->object_type == "product") {
                $object = $mProduct->getProductChildInId2($arr_id);
//                dd($object);
            }
            if ($voucher->object_type == "service") {
                $object = $mService->getServiceInId($arr_id);
            }
            if ($voucher->object_type == "service_card") {
                $object = $mServiceCard->getServiceCardInId($arr_id);
            }
        }
        if ($voucher->branch_id != null) {
            $arr_branch_id = explode(",", $voucher->branch_id);
            $branch = $this->branch->getBranchInId($arr_branch_id);
        }

        return \View::make("admin::voucher.popup.detail", [
            "voucher" => $voucher,
            "object" => $object,
            "branch" => $branch
        ]);
    }

    public function changeStatusAction(Request $request, $id)
    {
        try {
            $isactive = $this->voucher->changeStatus($id);
            return response()->json([
                'error' => 0,
                'message' => __('Đổi trạng thái thành công'),
                "is_active" => $isactive
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkSlug(Request $request)
    {
        $code = $request->code;
        $id = $request->id;
        $checkSlug = $this->voucher->checkSlug($code, $id);
        if ($checkSlug != null) {
            if ($checkSlug['is_deleted'] == 0) {
                return response()->json(['error' => 1]);
            }
        } else {
            return response()->json(['error' => 0]);
        }
    }

    /**
     * Upload image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAction(Request $request)
    {
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            return response()->json(["file" => $file, "success" => "1"]);
        }
    }


    /**
     * Lưu image vào folder temp
     *
     * @param $file
     * @return string
     */
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_voucher." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH. "/" .$file_name, file_get_contents($file));
        return $file_name;

    }

    /**
     * Move image vào folder voucher
     *
     * @param $filename
     * @return string
     */
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = VOUCHER_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(VOUCHER_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }
}
