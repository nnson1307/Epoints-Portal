<?php

namespace Modules\Warranty\Repository\ReportRepairCost;

use Carbon\Carbon;
use Modules\Warranty\Models\BranchTable;
use Modules\Warranty\Models\RepairTable;

class ReportRepairCostRepo implements ReportRepairCostRepoInterface
{
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $optionBranch = $mBranch->getOption();
        return [
            'optionBranch' => $optionBranch
        ];
    }

    /**
     * filter thời gian, chi nhánh, tên
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function filterAction($input)
    {
        $mRepair = new RepairTable();
        $time = $input['time'];
        $branchId = $input['branch'];
        $numberObject = $input['number_object'];
        $startTime = $endTime = null;
        $arrCategories = []; // Danh mục cho biểu đồ
        $dataSeries = []; // Data cho biểu đồ
        $totalCost = 0; // Tổng chi phí

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        // Lất tất cả record theo filter
        $arrDataObject = [];
        $getDataProduct = $mRepair->getAllProductByFilterTimeAndBranch($startTime, $endTime, $branchId)->toArray();
        $getDataService = $mRepair->getAllServiceByFilterTimeAndBranch($startTime, $endTime, $branchId)->toArray();
        $getDataServiceCard = $mRepair->getAllServiceCardByFilterTimeAndBranch($startTime, $endTime, $branchId)->toArray();
        // Gộp lại
        foreach ($getDataProduct as $item) {
            $arrDataObject[] = $item;
        }
        foreach ($getDataService as $item) {
            $arrDataObject[] = $item;
        }
        foreach ($getDataServiceCard as $item) {
            $arrDataObject[] = $item;
        }
        // sắp xếp mảng giảm dần theo chi phí từng object
        $arrSorted = collect($arrDataObject)->sortByDesc('total_pay');
        if (isset($numberObject) && $numberObject != null) {
            $numberObject = (int)$numberObject;
            $arrSorted = $arrSorted->take($numberObject);
        }
        // Đưa về data dạng biểu đồ và tính tổng
        foreach ($arrSorted->values()->all() as $key => $value) {
            $arrCategories [] = $value['object_name'];
            $dataSeries [] = round($value['total_pay'], 2);
            $totalCost += $value['total_pay'];
        }

        $dataReturn = [
            'arrayCategories' => $arrCategories,
            'dataSeries' => $dataSeries,
            'totalCost' => round($totalCost, 2),
            'countListObject' => count($arrCategories)
        ];

        return response()->json($dataReturn);
    }
}