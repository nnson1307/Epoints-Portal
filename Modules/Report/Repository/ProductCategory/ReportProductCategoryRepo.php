<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 4:49 PM
 */

namespace Modules\Report\Repository\ProductCategory;



use Modules\Report\Models\CustomerPotentialLogTable;
use Modules\Report\Models\OrderDetailTable;

class ReportProductCategoryRepo implements ReportProductCategoryRepoInterface
{
    /**
     * Load chart báo cáo sản phẩm
     *
     * @param $input
     * @return array|mixed
     */
    public function loadChart($input)
    {
        try {
            $mOrderDetail = new OrderDetailTable();
            $mCustomerPotential = new CustomerPotentialLogTable();

            $data = [];
            $dataName = [];
            $dataTotal = [];

            if ($input['type'] == "most_order") {
                $data = $mOrderDetail->getProductCategoryBuyTheMost($input['time'],$input['productCategoryId']);
            } else if ($input['type'] == "most_view") {
                $data = $mCustomerPotential->getMostViewProductCategory($input['time'],$input['productCategoryId']);
            }

            if (count($data) > 0) {
                foreach ($data as $v) {
                    $dataName [] = $v['category_name'];
                    $dataTotal [] = (int)$v['total'];
                }
            }

            return [
                'error' => 0,
                'dataName' => $dataName,
                'dataTotal' => $dataTotal
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage()
            ];
        }
    }
}