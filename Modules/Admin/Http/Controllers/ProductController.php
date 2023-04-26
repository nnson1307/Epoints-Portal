<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/2/2018
 * Time: 12:33 PM
 */

namespace Modules\Admin\Http\Controllers;

use App\Exports\CustomerExport;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Admin\Models\ProductAttributeTable;
use Modules\Admin\Models\ProductImageTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\MapProductAttribute\MapProductAttributeRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductAttribute\ProductAttributeRepositoryInterface;
use Modules\Admin\Repositories\ProductAttributeGroup\ProductAttributeGroupRepositoryInterface;
use Modules\Admin\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductImage\ProductImageRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\ProductModel\ProductModelRepositoryInterface;
use Modules\Admin\Repositories\Supplier\SupplierRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use App\Http\Middleware\S3UploadsRedirect;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;

class ProductController extends Controller
{
    protected $product;
    protected $productCategory;
    protected $productModel;
    protected $unit;
    protected $supplier;
    protected $productAttributeGroup;
    protected $productChild;
    protected $branch;
    protected $productImage;
    protected $productAttribute;
    protected $productBranchPrice;
    protected $mapProductAttribute;
    protected $code;
    protected $productInventory;
    protected $s3Disk;
    public function __construct(
        ProductRepositoryInterface $product,
        ProductChildRepositoryInterface $productChild,
        ProductCategoryRepositoryInterface $productCategory,
        ProductModelRepositoryInterface $productModel,
        UnitRepositoryInterface $unit,
        SupplierRepositoryInterface $supplier,
        ProductAttributeGroupRepositoryInterface $productAttributeGroup,
        ProductAttributeRepositoryInterface $productAttribute,
        BranchRepositoryInterface $branch,
        ProductBranchPriceRepositoryInterface $productBranchPrice,
        ProductImageRepositoryInterface $productImage,
        MapProductAttributeRepositoryInterface $mapProductAttribute,
        CodeGeneratorRepositoryInterface $code,
        ProductInventoryRepositoryInterface $productInventory,
        S3UploadsRedirect $_s3

    ) {
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->productModel = $productModel;
        $this->unit = $unit;
        $this->supplier = $supplier;
        $this->productAttributeGroup = $productAttributeGroup;
        $this->productAttribute = $productAttribute;
        $this->productChild = $productChild;
        $this->branch = $branch;
        $this->productImage = $productImage;
        $this->productBranchPrice = $productBranchPrice;
        $this->mapProductAttribute = $mapProductAttribute;
        $this->code = $code;
        $this->productInventory = $productInventory;
        $this->s3Disk = $_s3;
    }

    protected function filters()
    {
        $productCategoryList = (['' => __('Chọn danh mục')]) + $this->productCategory->getAll();
        $productModelList = (['' => __('Chọn nhãn')]) + $this->productModel->getAll();
        $branch = (['' => __('Chọn chi nhánh')]) + $this->branch->getBranch();
        return [
            'products$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng'),

                ]
            ],
            'products$product_category_id' => [
                'data' => $productCategoryList
            ],
            'products$product_model_id' => [
                'data' => $productModelList
            ],
            // 'sort' => [
            //     'data' => [
            //         '' => __('Sắp xếp'),
            //         'price_desc' => __('Giá giảm dần'),
            //         'price_asc' => __('Giá tăng dần'),

            //     ]
            // ],
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only([
            'page', 'display', 'search_type', 'search_keyword', 'sort',
            'products$product_category_id', 'products$supplier_id',
            'products$product_model_id', 'products$product_model_id', 'branches$branch_id', 'created_at', 'products$is_actived'
        ]);

        $productList = $this->product->list($filters);
        return view('admin::product.list', ['LIST' => $productList, 'page' => $filters['page']]);
    }

    public function indexAction()
    {
        $productList = $this->product->list();
        $productCategoryList = $this->productCategory->getAll();
        $productModelList = $this->productModel->getAll();
        $unitList = $this->unit->getAll();
        $supplierList = $this->supplier->getAll();
        $branch = (['' => __('Chọn chi nhánh')]) + $this->branch->getBranch();

        return view('admin::product.index', [
            'LIST' => $productList,
            'FILTER' => $this->filters(),
            'PRODUCTCATEGORY' => $productCategoryList,
            'PRODUCTMODEL' => $productModelList,
            'UNIT' => $unitList,
            'SUPPLIER' => $supplierList,
            'BRANCH' => $branch,
        ]);
    }

    public function removeAction($id)
    {
        $getListProductChild = $this->productChild->getProductChildByProductId(($id));
        $flag = 0;
        foreach ($getListProductChild as $item) {
            $checkProductChild = $this->productInventory->getQuantityProductInventoryByCode($item['product_code']);
            if ($checkProductChild != null) {
                if ($checkProductChild['quantityInventory'] != 0) {
                    $flag = 1;
                }
            }
        }
        if ($flag == 0) {
            $this->product->remove($id);
            return response()->json([
                'error' => 0,
                'message' => 'Remove success'
            ]);
        } else {
            return response()->json([
                'error' => 1,
                'message' => 'Remove success'
            ]);
        }
    }

    public function removeProductInventoryAction(Request $request)
    {
        $id = $request->id;
        $getListProductChild = $this->productChild->getProductChildByProductId(($id));
        foreach ($getListProductChild as $item) {
            $this->productChild->edit(['is_deleted' => 1], $item['product_child_id']);
        }
        $this->product->remove($id);
        return response()->json(['status' => 1]);
    }

    public function addAction()
    {
        if (session()->has('list-topping')){
            session()->forget('list-topping');
        }
        session()->put('list-topping',[]);
        $productCategoryList = $this->productCategory->getAll();
        $productModelList = $this->productModel->getAll();
        $unitList = $this->unit->getAll();
        $supplierList = $this->supplier->getAll();
        $productAttributeGroupList = $this->productAttributeGroup->getOption();
        $branch = $this->branch->getBranchOption();
        return view('admin::product.add', [
            'PRODUCTCATEGORY' => $productCategoryList,
            'PRODUCTMODEL' => $productModelList,
            'UNIT' => $unitList,
            'SUPPLIER' => $supplierList,
            'PRODUCTATTRIBUTEGROUP' => $productAttributeGroupList,
            'BRANCH' => $branch,
        ]);
    }

    public function getOptionProductAttributeGroupAction()
    {
        $data = $this->productAttributeGroup->getOption();
        return response()->json($data);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->product->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function editAction($id)
    {
        $product = $this->product->getItem($id);
        if ($product != null) {
            $category = $this->productCategory->getOptionEditProduct($product->productCategoryId);
            $model = $this->productModel->getOptionEditProduct($product->productModelId);
            $supplier = $this->supplier->getOptionEditProduct($product->supplierId);
            $unit = $this->unit->getOptionEditProduct($product->unitId);
            $dataProductBranchPrice = $this->productBranchPrice->getProductBranchPriceByProduct($id);
            $branch = $this->branch->getBranchOption();
            $dataProductChild = $this->productChild->getProductChildByProductId($id);
            $dataProductAttrGroupAndProductAttr = $this->mapProductAttribute->getMapProductAttributeGroupByProductId($id);
            $productAttribute = $this->productAttribute->getOption();
            $productAttributeByProductId = $this->mapProductAttribute->getProductAttributeByProductId($id);
            $arrayProductAttributeId = [];
            foreach ($productAttributeByProductId as $p) {
                $arrayProductAttributeId[] = $p->productAttributeId;
            }
            $productAttributeWhereNotIn = $this->productAttribute->getProductAttributeWhereNotIn($arrayProductAttributeId);
            $productImage = $this->productImage->getImageByProductId($id);


            $productChildBranchPrice = $this->productBranchPrice->getProductBranchPriceByProductChild($id);

            $arrayProductBranchPrice = [];
            foreach ($productChildBranchPrice as $key => $value) {
                if (!in_array($value, $arrayProductBranchPrice)) {
                    $arrayProductBranchPrice[] = $value;
                }
            }
            // Nếu không có avatar ở product master thì lấy avt ở product child
            if ($product->avatar == null || $product->avatar == "") {
                // Lấy ảnh đại diện sản phẩm (avatar)
                $avatarProduct = $this->productImage->getAvatarOfProductMaster($product['productId']);
                if ($avatarProduct != null) {
                    $product->avatar = $avatarProduct['name'];
                }
            }

            //            $type = '';
            //            $size = '';
            //            $width = '';
            //            $height = '';
            //            if ($product->avatar != null && $product->avatar != "") {
            //                $getimagesize = getimagesize($product->avatar);
            //                $type = strtoupper(substr($product->avatar, strrpos($product->avatar, '.') + 1));
            //                $width = $getimagesize[0];
            //                $height = $getimagesize[1];
            //                $size = (int)round(filesize($product->avatar) / 1024);
            //            }
            //            if (!in_array(Auth::user()->branch_id, $dataProductBranchPrice)) {
            //                session()->flash('error-branch', 1);
            //                return redirect()->route('admin.product');
            //            } else {
            $productAttributeGroup = $this->productAttributeGroup->getOptionAttributeGroup([]);
            $productAttrGroupAndProductAttrSelect = collect($dataProductAttrGroupAndProductAttr)->toArray();
            $productAttributeByProductIdSelect = collect($productAttributeByProductId)->unique('productAttributeId')->groupBy('productAttributeGroupId');
            return view('admin::product.edit', [
                'product' => $product,
                'category' => $category,
                'model' => $model,
                'supplier' => $supplier,
                'unit' => $unit,
                'branchByProduct' => $dataProductBranchPrice,
                'branch' => $branch,
                'productChild' => $dataProductChild,
                'productAttrGroupAndProductAttr' => $dataProductAttrGroupAndProductAttr,
                'productAttrGroupAndProductAttrSelect' => $productAttrGroupAndProductAttrSelect,
                'productAttributeByProductId' => $productAttributeByProductId,
                'productAttributeByProductIdGroup' => collect($productAttribute)->groupBy('product_attribute_group_id'),
                'productAttributeByProductIdSelect' => $productAttributeByProductIdSelect,
                'productAttributeWhereNotIn' => $productAttributeWhereNotIn,
                'id' => $id,
                'productImage' => $productImage,
                'arrayProductBranchPrice' => $arrayProductBranchPrice,
                'productAttributeGroup' => $productAttributeGroup
                //                'type' => $type,
                //                'size' => $size,
                //                'width' => $width,
                //                'height' => $height,
            ]);
            //            }
        } else {
            session()->flash('error-remove', 1);
            return redirect()->route('admin.product');
        }
    }

    public function submitEditAction(Request $request)
    {
        $param = $request->all();

        DB::beginTransaction();
        try {
            if ($request->avatarApp ?? false) {
                $productId = $request->id;
                $oProductImageTable = app()->get(ProductImageTable::class);
                $productImageId = $oProductImageTable->where('product_id', $productId)->where('is_avatar_web', 1)->get()->first()->id ?? false;
                if ($productImageId) {
                    $oProductImageTable->where('product_image_id', $productImageId)->update(['name' => $request->avatarApp]);
                } else {
                    $oProductImageTable->insert([
                        'name' => $request->avatarApp,
                        'product_id' => $productId,
                        'type' => 'desktop',
                        'is_avatar_web' => 1,
                    ]);
                }
            }

            if ($request->ajax()) {
                $arrayProductAttributeByGroup = $request->arrayProductAttributeByGroup;
                $id = $request->id;
                $branch = $request->branch;
                $productCode = $request->productCode;
                $productCategory = $request->productCategory;
                $productModel = $request->productModel;
                $productName = $request->productName;
//            $productNameEN = $request->productNameEN;
                $unit = $request->unit;
                $cost = $request->cost;
                $price = $request->price;
                $isPromo = $request->isPromo;
//                $is_topping = isset($request->is_topping) ? $request->is_topping : 0;
                $isInventoryWarning = $request->isInventoryWarning;
                $inventoryWarning = $request->inventoryWarning;
                $supplier = $request->supplier;
                $isAllBranch = $request->isAllBranch;
                $isActive = $request->isActive;
                $arrayCodeVersionDelete = $request->arrayCodeVersionDelete;
                $productChilds = $request->productChilds;
                $arrayProductAttribute = $request->arrayProductAttribute;
                $arrayAttrAndAttrGroup = $request->arrayAttrAndAttrGroup;
                $arrayAttributeExistsGet = $this->mapProductAttribute->getAllAttrByProductId($id);
                $arrayBranchPriceExistsGet = $this->productBranchPrice->getAllProductBranchPriceByProductId($id);
                $arrayAttributeExists = [];
                $arrayBranchPriceExists = [];
                $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $strlength = strlen($characters);
                $codeProduct = '';
                $response = [];
                $arrImageAjax = $request->arrImage;
                $getImagebyProductChild = $this->productImage->getImageByProductId($id);
                $arrImageDB = [];
                $arrayNewAttribute = [];
                $description = $request->description;
                $avatar = $request->avatar;
                $avatarApp = $request->avatarApp;
                $inventory_management = $request->inventory_management;
                $ajaxChildId = [];
                $childIdExists = [];
                $codeRandom = $this->code->generateCodeRandom("PB");
                $created_at = date("Y-m-d");
                $isSale = $request->isSale;

                $mProductImage = new ProductImageTable();

                if (isset($param['deleteSerial'])) {
                    $this->product->removeSerial($id);
                }

                if ($request->type_refer_commission == 'percent') {
                    if ($request->refer_commission_percent > 100) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => __('Hoa hồng người giới thiệu không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_refer_commission == 'money') {
                    if ($request->refer_commission_value > $cost) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => __('Hoa hồng người giới thiệu vươt quá giá sản phẩm')
                        ]);
                    }
                }
                if ($request->type_staff_commission == 'percent') {
                    if ($request->staff_commission_value > 100) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => __('Hoa hồng nhân viên phục vụ không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_staff_commission == 'money') {
                    if ($request->staff_commission_value > $cost) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => __('Hoa hồng nhân viên phục vụ vượt quá giá sản phẩm')
                        ]);
                    }
                }
                // Hoa hồng cho deal
                if ($request->type_deal_commission == 'percent') {
                    if ($request->deal_commission_percent > 100) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => __('Hoa hồng cho deal không hợp lệ')
                        ]);
                    }
                }
                if ($request->type_deal_commission == 'money') {
                    if ($request->deal_commission_value > $cost) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => __('Hoa hồng cho deal vượt quá giá sản phẩm')
                        ]);
                    }
                }
                if ($request->isSale == 1 && $request->percent_sale > 100) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => __('Tỉ lệ giảm giá không hợp lệ')
                    ]);
                }
                $arrGroupAttrMap = [];

                if (isset($arrayProductAttributeByGroup) && count($arrayProductAttributeByGroup) != 0){

                    $arrGroupAttrMap = $this->mapMatrix($arrayProductAttributeByGroup);
                }

                // Nếu thay đổi avatar
                if ($avatar != null) {
                    $link = $avatar;

                    // Xoá hết avatar product trong product_image theo product_id
                    $mProductImage->deleteAllAvatarByProductId($id);
                    // insert ảnh đại diện cho product child (xoá hết avatar cũ)
                    $listChild = $this->productChild->getProductChildByProductId($id);
                    foreach ($listChild as $key => $value) {
                        $this->productImage->add([
                            'product_id' => $id,
                            'product_child_code' => $value['product_code'],
                            'name' => isset($link) ? $link : '',
                            'created_by' => Auth::id(),
                            'is_avatar' => 1
                        ]);
                    }
                    //Edit product
                    $dataEditProduct = [
                        'product_category_id' => $productCategory,
                        'product_model_id' => $productModel,
                        'product_name' => $productName,
//                    'product_name_en' => $productNameEN,
                        'unit_id' => $unit,
                        'cost' => $cost,
                        'price_standard' => $price,
                        'is_promo' => $isPromo,
                        'is_inventory_warning' => $isInventoryWarning,
                        'inventory_warning' => $inventoryWarning,
                        'supplier_id' => $supplier,
                        'updated_by' => Auth::id(),
                        'is_actived' => $isActive,
                        'is_all_branch' => $isAllBranch,
                        'avatar' => $link,
                        'avatar_app' => $avatarApp,
                        'is_sales' => $isSale,
//                        'is_topping' => $is_topping,
                        'slug' => str_slug($productName),
//                    'slug_en' => str_slug($productNameEN),
                        'type_deal_commission' => $request->type_deal_commission,
                        'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent
                    ];
                    //                Storage::disk('public')->delete(parse_url($this->product->getItem($id)->avatar, PHP_URL_PATH));
                } else {
                    // Nếu không có avatar (đã xoá avt trên view)
                    if ($request->avatarExist == null) {
                        //Storage::disk('public')->delete(parse_url($this->product->getItem($id)->avatar, PHP_URL_PATH));
                        $dataEditProduct = [
                            'product_category_id' => $productCategory,
                            'product_model_id' => $productModel,
                            'product_name' => $productName,
//                        'product_name_en' => $productNameEN,
                            'unit_id' => $unit,
                            'cost' => $cost,
                            'price_standard' => $price,
                            'is_promo' => $isPromo,
                            'is_inventory_warning' => $isInventoryWarning,
                            'inventory_warning' => $inventoryWarning,
                            'supplier_id' => $supplier,
                            'updated_by' => Auth::id(),
                            'is_actived' => $isActive,
                            'is_all_branch' => $isAllBranch,
                            'is_sales' => $isSale,
                            'avatar' => '',
                            'avatar_app' => $avatarApp,
//                            'is_topping' => $is_topping,
                            'slug' => str_slug($productName),
//                        'slug_en' => str_slug($productNameEN),
                            'type_deal_commission' => $request->type_deal_commission,
                            'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent
                        ];
                    } else {
                        $dataEditProduct = [
                            'product_category_id' => $productCategory,
                            'product_model_id' => $productModel,
                            'product_name' => $productName,
//                        'product_name_en' => $productNameEN,
                            'unit_id' => $unit,
                            'cost' => $cost,
                            'price_standard' => $price,
                            'is_promo' => $isPromo,
                            'is_inventory_warning' => $isInventoryWarning,
                            'inventory_warning' => $inventoryWarning,
                            'supplier_id' => $supplier,
                            'updated_by' => Auth::id(),
                            'is_actived' => $isActive,
                            'avatar_app' => $avatarApp,
                            'is_all_branch' => $isAllBranch,
                            'is_sales' => $isSale,
//                            'is_topping' => $is_topping,
                            'slug' => str_slug($productName),
//                        'slug_en' => str_slug($productNameEN),
                            'type_deal_commission' => $request->type_deal_commission,
                            'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent
                        ];
                    }
                }
                $dataEditProduct['type_refer_commission'] = $request->type_refer_commission;
                $dataEditProduct['refer_commission_value'] = $request->type_refer_commission == 'money' ? $request->refer_commission_value : $request->refer_commission_percent;
                $dataEditProduct['type_staff_commission'] = $request->type_staff_commission;
                $dataEditProduct['staff_commission_value'] = $request->type_staff_commission == 'money' ? $request->staff_commission_value : $request->staff_commission_percent;
                $dataEditProduct['percent_sale'] = $request->percent_sale != null ? $request->percent_sale : 0;
                $dataEditProduct['description'] = $request->description;
                $dataEditProduct['description_detail'] = $request->description_detail;
                $dataEditProduct['inventory_management'] = $inventory_management;
                $type_app = null;
                if (isset($request->type_app) && count($request->type_app) > 0) {
                    $type_app = implode(',', $request->type_app);
                }
                $dataEditProduct['type_app'] = $type_app;

                $resultEditProduct = $this->product->edit($dataEditProduct, $id);
                $response = ['editProduct' => $resultEditProduct];
                if ($arrayAttrAndAttrGroup != null) {
                    foreach ($arrayAttrAndAttrGroup as $ke => $val) {
                        $arrayNewAttribute[] = explode('=>', $val);
                    }
                }
                foreach ($this->productAttribute->getOption() as $item) {
                    $arrayAttr[] = $item['product_attribute_label'];
                }
                //add new attribute
                foreach ($arrayNewAttribute as $ii => $jj) {
                    if (!in_array($jj[1], $arrayAttr)) {
                        $dataAddAttribute = [
                            'product_attribute_label' => $jj[1],
                            'product_attribute_code' => $jj[0] . $codeProduct,
                            'created_by' => Auth::id(),
                            'product_attribute_group_id' => $jj[0],
                        ];
                        $this->productAttribute->add($dataAddAttribute);
                        $response = ['addNewAttribute' => 1];
                    }
                }
                for ($i = 0; $i < 15; $i++) {
                    $codeProduct .= $characters[rand(0, $strlength - 1)];
                }
                if ($productCode == "") {
                    $code = $codeProduct;
                } else {
                    $code = $productCode;
                }
                foreach ($arrayAttributeExistsGet as $item) {
                    $arrayAttributeExists[] = $item['product_attribute_id'];
                }
                foreach ($arrayBranchPriceExistsGet as $arr) {
                    $arrayBranchPriceExists[] = $arr['branch_id'];
                }
                //Edit map product attribute.
                $this->mapProductAttribute->deleleAllByProductId($id);

//            if ($arrayProductAttribute != null) {
////                foreach ($arrayProductAttribute as $key => $value) {
////                    //list product attribute.
////                    $getIdAttribute = $this->productAttribute->getProductAttributeGroup($value);
////                    $testAttribute = $this->mapProductAttribute->testMapProductAttributeIsset($id, $getIdAttribute->product_attribute_id);
////                    if ($testAttribute == null) {
////                        $dataAddMapProductAttribute = [
////                            'product_attribute_group_id' => $getIdAttribute->product_attribute_group_id,
////                            'product_attribute_id' => $getIdAttribute->product_attribute_id,
////                            'product_id' => $id
////                        ];
////                        $this->mapProductAttribute->add($dataAddMapProductAttribute);
////                    }
////                }
//                foreach ($arrayProductAttribute as $a => $b) {
//                    $getIdAttr = $this->productAttribute->getProductAttributeGroup($b);
//                    $ar[] = $getIdAttr->product_attribute_id;
//                }
//
//                //$arrayAttributeExists id attribute of product exists.
//                foreach ($arrayAttributeExists as $k => $v) {
//                    if (!in_array($v, $ar)) {
//                        $this->mapProductAttribute->deleteMapProductAttrByAttrId($id, $v);
//                    }
//                }
//            }
////            else {
////                //Xóa hết map_product_attribute của SP.
////                $this->mapProductAttribute->deleleAllByProductId($id);
////            }
                //Edit product child.
                $dataProductChild = $this->productChild->getProductChildByProductId($id);
                foreach ($dataProductChild as $item) {
                    //                $childIdExists[] = $item['product_code'];
                    $childIdExists[] = $item['product_child_name'];
                }
                $arrayNameTemp = [];
                $ajaxChildId = [];
                if ($productChilds != null) {
                    $arrayDataProductChild = array_chunk($productChilds, 7, false);
                    $arrNameImg = [];
                    $mapChildAttr = [];
                    foreach ($arrayDataProductChild as $key => $value) {

                        $nameENTmp = explode('/',$value[0],2);
//                    $nameEN = $productNameEN;
//                    if(isset($nameENTmp[1])){
//                        $nameEN = $nameEN.'/'.$nameENTmp[1];
//                    }

                        $dataEditChildProduct = [
                            'product_id' => $id,
//                            'product_code' => $value[1],
                            'product_child_name' => $value[0],
//                        'product_child_name_en' => $nameEN,
                            'unit_id' => $unit,
                            'cost' => str_replace(",", "", $value[2]),
                            'price' => str_replace(",", "", $value[3]),
                            'updated_by' => Auth::id(),
                            'slug' => str_slug($value[0]),
                            'is_display' => (int)$value[5],
                            'inventory_management' => $inventory_management,
//                            'is_master' => isset($request->is_master_name) && $request->is_master_name == $value[0] ? 1 : 0
                        ];

                        $dataEditChildProduct['is_master'] = 0;

                        if ((!isset($request->is_master_name) && $key == 0) || isset($request->is_master_name) && $request->is_master_name == $value[0]){
                            $dataEditChildProduct['is_master'] = 1;
                        }

                        $matrix = [];
                        if (count($arrGroupAttrMap) != 0 && isset($nameENTmp[1]) && isset($arrGroupAttrMap[$nameENTmp[1]])){
                            $dataEditChildProduct['product_attribute_json'] = json_encode($arrGroupAttrMap[$nameENTmp[1]]['matrix']);
                            $matrix = $arrGroupAttrMap[$nameENTmp[1]]['matrix'];
                        }

                        //Mảng chứa mã phiên bản.
                        $detailProductChild = $this->productChild->getProductChildByCode($value[1]);
                        if($detailProductChild == null){
                            $detailProductChild = $this->productChild->getProductChildByMatrix($id,$matrix);
                        }

                        if($detailProductChild == null){
                            $addChild = $this->productChild->add($dataEditChildProduct);
                            $this->productChild->edit(['product_code' => $this->code->codeDMY('PB', $addChild)], $addChild);
                            $detailProductChild = $this->productChild->getProductChildByMatrix($id,$matrix);
                            $ajaxChildId[] = $addChild;
                        } else {
                            $dataEditChildProduct['product_code'] = $value[1];
                            $ajaxChildId[] = $detailProductChild['product_child_id'];
                        }

                        if (count($arrGroupAttrMap) != 0 && isset($nameENTmp[1]) && isset($arrGroupAttrMap[$nameENTmp[1]])){
//                        $dataEditChildProduct['product_attribute_json'] = json_encode($arrGroupAttrMap[$nameENTmp[1]]);
                            foreach ($arrGroupAttrMap[$nameENTmp[1]]['group'] as $itemMap){
                                $mapChildAttr[] = [
                                    'product_attribute_groupd_id' => $itemMap['product_attribute_groupd_id'],
                                    'product_attribute_id' => $itemMap['product_attribute_id'],
                                    'product_id' => $id,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'product_child_id' => $detailProductChild['product_child_id'],
                                    'product_attribute_json' => json_encode($arrGroupAttrMap[$nameENTmp[1]]['matrix'])
                                ];
                            }
                        }

//                        $ajaxChildId[] = $value[0];
                        $arrayNameTemp[] = $value[0];
                        // Nếu đã tồn tại sản phẩm con (product_child_code != null)
                        if ($value[1] != null) {
                            $this->productChild->updateByCode($dataEditChildProduct, $value[1]);
                            // insert image product child
                            if ($arrImageAjax != null) {
                                foreach ($arrImageAjax as $a => $im) {
                                    $nameImg = null;
                                    if ($key == 0) {
                                        $nameImg = $im;
                                        $arrNameImg[] = $nameImg;
                                    }
                                    $dataAddProductImage = [
                                        'product_id' => $id,
                                        'product_child_code' => $value[1],
                                        'name' => isset($arrNameImg[$a]) ? $arrNameImg[$a] : '',
                                        'created_by' => Auth::id(),
                                        'is_avatar' => 0
                                    ];
                                    $this->productImage->add($dataAddProductImage);
                                }
                            }
                        } else {
                            if ($this->productChild->checkSlug(str_slug($value[0])) == null) {
                                $idChildInsert = $this->productChild->add($dataEditChildProduct);
                                $this->productChild->edit(['product_code' => $this->code->codeDMY('PB', $idChildInsert)], $idChildInsert);
                                // insert avatar (nếu không có update avatar thì lấy từ product)
                                if ($avatar != null) {
                                    $this->productImage->add([
                                        'product_id' => $id,
                                        'product_child_code' => $value[1],
                                        'name' => isset($link) ? $link : '',
                                        'created_by' => Auth::id(),
                                        'is_avatar' => 1
                                    ]);
                                } else {
                                    $prod = $this->product->getItem($id);
                                    $this->productImage->add([
                                        'product_id' => $id,
                                        'product_child_code' => $value[1],
                                        'name' => $prod['avatar'],
                                        'created_by' => Auth::id(),
                                        'is_avatar' => 1
                                    ]);
                                }
                                // insert image product child
                                if ($arrImageAjax != null) {
                                    foreach ($arrImageAjax as $a => $im) {
                                        $nameImg = null;
                                        if ($key == 0) {
                                            $nameImg = $im;
                                            $arrNameImg[] = $nameImg;
                                        }
                                        $dataAddProductImage = [
                                            'product_id' => $id,
                                            'product_child_code' => $this->code->codeDMY('PB', $idChildInsert),
                                            'name' => isset($arrNameImg[$a]) ? $arrNameImg[$a] : '',
                                            'created_by' => Auth::id(),
                                            'is_avatar' => 0
                                        ];
                                        $this->productImage->add($dataAddProductImage);
                                    }
                                }
                                foreach ($branch as $key => $idBranch) {
                                    $ajaxBranch[] = $idBranch;
                                    $testBranch = $this->productBranchPrice->testBanchId($idChildInsert, $idBranch);
                                    if ($testBranch == null) {
                                        $dataAddProductBranchPrice = [
                                            'product_id' => $idChildInsert,
                                            'branch_id' => $idBranch,
                                            'product_code' => $this->code->codeDMY('PB', $idChildInsert),
                                            'updated_at' => $created_at,
                                            'old_price' => str_replace(",", "", $value[3]),
                                            'new_price' => str_replace(",", "", $value[3]),
                                        ];
                                        $this->productBranchPrice->add($dataAddProductBranchPrice);
                                    }
                                }
                            }
                        }
                    }

                    if(count($mapChildAttr) != 0){
                        $this->mapProductAttribute->insertArr($mapChildAttr);
                    }
                }

//                        dd($childIdExists,$ajaxChildId);
//                foreach ($childIdExists as $k => $v) {
//                    if (!in_array($v, $ajaxChildId)) {
//                        $this->productChild->removeByCode($v);
//                    }
//                }


                $this->productChild->removeByArrChildId($id,$ajaxChildId);
                foreach ($arrayNameTemp as $key => $value) {
                    $checkSlugs = $this->productChild->checkSlug(str_slug($value));
                    if ($checkSlugs != null) {
                        $this->productChild->edit(['is_deleted' => 0, 'inventory_management' => $inventory_management], $checkSlugs['product_child_id']);
                    }
                }
                //Nếu không có phiên bản thì thêm 1 phiên bản chính là SP.
                if ($productChilds == null) {
                    $dataProducts = [
                        'product_id' => $id,
                        'product_code' => '',
                        'product_child_name' => $productName,
//                    'product_child_name_en' => $productNameEN,
                        'unit_id' => $unit,
                        'cost' => $cost,
                        'price' => $price,
                        'created_by' => Auth::id(),
                        'created_at' => $created_at,
                        'slug' => str_slug($productName),
                        'inventory_management' => $inventory_management
                    ];
                    $idChildInsert = $this->productChild->add($dataProducts);
                    $this->productChild->edit(['product_code' => $this->code->codeDMY('PB', $idChildInsert)], $idChildInsert);
                }
                ////Edit product branch price.
                //            if ($branch != null) {
                //                $ajaxBranch = [];
                //                foreach ($branch as $key => $idBranch) {
                //                    $ajaxBranch[] = $idBranch;
                //                    $testBranch = $this->productBranchPrice->testBanchId($id, $idBranch);
                //                    if ($testBranch == null) {
                //                        $dataAddProductBranchPrice = [
                //                            'product_id' => $id,
                //                            'branch_id' => $idBranch,
                //                            'product_code' => $code,
                //                            'updated_at' => $created_at,
                //                        ];
                //                        $this->productBranchPrice->add($dataAddProductBranchPrice);
                //                    }
                //                }
                //                foreach ($arrayBranchPriceExists as $k => $v) {
                //                    if (!in_array($v, $ajaxBranch)) {
                //                        $this->productBranchPrice->deleteBranchPrice($id, $v);
                //                    }
                //                }
                //            }
                //get link image in db put in array
                foreach ($getImagebyProductChild as $img) {
                    $arrImageDB[] = $img['name'];
                }

                //edit product image
                //            if ($arrImageAjax != null) {
                //                foreach ($arrImageAjax as $a => $im) {
                //                    $dataAddProductImage = [
                //                        'product_id' => $id,
                //                        'name' => url('/') . '/' .$this->transferTempfileToAdminfile('temp_upload/' . $im, $im),
                //                        'created_by' => Auth::id()
                //                    ];
                //                    $this->productImage->add($dataAddProductImage);
                //                }
                //            }
                ////
                DB::commit();
                return response()->json($response);
            }
        }catch (Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Kiểm tra số serial trong kho
     * @param Request $request
     */
    public function checkSerialEdit(Request $request)
    {
        $data = $this->product->checkSerialEdit($request->all());
        return response()->json($data);
    }

    /**
     * Kiểm tra tồn kho
     * @param Request $request
     */
    public function checkBasicEdit(Request $request)
    {
        $data = $this->product->checkBasicEdit($request->all());
        return response()->json($data);
    }

    public function testProductCodeAction(Request $request)
    {
        if ($request->ajax()) {
            $code = $request->productCode;
            $testProductCodeInProductChild = $this->productChild->testProductCode($code);
            $testProductCodeInProductBranchPrice = $this->productBranchPrice->testProductCode($code);
            if ($testProductCodeInProductChild == null && $testProductCodeInProductBranchPrice == null) {
                $message = '';
            } else {
                $message = 'Mã đã tồn tại';
            }
            return response()->json($message);
        }
    }

    public function getProductAttribute(Request $request)
    {
        $sku = "";
        if (isset($request->cateId)) {
            $dataCategory = $this->productCategory->getItem($request->cateId);
            // dd($dataCategory['category_code']);
            $data = $this->productChild->getProductChildTopId();

            $sku = $dataCategory['category_code'] == '' ? "" : $dataCategory['category_code'] . $data['product_child_id'];
        }
        $attributeGroupId = $request->attributeGroupId;
        $id = uniqid("12345");
        $productAttributeGroup = $this->productAttributeGroup->getOptionAttributeGroup($attributeGroupId);
        $contents = view('admin::product.add-product-attribute', [
            'productAttributeGroup' => $productAttributeGroup,
            'id' => $id,
            'sku' => $sku
        ])
            ->render();
        return $contents;
    }

    private function transferTempfileToAdminfile($path, $imgName)
    {
        //  $imgName = str_replace("temp_upload/", "", $imageName);
        Storage::disk('public')->makeDirectory(PRODUCT_UPLOADS_PATH);
        $new_path = PRODUCT_UPLOADS_PATH . $imgName;
        Storage::disk('public')->move($path, $new_path);
        return $this->s3Disk->getRealPath($new_path);
    }

    public function getProductAttributeEditAction(Request $request)
    {
        // $attributeGroupId = $request->attributeGroupId;
        // $id = uniqid("12345");
        // $productAttributeGroup = $this->productAttributeGroup->getOptionAttributeGroup($attributeGroupId);
        // $contents = view('admin::product.add-product-attribute-edit', ['productAttributeGroup'
        // => $productAttributeGroup, 'id' => $id])
        //     ->render();
        // return $contents;
        $sku = "";
        if (isset($request->cateId)) {
            $dataCategory = $this->productCategory->getItem($request->cateId);
            // dd($dataCategory['category_code']);
            $data = $this->productChild->getProductChildTopId();

            $sku = $dataCategory['category_code'] == '' ? "" : $dataCategory['category_code'] . $data['product_child_id'];
        }
        $attributeGroupId = $request->attributeGroupId;
        $id = uniqid("12345");
        $productAttributeGroup = $this->productAttributeGroup->getOptionAttributeGroup($attributeGroupId);
        $contents = view('admin::product.add-product-attribute-edit', [
            'productAttributeGroup' => $productAttributeGroup,
            'id' => $id,
            'sku' => $sku
        ])
            ->render();
        return $contents;
    }

    public function submitAddAction(Request $request)
    {
        $arrayProductAttributeByGroup = $request->arrayProductAttributeByGroup;
        $category = $request->category;
        $productName = $request->productName;
//        $productNameEN = $request->productNameEN;
        $promo = $request->promo;
        $sale = $request->sale;
        $isActive = $request->isActive;
        $productModel = $request->productModel;
        $supplier = $request->supplier;
        $unit = $request->unit;
        $cost = str_replace(",", "", $request->cost);
        $price = str_replace(",", "", $request->price);
        $branch = $request->branch;
        $isInventoryWarning = $request->isInventoryWarning;
        $inventoryWarning = $request->inventoryWarning;
        $productChilds = $request->productChilds;
        $arrayProductAttribute = $request->arrayProductAttribute;
        $isAllBranch = $request->isAllBranch;
        $arrayAttrAndAttrGroup = $request->arrayAttrAndAttrGroup;
        $arrImage = $request->arrImage;
        $avatar = $request->avatar;
        $avatarApp = $request->avatarApp;
//        $is_topping = isset($request->is_topping) ? $request->is_topping : 0;
        $inventory_management = $request->inventory_management;
        $codeProduct = '';
        $arrayNewAttribute = [];
        $code = $this->code->generateCodeRandom("SP");
        $time = new \DateTime();
        $created_at = $time->format("Y-m-d");
        $link = null;
        $arrGroupAttrMap = [];
        if (isset($arrayProductAttributeByGroup) && count($arrayProductAttributeByGroup) != 0){

            $arrGroupAttrMap = $this->mapMatrix($arrayProductAttributeByGroup);
        }

        if (isset($productChilds) && count($productChilds) > 0) {
            $aData = array_chunk($productChilds, 6, false);
            foreach ($aData as $index => $objProductChild) {
                if ($objProductChild[3] != "") {
                    $checkResult = $this->productChild->checkSku($objProductChild[3], 0);
                    if ($checkResult != null) {
                        return response()->json([
                            'error_check' => 1,
                            'message' => 'Mã sku đã tồn tại'
                        ]);
                    }
                }
            }
        }
        foreach ($this->productAttribute->getOption() as $item) {
            $arrayAttr[$item['product_attribute_label']] = $item['product_attribute_group_id'];
        }
        if ($arrayAttrAndAttrGroup != null) {
            foreach ($arrayAttrAndAttrGroup as $ke => $val) {
                $arrayNewAttribute[] = explode('=>', $val);
            }
        }

        try {
            DB::beginTransaction();

            if ($request->type_refer_commission == 'percent') {
                if ($request->refer_commission_percent > 100) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => 'Hoa hồng người giới thiệu không hợp lệ'
                    ]);
                }
            }
            if ($request->type_refer_commission == 'money') {
                if ($request->refer_commission_value > $cost) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => 'Hoa hồng người giới thiệu vươt quá giá sản phẩm'
                    ]);
                }
            }
            if ($request->type_staff_commission == 'percent') {
                if ($request->staff_commission_percent > 100) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => 'Hoa hồng nhân viên phục vụ không hợp lệ'
                    ]);
                }
            }
            if ($request->type_staff_commission == 'money') {
                if ($request->staff_commission_value > $cost) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => 'Hoa hồng nhân viên phục vụ vượt quá giá sản phẩm'
                    ]);
                }
            }
            // Hoa hồng cho deal
            if ($request->type_deal_commission == 'percent') {
                if ($request->deal_commission_percent > 100) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => 'Hoa hồng cho deal không hợp lệ'
                    ]);
                }
            }
            if ($request->type_deal_commission == 'money') {
                if ($request->deal_commission_value > $cost) {
                    return response()->json([
                        'error_check' => 1,
                        'message' => 'Hoa hồng cho deal vượt quá giá sản phẩm'
                    ]);
                }
            }
            if ($request->sale == 1 && $request->percent_sale > 100) {
                return response()->json([
                    'error_check' => 1,
                    'message' => 'Tỉ lệ giảm giá không hợp lệ'
                ]);
            }

            if ($avatar != null) {
                $link = $avatar;
            }

            $dataAddAttribute = '';
            foreach ($arrayNewAttribute as $ii => $jj) {
                foreach ($arrayAttr as $c => $d) {
                    if ($jj[0] != $d && $jj[1] != $c) {
                        $dataAddAttribute = [
                            'product_attribute_label' => $jj[1],
                            'product_attribute_code' => $jj[0] . $codeProduct,
                            'created_by' => Auth::id(),
                            'product_attribute_group_id' => $jj[0],
                        ];
                    }
                }
                if ($dataAddAttribute != '') {
                    $this->productAttribute->add($dataAddAttribute);
                }
            }

            $type_app = null;

            if (isset($request->type_app) && count($request->type_app) > 0) {
                $type_app = implode(',', $request->type_app);
            }
            $dataProduct = [
                'product_category_id' => $category,
                'product_model_id' => $productModel,
                'product_name' => $productName,
//                'product_name_en' => $productNameEN,
                'unit_id' => $unit,
                'cost' => str_replace(",", "", $cost),
                'price_standard' => str_replace(",", "", $price),
                'is_promo' => $promo,
                'supplier_id' => $supplier,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_actived' => $isActive,
                'is_inventory_warning' => $isInventoryWarning,
                'inventory_warning' => $inventoryWarning,
                'is_all_branch' => $isAllBranch,
                'description' => $request->description,
                'description_detail' => $request->description_detail,
                'type_app' => $type_app,
                'avatar' => $link,
                'avatar_app' => $avatarApp,
                'created_at' => $created_at,
                'is_sales' => $sale,
//                'is_topping' => $is_topping,
                'inventory_management' => $inventory_management,
                'slug' => str_slug($productName),
//                'slug_en' => str_slug($productNameEN),
                'type_refer_commission' => $request->type_refer_commission,
                'refer_commission_value' => $request->type_refer_commission == 'money' ? $request->refer_commission_value : $request->refer_commission_percent,
                'type_staff_commission' => $request->type_staff_commission,
                'staff_commission_value' => $request->type_staff_commission == 'money' ? $request->staff_commission_value : $request->staff_commission_percent,
                'type_deal_commission' => $request->type_deal_commission,
                'deal_commission_value' => $request->type_deal_commission == 'money' ? $request->deal_commission_value : $request->deal_commission_percent,
                'percent_sale' => $request->percent_sale != null ? $request->percent_sale : 0
            ];
            $productId = $this->product->add($dataProduct);

            if ($request->avatarApp ?? false) {
                $oProductImageTable = app()->get(ProductImageTable::class);
                $productImageId = $oProductImageTable->where('product_id', $productId)->where('is_avatar_web', 1)->get()->first()->id ?? false;
                if ($productImageId) {
                    $oProductImageTable->where('product_image_id', $productImageId)->update(['name' => $request->avatarApp]);
                } else {
                    $oProductImageTable->insert([
                        'name' => $request->avatarApp,
                        'product_id' => $productId,
                        'type' => 'desktop',
                        'is_avatar_web' => 1,
                    ]);
                }
            }

            //add map product attribute.
//            if ($arrayProductAttribute != null) {
//                foreach ($arrayProductAttribute as $key => $value) {
//                    $idProductAttributeGroup = $this->productAttribute->getProductAttributeGroup($value);
//                    $dataMapProductAttbute['product_attribute_group_id'] = $idProductAttributeGroup->product_attribute_group_id;
//                    $dataMapProductAttbute['product_attribute_id'] = $idProductAttributeGroup->product_attribute_id;
//                    $dataMapProductAttbute['product_id'] = $productId;
//                    $this->mapProductAttribute->add($dataMapProductAttbute);
//                }
//            }
            //Add product child
            if ($productChilds != null) {
                $aData = array_chunk($productChilds, 6, false);
                $arrNameImg = [];

                foreach ($aData as $key => $value) {
                    $nameENTmp = explode('/',$value[0],2);
//                    $nameEN = $productNameEN;
//                    if(isset($nameENTmp[1])){
//                        $nameEN = $nameEN.'/'.$nameENTmp[1];
//                    }

                    $checkSlug = $this->productChild->checkSlug(str_slug($value[0]));
//                    $checkSlugEN = $this->productChild->checkSlugEN(str_slug($nameEN));
                    if ($checkSlug == null) {
                        $dataProductChild = [
                            'product_id' => $productId,
                            'product_child_name' => $value[0],
//                            'product_child_name_en' => $nameEN,
                            'unit_id' => $unit,
                            'cost' => str_replace(",", "", $value[1]),
                            'price' => str_replace(",", "", $value[2]),
                            'product_child_sku' => str_replace(",", "", $value[3]),
                            'created_by' => Auth::id(),
                            'created_at' => $created_at,
                            'slug' => str_slug($value[0]),
//                            'slug_en' => str_slug($nameEN),
                            'is_display' => (int)$value[4],
                            'inventory_management' => $inventory_management,
//                            'is_master' => !isset($request->is_master_name) && $request->is_master_name == $value[0] ? 1 : 0
                        ];
                        $dataProductChild['is_master'] = 0;

                        if ((!isset($request->is_master_name) && $key == 0) || isset($request->is_master_name) && $request->is_master_name == $value[0]){
                            $dataProductChild['is_master'] = 1;
                        }

                        if (count($arrGroupAttrMap) != 0 && isset($nameENTmp[1])){
                            $dataProductChild['product_attribute_json'] = json_encode($arrGroupAttrMap[$nameENTmp[1]]['matrix']);
                        }

                        $addChild = $this->productChild->add($dataProductChild);
                        $mapChildAttr = [];
                        if (count($arrGroupAttrMap) != 0 && isset($nameENTmp[1])){
//                            $dataProductChild['product_attribute_json'] = json_encode($arrGroupAttrMap[$nameENTmp[1]]);
                            foreach ($arrGroupAttrMap[$nameENTmp[1]]['group'] as $itemMap){
                                $mapChildAttr[] = [
                                    'product_attribute_groupd_id' => $itemMap['product_attribute_groupd_id'],
                                    'product_attribute_id' => $itemMap['product_attribute_id'],
                                    'product_id' => $productId,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'product_child_id' => $addChild,
                                    'product_attribute_json' => json_encode($arrGroupAttrMap[$nameENTmp[1]]['matrix'])
                                ];
                            }
                        }

                        if (count($mapChildAttr) != 0){
                            $this->mapProductAttribute->insertArr($mapChildAttr);
                        }

                        $this->productChild->edit(['product_code' => $this->code->codeDMY('PB', $addChild)], $addChild);
                        //Thêm avatar vào product image
                        $this->productImage->add([
                            'product_id' => $productId,
                            'product_child_code' => $this->code->codeDMY('PB', $addChild),
                            'name' => isset($link) ? $link : '',
                            'created_by' => Auth::id(),
                            'created_at' => $created_at,
                            'is_avatar' => 1
                        ]);
                        //add product image
                        if ($arrImage != null) {
                            foreach ($arrImage as $a => $im) {
                                $nameImg = null;
                                if ($key == 0) {
                                    $nameImg = $im;
                                    $arrNameImg[] = $nameImg;
                                }
                                $dataAddProductImage = [
                                    'product_id' => $productId,
                                    'product_child_code' => $this->code->codeDMY('PB', $addChild),
                                    'name' => isset($arrNameImg[$a]) ? $arrNameImg[$a] : '',
                                    'created_by' => Auth::id(),
                                    'created_at' => $created_at,
                                ];
                                $this->productImage->add($dataAddProductImage);
                            }
                        }
                        if ($branch != null) {
                            foreach ($branch as $key => $value) {
                                $dataBranch = [
                                    'product_id' => $addChild,
                                    'branch_id' => $value,
                                    'product_code' => $this->code->codeDMY('PB', $addChild),
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'is_actived' => $request->isActive,
                                    'created_at' => $created_at,
                                    'old_price' => $price,
                                    'new_price' => $price,
                                ];
                                $this->productBranchPrice->add($dataBranch);
                            }
                        }
                    } else {
                        $this->productChild->edit(['is_deleted' => 0], $checkSlug['product_child_id']);
                    }
                }
            } else {
                $checkSlug = $this->productChild->checkSlug(str_slug($productName));
//                $checkSlugEN = $this->productChild->checkSlugEN(str_slug($productNameEN));
                if ($checkSlug == null) {
                    $dataProducts = [
                        'product_id' => $productId,
                        'product_code' => $code,
                        'product_child_name' => $productName,
                        'unit_id' => $unit,
                        'cost' => $cost,
                        'price' => $price,
                        'created_by' => Auth::id(),
                        'created_at' => $created_at,
                        'slug' => str_slug($productName),
                        'inventory_management' => $inventory_management,
                        'is_master' => 1
                    ];
                    $addChild = $this->productChild->add($dataProducts);
                    if ($branch != null) {
                        foreach ($branch as $key => $value) {
                            $dataBranch = [
                                'product_id' => $addChild,
                                'branch_id' => $value,
                                'product_code' => $this->code->codeDMY('PB', $addChild),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'is_actived' => $request->isActive,
                                'created_at' => $created_at,
                                'old_price' => $price,
                                'new_price' => $price,
                            ];
                            $this->productBranchPrice->add($dataBranch);
                        }
                    }
                    $this->productChild->edit(['product_code' => $this->code->codeDMY('PB', $addChild)], $addChild);
                    //add product image
                    if ($arrImage != null) {
                        foreach ($arrImage as $a => $im) {
                            $dataAddProductImage = [
                                'product_id' => $productId,
                                'product_child_code' => $this->code->codeDMY('PB', $addChild),
                                'name' => $im,
                                'created_by' => Auth::id(),
                                'created_at' => $created_at,
                            ];
                            $this->productImage->add($dataAddProductImage);
                        }
                    }
                } else {
                    $this->productChild->edit(['is_deleted' => 0, 'inventory_management' => $inventory_management], $checkSlug['product_child_id']);
                }
            }
            //add product image
            //            if ($arrImage != null) {
            //                foreach ($arrImage as $a => $im) {
            //                    $dataAddProductImage = [
            //                        'product_id' => $productId,
            //                        'name' => url('/') . '/' .$this->transferTempfileToAdminfile('temp_upload/' . $im, $im),
            //                        'created_by' => Auth::id(),
            //                        'created_at' => $created_at,
            //                    ];
            //
            //                    $this->productImage->add($dataAddProductImage);
            //                }
            //            }
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function mapMatrix($arrayProductAttributeByGroup){
        $arrayProductAttributeByGroup = collect($arrayProductAttributeByGroup)->groupBy('attrGroupId');
//            Map các thuộc tính
        $arrGroupAttrMap = $this->mapArrAttrGroup([],$arrayProductAttributeByGroup);

        $tmp = [];

        foreach ($arrGroupAttrMap as $item){
            $tmp = collect($tmp)->merge($item)->unique();
        }

        $mProductAttr = app()->get(ProductAttributeTable::class);
//        Lấy danh sách thuộc tính
        $listAttr = $mProductAttr->getListByArrId($tmp);

        if(count($listAttr) != 0){
            $listAttr = collect($listAttr)->keyBy('product_attribute_id');
        }

        $arrMatrix = [];
        foreach ($arrGroupAttrMap as $key => $item){
            $keyName = '';
            $group = [];
            foreach ($item as $itemValue){
                $keyName = $keyName.($keyName == '' ? '' :'/').$listAttr[$itemValue]['product_attribute_label'];
                $group[] = [
                    'product_attribute_groupd_id' => $listAttr[$itemValue]['product_attribute_group_id'],
                    'product_attribute_id' => $itemValue,
                ];
            }
            $arrMatrix[$keyName]['group'] = $group;
            $arrMatrix[$keyName]['matrix'] = $item;
        }

        return $arrMatrix;
    }

    public function mapArrAttrGroup($arrGroupAttr,$arrayProductAttributeByGroup){
        $tmpAttr = [];
        $arrayProductAttributeByGroup = array_values(collect($arrayProductAttributeByGroup)->toArray());
        if (count($arrGroupAttr) != 0){
            foreach ($arrGroupAttr as $key => $item){
                foreach ($arrayProductAttributeByGroup[0] as $keyAttr => $itemAttr){
                    $tmpAttr[] = array_merge($item,[(int)$itemAttr['attributeId']]);
                }
            }
        } else {
            foreach ($arrayProductAttributeByGroup[0] as $item){
                $tmpAttr[] = [(int)$item['attributeId']];
            }
        }
        unset($arrayProductAttributeByGroup[0]);

        if (count($arrayProductAttributeByGroup) != 0){
            return $this->mapArrAttrGroup($tmpAttr,$arrayProductAttributeByGroup);
        } else {
            return $tmpAttr;
        }
    }

    public function addProductAttributeAction(Request $request)
    {
        if ($request->ajax()) {
            $attributeGroup = $request->productAttributeGroup_id;
            $data = [
                'product_attribute_group_id' => $attributeGroup,
                'product_attribute_label' => $request->productAttributeLabel,
                'product_attribute_code' => time() . '_' . date('Ymd') . '_' . $attributeGroup,
                'is_actived' => $request->isActived,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
            $this->productAttribute->add($data);
        }
    }

    public function productVersionAction(Request $request)
    {
        $proAttribute = $request->proAttribute;
        $branch = $request->branches;
        $arr = array();
        $jsonBranch = [];
        $jsonAttribute = [];
        if ($branch != "") {
            foreach ($branch as $key => $value) {
                $itemBranch = $this->branch->getItem($value);
                $branchArray[] = [$itemBranch->branch_id => $itemBranch->branch_name];
                $jsonBranch = [
                    'branchVersion' => $branchArray,
                ];
            }
        }
        if ($proAttribute != "") {
            foreach ($proAttribute as $key => $value) {
                if (!in_array($value, $arr)) {
                    $arr[] = $value;
                    $itemProductAttribute = $this->productAttribute->getItem($value);
                    $jsonAttribute = [
                        'attribute' => $itemProductAttribute->product_attribute_label,
                        'attributeId' => $itemProductAttribute->product_attribute_id,
                    ];
                }
            }
        }
        return response()->json(['jsonBranch' => $jsonBranch, 'jsonAttribute' => $jsonAttribute]);
    }

    public function uploadsAction(Request $request)
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
            return response()->json($upload_success, 200);
        } // Else, return error 400
        else {
            return response()->json('error', 400);
        }
    }

    public function deleteFileAction(Request $request)
    {
        $path = TEMP_PATH . '/' . $request->filename;
        Storage::disk('public')->delete($path);
        return response()->json(["success" => "1"]);
    }

    public function detailAction($id)
    {
        $dataDetailProduct = $this->product->getDetailProduct($id);
        // Nếu không có avatar ở product master thì lấy avt ở product child
        if ($dataDetailProduct['avatar'] == null || $dataDetailProduct['avatar'] == "") {
            $avatarProduct = $this->productImage->getAvatarOfProductMaster($dataDetailProduct['productId']);
            if ($avatarProduct != null) {
                $dataDetailProduct['avatar'] = $avatarProduct['name'];
            }
        }
        $dataProductChild = $this->productChild->getProductChildByProductId($id);
        $imageProduct = $this->productImage->getImageByProductId($id);
        return view('admin::product.detail-product', [
            'dataDetailProduct' => $dataDetailProduct,
            'dataProductChild' => $dataProductChild,
            'imageProduct' => $imageProduct,
        ]);
    }

    //
    public function createNameProductChild(Request $request)
    {

        $main_array = $request->arrAttribute;
        if (!empty($main_array)) {
            $count_arr = count($main_array);
            $arr_result = [];
            $start_arr = $main_array[0];
            $temp = $main_array[0];

            for ($i = 1; $i < $count_arr; $i++) {
                foreach ($temp as $key1 => $value1) {
                    foreach ($main_array[$i] as $key2 => $value2) {
                        $arr_result[] = [$value1, $value2];
                    }
                }
                $temp = $arr_result;
            }
            function arrayFlatten(array $array)
            {
                $flatten = array();
                array_walk_recursive($array, function ($value) use (&$flatten) {
                    $flatten[] = $value;
                });

                return $flatten;
            }

            foreach ($arr_result as $key => &$value) {
                if (is_array($value)) {
                    $value = arrayFlatten($value);
                }
            }

            foreach ($arr_result as $key => &$value) {
                if (count($value) < $count_arr) {
                    unset($arr_result[$key]);
                }
            }
            $result = [];
            foreach ($arr_result as $k => $v) {
                $result[] = implode('/', $v);
            }
            return response()->json($result);
        }
    }

    //Kiểm tra trùng tên sản phẩm
    public function checkNameAction(Request $request)
    {
        $productName = $request->productName;
//        $productNameEN = $request->productNameEN;
        $id = $request->id;
        $message = __('Sản phẩm đã tồn tại');
        $checkResult = $this->product->checkName(str_slug($productName), $id);
//        $checkResultEN = $this->product->checkNameEN(str_slug($productNameEN), $id);
        if ($checkResult == null) {
            return response()->json(['error' => 0]);
        } else {
            return response()->json([
                'error' => 1,
                'message' => $checkResult != null ? $message : '',
//                'message_en' => $checkResultEN != null ? $message : ''
            ]);
        }
    }

    //Kiểm tra trùng tên sản phẩm
    public function checkSkuAction(Request $request)
    {
        $productSku = $request->productSku;
        $id = $request->id;
        if ($productSku != "") {
            return response()->json(['error' => 0]);
        }
        $checkResult = $this->product->checkSku($productSku, $id);
        if ($checkResult == null) {
            return response()->json(['error' => 0]);
        } else {
            return response()->json(['error' => 1]);
        }
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
            $upload_success = Storage::disk('public')->put(TEMP_PATH . "/" . $filename, file_get_contents($image), 'public');
            // If the upload is successful, return the name of directory/filename of the upload.
            if ($upload_success) {
                return response()->json($filename, 200);
            } // Else, return error 400
            else {
                return response()->json('error', 400);
            }
        }
    }

    public function deleteImageByProductIdAndLinkAction(Request $request)
    {
        $productId = $request->productId;
        $link = $request->link;
        $deleteImage = Storage::disk('public')->delete($link);
        $result = $this->productImage->deleteImageByProductIdAndLink($productId, $link);
        if ($deleteImage == true) {
            return response()->json(['error' => 0]);
        } else {
            return response()->json(['error' => 1]);
        }
    }

    public function deleteImageTempAction(Request $request)
    {
        $img = str_replace("temp_upload/", "", $request->img);
        $path = TEMP_PATH . '/' . $img;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function editProductBranchPrice(Request $request)
    {
        $id = $request->id;
        $branch = $request->branch;

        $productChildBranchPrice = $this->productBranchPrice->getProductChildBranchPriceByParentId($id);

        foreach ($productChildBranchPrice as $item) {
            if (!in_array($item['branchId'], $branch)) {
                $this->productBranchPrice->edit(['is_deleted' => 1], $item['product_branch_price_id']);
            }
        }
        $productChild = $this->productChild->getProductChildByProductId($id);

        foreach ($productChild as $item) {
            foreach ($branch as $key => $value) {
                $checkProductBranchPrice = $this->productBranchPrice->checkProductChildIssetBranchPrice($value, $item['product_code']);
                if ($checkProductBranchPrice != null) {
                    $data = [
                        'old_price' => $item['price'],
                        'new_price' => $item['price'],
                        'is_deleted' => 0,
                        'is_actived' => 1,
                        'updated_by' => Auth::id(),

                    ];
                    $this->productBranchPrice->edit($data, $checkProductBranchPrice['product_branch_price_id']);
                } else {
                    $data = [
                        'product_id' => $item['product_child_id'],
                        'branch_id' => $value,
                        'product_code' => $item['product_code'],
                        'old_price' => $item['price'],
                        'new_price' => $item['price'],
                        'is_deleted' => 0,
                        'is_actived' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::id(),
                    ];
                    $this->productBranchPrice->add($data);
                }
            }
        }
    }

    /**
     * Import excel file image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importFileImageAction(Request $request)
    {
        $data = $this->product->importFileImage($request->all());

        return response()->json($data);
    }

    /**
     * Tắt hiển thị sp không có hình ảnh
     *
     * @return mixed
     */
    public function unDisplayAction()
    {
        return $this->product->unDisplay();
    }

    /**
     * Hiển thị popup serial
     */
    public function showPopupSerial(Request $request)
    {
        $data = $this->product->showPopupSerial($request->all());

        return response()->json($data);
    }

    /**
     * Lấy danh sách serial
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSerial(Request $request)
    {
        $data = $this->product->searchSerial($request->all());

        return response()->json($data);
    }

    /**
     * Lấy danh sách serial
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function genSkuProduct(Request $request)
    {
        $dataCategory = $this->productCategory->getItem($request->cateId);
        // dd($dataCategory['category_code']);
        $data = $this->product->getProductTopId();
        $sku = $dataCategory['category_code'] == '' ? "" : $dataCategory['category_code'] . $data['product_id'];
        return response()->json([
            'sku' => $sku
        ]);
    }

    public function getListProductChild(Request $request){
        $param = $request->all();
        $data = $this->product->getListProductChild($param);
        return response()->json($data);
    }

    public function importProductAction(Request $request){
        $file = $request->file('file');
        if (isset($file)) {
            $typeFileExcel = $file->getClientOriginalExtension();
            if ($typeFileExcel == "xlsx") {
                $reader = ReaderFactory::create(Type::XLSX);
                $reader->open($file);


                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $key => $row) {
                        if ($key > 1) {
                            $code = strip_tags(isset($row[1]) ? $row[1] : '');
                            $barcode = strip_tags(isset($row[2]) ? $row[2] : '');
                            $origin = strip_tags(isset($row[3]) ? $row[3] : '');
                            $category = strip_tags(isset($row[4]) ? $row[4] : '');
                            $brand = strip_tags(isset($row[5]) ? $row[5] : '');
                            $name = strip_tags(isset($row[6]) ? $row[6] : '');
                            $nameVi = strip_tags(isset($row[7]) ? $row[7] : '');
                            $attribute = strip_tags(isset($row[9]) ? $row[9] : '');
                            $price = strip_tags(isset($row[10]) ? $row[10] : '');
                            $description = strip_tags(isset($row[11]) ? $row[11] : '');
                            $image = strip_tags(isset($row[12]) ? $row[12] : '');

                            $this->product->importProduct([
                                'code' => $code,
                                'barcode' => $barcode,
                                'origin' => $origin,
                                'category' => $category,
                                'brand' => $brand,
                                'name' => $name,
                                'name_vi' => $nameVi,
                                'attribute' => $attribute,
                                'price' => $price,
                                'description' => $description,
                                'image' => $image,
                            ]);
                        }
                    }
                }

                $reader->close();
            }
            return response()->json([
                'error' => false,
                'success' => 1,
                'message' => __('Import thông tin sản phẩm thành công')
            ]);
        }
    }

    public function exportProductTemplateAction(Request $request){
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ProductExport(), 'products-template.xlsx');
    }

}
