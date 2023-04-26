<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 16:35
 */

namespace Modules\Admin\Repositories\ProductConfig;


use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\ProductCategoryTable;
use Modules\Admin\Models\ProductConfigDetailTable;
use Modules\Admin\Models\ProductConfigTable;
use Modules\Admin\Models\ProductTagTable;

class ProductConfigRepo implements ProductConfigRepoInterface
{
    /**
     * Lấy dữ liệu view
     *
     * @return array|mixed
     */
    public function getDataView()
    {
        $mConfig = app()->get(ProductConfigTable::class);
        $mConfigDetail = app()->get(ProductConfigDetailTable::class);
        $mProductCategory = app()->get(ProductCategoryTable::class);

        //Lấy thông tin cấu hình
        $getConfig = $mConfig->getInfo();
        //Lấy chi tiết cấu hình
        $getConfigDetail = $mConfigDetail->getConfigDetail($getConfig['product_config_id']);
        //Lấy option loại sp
        $optionCategory = $mProductCategory->getAll();

        $arrObjectDetail = [];

        if (count($getConfigDetail) > 0) {
            foreach ($getConfigDetail as $v) {
                $arrObjectDetail [] = $v['object_id'];
            }
        }

        return [
            'config' => $getConfig,
            'configDetail' => $getConfigDetail,
            'optionCategory' => $optionCategory,
            'arrObjectDetail' => $arrObjectDetail
        ];
    }

    /**
     * Lưu thông tin
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mConfig = app()->get(ProductConfigTable::class);
            $mConfigDetail = app()->get(ProductConfigDetailTable::class);

            //Cập nhật thông tin cấu hình
            $mConfig->edit([
                'display_view_category' => $input['display_view_category'],
                'is_display_bundled' => $input['is_display_bundled'],
                'type_bundled_product' => $input['type_bundled_product'],
                'limit_item' => $input['limit_item']
            ], $input['product_config_id']);
            //Xoá chi tiết
            $mConfigDetail->removeConfigDetail($input['product_config_id']);

            $arrDetail = [];

            if (isset($input['product_category']) && count($input['product_category']) > 0) {
                foreach ($input['product_category'] as $v) {
                    $arrDetail [] = [
                        'product_config_id' => $input['product_config_id'],
                        'object_type' => $input['type_bundled_product'],
                        'object_id' => $v
                    ];
                }
            }
            //Insert chi tiết
            $mConfigDetail->insert($arrDetail);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }

    }
}