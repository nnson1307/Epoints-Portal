<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/26/2021
 * Time: 9:50 AM
 */

namespace Modules\Report\Repository\ServiceStaff;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\StaffTable;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderDetailTable;

class ServiceStaffRepo implements ServiceStaffRepoInterface
{
    protected $orderDetail;

    public function __construct(
        OrderDetailTable $orderDetail
    )
    {
        $this->orderDetail = $orderDetail;
    }

    /**
     * Load data view index
     *
     * @return array|mixed
     */
    public function dataViewIndex()
    {
        $mBranch = app()->get(BranchTable::class);
        $mStaff = new StaffTable();
        $optionStaff = $mStaff->getOption();
        //Lấy option chi nhánh
        $optionBranch = $mBranch->getOption();

        return [
            'optionBranch' => $optionBranch,
            'optionStaff' => $optionStaff,
//            'LIST' => $data['list'],
//            'LIST' => [],
            'FILTER' => []
        ];
    }

    /**
     * Load data chart + table chi tiết
     *
     * @param $input
     * @return array|mixed
     */
    public function dataChart($input)
    {
        $filter = $input;
        $startTime = null;
        $endTime = null;

        if ($input['time'] != null) {
            $time = explode(" - ", $input['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }
        $input['numberLoad'] = isset($input['numberLoad']) ? $input['numberLoad'] : null;
        $input['staffId'] = isset($input['staffId']) ? $input['staffId'] : null;
        //Lấy doanh thu nv phục vụ
        $revenue = $this->orderDetail->getRevenueServiceStaff($startTime, $endTime, $input['branch'], $input['numberLoad'])->toArray();
        $dataReturn = $this->aStaff2nStaffWithAmount($revenue, $filter);
        $revenueStaffWithoutGroupStaff = $dataReturn['listStaff'];
        $revenueStaff = [];
        $arrStaffId = [];
        foreach ($revenueStaffWithoutGroupStaff as $item){
            if(array_key_exists($item['staff_id'], $arrStaffId)){
                $revenueStaff[$item['staff_id']] = [
                    'staff_id' => $item['staff_id'],
                    'staff_name' => $item['staff_name'],
                    'amount' => $revenueStaff[$item['staff_id']]['amount'] + $item['amount']
                ];
            }
            else{
                $revenueStaff[$item['staff_id']] = [
                    'staff_id' => $item['staff_id'],
                    'staff_name' => $item['staff_name'],
                    'amount' => $item['amount']
                ];
                $arrStaffId[$item['staff_id']] = $item['staff_id'];
            }
        }
        //Data chart
        $arrName = [];
        $arrNumber = [];

        // tính tổng doanh thu
        $totalAmount = $dataReturn['totalRevenue'];

        if (count($revenueStaff) > 0) {
            foreach ($revenueStaff as $v) {
                $arrName [] = $v['staff_name'] . '<br>';
                $arrNumber [] = floatval($v['amount']);
            }
        }

        return [
            'arrayCategories' => $arrName,
            'dataSeries' => $arrNumber,
            'totalAmount' => $totalAmount
        ];
    }
    public function aStaff2nStaffWithAmount($data = [], $filter = []){
        $totalRevenue = 0;
        $mStaff = new StaffTable();
        $listStaff = [];
        if(count($data) > 0){
            foreach ($data as $item) {
                $arrStaff = isset($item['staff_id']) != '' ? explode(',',$item['staff_id']) : null;
                if(count($arrStaff) > 0){
                    if(isset($filter['staffId']) != ''){
                        foreach ($arrStaff as $key => $value){
                            if($value == $filter['staffId']){
                                $totalRevenue += floatval($item['amount']);
                                $temp = $item;
                                $detailStaff = $mStaff->getDetail($value);
                                $temp['staff_id'] = $value;
                                $temp['staff_name'] = isset($detailStaff['full_name']) != '' ? $detailStaff['full_name'] : '';
                                $listStaff[] = $temp;
                            }
                        }
                    }
                    else{
                        $totalRevenue += floatval($item['amount']);
                        foreach ($arrStaff as $key => $value){
                            $temp = $item;
                            $detailStaff = $mStaff->getDetail($value);
                            $temp['staff_id'] = $value;
                            $temp['staff_name'] = isset($detailStaff['full_name']) != '' ? $detailStaff['full_name'] : '';
                            $listStaff[] = $temp;
                        }
                    }
                }
            }
        }
        return [
            'listStaff' => $listStaff,
            'totalRevenue' => $totalRevenue
        ];
    }
    public function aStaff2nStaff($data = [], $filter = []){
        $mStaff = new StaffTable();
        $listStaff = [];
        if(count($data) > 0){
            foreach ($data as $item) {
                $arrStaff = isset($item['staff_id']) != '' ? explode(',',$item['staff_id']) : null;
                if(count($arrStaff) > 0){
                    if(isset($filter['staff_id_detail']) != ''){
                        foreach ($arrStaff as $key => $value){
                            if($value == $filter['staff_id_detail']){
                                $temp = $item;
                                $detailStaff = $mStaff->getDetail($value);
                                $temp['staff_id'] = $value;
                                $temp['staff_name'] = isset($detailStaff['full_name']) != '' ? $detailStaff['full_name'] : '';
                                $listStaff[] = $temp;
                            }
                        }
                    }
                    elseif(isset($filter['staff_id_export_total']) != ''){
                        foreach ($arrStaff as $key => $value){
                            if($value == $filter['staff_id_export_total']){
                                $temp = $item;
                                $detailStaff = $mStaff->getDetail($value);
                                $temp['staff_id'] = $value;
                                $temp['staff_name'] = isset($detailStaff['full_name']) != '' ? $detailStaff['full_name'] : '';
                                $listStaff[] = $temp;
                            }
                        }
                    }
                    elseif(isset($filter['staff_id_export_detail']) != ''){
                        foreach ($arrStaff as $key => $value){
                            if($value == $filter['staff_id_export_detail']){
                                $temp = $item;
                                $detailStaff = $mStaff->getDetail($value);
                                $temp['staff_id'] = $value;
                                $temp['staff_name'] = isset($detailStaff['full_name']) != '' ? $detailStaff['full_name'] : '';
                                $listStaff[] = $temp;
                            }
                        }
                    }
                    else{
                        foreach ($arrStaff as $key => $value){
                            $temp = $item;
                            $detailStaff = $mStaff->getDetail($value);
                            $temp['staff_id'] = $value;
                            $temp['staff_name'] = isset($detailStaff['full_name']) != '' ? $detailStaff['full_name'] : '';
                            $listStaff[] = $temp;
                        }
                    }
                }
            }
        }
        return $listStaff;
    }
    /**
     * Table chi tiết đơn hàng
     *
     * @param $input
     * @return mixed|void
     */
    public function listDetail($input)
    {
        $filter = $input;
        $mStaff = new StaffTable();
        $listWithoutStaffDetail = $this->orderDetail->getListDetailServiceStaff($input);
        $listStaff = $this->aStaff2nStaff($listWithoutStaffDetail, $filter);
        $page = 1;

        if (isset($input['page'])) {
            $page = $input['page'];
        }
        // Get current page form url e.x. &page=1
        $currentPage = intval($page);

        // Create a new Laravel collection from the array data
        $itemCollection = collect($listStaff);

        // Tổng item trên 1 trang
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath(url()->current());
        return [
            'list' => $paginatedItems
        ];
    }

    /**
     * Export excel total
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelTotal($input)
    {
        $filter = $input;
        $heading = [
            __('TÊN NHÂN VIÊN'),
            __('DOANH THU'),
            __('CHI NHÁNH')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];

        $startTime = null;
        $endTime = null;

        if ($input['time_export_total'] != null) {
            $time = explode(" - ", $input['time_export_total']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }
        $input['numberLoad'] = isset($input['numberLoad']) ? $input['numberLoad'] : null;
        $input['staffId'] = isset($input['staff_id_export_total']) ? $input['staff_id_export_total'] : null;
        //Lấy doanh thu nv phục vụ
        $revenue = collect($this->orderDetail->getRevenueServiceStaff($startTime, $endTime, $input['branch_export_total'], $input['numberLoad'])->toArray());
        $dataReturn = $this->aStaff2nStaffWithAmount($revenue, $filter);
        $revenueStaffWithoutGroupStaff = $dataReturn['listStaff'];
        $grouped = [];
        $arrStaffId = [];
        $arrOrderId = [];
        foreach ($revenueStaffWithoutGroupStaff as $item){
            $num = 0;
            if(!array_key_exists($item['order_id'].'_'.$item['staff_id'], $arrStaffId)){
                $arrOrderId[$item['order_id'].'_'.$item['staff_id']] = $item['staff_id'];
            }else{
                $num = 1;
            }
            if(array_key_exists($item['staff_id'], $arrStaffId)){
                $grouped[$item['staff_id']] = [
                    'staff_id' => $item['staff_id'],
                    'staff_name' => $item['staff_name'],
//                    'count' => (int)$grouped[$item['staff_id']]['count'] + $num,
                    'amount' => $grouped[$item['staff_id']]['amount'] + $item['amount']
                ];
            }
            else{
                $grouped[$item['staff_id']] = [
                    'staff_id' => $item['staff_id'],
                    'staff_name' => $item['staff_name'],
//                    'count' => 1,
                    'amount' => $item['amount']
                ];
                $arrStaffId[$item['staff_id']] = $item['staff_id'];
            }
        }
        if (count($grouped) > 0) {
            $mBranch = app()->get(BranchTable::class);
            //Lấy tên chi nhánh
            $branch = $mBranch->getBranch($input['branch_export_total']);
            $branchName = $branch != null ? $branch['branch_name'] : __('Tất cả');

            foreach ($grouped as $k => $v) {
                $data [] = [
                    $v['staff_name'], //Tên nhân viên
                    $v['amount'],
                    $branchName
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-total.xlsx');
    }

    /**
     * Export excel detail
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelDetail($input)
    {
        $filter = $input;
        $heading = [
            __('TÊN NHÂN VIÊN'),
            __('MÃ ĐƠN HÀNG'),
            __('CHI NHÁNH'),
            __('LOẠI'),
            __('TÊN SẢN PHẨM'),
            __('GIÁ BÁN'),
            __('SỐ LƯỢNG'),
            __('GIẢM'),
            __('THÀNH TIỀN'),
            __('NGÀY PHỤC VỤ')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];

        $startTime = null;
        $endTime = null;

        if ($input['time_export_detail'] != null) {
            $time = explode(" - ", $input['time_export_detail']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }
        $input['numberLoad'] = isset($input['numberLoad']) ? $input['numberLoad'] : null;
        $input['staffId'] = isset($input['staff_id_export_detail']) ? $input['staff_id_export_detail'] : null;
        //Lấy doanh thu nv phục vụ
        $revenue = $this->orderDetail->getListExportDetailServiceStaff($startTime, $endTime, $input['branch_export_detail'], $input['numberLoad'], $input['staffId'])->toArray();
        $revenueStaff = $this->aStaff2nStaff($revenue, $filter);
        if (count($revenueStaff) > 0) {
            foreach ($revenueStaff as $v) {
                $objectType = "";
                switch ($v['object_type']) {
                    case 'product':
                        $objectType = __('Sản phẩm');
                        break;
                    case 'service':
                        $objectType = __('Dịch vụ');
                        break;
                    case 'service_card':
                        $objectType = __('Thẻ dịch vụ');
                        break;
                    case 'member_card':
                        $objectType = __('Thẻ thành viên');
                        break;
                }

                $data [] = [
                    $v['staff_name'],
                    $v['order_code'],
                    $v['branch_name'],
                    $objectType,
                    $v['object_name'],
                    floatval($v['price']),
                    $v['quantity'],
                    floatval($v['discount']),
                    floatval($v['amount']),
                    Carbon::parse($v['created_at'])->format('d/m/Y H:i')
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}