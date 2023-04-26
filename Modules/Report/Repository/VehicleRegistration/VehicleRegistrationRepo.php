<?php

namespace Modules\Report\Repository\VehicleRegistration;


use Carbon\Carbon;
use Modules\Report\Models\ProductTable;

class VehicleRegistrationRepo implements VehicleRegistrationRepoInterface
{
    const PRODUCT_ATTRIBUTE_GROUP_SLUG = 'ngay-dang-kiem';
    public function filterAction($input)
    {
        $mProductChild = new ProductTable();
        $getAll = $mProductChild->getAllProductHaveRegistrationDate()->toArray();
        // Key theo ngày đăng kiểm
        $arrRegisDate = collect($getAll)->groupBy('product_attribute_label');
        // filter theo hạn đăng kiểm
        $startDate = $endDate = null;
        if(isset($input['expiration_date'])) {
            switch ($input['expiration_date']) {
                case 'week':
                    $startDate = date('Y-m-d');
                    $endDate = Carbon::now()->addDay(7)->format('Y-m-d');
                    break;
                case 'month':
                    $startDate = date('Y-m-d');
                    $endDate = Carbon::now()->addDay(30)->format('Y-m-d');
                    break;
                case 'expired':
                    $endDate = date('Y-m-d');
                    break;
                default:
                    break;
            }
        }
        $arrRegisDateNew = [];
        if ($startDate != null && $endDate != null) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            foreach ($arrRegisDate as $date => $v) {
                // check ngày đăng kiểm nằm trong khoảng startTime, endTime
                $between = Carbon::parse($date);
                if ($between->greaterThanOrEqualTo($startDate) && $between->lessThanOrEqualTo($endDate)) {
                    $temp = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
                    $arrRegisDateNew [$temp] = $v;
                }
            }
        } elseif ($startDate == null && $endDate != null) {
            $endDate = Carbon::parse($endDate);
            foreach ($arrRegisDate as $date => $v) {
                // check ngày đăng kiểm < endTime
                $between = Carbon::parse($date);
                if ($between->lessThan($endDate)) {
                    $temp = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
                    $arrRegisDateNew [$temp] = $v;
                }
            }
        } else {
            // $arrRegisDateNew = $arrRegisDate;
            foreach ($arrRegisDate as $date => $v) {
                $temp = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
                $arrRegisDateNew [$temp] = $v;
            }
        }

        $html = \View::make('report::vehicle-registration-date.table-report', [
            'data' => $arrRegisDateNew
        ])->render();
        return [
            'html' => $html
        ];
    }
}