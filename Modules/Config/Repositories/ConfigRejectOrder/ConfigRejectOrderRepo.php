<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 11:36
 */

namespace Modules\Config\Repositories\ConfigRejectOrder;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Config\Models\ConfigRejectOrderDetailTable;
use Modules\Config\Models\ConfigRejectOrderTable;
use Modules\Config\Models\DistrictTable;
use Modules\Config\Models\ProvinceTable;

class ConfigRejectOrderRepo implements ConfigRejectOrderRepoInterface
{
    /**
     * Lấy data view
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataView()
    {
        $mProvince = app()->get(ProvinceTable::class);
        $mDistrict = app()->get(DistrictTable::class);
        $mRejectOrder = app()->get(ConfigRejectOrderTable::class);
        $mRejectOrderDetail = app()->get(ConfigRejectOrderDetailTable::class);

        //Lấy data cấu hình
        $rejectOrder = $mRejectOrder->getData();

        if (count($rejectOrder) > 0) {
            foreach ($rejectOrder as $v) {
                $arrDetail = [];

                //Lấy data chi tiết
                $getDetail = $mRejectOrderDetail->getDetail($v['config_reject_order_id']);

                foreach ($getDetail as $v1) {
                    $arrDetail [] = $v1['district_id'];
                }

                //Lấy tỉnh thành theo quận huyện
                $v['district'] = $mDistrict->getOptionDistrict($v['province_id']);
                $v['detail'] = $arrDetail;
            }
        }

        //Lấy option tỉnh thành
        $optionProvince = $mProvince->getOptionProvince();

        return [
            'optionProvince' => $optionProvince,
            'rejectOrder' => $rejectOrder
        ];
    }

    public function save($input)
    {
        DB::beginTransaction();
        try {
            $mRejectOrder = app()->get(ConfigRejectOrderTable::class);
            $mRejectOrderDetail = app()->get(ConfigRejectOrderDetailTable::class);

            $arrProvince = [];

            if (count($input['listProvince']) > 0) {
                foreach ($input['listProvince'] as $v) {
                    $arrProvince [] = $v['province_id'];
                }
            }

            //Validate tỉnh thành có bị trùng không
            if (count(array_unique($arrProvince)) < count($arrProvince)) {
                // Array has duplicates
                return response()->json([
                    'error' => true,
                    'message' => __('Tỉnh thành đã bị trùng'),
                ]);
            }

            //Xoá tỉnh thành cũ
            $mRejectOrder->removeAll();
            //Xoá chi tiết cũ
            $mRejectOrderDetail->removeAllDetail();

            if (count($input['listProvince']) > 0) {
                foreach ($input['listProvince'] as $v) {
                    //Thêm cấu hình tỉnh thành
                    $idRejectOrder = $mRejectOrder->add([
                        'province_id' => $v['province_id'],
                        'created_by' => Auth()->id()
                    ]);

                    $dataDetail = [];

                    foreach ($v['district_id'] as $v1) {
                        $dataDetail [] = [
                            "config_reject_order_id" => $idRejectOrder,
                            "province_id" => $v['province_id'],
                            "district_id" => $v1,
                            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }

                    //Insert data chi tiết
                    $mRejectOrderDetail->insert($dataDetail);
                }
            }


            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Lưu thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Lưu thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

}