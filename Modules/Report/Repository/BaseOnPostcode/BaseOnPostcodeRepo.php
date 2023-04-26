<?php

namespace Modules\Report\Repository\BaseOnPostcode;

use Modules\Report\Models\DeliveryCostTable;

class BaseOnPostcodeRepo implements BaseOnPostcodeRepoInterface
{
    /**
     * Data cho biểu đồ theo filter
     *
     * @param $input
     * @return array|mixed
     */
    public function loadChart($input)
    {
        try {
            $mDeliveryCost = new DeliveryCostTable();
            $dataSeries = [];
            $arrCategories = [];
            $data = $mDeliveryCost->getPostcode($input['delivery_cost'], $input['time'])->toArray();
            $dataGroupPostcode = collect($data)->groupBy("postcode");

            // Lấy danh sách postcode
            foreach ($dataGroupPostcode as $key => $val) {
                $arrCategories [] = $key;
                $dataSeries [] = count($val);
            }
//
//            if (count($data) > 0) {
//                foreach ($data as $item) {
//                    $dataPostcode[] = $item['postcode'];
//                    $dataTotal[] = $item['total'];
//                }
//            }

            return [
                'error' => 0,
                'dataPostcode' => $arrCategories,
                'dataTotal' => $dataSeries,
                'countListObject' => count($arrCategories)
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * dữ liệu cho màn hình báo cáo
     *
     * @return array|mixed
     */
    public function dataView()
    {
        $mDeliveryCost = new DeliveryCostTable();
        $optionDeliveryCost = $mDeliveryCost->getOption();
        return [
            'optionDeliveryCost' => $optionDeliveryCost
        ];
    }
}