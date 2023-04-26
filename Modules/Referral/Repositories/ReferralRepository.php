<?php


namespace Modules\Referral\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Controllers\CalendarController;
use Modules\Referral\Models\ReferralProgramRateTable;
use Modules\Referral\Models\ReferralTable;
use Modules\Referral\Models\ReferralConfigTable;
use Modules\Referral\Models\MultiLevelConfigTable;
use Modules\Referral\Models\ReferralProgramTable;
use Modules\Referral\Models\ServicesTable;
use Modules\Referral\Models\StaffsTable;
use Modules\Referral\Models\ReferralProgramConditionTable;
use Modules\Referral\Models\ProductCategoriesTable;
use Modules\Referral\Models\ServiceCardGroupTable;
use Modules\Referral\Models\ServicesCategoriesTable;
use Modules\Referral\Models\ProductChildsTable;
use Modules\Referral\Models\ServiceCardsTable;
use Modules\Referral\Models\ProductsTable;
use Modules\Referral\Models\ReferralProgramItemTable;
use Modules\Referral\Models\ReferralProgramLogTable;
use Modules\Referral\Models\ReferralProgramInviteTable;
use phpDocumentor\Reflection\DocBlock\Description;

class ReferralRepository implements ReferralInterface
{
    public function getTest()
    {
        dd(123445);
    }

    public function getSelectInfo()
    {
        $getSelect = app()->get(ReferralTable::class);
        $info = $getSelect->getSelectInfo();
        $data = [

            "type_of_criteria" => ['CPS', 'CPI'],
            "accountable_by" => $info,
            "apply_for" => ['Tất cả', 'Khách hàng']

        ];
        return $data;
    }

    public function getInfoOld()
    {
        $getOld = app()->get(ReferralConfigTable::class);
        $infoOld = $getOld->getInfoOld();
        return $infoOld;
    }
    public function getInfoOldMulti(){
        $getOld = app()->get(MultiLevelConfigTable::class);
        $infoOld = $getOld->getOldInfo();
        return $infoOld;
    }

    public function getInfoRate($id){
        $getOld = app()->get(ReferralProgramRateTable::class);
        return $getOld->getAllActiveByProgram($id);
    }

    public function getInfoOldById($id)
    {
        $getOldById = app()->get(ReferralConfigTable::class);
        $infoOld = $getOldById->getInfoOldById($id);
        ///lay id cau hinh moi nhat
        $getOldFinal = app()->get(ReferralConfigTable::class);
        $infoOldFinal = $getOldFinal->getInfoOld();
        $infoOld['final_id'] = $infoOldFinal['referral_config_id'];
        return $infoOld;
    }

    public function saveGeneralConfig($input)
    {
        if($input['config_code_type1'] != 'custom'){
            $input['config_code_type_custom'] = null;
        }
        $save = app()->get(ReferralConfigTable::class);
        $input['config_code_type'] = "";
        if ($input['config_code_type1'] == null) {
            $input['config_code_type'] = $input['config_code_type_custom'];
        } else {
            $input['config_code_type'] = $input['config_code_type1'];
        }
        unset($input['config_code_type1']);

        $input['created_at'] = Carbon::now();
        $input['start'] = Carbon::now();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if (isset($input['config_number_random']) && $input['config_number_random'] == null) {
            $input['config_number_random'] = 10;
        }
        if(is_numeric($input['config_number_random']) == false){
            return [
                'error' => true,
                'message' => __('Số kí tự random phải là kiểu số nguyên!')
            ];
        }

        if ($input['config_code_type_custom'] && $input['config_code_type_custom'] != null) {
            $input['config_code'] = $input['config_code_type_custom'];
        } else {
            $input['config_code'] = null;
        }
        unset($input['config_code_type_custom']);

        if ($input['config_description'] == null) {
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Nội dung cấu hình!')
            ];
        } elseif ( $input['config_code_type'] == null) {
            return [
                'error' => true,
                'message' => __('Vui lòng nhập định dạng mã giới thiệu!')
            ];
        } elseif (  $input['date_auto_confirm'] == null) {
            return [
                'error' => true,
                'message' => __('Vui lòng chọn trường Hệ thống tự động ghi nhận hoa hồng!')
            ];
        } elseif ( $input['payment_cycle_type'] == null || $input['payment_cycle_value'] == null) {
            return [
                'error' => true,
                'message' => __('Vui lòng chọn Thời gian tự động tạo chu kì thanh toán hoa hồng!')
            ];
        } elseif (strlen($input['config_description']) > 191) {
            return [
                'error' => true,
                'message' => __('Nội dung cấu hình không quá 191 kí tự!')
            ];
        } elseif ($input['config_number_random'] > 13 || $input['config_number_random'] < 10) {

            return [
                'error' => true,
                'message' => __('Số kí tự random phải từ 10 đến 13!')
            ];
        } else {
            $oldId = app()->get(ReferralConfigTable::class);
            $getOldId = $oldId->getInfoOld();
            $oldId = $getOldId['referral_config_id'];
            $input['created_by'] = Auth()->id();
            $generalConfig = $save->saveGeneralConfig($input);

            $dataUpdate = [
                'end' => Carbon::now(),
                'payment_cycle_status' => "inactive",
            ];
            unset($dataUpdate['updated_at']);
            $update = app()->get(ReferralConfigTable::class);
            $oldGeneralConfig = $update->updateOldGeneralConfig($oldId, $dataUpdate);

            return [
                'error' => false,
                'message' => __('Lưu cấu hình thành công!')
            ];
        }
    }

    public function getOldInfo()
    {
        $oldInfo = app()->get(MultiLevelConfigTable::class);
        $getOld = $oldInfo->getOldInfo();
        $getOld['percent'] = floatval($getOld['percent']);
        return $getOld;
    }

    public function saveMultilevelConfig($input)
    {
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $input['created_by'] = Auth()->id();
        $input['updated_by'] = Auth()->id();

        if ($input['level'] == null) {
            return [
                'error' => true,
                'message' => __('Vui lòng Chọn tầng đa cấp!')
            ];
        }else {
            $save = app()->get(MultiLevelConfigTable::class);
            $multiLevelConfig = $save->saveMultilevelConfig($input);
            return [
                'error' => false,
                'message' => __('Lưu cấu hình thành công!')
            ];
        }
    }

    public function getListCommission($input)
    {
        $list = app()->get(ReferralProgramTable::class);

        $getList = $list->getListCommission($input);

        foreach ($getList as $key => $value) {
            ///lay ten người cap nhat
            $getName = app()->get(StaffsTable::class);
            $name = $getName->getNameStaff($value['updated_by']);
            $getList[$key]['staff_name'] = $name != null ? $name['full_name'] : null;
            ///lấy trạng thái có hoa hồng hay không
            $check = app()->get(ReferralProgramInviteTable::class);
            $moneyCommission = $check->checkMoneyCommisson($value['referral_program_id']);
            if ($moneyCommission != null) {
                $getList[$key]['money_commission'] = 1;
            } else {
                $getList[$key]['money_commission'] = 0;
            }
        }
        return $getList;
    }

    public function createNewCommission($input)
    {
        $input['step'] = '';
        if ($input['type'] == 'CPI') {
            $input['step'] = 2;
        } else {
            $input['step'] = 3;
        }
        if ($input['referral_program_name'] == null) {
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Tên tiêu chí')
            ];
        }elseif( $input['apply_for'] == null){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Áp dụng cho')
            ];
        }elseif( $input['referral_criteria_code'] == null  && $input['type'] == 'CPS'){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Tiêu chí tính theo')
            ];
        }elseif( $input['description'] == null || $input['description'] == "<p><br></p>"){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Mô tả hiển thị trên app')
            ];
        }elseif( $input['date_start'] == null){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Ngày bắt đầu hiệu lực')
            ];
        }elseif( $input['date_end'] == null){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Ngày kết thúc hiệu lực')
            ];
        } elseif (strlen($input['referral_program_name']) > 191) {
            return [
                'error' => true,
                'message' => __('Nội dung cấu hình không quá 191 kí tự!')
            ];
        } else {
            $createCommission = [];
            $checkList = app()->get(ReferralProgramTable::class);
            $check = $checkList->checkListCommission($input['referral_program_name']);
            if ($check != []) {
                return [
                    'error' => true,
                    'message' => __('Tên chính sách đã tồn tại!')
                ];
            }
            if ($input['type'] == "CPS") {
                $input['type'] = "cps";
            } elseif ($input['type'] == "CPI") {
                $input['type'] = "cpi";
            }
            if ($input['apply_for'] == "Khách hàng") {
                $input['apply_for'] = "customer";
            } elseif ($input['apply_for'] == "Tất cả") {
                $input['apply_for'] = "all";
            }
            if ($input['referral_criteria_code'] == 1) {
                $input['referral_criteria_code'] = "total_order";
            }
            $input['date_start'] = Carbon::createFromFormat('d/m/Y', $input['date_start'])->format('Y-m-d');
            $input['date_end'] = Carbon::createFromFormat('d/m/Y', $input['date_end'])->format('Y-m-d');
            $create = app()->get(ReferralProgramTable::class);
            $input['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $now = Carbon::now()->format('Y-m-d');

            $input['created_by'] = Auth()->id();
            $checkTime = $checkList->checkTime($input);
            foreach ($checkTime as $k => $v){
                if(strtotime($input['date_start']) > strtotime($v['date_start']) && strtotime($input['date_start']) < strtotime($v['date_end']))
                    return [
                        'error' => true,
                        'message' => __('Đã có chính sách hoạt động trong thời gian này!')
                    ];
            }
            if (strtotime($input['date_start']) < strtotime($now) || strtotime($input['date_end']) < strtotime($now)) {
                return [
                    'error' => true,
                        'message' => __('Ngày bắt đầu hiệu lực và kết thúc hiệu lực phải lớn hơn hoặc bằng thời điểm hiện tại!')
                ];
            } elseif (strtotime($input['date_start']) > strtotime($input['date_end'])) {
                return [
                    'error' => true,
                    'message' => __('Ngày kết thúc hiệu lực phải lớn hơn hoặc bằng Ngày bắt đầu hiệu lực!')
                ];
            } else {
                ///lấy tên người thực hiện
                $staff = app()->get(StaffsTable::class);
                $staffName = $staff->getNameStaff($input['created_by']);
                ////
                $createCommission = $create->createNewCommission($input);
                ///lưu log thêm chính sách

                $dataLog = [
                    'referral_program_id' => $createCommission,
                    'staff_id' => Auth()->id(),
                    'content' => __(' đã thêm chính sách hoa hồng thành công. '),
                    'created_at' => Carbon::now()
                ];
                $saveLog = app()->get(ReferralProgramLogTable::class);
                $id = $saveLog->saveLogCreateCommission($dataLog);
                ////
                return [
                    'error' => false,
                    'message' => __('Tạo thành công!'),
                    'createCommission' => $createCommission
                ];
            }

        }
    }

    public function saveNewConditionCPI($data, $referral_program_id)
    {
        $data['commission_value']=str_replace(',', '', $data["commission_value"]);
        if($data['cpi_time_use_time'] <= 0 ){
            return [
                'error' => true,
                'message' => __('Thời gian sử dụng app(Phút) phải lớn hơn 0!')
            ];
        }
        if( $data['cpi_time_use_date'] <= 0){
            return [
                'error' => true,
                'message' => __('Thời gian sử dụng app(Ngày) phải lớn hơn 0!')
            ];
        }
        if ($data['cpi_time_use_time'] == null) {
            $data['cpi_time_use_time'] = 30;
        }
        if ($data['cpi_time_use_date'] == null) {
            $data['cpi_time_use_date'] = 30;
        }
        if ($data['commission_value'] == "0" || $data['commission_value'] == null) {
            return [
                'error' => true,
                'message' => __('Nhập tiền hoa hồng!')
            ];
        }
        if(is_numeric($data['commission_value']) == false){
            return [
                'error' => true,
                'message' => __('Tiền hoa hồng phải là một số!')
            ];
        }

        //lưu số tiền hoa hồng cpi
        $data_commission_value = [
            'commission_value' => $data['commission_value']
        ];
        $saveMoney = app()->get(ReferralProgramTable::class);
        $saveMoneyCPI = $saveMoney->saveMoneyCommissionCPI($data_commission_value, $referral_program_id);
        ///luu điều kiện cpi
        unset($data['commission_value']);
        $save = app()->get(ReferralProgramConditionTable::class);
        ///kiem tra dang them moi hay cap nhat
        $check = $save->checkCondition($referral_program_id);
        if (count($check) == 0) {
            $saveCondition = $save->saveNewConditionCPI($data, $referral_program_id);

            ///lưu log thêm điều kiện chinh sách hoa hồng CPS
            $dataLog = [
                'referral_program_id' => $referral_program_id,
                'staff_id' => Auth()->id(),
                'content' => __(' đã thêm điều kiện tính hoa hồng thành công. '),
                'created_at' => Carbon::now()
            ];

            $saveLog = app()->get(ReferralProgramLogTable::class);
            $id = $saveLog->saveLogCreateCommission($dataLog);
        } else {

            ///xóa cấu hình cũ nếu đang cập nhật
            $delete = $save->deleteOldCondition($referral_program_id);
            ///
            $saveCondition = $save->saveNewConditionCPI($data, $referral_program_id);

            ///lưu log thêm điều kiện chính sách hoa hồng CPS
            $dataLog = [
                'referral_program_id' => $referral_program_id,
                'staff_id' => Auth()->id(),
                'content' => __(' đã cập nhật điều kiện tính hoa hồng thành công. '),
                'created_at' => Carbon::now()
            ];
            $saveLog = app()->get(ReferralProgramLogTable::class);
            $id = $saveLog->saveLogCreateCommission($dataLog);
        }


        return [
            'error' => false,
            'message' => __('Lưu thành công!'),
        ];
    }

    public function getGroupCommodity($type_commodity)
    {

        if ($type_commodity['type-commodity'] == 'service') {
            $groupCommidity = app()->get(ServicesCategoriesTable::class);
            $getGroup = $groupCommidity->getGroupCommodity();
        } elseif ($type_commodity['type-commodity'] == 'product') {
            $groupCommidity = app()->get(ProductCategoriesTable::class);
            $getGroup = $groupCommidity->getGroupCommodity();

        } elseif ($type_commodity['type-commodity'] == 'service_card') {
            $groupCommidity = app()->get(ServiceCardGroupTable::class);
            $getGroup = $groupCommidity->getGroupCommodity();


        } elseif ($type_commodity['type-commodity'] == 'all') {
            $groupCommidity1 = app()->get(ServicesCategoriesTable::class);
            $groupCommidity2 = app()->get(ProductCategoriesTable::class);
            $groupCommidity3 = app()->get(ServiceCardGroupTable::class);
            $getGroup1 = $groupCommidity1->getGroupCommodity();
            $getGroup2 = $groupCommidity2->getGroupCommodity();
            $getGroup3 = $groupCommidity3->getGroupCommodity();
            $getGroup = array_merge($getGroup1, $getGroup2, $getGroup3);

        } else {
            $getGroup = 'choose_type_commodity';
        }
        return $getGroup;

    }

    public function getListCommodity($commodity, $commodityNow)
    {
        if ($commodityNow != []) {
            foreach ($commodityNow as $k => $v) {
                if ($v['object_type'] == 'products') {
                    $v['object_type'] = 'product';
                    $commodityNow[$k] = $v;
                } elseif ($v['object_type'] == 'services') {
                    $v['object_type'] = 'service';
                    $commodityNow[$k] = $v;
                } elseif ($v['object_type'] == 'service_cards') {
                    $v['object_type'] = 'service_card';
                    $commodityNow[$k] = $v;
                } else {
                }
            }
        }

        if ($commodity['group_commodity'] != null) {
            $dataGroup = explode('|', $commodity['group_commodity']);
            $type = $dataGroup[0];
            $id = $dataGroup[1];
            $listCommodity = [];
            if ($type == 'product' && $id != 'all') {
                $getCommodity = app()->get(ProductChildsTable::class);
                $listCommodity = $getCommodity->getListCommodity($id);
                foreach ($listCommodity as $k => $v) {
                    $v["type"] = $type;
                    $listCommodity[$k] = $v;
                }
            } elseif ($type == 'product' && $id == 'all') {
                $getCommodity = app()->get(ProductsTable::class);
                $listCommodity = $getCommodity->getListCommodityAll1();
                foreach ($listCommodity as $k => $v) {
                    $v["type"] = $type;
                    $listCommodity[$k] = $v;
                }
            } elseif ($type == 'service' && $id != 'all') {
                $getCommodity = app()->get(ServicesTable::class);
                $listCommodity = $getCommodity->getListCommodity($id);
                foreach ($listCommodity as $k => $v) {
                    $v["type"] = $type;
                    $listCommodity[$k] = $v;
                }
            } elseif ($type == 'service' && $id == 'all') {
                $getCommodity = app()->get(ServicesTable::class);
                $listCommodity = $getCommodity->getListCommodityAll2();
                foreach ($listCommodity as $k => $v) {
                    $v["type"] = $type;
                    $listCommodity[$k] = $v;
                }
            } elseif ($type == 'service_card' && $id != 'all') {
                $getCommodity = app()->get(ServiceCardsTable::class);
                $listCommodity = $getCommodity->getListCommodity($id);
                foreach ($listCommodity as $k => $v) {
                    $v["type"] = $type;
                    $listCommodity[$k] = $v;
                }
            } elseif ($type == 'service_card' && $id == 'all') {
                $getCommodity = app()->get(ServiceCardsTable::class);
                $listCommodity = $getCommodity->getListCommodityAll3();
                foreach ($listCommodity as $k => $v) {
                    $v["type"] = $type;
                    $listCommodity[$k] = $v;
                }
            } else {
                ////trường hợp chon full tất cả
                $getCommodity1 = app()->get(ProductsTable::class);
                $listCommodity1 = $getCommodity1->getListCommodityAll1();
                foreach ($listCommodity1 as $k => $v) {
                    $v["type"] = 'product';
                    $listCommodity1[$k] = $v;
                }
                $getCommodity2 = app()->get(ServicesTable::class);
                $listCommodity2 = $getCommodity2->getListCommodityAll2();
                foreach ($listCommodity2 as $k => $v) {
                    $v["type"] = 'service';
                    $listCommodity2[$k] = $v;
                }
                $getCommodity3 = app()->get(ServiceCardsTable::class);
                $listCommodity3 = $getCommodity3->getListCommodityAll3();
                foreach ($listCommodity3 as $k => $v) {
                    $v["type"] = 'service_card';
                    $listCommodity3[$k] = $v;
                }
                $listCommodity = array_merge($listCommodity1, $listCommodity2, $listCommodity3);
            }
            foreach ($commodityNow as $k1 => $v1) {
                foreach ($listCommodity as $k2 => $v2) {
                    if ($v1['object_type'] == $v2['type'] && $v1['object_id'] == $v2['id']) {
                        unset ($listCommodity[$k2]);
                    }
                }
            }

        } else {
            $listCommodity = "choose_commodity";
        }
        return $listCommodity;
    }

    public function addCommodity($commoditySelected)
    {
        $dataCommodity = explode('|', $commoditySelected['commodity']);
        $dataGroupCommodity = explode('|', $commoditySelected['group_commodity']);

        $type = $dataCommodity[0];
        $typeGroup = $dataGroupCommodity[0];

        $id = $dataCommodity[1];
        $idGroup = $dataGroupCommodity[1];
        $commodity = [];
        switch ($type) {
            case 'service':
                if ($id != 'all') {
                    $select = app()->get(ServicesTable::class);
                    $commodity = $select->getCommodityChoose($id);
                    $commodity[0]['type'] = $type;
                }

                if ($id == 'all') {
                    $getCommodity = app()->get(ServicesTable::class);
                    $commodity = $getCommodity->getListCommodityAll2($idGroup);
                    foreach ($commodity as $k => $v) {
                        $v["type"] = $type;
                        $commodity[$k] = $v;
                    }
                }

                if ($id == 'all' && $idGroup == 'all') {
                    $getCommodity = app()->get(ServicesTable::class);
                    $commodity = $getCommodity->getListCommodityAll2();
                    foreach ($commodity as $k => $v) {
                        $v["type"] = $type;
                        $commodity[$k] = $v;
                    }
                }
                break;
            case 'product':
                if ($id != 'all') {
                    $getCommodity = app()->get(ProductsTable::class);
                    $commodity = $getCommodity->getCommodityChoose($id);
                    $commodity[0]['type'] = $type;
                }

                if ($id == 'all') {
                    $getCommodity = app()->get(ProductsTable::class);
                    $commodity = $getCommodity->getListCommodityAll1($idGroup);
                    foreach ($commodity as $k => $v) {
                        $v["type"] = $type;
                        $commodity[$k] = $v;
                    }
                }

                if ($id == 'all' && $idGroup == 'all') {
                    $getCommodity = app()->get(ProductsTable::class);
                    $commodity = $getCommodity->getListCommodityAll1();
                    foreach ($commodity as $k => $v) {
                        $v["type"] = $type;
                        $commodity[$k] = $v;
                    }
                }
                break;
            case 'service_card':
                if ($id != 'all') {
                    $getCommodity = app()->get(ServiceCardsTable::class);
                    $commodity = $getCommodity->getCommodityChoose($id);
                    $commodity[0]['type'] = $type;
                }

                if ($id == 'all') {
                    $getCommodity = app()->get(ServiceCardsTable::class);
                    $commodity = $getCommodity->getListCommodityAll3($idGroup);
                    foreach ($commodity as $k => $v) {
                        $v["type"] = $type;
                        $commodity[$k] = $v;
                    }
                }
                if ($id == 'all' && $idGroup == 'all') {
                    $getCommodity = app()->get(ServiceCardsTable::class);
                    $commodity = $getCommodity->getListCommodityAll3();
                    foreach ($commodity as $k => $v) {
                        $v["type"] = $type;
                        $commodity[$k] = $v;
                    }
                }
                break;
        }

/////lưu log khi thêm hàng hóa cho chính sách
        //// lấy hàng hóa
        $getCommodity1 = app()->get(ProductsTable::class);
        $listCommodity1 = $getCommodity1->getListCommodityAll1();
        foreach ($listCommodity1 as $k => $v) {
            $v["type"] = 'product';
            $listCommodity1[$k] = $v;
        }
        $getCommodity2 = app()->get(ServicesTable::class);
        $listCommodity2 = $getCommodity2->getListCommodityAll2();
        foreach ($listCommodity2 as $k => $v) {
            $v["type"] = 'service';
            $listCommodity2[$k] = $v;
        }
        $getCommodity3 = app()->get(ServiceCardsTable::class);
        $listCommodity3 = $getCommodity3->getListCommodityAll3();
        foreach ($listCommodity3 as $k => $v) {
            $v["type"] = 'service_card';
            $listCommodity3[$k] = $v;
        }
        ///loại hàng hóa
        $typeCommodity = [
            'all' => __('Tất cả'),
            'service' => __('Dịch vụ'),
            'product' => __('Sản phẩm'),
            'service_card' => __('Thẻ dịch vụ'),
        ];
        $allCommodity = array_merge($listCommodity1, $listCommodity2, $listCommodity3);
        ///
        foreach ($typeCommodity as $k => $v) {
            if ($k = $commoditySelected['type']) {
                $typeChoose = $typeCommodity[$k];
            }
        }
        if ($idGroup == 'all') {
            $groupChoose = __('Tất cả');
        } else {
            foreach ($allCommodity as $k => $v) {
                if ($v['type'] == $typeGroup && $v['category_id'] == $idGroup) {
                    $groupChoose = $v['category_name'];
                }
            }
        }
        if ($id == 'all') {
            $commodityChoose = __('Tất cả');
        } else {
            foreach ($commodity as $k => $v) {
                if ($v['type'] == $type && $v['id'] == $id) {
                    $commodityChoose = $v['name'];
                }

            }
        }
        ///lưu log
        $dataLog = [
            'referral_program_id' => $commoditySelected['referral_program_id'],
            'staff_id' => Auth()->id(),
            'content' => __(' đã thêm hàng hóa: ') . $commodityChoose . __(', nhóm: ') . $groupChoose . __(', loại: ') . $typeChoose,
            'created_at' => Carbon::now()
        ];
        $saveLog = app()->get(ReferralProgramLogTable::class);
        $id = $saveLog->saveLogCreateCommission($dataLog);
        return $commodity;
    }

    ////them hang hoa da chon vao bang
    public function add($selectCommodity)
    {

        foreach ($selectCommodity as $k => $v) {
            if ($v['type'] == 'product') {
                $v['object_type'] = 'products';

            } elseif ($v['type'] == 'service') {
                $v['object_type'] = 'services';

            } else {
                $v['object_type'] = 'service_cards';
            }
            $v['object_id'] = $v['id'];
            $v['created_at'] = Carbon::now();
            $v['created_by'] = Auth()->id();
            unset($v['id'], $v['name'], $v['category_name'], $v['type'], $v['name'], $v['category_id']);
            $selectCommodity[$k] = $v;


        }
        foreach ($selectCommodity as $k => $v) {
            $addInDB = app()->get(ReferralProgramItemTable::class);
            $addCommodityInDB = $addInDB->add($v);
        }
        return $addCommodityInDB;

    }

    public function getInfoTable($data)
    {

        $mCommodityTable = app()->get(ReferralProgramItemTable::class);
        $mProduct = app()->get(ProductsTable::class);
        $mService = app()->get(ServicesTable::class);
        $mServiceCard = app()->get(ServiceCardsTable::class);

        ///lấy data table không phân trang
        $dataNotPage = $mCommodityTable->getInfoNotPage($data);

        ///  xóa các sản phẩm đã chọn(có trùng hoặc không trùng)
        $del = $mCommodityTable->delDuplicate($data['referral_program_id']);


        if(isset($dataNotPage) && count($dataNotPage) > 1){
            $dataNotDuplicate =  collect($dataNotPage)->keyBy(function ($item){
                return $item['object_type'].'-'.$item['object_id'];
            })->toArray();
            $dataNotPage = array_values($dataNotDuplicate);
        }
        ///  thêm lại các sản phẩm không trùng
        foreach ($dataNotPage as $k => $v){
            $v['referral_program_id'] = $data['referral_program_id'];
            $v['created_at'] = Carbon::now();
            $v['created_by'] = Auth()->id();
            $dataNotPage[$k] = $v;
        }
        $addNotDuplicate = $mCommodityTable->addNotDuplicate($dataNotPage);


        $getDataTable = $mCommodityTable->getInfoTable($data);
        $data = [];
        foreach ($getDataTable as $k => $v) {
            if ($v['object_type'] == 'products') {
                $getInfoObject = $mProduct->getInfo($v['object_id']);
                $getDataTable[$k]['category_name'] = $getInfoObject['category_name'];
            } elseif ($v['object_type'] == 'services') {
                $getInfoObject = $mService->getInfo($v['object_id']);
                $getDataTable[$k]['category_name'] = $getInfoObject['category_name'];
            } else {
                $getInfoObject = $mServiceCard->getInfo($v['object_id']);
                $getDataTable[$k]['category_name'] = $getInfoObject['category_name'];
            }

            $getDataTable[$k]['name'] = $getInfoObject['name'];

//                $data[] = $v;
        }


        return $getDataTable;

    }

    public function getCommodityNow($data)
    {
        $commodityNow = app()->get(ReferralProgramItemTable::class);
        $getCommodityNow = $commodityNow->getInfoTable($data);
        return $getCommodityNow;
    }

    public function deleteCommodity($locate)
    {
        $delCommodity = app()->get(ReferralProgramItemTable::class);
        ////lưu log khi xóa hàng hóa
        $type = [
            'services' => __('Dịch vụ'),
            'products' => __('Sản phẩm'),
            'service_cards' => __('Thẻ dịch vụ'),
        ];
        foreach ($type as $k => $v) {
            if ($k == $locate['idCommodity']['object_type']) {
                $locate['idCommodity']['type'] = $v;
            }
        }
        $dataLog = [
            'referral_program_id' => $locate['referral_program_id'],
            'staff_id' => Auth()->id(),
            'content' => __(' đã xóa hàng hóa: ') . $locate['idCommodity']['name'] . __(', nhóm: ') . $locate['idCommodity']['category_name'] . __(', loại: ') . $locate['idCommodity']['type'],
            'created_at' => Carbon::now()
        ];

        $saveLog = app()->get(ReferralProgramLogTable::class);
        $id = $saveLog->saveLogCreateCommission($dataLog);

        $delete = $delCommodity->deleteCommodity($locate);
        return $delete;
    }

    /**
     * Chuyển trang danh sách sản phẩm
     * @param $data
     * @return mixed|void
     */
    public function changePageProduct($data)
    {
        $list = $this->getInfoTable($data);
        $view = view('referral::GroupCommodity.commodityTable', ['dataCommodity' => $list])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }

    public function getInfoById($id)
    {
        $getInfoByCommission = app()->get(ReferralProgramTable::class);
        $infoCommission = $getInfoByCommission->getInfoById($id);
        if ($infoCommission['type'] == 'cps') {
            $infoCommission['type'] = 'CPS';
        } else {
            $infoCommission['type'] = 'CPI';
        }

        if ($infoCommission['apply_for'] == 'customer') {
            $infoCommission['apply_for_choose'] = __('Khách hàng');
        } else {
            $infoCommission['apply_for_choose'] = __('Tất cả');
        }
        unset($infoCommission['apply_for']);
        $infoCommission['date_start'] = Carbon::createFromFormat('Y-m-d', $infoCommission['date_start'])->format('d/m/Y');
        $infoCommission['date_end'] = Carbon::createFromFormat('Y-m-d', $infoCommission['date_end'])->format('d/m/Y');
        if ($infoCommission['referral_criteria_code']) {
            $infoCommission['accountable_by_choose'] = __('Tổng giá trị đơn hàng');
        }

        return $infoCommission;
    }

    public function saveConditionOrderPrice($input)
    {
        $input['commission_value'] = str_replace(',', '', $input['commission_value']);
        $input['commission_max_value'] = str_replace(',', '', $input['commission_max_value']);

        if(!isset($input['commission_type']) == true || $input['commission_type'] == 'money' ){
            $input['commission_type'] = 'money';
        }else{
            $input['commission_type'] = 'percent';
        }
        if ($input['commission_value'] == 0  || $input['commission_value'] == null  ) {
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Giá trị hoa hồng - Giá trị hoa hồng phải là 1 số')
            ];
        } elseif ( ($input['commission_max_value'] == 0||$input['commission_max_value'] == null) && $input['commission_type'] == 'percent') {
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Giá trị hoa hồng tối đa - Giá trị hoa hồng tối đa phải là 1 số')
            ];
        }elseif ($input['commission_value'] <= 0 || $input['commission_value'] <= 0) {
            return [
                'error' => true,
                'message' => __('Tiền hoa hồng không được nhỏ hơn 0!')
            ];
        }elseif ( $input['commission_value'] > 100 && $input['commission_type'] == 'percent') {
            return [
                'error' => true,
                'message' => __('Giá trị hoa hồng không được vượt quá giá trị tối đa')
            ];
        } else {
            $save = app()->get(ReferralProgramTable::class);
            $conditionCPS = app()->get(ReferralProgramConditionTable::class);

            $referral_program_id = $input['referral_program_id'];
            unset($input['referral_program_id']);
            $dataCondition = [];
            if (isset($input['cps_total_order_is_transport_fee'])) {
                $dataCondition['cps.total_order.is_transport_fee'] = 1;
            } else {
                $dataCondition['cps.total_order.is_transport_fee'] = 0;
            }
            unset($input['cps_total_order_is_transport_fee']);
            unset($input['cps_total_order_condition']);
            $dataCondition['cps.total_order.condition'] = 'every_order';
            $dataConditionCPS = [
                [
                    'referral_program_id' => $referral_program_id,
                    'key' => 'cps.total_order.is_transport_fee',
                    'value' => $dataCondition['cps.total_order.is_transport_fee']
                ],
                [
                    'referral_program_id' => $referral_program_id,
                    'key' => 'cps.total_order.condition',
                    'value' => $dataCondition['cps.total_order.condition']
                ]
            ];
            $check = $conditionCPS->checkCondition($referral_program_id);
            if (count($check) == 0) {
                ///lưu điều kiện CPS
                unset($input['commission_type_condition']);
                $input['calculation_type'] = 'every_order';
                $saveCondition = $save->saveConditionOrderPrice($referral_program_id, $input);
                $saveConditionCPS = $conditionCPS->saveConditionOrderPrice($dataConditionCPS);
                ///lưu log thêm điều kiện chinh sách hoa hồng CPS
                $dataLog = [
                    'referral_program_id' => $referral_program_id,
                    'staff_id' => Auth()->id(),
                ];
                ///lấy tên người thực hiện
                $dataLog['content'] = __(' đã thêm điều kiện tính hoa hồng thành công. ');
                $dataLog['created_at'] = Carbon::now();
                $saveLog = app()->get(ReferralProgramLogTable::class);
                $id = $saveLog->saveLogCreateCommission($dataLog);
            } else {
                //lấy giá trị hoa hồng tối đa cấu hình cũ
                $condition = app()->get(ReferralProgramTable::class);
                $infoCondition = $condition->getInfoCondition($referral_program_id);
                if (isset($input['commission_max_value']) && $infoCondition['commission_max_value'] != $input['commission_max_value']) {
                    ///lưu log lập nhật hoa hồng tối đa
                    $dataLog = [
                        'referral_program_id' => $referral_program_id,
                        'staff_id' => Auth()->id(),
                        'created_at' => Carbon::now(),
                        'content' => __(' đã cập nhật giá trị hoa hồng tối đa từ ') . $infoCondition['commission_max_value'] . __(' VNĐ thành ') . $input['commission_max_value'] . __(' VNĐ. ')
                    ];
                    $saveLog = app()->get(ReferralProgramLogTable::class);
                    $id = $saveLog->saveLogCreateCommission($dataLog);
                }

                ///xóa cấu hình cũ nếu đang cập nhật
                $delete = $conditionCPS->deleteOldCondition($referral_program_id);
                ///lưu điều kiện CPS

                unset($input['cps_total_order.is_transport_fee']);
                unset($input['cps_total_order.condition']);
                unset($input['commission_type_condition']);
                if($input['commission_type'] == 'money'){
                    $input['commission_max_value'] = null;
                }
                $saveCondition = $save->saveConditionOrderPrice($referral_program_id, $input);
                $saveConditionCPS = $conditionCPS->saveConditionOrderPrice($dataConditionCPS);
                ///lưu log chỉnh sửa điều kiện chính sách hoa hồng CPS
                $dataLog = [
                    'referral_program_id' => $referral_program_id,
                    'staff_id' => Auth()->id(),
                    'created_at' => Carbon::now(),
                    'content' => __(' đã cập nhật điều kiện tính hoa hồng thành công. '),
                ];
                $saveLog = app()->get(ReferralProgramLogTable::class);
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            return [
                'error' => false,
                'message' => __('Lưu thành công!')
            ];

        }


    }

    public function getInfoCondition($id)
    {
        $condition = app()->get(ReferralProgramTable::class);
        $infoCondition = $condition->getInfoCondition($id);
        if ($infoCondition == null) {
            $infoCondition = [];
        } else {
            if ($infoCondition['commission_type'] == 'percent') {
                $infoCondition['commission_type_choose'] = __('%');
            }
            $infoCondition['commission_type_choose'] = __('VNĐ');
        }
        return $infoCondition;
    }

    public function editInfoCommission($params)
    {

        $referral_program_id = $params['referral_program_id'];
        ///kiểm tra tên chính sách tồn tại chưa
        $checkList = app()->get(ReferralProgramTable::class);
        $check = $checkList->checkCommission($params);
        if ($check != []) {
            return [
                'error' => true,
                'message' => __('Tên chính sách đã tồn tại!')
            ];
        }
        if($params['referral_program_name'] == null){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập tên chính sách!')
            ];
        }
        if($params['description'] == "<p><br></p>"){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Nội dung hiển thị mô tả trên app!')
            ];
        }
        if( $params['type'] == "CPS" && $params['referral_criteria_code'] == null ){
            return [
                'error' => true,
                'message' => __('Vui lòng nhập Tiêu chí tính theo!')
            ];
        }
        ///lấy ngày bắt đầu và kết thúc cũ
        $getInfoByCommission = app()->get(ReferralProgramTable::class);
        $infoCommission = $getInfoByCommission->getInfoById($referral_program_id);
        $params['date_start_before'] = $infoCommission['date_start'];
        $params['date_end_before'] = $infoCommission['date_end'];

        unset($params['referral_program_id']);

        $params['date_start'] = Carbon::createFromFormat('d/m/Y', $params['date_start'])->format('Y-m-d');
        $params['date_end'] = Carbon::createFromFormat('d/m/Y', $params['date_end'])->format('Y-m-d');
        $params['updated_at'] = Carbon::now();
        $params['updated_by'] = Auth()->id();

        if (strtotime($params['date_start']) < strtotime($params['date_start_before']) || strtotime($params['date_end']) < strtotime($params['date_start_before'])) {
            return [
                'error' => true,
                'message' => __('Thời gian hiệu lực từ và đến không được trước thời gian đã chọn trước đó!')
            ];
        } elseif (strtotime($params['date_start']) > strtotime($params['date_end'])) {
            return [
                'error' => true,
                'message' => __('Thời gian hiệu lực từ phải nhỏ hơn hoặc bằng thời gian hiệu lực đến!')
            ];
        } else {
            $edit = app()->get(ReferralProgramTable::class);
            unset($params['date_start_before'], $params['date_end_before']);
            if ($params['apply_for'] == 'Khách hàng') {
                $params['apply_for'] = __('customer');
            } else {
                $params['apply_for'] = __('all');
            }
            if ($params['type'] == 'CPS') {
                $params['type'] = __('cps');
            } else {
                $params['type'] = __('cpi');

            }
            ///lấy thông tin hoa hồng hiện tại
            $getInfoByCommission = app()->get(ReferralProgramTable::class);
            $infoCommission = $getInfoByCommission->getInfoById($referral_program_id);

            $editInfo = $edit->editInfoCommission($referral_program_id, $params);

            $saveLog = app()->get(ReferralProgramLogTable::class);
            $dataLog = [
                'referral_program_id' => $referral_program_id,
                'staff_id' => Auth()->id(),
                'created_at' => Carbon::now()
            ];

            if ($infoCommission['referral_program_name'] != $params['referral_program_name']) ///nếu thay đổi tên Chính sách
            {
                $dataLog['content'] = __(' đã thay đổi tên chính sách từ ') . $infoCommission['referral_program_name'] . __(' thành ') . $params['referral_program_name'];
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            if ($infoCommission['apply_for'] != $params['apply_for']) //nếu thay đổi Áp dụng cho
            {
                if ($infoCommission['apply_for'] == 'customer') {
                    $apply_for_before = __('Khách hàng');
                } else {
                    $apply_for_before = __('Tất cả');
                }
                if ($params['apply_for'] == 'customer') {
                    $apply_for_after = __('Khách hàng');
                } else {
                    $apply_for_after = __('Tất cả');
                }
                $dataLog['content'] = __(' đã thay đổi từ áp dụng cho ') . $apply_for_before . __(' thành áp dụng cho ') . $apply_for_after;
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            if ($infoCommission['img'] != $params['img']) /////nếu thay đổi ảnh
            {
                $dataLog['content'] = __(' đã thay đổi Ảnh đại diện chính sách từ ') . $infoCommission['img'] . __(' thành ') . $params['img'];
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            if ($infoCommission['description'] != $params['description']) /////Thay đổi Mô tả
            {
                $dataLog['content'] = __(' đã thay đổi Nội dung hiển thị mô tả trên app ');
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            if ($infoCommission['date_start'] != $params['date_start']) /////Thay đổi ngày bắt đầu hiệu lực
            {
                $oldDateStart = Carbon::createFromFormat('Y-m-d', $infoCommission['date_start'])->format('d/m/Y');
                $newDateStart = Carbon::createFromFormat('Y-m-d', $params['date_start'])->format('d/m/Y');
                $dataLog['content'] = __(' đã thay đổi Ngày bắt đầu hiệu lực từ ') . $oldDateStart . __(' thành ') . $newDateStart;
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            if ($infoCommission['date_end'] != $params['date_end']) /////Thay đổi ngày hết hiệu lực
            {
                $oldDateEnd = Carbon::createFromFormat('Y-m-d', $infoCommission['date_end'])->format('d/m/Y');
                $newDateEnd = Carbon::createFromFormat('Y-m-d', $params['date_end'])->format('d/m/Y');
                $dataLog['content'] = __(' đã thay đổi Ngày kết thúc hiệu lực từ ') . $oldDateEnd . __(' thành ') . $newDateEnd;
                $id = $saveLog->saveLogCreateCommission($dataLog);
            }
            return [
                'error' => false,
                'message' => __('Cập nhật thành công!'),
                'createCommission' => $referral_program_id
            ];
        }
    }

    public function deleteCommission($id)
    {
        $delete = app()->get(ReferralProgramTable::class);
        $delCommission = $delete->deleteCommission($id);
        if ($delCommission != 1) {
            return [
                'error' => true,
                'message' => __('Xóa không thành công!'),

            ];
        }
        return [
            'error' => false,
            'message' => __('Xóa thành công!'),

        ];
    }

    public function getHistoryGeneralConfig($params = [])
    {
        try {
            if ($params != []) {
                $history = app()->get(ReferralConfigTable::class);
                if (isset($params['start']) && $params['start'] != null) {
                    $dayStart = explode(' - ', $params['start']);
                    $params['date_start_0'] = Carbon::createFromFormat('d/m/Y', $dayStart[0])->format('Y-m-d');
                    $params['date_start_1'] = Carbon::createFromFormat('d/m/Y', $dayStart[1])->format('Y-m-d');
                }
                if (isset($params['end']) && $params['end'] != null) {
                    $dayEnd = explode(' - ', $params['end']);

                    $params['date_end_0'] = Carbon::createFromFormat('d/m/Y', $dayEnd[0])->format('Y-m-d');
                    $params['date_end_1'] = Carbon::createFromFormat('d/m/Y', $dayEnd[1])->format('Y-m-d');
                }
                $historyGeneralConfig = $history->getHistoryGeneralConfig($params);
                foreach ($historyGeneralConfig as $k => $v) {
                    ///lây tên người cập nhật
                    $getName = app()->get(StaffsTable::class);
                    $nameStaff = $getName->getNameStaff($v['created_by']);
                    $v['staff_update'] = $nameStaff['full_name'];
                    if ($v['start'] != null) {
                        $v['start'] = Carbon::createFromFormat('Y-m-d H:i:s', $v['start'])->format('d/m/Y H:i');
                    }
                    if ($v['end'] != null) {
                        $v['end'] = Carbon::createFromFormat('Y-m-d H:i:s', $v['end'])->format('d/m/Y H:i');
                    }
                    if ($v['created_at'] != null) {
                        $v['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $v['created_at'])->format('d/m/Y H:i');
                    }
                    $historyGeneralConfig[$k] = $v;
                }
            } else {
                $history = app()->get(ReferralConfigTable::class);
                $historyGeneralConfig = $history->getHistoryGeneralConfig($params);

                foreach ($historyGeneralConfig as $k => $v) {
                    ///lây tên người cập nhật
                    $getName = app()->get(StaffsTable::class);
                    $nameStaff = $getName->getNameStaff($v['created_by']);
                    $v['staff_update'] = $nameStaff['full_name'];

                    if ($v['start'] != null) {
                        $v['start'] = Carbon::createFromFormat('Y-m-d H:i:s', $v['start'])->format('d/m/Y H:i');
                    }
                    if ($v['end'] != null) {
                        $v['end'] = Carbon::createFromFormat('Y-m-d H:i:s', $v['end'])->format('d/m/Y H:i');
                    }

                    if ($v['created_at'] != null) {
                        $v['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $v['created_at'])->format('d/m/Y H:i');
                    }

                    $historyGeneralConfig[$k] = $v;
                }

            }
            return $historyGeneralConfig;
        } catch (\Exception $e) {
            dd($e->getLine(), $e->getMessage());
        }
    }

    public function getFilter()
    {
        $fillter = app()->get(StaffsTable::class);
        $fillterGeneralConfig = $fillter->getFillter();
        return $fillterGeneralConfig;
    }

    public function getDetailCommission($params)
    {

        try {
            $id = $params['id'];
            $detail = app()->get(ReferralProgramTable::class);
            $detailCommission = $detail->getDetailCommission($params['id']);
            $detailCommission['date_start'] = Carbon::createFromFormat('Y-m-d', $detailCommission['date_start'])->format('d/m/Y');
            $detailCommission['date_end'] = Carbon::createFromFormat('Y-m-d', $detailCommission['date_end'])->format('d/m/Y');
            /// lấy trainsport_fee của cps
            $getCondition = app()->get(ReferralProgramConditionTable::class);
            $condition = $getCondition->conditioncpi($id);

            if (count($condition) != 0 && $condition[0]['value'] == 1) {
                $detailCommission['trainsport_fee'] = 1;
            } else {
                $detailCommission['trainsport_fee'] = 0;
            }
            if(isset($detailCommission['commission_value']) && $detailCommission['commission_value']!= null){
                $detailCommission['commission_value'] =  number_format( $detailCommission['commission_value']);
            }
            if(isset($detailCommission['commission_max_value']) && $detailCommission['commission_max_value']!= null){
                $detailCommission['commission_max_value'] =  number_format( $detailCommission['commission_max_value']);
            }

            return $detailCommission;
        } catch (\Exception $e) {
            dd($e->getLine(), $e->getMessage());
        }

    }

    public function getInfoCommodity($params)
    {
        $id = $params['id'];

        $mCommodityTable = app()->get(ReferralProgramItemTable::class);
        $mProduct = app()->get(ProductsTable::class);
        $mService = app()->get(ServicesTable::class);
        $mServiceCard = app()->get(ServiceCardsTable::class);


        $getDataCommodity = $mCommodityTable->getInfoCommodity($params);
        foreach ($getDataCommodity as $k => $v) {
            if ($v['object_type'] == 'products') {
                $getInfoObject = $mProduct->getInfoProduct($v['object_id']);
                $v['commodity'] = $getInfoObject;
                $getDataCommodity[$k] = $v;
            } elseif ($v['object_type'] == 'services') {
                $getInfoObject = $mService->getInfoService($v['object_id']);
                $v['commodity'] = $getInfoObject;
                $getDataCommodity[$k] = $v;
            } else {
                $getInfoObject = $mServiceCard->getInfoServiceCard($v['object_id']);
                $v['commodity'] = $getInfoObject;
                $getDataCommodity[$k] = $v;
            }
        }
        return $getDataCommodity;
    }

    public function conditioncpi($id)
    {
        $getConditionCPI = app()->get(ReferralProgramConditionTable::class);
        $conditionCPI = $getConditionCPI->conditioncpi($id);


        $moneyCpi = app()->get(ReferralProgramTable::class);
        $money = $moneyCpi->getDetailCommission($id);
        if ($conditionCPI != []) {
            $data = [
                'time_use_app' => $conditionCPI[0]['value'],
                'compare' => $conditionCPI[1]['value'],
                'time_use_time' => $conditionCPI[2]['value'],
                'time_use_date' => $conditionCPI[3]['value'],
                'commission_value' => $money['commission_value']
            ];
        } else {
            $data = [];
        }
        return $data;
    }

    public function stateChange($params)
    {
        $check = app()->get(ReferralProgramConditionTable::class);
        $change = app()->get(ReferralProgramTable::class);
        $checkStatus = app()->get(ReferralProgramTable::class);
        //kiểm tra xem có chính sách nháp nào đã được duyệt chưa->thông báo được duyệt hay không
                    //lay thoi gian chua chinh sach dang yeu cau duyet
        $time = $checkStatus->getDetailCommission($params['id']);
        $conditionRequest['time_start'] = $time['date_start'];
        $conditionRequest['type'] = $time['type'];
                    //lay thoi gian cac chinh sach cung loai
        $timeSameType =$checkStatus->timeSameType($conditionRequest);
        if(count($timeSameType) != 0 && $params['job'] == 'duyệt' ){
           foreach ($timeSameType as $k => $v){
               if($conditionRequest['time_start'] >= $v['date_start'] && $conditionRequest['time_start'] <= $v['date_end']){
                   return [
                       'error' => true,
                       'message' => __('Không thể duyệt chính sách này vì chính sách cùng loại là: ').$v['referral_program_name'].__(' đang ở trạng thái Đã duyệt'),
                   ];
               }
           }
        }
        ///kiểm tra đủ các điều kiện để gửi duyệt chưa
        if($params['job'] == "gửi duyệt"){
            $checkCondition = $check -> checkCondition($params['id']);
            if(count($checkCondition) == 0){
                return [
                    'error' => true,
                    'message' => __('Chính sách chưa đủ thông tin - Không thể gửi duyệt!'),
                ];
            }
        }
        $referral_program_id = $params['id'];
        $statusUpdate = '';
        switch ($params['job']) {
            case 'gửi duyệt':
                $statusUpdate = [
                    'status' => 'waiting'
                ];
                break;
            case 'duyệt':
                $statusUpdate = [
                    'status' => 'approved'
                ];
                break;
            case 'từ chối':
                $statusUpdate = [
                    'status' => 'reject'
                ];
                break;
            case 'lưu nháp':
                $statusUpdate = [
                    'status' => 'new'
                ];
                break;
            case 'hủy':
                $statusUpdate = [
                    'status' => 'cancel'
                ];
                break;
            case 'dừng':
                $statusUpdate = [
                    'status' => 'pending'
                ];
                break;
            case 'kết thúc':
                $statusUpdate = [
                    'status' => 'finish'
                ];
                break;
            case 'tiếp tục':
                $statusUpdate = [
                    'status' => 'actived'
                ];
                break;
            default:
        }
///lưu log chuyển trạng thái

        $dataLog = [
            'referral_program_id' => $referral_program_id,
            'staff_id' => Auth()->id(),
        ];
        ///lấy trạng thái hiện tại của chính sách
        $detail = app()->get(ReferralProgramTable::class);
        $detailCommission = $detail->getDetailCommission($referral_program_id);
        $oldStatus = $detailCommission['status'];
        $newStatus = $statusUpdate['status'];
        ///chuyển tên trạng thái sang tiếng việt để thêm log
        $statusConvert = [
            'new' => __('Nháp'),
            'waiting' => __('Chờ duyệt'),
            'reject' =>  __('Từ chối'),
            'cancel' => __('Hủy'),
            'pending' => __('Dừng'),
            'finish' => __('Kết thúc'),
            'actived' => __('Hoạt động'),
            'approved' => __('Đã duyệt'),
        ];
        foreach ($statusConvert as $k => $v) {
            if ($k == $oldStatus) {
                $old = $v;
            }
            if ($k == $newStatus) {
                $new = $v;
            }
        }
        $dataLog['content'] = __(' đã chuyển trạng thái chính sách từ ') . $old . __(' sang ') . $new;
        $dataLog['created_at'] = Carbon::now();
        $saveLog = app()->get(ReferralProgramLogTable::class);
        $id = $saveLog->saveLogCreateCommission($dataLog);
/////
        $responseChange = $change->stateChange($referral_program_id, $statusUpdate);

        return [
            'error' => false,
            'message' => __('Cập nhật không thành công!'),

        ];
    }

    public function getLog($params)
    {
        if (isset($params['created_at']) && $params['created_at'] != null) {
            $dateSearch = explode(' - ', $params['created_at']);

            $dateSearch_from = Carbon::createFromFormat('d/m/Y', $dateSearch[0])->format('Y-m-d');
            $dateSearch_to = Carbon::createFromFormat('d/m/Y', $dateSearch[1])->format('Y-m-d');
            $params['dateSearch_from'] = $dateSearch_from;
            $params['dateSearch_to'] = $dateSearch_to;
        }

        $getLog = app()->get(ReferralProgramLogTable::class);
        $log = $getLog->getLog($params);
        if (count($log) != 0) {
            foreach ($log as $k => $v) {
                $explode = explode(' ', $v['created_at']);
                $day = Carbon::createFromFormat('Y-m-d', $explode[0])->format('d/m/Y');
                $hour = $explode[1];
                $now = Carbon::now()->format('d/m/Y');
                if ($day == $now) {
                    $day = __('Hôm nay');
                }
                $v['day'] = $day;
                $v['hour'] = $hour;
                $staff = app()->get(StaffsTable::class);
                $staffName = $staff->getNameStaff($v['staff_id']);
                $v['staff_name'] = $staffName['full_name'];
                $log[$k] = $v;
            }
        } else {
            $log = [];
        }
        return $log;
    }


}
