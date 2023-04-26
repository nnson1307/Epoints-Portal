<?php


namespace Modules\Report\Repository\PurchaseByHour;


use Modules\Report\Models\OrderTable;

class PurchaseByHourRepo implements PurchaseByHourRepoInterface
{
    /**
     * Load biểu đồ báo cáo tỉ lệ mua hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function loadChart($input)
    {
        try {
            $mOrder = new OrderTable();

            $data = $mOrder->getOrderByHour($input['time'])->toArray();

            // Tạo mảng 24 phần tử tương ứng 24h
            $arrHour = [];
            $arrName = [];
            for ($i = 0; $i < 24; $i++) {
                $arrHour[$i] = [
                    '0',
                    0.00
                ];
                $arrName[$i] = [$i .'h'];
            }

            $totalOrder = 0;
            if ($data != null && count($data) > 0) {
                foreach ($data as $item) {
                    $totalOrder = $totalOrder + $item['usages'];
                }
                foreach ($data as $item) {
                    $arrHour[$item['hours']] = [
                        $item['usages'].' đơn hàng',
                        round($item['usages'] * 100 / $totalOrder, 2)
                    ];
                }
            }
//            dd($arrHour);
            return [
                'error' => 0,
                'data' => $arrHour,
                'arrName' => $arrName,
                'totalOrder' => $totalOrder
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage()
            ];
        }
    }
}