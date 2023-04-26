<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/10/2018
 * Time: 12:17 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;
use Modules\Admin\Repositories\ServiceImage\ServiceImageRepositoryInterface;
use Modules\Admin\Repositories\ServiceMaterial\ServiceMaterialRepository;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;


class ServiceController extends Controller
{
    protected $service;
    protected $service_category;
    protected $service_branch_price;
    protected $branch;
    protected $product;
    protected $unit;
    protected $service_material;
    protected $code;
    protected $service_image;
    protected $product_child;

    public function __construct(
        ServiceRepositoryInterface            $services,
        ServiceCategoryRepositoryInterface    $service_categories,
        BranchRepositoryInterface             $branchs,
        ServiceBranchPriceRepositoryInterface $service_branch_prices,
        ProductRepositoryInterface            $products,
        UnitRepositoryInterface               $units,
        ServiceMaterialRepository             $service_materials,
        CodeGeneratorRepositoryInterface      $codes,
        ServiceImageRepositoryInterface       $service_images,
        ProductChildRepositoryInterface       $product_childs
    ){
        $this->service = $services;
        $this->service_category = $service_categories;
        $this->branch = $branchs;
        $this->service_branch_price = $service_branch_prices;
        $this->product = $products;
        $this->unit = $units;
        $this->service_material = $service_materials;
        $this->code = $codes;
        $this->service_image = $service_images;
        $this->product_child = $product_childs;
    }

    const PRODUCT = "product";
    const SERVICE = "service";

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $get = $this->service->list();
        $optionCategory = $this->service_category->getOptionServiceCategory();
        $optionBranch = $this->branch->getBranch();
        return view('admin::service.index', [
            'LIST' => $get,
            'FILTER' => $this->filters(),
            'optionCate' => $optionCategory,
            'optionBranch' => $optionBranch
        ]);
    }


    /**
     * @return array
     */
    protected function filters()
    {
        $optionCate = $this->service_category->getOptionServiceCategory();
        $groupCate = (['' => __('Chọn nhóm')]) + $optionCate;
        return [
            'services$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ],
            'services$service_category_id' => [
                'data' => $groupCate

            ],

        ];
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'services$is_actived',
            'services$service_category_id', 'created_at', 'search']);
        $list = $this->service->list($filter);
        return view('admin::service.list', [
            'LIST' => $list,
            'page' => $filter['page']
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexPriceServiceAction()
    {
        $get = $this->service->listPriceService();
        $optionCategory = $this->service_category->getOptionServiceCategory();
        $optionBranch = $this->branch->getBranch();
        return view('admin::price-service.index', [
            'LIST' => $get,
            'FILTER' => $this->filtersPriceService(),
            'optionCate' => $optionCategory,
            'optionBranch' => $optionBranch
        ]);
    }

    /**
     * @return array
     */
    protected function filtersPriceService()
    {
        $optionCate = $this->service_category->getOptionServiceCategory();
        $groupCate = (["" => "Nhóm"]) + $optionCate;
        return [
//            'services$service_category_id' => [
//                'data' => $groupCate
//
//            ],
//            'services$is_actived' => [
//                'data' => [
//                    '' => 'Trạng thái',
//                    1 => 'Hoạt động',
//                    0 => 'Tạm ngưng'
//                ]
//            ],

        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listPriceServiceAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'services$is_actived',
            'services$service_category_id', 'created_at', 'search']);
        $list = $this->service->listPriceService($filter);
        return view('admin::price-service.list', ['LIST' => $list]);
    }

    /**
     * View thêm dịch vụ
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAction()
    {
        $optionCategory = $this->service_category->getOptionServiceCategory();
        $optionBranch = $this->branch->getBranchOption();
        $optionProduct = $this->product_child->getOptionChildSonService();
        $codeInput = $this->code->generateServiceCardCode("");
        return view('admin::service.add', [
            'optionCategory' => $optionCategory,
            'optionBranch' => $optionBranch,
            'optionProduct' => $optionProduct,
            'codeInput' => $codeInput
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionBranchAction()
    {
        $data = $this->branch->getBranchOption();
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionProductAction(Request $request)
    {

        $data = $request->all();
        $value = $this->product->searchProductChild($data['search']);
        $search = [];
        foreach ($value as $item) {
            $search['results'][] = [
                'id' => $item['product_child_id'],
                'text' => $item['product_child_name']
            ];

        }
        return response()->json($search);

    }

    /**
     * Thêm dịch vụ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddAction(Request $request)
    {
        try {
            DB::beginTransaction();

            $service_name = $request->service_name;
            $test = $this->service->testName(str_slug($service_name), 0);

            if (empty($test['service_name'])) {
                if ($request->type_refer_commission == 'percent') {
                    if ($request->refer_commission_percent > 100) {
                        return response()->json([
                            'error_refer_commission' => 1,
                            'message' => __('Hoa hồng người giới thiệu không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_refer_commission == 'money') {
                    if ($request->refer_commission_value > $request->price_standard) {
                        return response()->json([
                            'error_refer_commission' => 1,
                            'message' => __('Hoa hồng người giới thiệu vươt quá giá dịch vụ')
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
                    if ($request->staff_commission_value > $request->price_standard) {
                        return response()->json([
                            'error_staff_commission' => 1,
                            'message' => __('Hoa hồng nhân viên phục vụ vượt quá giá dịch vụ')
                        ]);
                    }
                }
                // Hoa hong cho deal
                if ($request->type_deal_commission == 'percent') {
                    if ($request->deal_commission_percent > 100) {
                        return response()->json([
                            'error_deal_commission' => 1,
                            'message' => __('Hoa hồng cho deal không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_deal_commission == 'money') {
                    if ($request->deal_commission_value > $request->price_standard) {
                        return response()->json([
                            'error_deal_commission' => 1,
                            'message' => __('Hoa hồng cho deal vượt quá giá dịch vụ')
                        ]);
                    }
                }

                $data = [
                    'service_category_id' => $request->service_category_id,
                    'service_name' => $service_name,
                    'slug' => str_slug($service_name),
                    'time' => $request->time,
                    'price_standard' => $request->price_standard,
                    'new_price' => $request->new_price,
                    'branch_id' => $request->branch_id,
                    'product_id' => $request->product_id,
                    'is_actived' => 1,
                    'is_surcharge' => $request->is_surcharge,
                    'detail_description' => $request->detail_description,
                    'type_refer_commission' => $request->type_refer_commission,
                    'refer_commission_value' => $request->type_refer_commission == 'money' ? $request->refer_commission_value : $request->refer_commission_percent,
                    'type_staff_commission' => $request->type_staff_commission,
                    'staff_commission_value' => $request->type_staff_commission == 'money' ? $request->staff_commission_value : $request->staff_commission_percent,
                    'type_deal_commission' => $request->type_deal_commission,
                    'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent,
                    'description' => $request->description,
                    'is_remind' => $request->is_remind,
                    'remind_value' => $request->is_remind == 1 ? $request->remind_value : null,
                    'is_upload_image_ticket' => $request->is_upload_image_ticket,
                    'is_upload_image_sample' => $request->is_upload_image_sample,
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id()
                ];
                if ($request->service_avatar != null) {
                    $data['service_avatar'] = $request->service_avatar;
                }
                $id_service = $this->service->add($data);
                $day_code = date('dmY');
                if ($id_service < 10) {
                    $id_service = '0' . $id_service;
                }
                $data_code = [
                    'service_code' => 'DV_' . $day_code . $id_service
                ];
                $this->service->edit($data_code, $id_service);

                if ($request->branch_tb != null) {
                    $aData = array_chunk($request->branch_tb, 7, false);
                    foreach ($aData as $key => $value) {
                        $data = [
                            'branch_id' => $value[1],
                            'service_id' => $id_service,
                            'old_price' => str_replace(',', '', $value[2]),
                            'new_price' => str_replace(',', '', $value[3]),
                            'price_week' => str_replace(',', '', $value[4]),
                            'price_month' => str_replace(',', '', $value[5]),
                            'price_year' => str_replace(',', '', $value[6]),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'is_actived' => 1
                        ];
                        $this->service_branch_price->add($data);
                    }
                } else {
                    return response()->json([
                        'branch_null' => 1,
                        'message' => __('Vui lòng chọn chi nhánh')
                    ]);
                }

                if ($request->product_tb != "") {
                    $aDataProduct = array_chunk($request->product_tb, 3, false);
                    foreach ($aDataProduct as $key => $value) {
                        //Lấy thông tin sản phẩm
                        $code_child = $this->product_child->getProductChildById($value[0]);

                        $data = [
                            'service_id' => $id_service,
                            'material_code' => $code_child['product_code'],
                            'material_id' => $value[0],
                            'quantity' => $value[1],
                            'unit_id' => $value[2],
                            'service_material_type' => self::PRODUCT,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ];
                        $this->service_material->add($data);
                    }
                }

                if ($request->services_tb != "") {
                    $aDataServices = array_chunk($request->services_tb, 2, false);
                    foreach ($aDataServices as $key => $value) {
                        $data = [
                            'service_id' => $id_service,
                            'material_code' => $value[1],
                            'material_id' => $value[0],
                            'quantity' => 1,
                            'unit_id' => 0,
                            'service_material_type' => self::SERVICE,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ];
                        $this->service_material->add($data);
                    }
                }

                if ($request->image != "") {
                    $aData = array_chunk($request->image, 1, false);
                    foreach ($aData as $key => $value) {
                        $data = [
                            'service_id' => $id_service,
                            'name' => $value[0],
                            'created_by' => Auth::id()
                        ];

                        $this->service_image->add($data);
                    }
                }
                DB::commit();
//                return response()->json(['status' => 1]);
                return response()->json([
                    'error' => false,
                    'message' => __('Thêm dịch vụ thành công'),
                ]);
            } else {
//                return response()->json(['status' => 0]);
                return response()->json([
                    'error' => true,
                    'message' => __('Tên dịch vụ đã tồn tại'),
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }


//    public function deleteImageAction(Request $request)
//    {
//        $path = TEMP_PATH . '/' . $request->filename;
//        Storage::disk('public')->delete($path);
//        return response()->json(["success" => "1"]);
//    }
//
//    private function transferTempfileToAdminfile($path, $imageName)
//    {
//
//        $cut = (str_replace('temp_upload/', '', $path));
//        $new_path = SERVICE_UPLOADS_PATH . date('Ymd') . '/' . time() . $cut;
////        $new='/'.$new_path;
//        Storage::disk('public')->makeDirectory(SERVICE_UPLOADS_PATH . date('Ymd'));
//        Storage::disk('public')->move($path, $new_path);
//        return $new_path;
//    }
    public function uploadAction(Request $request)
    {
        $this->validate($request, [
            "service_image" => "mimes:jpg,jpeg,png,gif|max:10000"
        ], [
            "service_image.mimes" => "File này không phải file hình",
            "service_image.max" => "File quá lớn"
        ]);
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            return response()->json(["file" => $file, "success" => "1"]);
        }

    }
//
//    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_service." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH . "/" . $file_name, file_get_contents($file));
        return $file_name;

    }

//    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = SERVICE_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(SERVICE_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    private function transferTempfileToAdminfileDrop($path, $imgName)
    {

        $imgName = str_replace("temp_upload/", "", $imgName);
        Storage::disk('public')->makeDirectory(SERVICE_UPLOADS_PATH . date('Ymd'));
        $new_path = SERVICE_UPLOADS_PATH . date('Ymd') . '/' . $imgName;
        Storage::disk('public')->move('temp_upload/' . $path, $new_path);
        return $new_path;
    }

    public function uploadDropzoneAction(Request $request)
    {
        $time = Carbon::now();
        // Requesting the file from the form
        $image = $request->file('file');
        // Getting the extension of the file
        $extension = $image->getClientOriginalExtension();
        //tên của hình ảnh
        $filename = $image->getClientOriginalName();
        //$filename = time() . str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . time() . "." . $extension;
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

//    //function delete image
    public function deleteImageAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionUnitAction()
    {
        $data = $this->unit->getUnitOption();
        return response()->json($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailAction($id, Request $request)
    {
        $item = $this->service->getItem($id);
        $itemImage = $this->service_image->getItem($id);
        $itemBranch = $this->service_branch_price->getListServiceDetail($id);
        $filter['service_material_type'] = 'product';
        $itemMaterial = $this->service_material->getListServiceDetail($id, $filter);
        $itemServiceMaterial = $this->service_material->getSelectMaterialsService($id);
        return view('admin::service.service-detail', [
            'item' => $item,
            'itemBranch' => $itemBranch,
            'itemMaterial' => $itemMaterial,
            'itemImage' => $itemImage,
            'itemServiceMaterial' => $itemServiceMaterial,
            'page' => 1,
            'display' => PAGING_ITEM_PER_PAGE
        ]);
    }

    public function listBranchDetail(Request $request, $id)
    {
        $filter = $request->only(['page', 'display', 'search_keyword']);
        $filter['display'] = PAGING_ITEM_PER_PAGE;

        $itemBranch = $this->service_branch_price->getListServiceDetail($id, $filter);
        return view('admin::service.list-branch-detail', [
            'itemBranch' => $itemBranch,
            'page' => $filter['page'],
            'display' => $filter['display']
        ]);
    }

    public function listMaterialDetail(Request $request, $id)
    {
        $filter = $request->only(['page', 'display', 'search_keyword']);
        $filter['display'] = PAGING_ITEM_PER_PAGE;
        $filter['service_material_type'] = 'product';

        $itemMaterial = $this->service_material->getListServiceDetail($id, $filter);

        return view('admin::service.list-material-detail', [
            'itemMaterial' => $itemMaterial,
            'page' => $filter['page'],
            'display' => $filter['display']
        ]);
    }

    /**
     * View chỉnh sửa dịch vụ
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editAction($id)
    {
        //Lấy thông tin dịch vụ
        $item = $this->service->getItem($id);
        $optionCategory = $this->service_category->getOptionServiceCategory();
        $optionBranch = $this->branch->getBranchOption();

        $optionUnit = $this->unit->getUnitOption();

        $optionProduct = $this->product_child->getOptionChildSonService();
        $getSelectBranch = $this->service_branch_price->getSelectBranch($id);
        $getSelectProduct = $this->service_material->getSelect($id);
        $branch_price = $this->service_branch_price->getItem($id);

        //Lấy sản phẩm kèm theo
        $itemMaterial = $this->service_material->getItem($id);
        //Lấy dịch vụ kèm theo
        $itemServiceMaterial = $this->service_material->getSelectMaterialsService($id);

        $itemImage = $this->service_image->getItem($id);
        $type = '';
        $size = '';
        $width = '';
        $height = '';

//        if (Storage::disk('public')->exists($item['service_avatar']) && $item['service_avatar'] != null) {
//            $getimagesize = getimagesizefromstring(Storage::disk('public')->get($item['service_avatar']));
//            $type = strtoupper(substr($item['service_avatar'], strrpos($item['service_avatar'], '.') + 1));
//            $width = $getimagesize[0];
//            $height = $getimagesize[1];
//            $size = (int)round(Storage::disk('public')->size($item['service_avatar']) / 1024);
//        }

        return view('admin::service.edit', [
            'item' => $item,
            'optionCategory' => $optionCategory,
            'branch_price' => $branch_price,
            'optionBranch' => $optionBranch,
            'selectBranch' => $getSelectBranch,
            'selectProduct' => $getSelectProduct,
            'itemMaterial' => $itemMaterial,
            'itemServiceMaterial' => $itemServiceMaterial,
            'optionUnit' => $optionUnit,
            'optionProduct' => $optionProduct,
            'itemImage' => $itemImage,
            'type' => $type,
            'size' => $size,
            'width' => $width,
            'height' => $height
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitEditAction(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->service_id;
            $test = $this->service->testName(str_slug($request->service_name), $id);

            if (empty($test['slug'])) {
                if ($request->type_refer_commission == 'percent') {
                    if ($request->refer_commission_percent > 100) {
                        return response()->json([
                            'error_refer_commission' => 1,
                            'message' => __('Hoa hồng người giới thiệu không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_refer_commission == 'money') {
                    if ($request->refer_commission_value > $request->price_standard) {
                        return response()->json([
                            'error_refer_commission' => 1,
                            'message' => __('Hoa hồng người giới thiệu vươt quá giá dịch vụ')
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
                    if ($request->staff_commission_value > $request->price_standard) {
                        return response()->json([
                            'error_staff_commission' => 1,
                            'message' => __('Hoa hồng nhân viên phục vụ vượt quá giá dịch vụ')
                        ]);
                    }
                }
                // hoa hong cho deal
                if ($request->type_deal_commission == 'percent') {
                    if ($request->deal_commission_percent > 100) {
                        return response()->json([
                            'error_deal_commission' => 1,
                            'message' => __('Hoa hồng cho deal không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_deal_commission == 'money') {
                    if ($request->deal_commission_value > $request->price_standard) {
                        return response()->json([
                            'error_deal_commission' => 1,
                            'message' => __('Hoa hồng cho deal vượt quá giá dịch vụ')
                        ]);
                    }
                }
                $data = [
                    'service_name' => $request->service_name,
                    'slug' => str_slug($request->service_name),
                    'service_category_id' => $request->service_category_id,
                    'price_standard' => $request->price_standard,
                    'time' => $request->time,
                    'detail_description' => $request->detail_description,
                    'service_id' => $request->service_id,
                    'is_actived' => $request->is_actived,
                    'is_surcharge' => $request->is_surcharge,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'type_refer_commission' => $request->type_refer_commission,
                    'refer_commission_value' => $request->type_refer_commission == 'money' ? $request->refer_commission_value : $request->refer_commission_percent,
                    'type_staff_commission' => $request->type_staff_commission,
                    'staff_commission_value' => $request->type_staff_commission == 'money' ? $request->staff_commission_value : $request->staff_commission_percent,
                    'type_deal_commission' => $request->type_deal_commission,
                    'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent,
                    'description' => $request->description,
                    'is_remind' => $request->is_remind,
                    'remind_value' => $request->is_remind == 1 ? $request->remind_value : null,
                    'is_upload_image_ticket' => $request->is_upload_image_ticket,
                    'is_upload_image_sample' => $request->is_upload_image_sample
                ];
                if ($request->service_avatar != null) {
                    $data['service_avatar'] = $request->service_avatar;
                } else {
                    $data['service_avatar'] = $request->service_avatar_edit;
                }
                //Chỉnh sửa dịch vụ
                $this->service->edit($data, $id);

                //Xoá dịch vụ theo chi nhánh
                $idBranchDelete = $this->service_branch_price->deleteByService($request->service_id);

                if ($request->branch_tb != '') {
                    $aData = array_chunk($request->branch_tb, 8, false);
                    foreach ($aData as $key => $value) {
                        $data = [
                            'branch_id' => $value[2],
                            'service_id' => $request->service_id,
                            'old_price' => str_replace(',', '', $value[3]),
                            'new_price' => str_replace(',', '', $value[4]),
                            'price_week' => str_replace(',', '', $value[5]),
                            'price_month' => str_replace(',', '', $value[6]),
                            'price_year' => str_replace(',', '', $value[7]),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'is_actived' => 1
                        ];
                        //Thêm dịch vụ theo chi nhánh
                        $this->service_branch_price->add($data);
                    }
                }

                $rerturnDelete =  $this->service_material->deleteItem($id);

                if ($request->product_tb != '') {
                    $aData1 = array_chunk($request->product_tb, 4, false);
                    foreach ($aData1 as $key => $value) {
                        //Lấy thông tin sản ẩm
                        $code_child = $this->product_child->getProductChildById($value[0]);
                        $data = [
                            'service_id' => $request->service_id,
                            'material_id' => $value[0],
                            'quantity' => $value[2],
                            'unit_id' => $value[3],
                            'material_code' => $code_child['product_code'],
                            'service_material_type' => self::PRODUCT,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ];
                        $this->service_material->add($data);
                    }
                }

                if ($request->services_tb != "") {
                    $aDataServices = array_chunk($request->services_tb, 2, false);
                    foreach ($aDataServices as $key => $value) {
                        $data = [
                            'service_id' => $request->service_id,
                            'material_code' => $value[1],
                            'material_id' => $value[0],
                            'quantity' => 1,
                            'unit_id' => 0,
                            'service_material_type' => self::SERVICE,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ];
                        $this->service_material->add($data);
                    }
                }

                if ($request->remove_image != null) {
                    $aData_image = $request->remove_image;

                } else {
                    $aData_image = [];
                }

                $list_image = $this->service_image->getItem($id);
                if (count($list_image) > 0) {
                    $name = [];
                    foreach ($list_image as $ima_key => $ima_val) {
                        $name[] = $ima_val['name'];

                    }
                    $cut = array_diff($name, $aData_image);
                    foreach ($cut as $i_cut) {
                        $this->service_image->remove($i_cut);
                    }
                }
                if ($request->add_image != "") {
                    $aData = array_chunk($request->add_image, 1, false);
                    foreach ($aData as $key => $value) {
                        $data = [
                            'service_id' => $request->service_id,
                            'name' => $value[0],
                            'created_by' => Auth::id()
                        ];
                        $this->service_image->add($data);
                    }
                }

                DB::commit();
//                return response()->json(['status' => 1]);
                return response()->json([
                    'error' => false,
                    'message' => __('Cập nhật dịch vụ thành công'),
                ]);
            } else {
//                return response()->json(['status' => 0]);
                return response()->json([
                    'error' => true,
                    'message' => __('Tên dịch vụ đã tồn tại'),
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }


    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function removeAction($id)
    {
        $this->service->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public
    function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_actived'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->service->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => 'Trạng thái đã được cập nhật '
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionServicesAction(Request $request)
    {
        $data = $request->all();
        $id = 0;
        if (isset($request->service_id)) {
            $id = $request->service_id;
        }
        $value = $this->service->getItemServiceSearch($data['search'], $id);

        $search = [];
        foreach ($value as $item) {
            $search['results'][] = [
                'id' => $item['service_id'],
                'text' => $item['service_name'],
                'code' => $item['service_code']
            ];
        }
        return response()->json($search);
    }
}