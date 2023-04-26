<?php


namespace Modules\Admin\Repositories\ProductChildNew;



use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\InventoryCheckingStatusTable;
use Modules\Admin\Models\ProductBranchPriceTable;
use Modules\Admin\Models\ProductChildCustomDefineTable;
use Modules\Admin\Models\ProductChildNewTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductImageTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\ProductInventoryTable;
use Modules\Admin\Models\ProductTagMapTable;
use Modules\Admin\Models\ProductTagTable;
use Modules\Admin\Models\WarehouseTable;

class ProductChildNewRepository implements ProductChildNewRepositoryInterface
{
    protected $productChild;

    public function __construct(
        ProductChildNewTable $productChild
    ) {
        $this->productChild = $productChild;
    }

    /**
     * Danh sách product child
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        $mProductPrice = new ProductBranchPriceTable();

        $list =  $this->productChild->getList($filters);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $item) {
                $item['site_id'] = "";

                $getProductBranch = $mProductPrice->getProductPrice($item['product_code']);

                foreach ($getProductBranch as $k => $v) {
                    $item['site_id'] .= $k == 0 ? $v['site_id'] : "," . $v['site_id'];
                }
            }
        }

        return $list;
    }

    /**
     * Cập nhật trạng thái cho is_active, is_display
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateStatus($input)
    {
        try {
            $this->productChild->edit($input, $input['product_child_id']);
            return response()->json([
                'error' => false,
                'message' => __('Cập nhật thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Cập nhật thất bại'),
            ]);
        }
    }

    /**
     * data màn hình chỉnh sửa
     *
     * @param $id
     * @return array|mixed
     */
    public function dataViewEdit($id)
    {
        $mProductImage = new ProductImageTable();
        $mTag = new ProductTagTable();
        $mTagMap = new ProductTagMapTable();
        $mWarehouse = app()->get(WarehouseTable::class);

        $productChild = $this->productChild->getItem($id);
        $productAvatar = $mProductImage->getAvatar($productChild['product_code']);
        $listImage = $mProductImage->getImageExceptAvatar($productChild['product_code']);
        //Lấy cấu hình thông tin kèm theo của KH
        $mCustomDefine = new ProductChildCustomDefineTable();
        $customDefine = $mCustomDefine->getDefine();
        //Lấy option tag
        $optionTag = $mTag->getOption();
        //Lấy array tag map
        $getTagMap = $mTagMap->getMapByProduct($id);

        $arrTagMap = [];

        if (count($getTagMap) > 0) {
            foreach ($getTagMap as $v) {
                $arrTagMap[] = $v['tag_id'];
            }
        }

        $listWarehouse = $mWarehouse->getWareHouseOption();

        return [
            'productChild' => $productChild,
            'productAvatar' => $productAvatar,
            'listImage' => $listImage,
            'customDefine' => $customDefine,
            'optionTag' => $optionTag,
            'arrTagMap' => $arrTagMap,
            'listWarehouse' => $listWarehouse
        ];
    }

    /**
     * Chỉnh sửa product child
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateAction($input)
    {
        try {
            $mCustomDefine = new ProductChildCustomDefineTable();
            $mTagMap = new ProductTagMapTable();

            // TODO: check unique custom define
            if (isset($input['arrCustom']) && count($input['arrCustom']) > 0) {
                foreach ($input['arrCustom'] as $key => $value) {
                    $defineDetail = $mCustomDefine->getDefineDetail($key);
                    if ($defineDetail['type'] == 'product_code' && $value != "") {
                        $check = $this->productChild->checkExistsCustomDefineProductCode($key, $value, $input['product_child_id']);
                        if ($check != null) {
                            $title = $defineDetail['title'];
                            return response()->json([
                                'error' => true,
                                'message' => $title . __(" đã tồn tại"),
                            ]);
                        }
                    }
                }
            }
            //check sku is exist
            if (isset($input['product_child_sku']) && $input['product_child_sku'] != '') {
                $isExistSku = $this->productChild->checkExistProductSku($input['product_child_id'], $input['product_child_sku']);
                if (isset($isExistSku)) {
                    return response()->json([
                        'error' => true,
                        'message' => __("SKU: ") . $input['product_child_sku'] . __(" đã tồn tại"),
                    ]);
                }
            }

            $mProductImage = new ProductImageTable();
            $avatar = $input['product_avatar'];
            $price = floatval(str_replace(",", "", $input['price']));
            $cost = floatval(str_replace(",", "", $input['cost']));
            if ($cost < 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá nhập phải lớn hơn 0'),
                ]);
            }
            if ($price < 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá bán phải lớn hơn 0'),
                ]);
            }
            if ($price < $cost) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá bán phải lớn hơn giá nhập'),
                ]);
            }
            // nếu product_avatar != null thì cập nhật is_avatar
            if ($avatar != null) {
                $linkAvatar = $avatar;
                $getAvatar = $mProductImage->getAvatar($input['product_child_code']);
                if ($getAvatar == null) {
                    $mProductImage->add([
                        'product_id' => $input['product_id'],
                        'product_child_code' => $input['product_child_code'],
                        'name' => $linkAvatar,
                        'created_by' => Auth()->id(),
                        'is_avatar' => 1,
                    ]);
                } else {
                    $mProductImage->editAvatar(['name' => $linkAvatar], $input['product_child_code']);
                }
            }
            // Xoá hết ảnh cũ ?
            $mProductImage->removeImageByProdChildCode($input['product_child_code']);
            // imageOld: không cần sửa lại link ảnh
            if (isset($input['arrImageOld']) && count($input['arrImageOld']) > 0) {
                foreach ($input['arrImageOld'] as $v) {
                    $temp = [
                        'product_id' => $input['product_id'],
                        'product_child_code' => $input['product_child_code'],
                        'name' => $v,
                        'created_by' => Auth()->id(),
                        'is_avatar' => 0,
                    ];
                    $mProductImage->add($temp);
                }
            }
            // imageNew: phải sửa lại link ảnh
            if (isset($input['arrImageNew']) && count($input['arrImageNew']) > 0) {
                foreach ($input['arrImageNew'] as $v) {
                    $linkAvatar = $v;

                    $temp = [
                        'product_id' => $input['product_id'],
                        'product_child_code' => $input['product_child_code'],
                        'name' => $linkAvatar,
                        'created_by' => Auth()->id(),
                        'is_avatar' => 0,
                    ];
                    $mProductImage->add($temp);
                }
            }

            $dataProduct = [
                'product_child_name' => $input['product_child_name'],
                'cost' => str_replace(",", "", $input['cost']),
                'price' => str_replace(",", "", $input['price']),
                'product_child_sku' => $input['product_child_sku'],
                'is_actived' => $input['is_actived'],
                'is_display' => $input['is_display'],
                'is_surcharge' => $input['is_surcharge'],
                'is_applied_kpi' => $input['is_applied_kpi'],
                'is_remind' => $input['is_remind'],
                'remind_value' => $input['is_remind'] == 1 ? $input['remind_value'] : null,
                'barcode' => $input['barcode']
            ];
            //Define sẵn 10 trường thông tin kèm theo
            for ($i = 1; $i <= 10; $i++) {
                $custom = "custom_$i";
                $dataProduct["custom_$i"] = isset($input['arrCustom'][$custom]) ? $input['arrCustom'][$custom] : null;
            }
            //Update sản phẩm con
            $this->productChild->edit($dataProduct, $input['product_child_id']);

            //Xoá tag map
            $mTagMap->removeMapByProduct($input['product_child_id']);

            $arrTagMap = [];

            if (isset($input['tag_id']) && count($input['tag_id']) > 0) {
                foreach ($input['tag_id'] as $v) {
                    $arrTagMap[] = [
                        "tag_id" => $v,
                        "product_child_id" => $input['product_child_id']
                    ];
                }
            }
            //Insert tag
            $mTagMap->insert($arrTagMap);

            return response()->json([
                'error' => false,
                'message' => __('Cập nhật thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function transferTempfileToAdminfile($path, $imgName)
    {
        //  $imgName = str_replace("temp_upload/", "", $imageName);
        Storage::disk('public')->makeDirectory(PRODUCT_UPLOADS_PATH);
        $new_path = PRODUCT_UPLOADS_PATH . $imgName;
        Storage::disk('public')->move($path, $new_path);
        return $new_path;
    }

    /**
     * Lấy danh sách sản phẩm tồn kho
     * @param $data
     * @return mixed|void
     */
    public function getListInventory($data)
    {
        try {

            $mProductInventory = app()->get(ProductInventoryTable::class);

            $listInventory = $mProductInventory->getListInventoryByCodeProduct($data);

            $view = view('admin::product-child-new.append.list-product', [
                'listInventory' => $listInventory
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách serial tồn kho
     * @param $data
     * @return mixed|void
     */
    public function showPopupSerial($data)
    {
        try {

            $mProductChild = app()->get(ProductChildTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);


            $listStatus = $mInventoryCheckingStatus->getAll();
            $detailProduct = $mProductChild->getProductChildByCode($data['product_code']);
            $listSerial = $mProductInventorySerial->getListSerialByCodeProduct($data);
            $view = view('admin::product-child-new.popup.popup-list-serial', [
                'detailProduct' => $detailProduct,
                'listStatus' => $listStatus,
                'listSerial' => $listSerial,
                'data' => $data
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * lấy danh sách serial tồn kho
     * @param $data
     * @return mixed|void
     */
    public function getListSerialPopup($data)
    {
        try {

            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

            $listSerial = $mProductInventorySerial->getListSerialByCodeProduct($data);
            $view = view('admin::product-child-new.append.list-serial', [
                'listSerial' => $listSerial,
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                '__message' => $e->getMessage()
            ];
        }
    }
}
