<?php

namespace Modules\Referral\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Referral\Repositories\ReferralInterface;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('referral::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('referral::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('referral::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('referral::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function policyCommission(Request $request)
    {
        $input = $request->all();
        $list = app()->get(ReferralInterface::class);
        $input['perpage'] = $input['display'] ?? 25;
        $getList = $list->getListCommission($input);

        return view('referral::PolicyCommission.policy', ['data' => $getList, 'param' => $input]);
    }

    public function detailCommission($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        if (isset($params['page'])) {
            $page = $params['page'];
        } else {
            $page = 1;
        }
        ///lay thong tin chinh sách hoa hồng
        $detail = app()->get(ReferralInterface::class);
        $detailCommission = $detail->getDetailCommission($params);

        ///lấy sản phẩm đã chọn nếu là CPS
        $getCommodity = app()->get(ReferralInterface::class);
        $commodity = $getCommodity->getInfoCommodity($params);
        ///lấy fillter cho lịch sử thay đổi
        $filter = app()->get(ReferralInterface::class);
        $filterConfig = $filter->getFilter();
        /////lay điều kiện CPI
        if ($detailCommission['type'] == 'cpi') {
            $getConditionCPI = app()->get(ReferralInterface::class);
            $conditionCPI = $getConditionCPI->conditioncpi($detailCommission['referral_program_id']);
        } else {
            $conditionCPI = [];
        }
        ///lấy log
        $getLog = app()->get(ReferralInterface::class);
        $log = $getLog->getLog($detailCommission);

        $repoReferral = app()->get(ReferralInterface::class);
        $dataRate = $repoReferral->getInfoRate($id);
        if($dataRate){
            $dataRate = collect($dataRate)->keyBy('level')->toArray();
        }

        return view('referral::PolicyCommission.detail',
            [
                'dataRate' => $dataRate,
                'data' => $detailCommission,
                'commodity' => $commodity,
                'page' => $page,
                'filter' => $filterConfig,
                'conditionCPI' => $conditionCPI,
                'log' => $log
            ]);
    }

    public function historyCommission(Request $request)
    {
        try {
            $params = $request->all();
            $getLog = app()->get(ReferralInterface::class);
            $log = $getLog->getLog($params);
            $html = view('referral::PolicyCommission.listHistory', ['log' => $log])->render();
            return [
                'error' => false,
                'view' => $html
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'des' => $e->getMessage(),
                'message' => __('Lỗi'),
            ];
        }

    }

    public function multiLevelConfig()
    {
        $oldInfo = app()->get(ReferralInterface::class);
        $getOld = $oldInfo->getOldInfo();
        return view('referral::MultiLevelConfig.multiLevelConfig', ['info' => $getOld]);
    }

    public function submitEditMultiLevelConfig(Request $request){
        $data = $request->all();

        $percent = 0;

        $error = false;
        $arrRate = [];
        foreach ($data['input_level'] as $level => $percentLevel){

            if($level <= $data['level']){
                if(!is_numeric($percentLevel) || (float)$percentLevel <= 0){
                    $error = true;
                } else {
                    $arrRate[] = [
                        'referral_program_id' => $data['referral_program_id'],
                        'level' => $level,
                        'percent' => $percentLevel,
                        'is_actived' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id()
                    ];
                    $percent = $percent + $percentLevel;
                }

            }
        }
        if($error){
            return response()->json([
                'error' => true,
                'message' => 'Giá trị chiết khấu phải là chữ số có giá trị từ 1 đến 100'
            ]);
        }

        if($percent != 100){
            return response()->json([
                'error' => true,
                'message' => 'Tổng % chiết khấu phải bằng 100%'
            ]);
        }
        if($arrRate){
            DB::table('referral_program_rate')
                ->where('referral_program_id', $data['referral_program_id'])
                ->delete();
            DB::table('referral_program_rate')->insert($arrRate);
        }

        DB::table('referral_program')
            ->where('referral_program_id', $data['referral_program_id'])
            ->update(['level_rate' => $data['level']]);

        return response()->json([
            'error' => false,
        ]);
    }

    public function editMultiLevelConfig($id, Request $request)
    {
        $repoReferral = app()->get(ReferralInterface::class);
        $data = $repoReferral->getInfoRate($id);
        if($data){
            $data = collect($data)->keyBy('level')->toArray();
        }

        $dataProgram = DB::table('referral_program')
            ->where('referral_program_id', $id)
            ->first();

        return view('referral::MultiLevelConfig.editMultiLevelConfig',[
            'info' => $data,
            'referral_program_id' => $id,
            'dataProgram' => $dataProgram
        ]);
    }

    public function listGeneralConfig()
    {
        return view('referral::GeneralConfig.listGeneralConfig');
    }

    public function generalConfig($id = null)
    {
        if ($id == null) {
            $getOld = app()->get(ReferralInterface::class);
            $infoOld = $getOld->getInfoOld();
            return view('referral::GeneralConfig.generalConfig', ['info' => $infoOld]);
        } else {
            $getOldById = app()->get(ReferralInterface::class);
            $infoOld = $getOldById->getInfoOldById($id);

            return view('referral::GeneralConfig.generalConfig', ['info' => $infoOld]);
        }

    }

    public function editGeneralConfig(Request $request)
    {
        $getOld = app()->get(ReferralInterface::class);
        $infoOld = $getOld->getInfoOld();
        return view('referral::GeneralConfig.editGeneralConfig', ['info' => $infoOld]);
    }

    public function historyGeneralConfig(Request $request)
    {
        $params = $request->all();
        $history = app()->get(ReferralInterface::class);
        $filter = app()->get(ReferralInterface::class);

        $filterConfig = $filter->getFilter();
        $historyGeneralConfig = $history->getHistoryGeneralConfig($params);

        $data = [
            'filter' => $filterConfig,
            'table' => $historyGeneralConfig,
        ];
        return view('referral::GeneralConfig.listGeneralConfig', ['data' => $data, 'param' => $params]);
    }


    public function listReferrer()
    {
        return view('referral::ListReferrer.listReferrer');
    }

    public function detailReferrer()
    {
        return view('referral::ListReferrer.detailReferrer');
    }

    public function referrerCommission()
    {
        return view('referral::ReferrerCommission.referrerCommission');
    }

    public function payment()
    {
        return view('referral::Payment.payment');
    }

    public function addCommission($id = null, Request $request)
    {
        if ($id == null) {
            $getSelect = app()->get(ReferralInterface::class);
            $info = $getSelect->getSelectInfo();
            return view('referral::AddCommission.addCommission', $info);
        } else {
            $getSelect = app()->get(ReferralInterface::class);
            $info = $getSelect->getSelectInfo();
            ///lay thong tin chinh sách đã lưu
            $getInfoByCommission = app()->get(ReferralInterface::class);
            $infoCommission = $getInfoByCommission->getInfoById($id);
            $infoCommission = array_merge($info, $infoCommission);
            return view('referral::AddCommission.addCommission', $infoCommission);
        }

    }

    public function addCommissionOrderPrice()
    {
        return view('referral::AddCommission.addCommissionOrderPrice');
    }

    public function editCommission(Request $request)
    {
        $params = $request->all();
        return response()->json([
            'link' => route('referral.editInfoCommission',
                ['id' => $params['referral_program_id']])
        ]);
    }

    public function editInfoCommission($id, Request $request)
    {
        $getSelect = app()->get(ReferralInterface::class);
        $info = $getSelect->getSelectInfo();
        ///lay thong tin chinh sách đã lưu
        $getInfoByCommission = app()->get(ReferralInterface::class);
        $infoCommission = $getInfoByCommission->getInfoById($id);
        $infoCommission = array_merge($info, $infoCommission);
        $infoCommission['id'] = $id;
        if ($infoCommission['img'] != null) {
            $infoImage = getimagesize($infoCommission['img']);
            $image = get_headers($infoCommission['img'], 1);
            $bytes = $image["Content-Length"];
            $kb = $bytes / 1024;
            $infoImage['capacity'] = floor($kb);
            $infoCommission['info_image'] = $infoImage;
        } else {
            $infoCommission['info_image'] = null;
        }
        return view('referral::AddCommission.editCommission', $infoCommission);
    }

    public function chooseProduct()
    {
        return view('referral::ChooseProduct.chooseProduct');
    }

    public function saveInfoCommission(Request $request)
    {
        $input = $request->all();
        $create = app()->get(ReferralInterface::class);
        $createCommission = $create->createNewCommission($input);
        if ($createCommission['error'] == false) {
            if ($input['type'] == 'CPS') {
                return response()->json([
                    'link' => route('referral.chooseOrderPrice', ['id' => $createCommission['createCommission']]),
                ]);
            } else {
                return response()->json([
                    'link' => route('referral.commissionConditionCPI', ['id' => $createCommission['createCommission']]),
                ]);
            }
        } else {
            return response()->json($createCommission);
        }
    }

    public function chooseOrderPrice($id, Request $request)
    {
        $param = $request->all();
        $param['referral_program_id'] = $id;
        $getTable = app()->get(ReferralInterface::class);
        $info = $getTable->getInfoTable($param);

//        $listGroup = $getTable->getListGroup();
//        $listCommodityAll = $getTable->getListCommodityAll();

        return view('referral::ChooseProduct.chooseOrderPrice',
            [
                'referral_program_id' => $id,
                'table' => $info,
//            'listGroup'=>$listGroup,
//            'listCommodityAll'=>$listCommodityAll,
            ]);
    }

    public function chooseCategoryProduct()
    {
        return view('referral::ChooseProduct.chooseCategoryProduct');
    }

    public function chooseService()
    {
        return view('referral::ChooseProduct.chooseService');
    }

    public function chooseGroupService()
    {
        return view('referral::ChooseProduct.chooseGroupService');
    }

    public function chooseCardService()
    {
        return view('referral::ChooseProduct.chooseCardService');
    }

    public function chooseTypeCardService()
    {
        return view('referral::ChooseProduct.chooseTypeCardService');
    }

    public function commissionConditionBooking()
    {
        return view('referral::CommissionCondition.commissionConditionBooking');
    }

    public function step3ChooseOrderPrice(Request $request)
    {
        $params = $request->all();
        return response()->json([
            'link' => route('referral.commissionCondition', ['id' => $params['referral_program_id']]),
        ]);


    }

    public function commissionCondition($id)
    {
        ///lay thong tin của điều kiện
        $condition = app()->get(ReferralInterface::class);
        $infoCondition = $condition->getInfoCondition($id);
        return view('referral::CommissionCondition.commissionCondition',
            ['referral_program_id' => $id,
                'infoCondition' => $infoCondition]);
    }

    public function saveConditonOrderPrice(Request $request)
    {
        $input = $request->all();
        $save = app()->get(ReferralInterface::class);
        $saveCondition = $save->saveConditionOrderPrice($input);
        return response()->json($saveCondition);

    }

    public function commissionConditionCPI($id, Request $request)
    {
        /////lay điều kiện CPI
        $getConditionCPI = app()->get(ReferralInterface::class);
        $conditionCPI = $getConditionCPI->conditioncpi($id);


        return view('referral::CommissionCondition.commissionConditionCPI',
            [
                'referral_program_id' => $id,
                'data' => $conditionCPI
            ]);

    }

    public function commissionConditionCategory()
    {
        return view('referral::CommissionCondition.commissionConditionCategory');
    }

    public function commissionConditionOrderPrice()
    {
        return view('referral::CommissionCondition.commissionConditionOrderPrice');
    }

    public function commissionConditionService()
    {
        return view('referral::CommissionCondition.commissionConditionService');
    }

    public function commissionConditionGroupService()
    {
        return view('referral::CommissionCondition.commissionConditionGroupService');
    }

    public function commissionConditionCardService()
    {
        return view('referral::CommissionCondition.commissionConditionCardService');
    }

    public function commissionConditionTypeCardService()
    {
        return view('referral::CommissionCondition.commissionConditionTypeCardService');
    }


    public function saveGeneralConfig(Request $request)
    {
        $input = $request->all();
        $save = app()->get(ReferralInterface::class);
        $generalConfig = $save->saveGeneralConfig($input);
        return response()->json($generalConfig);
    }

    public function saveMultiLevelConfig(Request $request)
    {
        $input = $request->all();
        $save = app()->get(ReferralInterface::class);
        $multiLevelConfig = $save->saveMultilevelConfig($input);
        return response()->json($multiLevelConfig);
    }

    public function saveConditionCPI(Request $request)
    {

        $data = $request->all();
        $referral_program_id = $data['referral_program_id'];
        unset($data['referral_program_id']);
        $save = app()->get(ReferralInterface::class);
        $saveCondition = $save->saveNewConditionCPI($data, $referral_program_id);
        return response()->json($saveCondition);
    }

    ///lay nhom hang hoa tu loai hang hoa
    public function getGroupCommodity(Request $request)
    {
        try {
            $params = $request->all();
            $groupCommidity = app()->get(ReferralInterface::class);
            $getGroup = $groupCommidity->getGroupCommodity($params);
            $html = view('referral::GroupCommodity.groupCommodity',
                [
                    'data' => $getGroup,
                    'typeGroup' => $params['type-commodity']
                ])->render();
            return [
                'error' => false,
                'view' => $html
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lỗi'),
                'des' => $e->getMessage(),
            ];
        }
    }

    ///load ds nhom hang hoa
    public function loadGroupCommodity()
    {
        return view('referral::GroupCommodity.groupCommodity');
    }

    ///lay tat ca nhom hang hoa
    public function loadGroupCommodityAll()
    {
        return view('referral::GroupCommodity.groupCommodityAll');
    }

    //lay hang hoa
    public function getCommodity(Request $request)
    {
        try {
            $commodity = $request->all();
            ///lay ds san pham dang co cua chinh sach
            $commoditychoosed = app()->get(ReferralInterface::class);
            $commodityNow = $commoditychoosed->getCommodityNow($commodity);
            ///
            $getCommodity = app()->get(ReferralInterface::class);
            $listCommodity = $getCommodity->getListCommodity($commodity, $commodityNow);

            $dataGroup = explode('|', $commodity['group_commodity']);
            $type = $dataGroup[0];
            $id = $dataGroup[1];
            $html = view('referral::GroupCommodity.commodity',
                [
                    'data' => $listCommodity,
                    'typeGroup' => $type,
                    'param' => $commodity
                ])->render();
            return [
                'error' => false,
                'view' => $html
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'des' => $e->getMessage(),
                'message' => __('Lỗi'),
            ];
        }
    }

    //them hang hoa vao bang
    public function addCommodity(Request $request)
    {
        try {
            $commoditySelected = $request->all();

            $select = app()->get(ReferralInterface::class);
            $selectCommodity = $select->addCommodity($commoditySelected);

            if ($selectCommodity != []) {
                $referral_program_id = $commoditySelected['referral_program_id'];
                /////them hang hoa đã chọn vào db
                foreach ($selectCommodity as $k => $v) {
                    $v['referral_program_id'] = $commoditySelected['referral_program_id'];
                    $v['object_type'] = $v['type'];
                    $selectCommodity[$k] = $v;
                }

                $addInDB = app()->get(ReferralInterface::class);
                $addCommodityInDB = $addInDB->add($selectCommodity);

                ////lay hang hoa vừa thêm hiển thị ra bảng

                $getTable = app()->get(ReferralInterface::class);
                $info = $getTable->getInfoTable($commoditySelected);
            } else {
                $info = [];
            }


            $html = view('referral::GroupCommodity.commodityTable', ['dataCommodity' => $info])->render();
            return [
                'error' => false,
                'view' => $html
            ];
            ////
        } catch (\Exception $e) {
            return [
                'error' => true,
                'des' => $e->getMessage(),
                'message' => __('Lỗi'),
            ];
        }
    }

    public function deleteCommodity(Request $request)
    {
        try {
            $locate = $request->all();
            $delCommodity = app()->get(ReferralInterface::class);
            $delete = $delCommodity->deleteCommodity($locate);
            return [
                'error' => false,
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'des' => $e->getMessage(),
                'message' => __('Lỗi'),
            ];
        }

    }

    public function changePageProduct(Request $request)
    {
        $param = $request->all();
        $rReferral = app()->get(ReferralInterface::class);
        $data = $rReferral->changePageProduct($param);
        return \response()->json($data);
    }

    public function saveEditInfoCommission(Request $request)
    {

        $params = $request->all();
        $edit = app()->get(ReferralInterface::class);
        $editInfo = $edit->editInfoCommission($params);
        if ($editInfo['error'] == false) {
            if ($params['type'] == 'CPS') {
                return response()->json([
                    'link' => route('referral.chooseOrderPrice', ['id' => $editInfo['createCommission']]),
                ]);
            } else {
                return response()->json([
                    'link' => route('referral.commissionConditionCPI', ['id' => $editInfo['createCommission']]),
                ]);
            }
        } else {
            return response()->json($editInfo);
        }
    }

    public function deleteCommission(Request $request)
    {
        $params = $request->all();
        $id = $params['referral_program_id'];
        $delete = app()->get(ReferralInterface::class);
        $delCommission = $delete->deleteCommission($id);
        return $delCommission;
    }

    public function stateChange(Request $request)
    {
        $params = $request->all();
        $change = app()->get(ReferralInterface::class);
        $responseChange = $change->stateChange($params);
        if(isset($responseChange['error']) && $responseChange['error'] == true){
            return \response()->json([
                'error' => $responseChange['error'],
                'message' => $responseChange['message']
            ]);
        }

        $repoReferral = app()->get(ReferralInterface::class);
        $dataRate = $repoReferral->getInfoRate($params['id']);
        if(!count($dataRate)){
            return \response()->json([
                'error' => true,
                'message' => 'Vui lòng cài đặt tỷ lệ chiết khấu'
            ]);
        }

        return response()->json([
            'link' => route('referral.detailCommission',['id' => $params['id']]),
            'error' => false
        ]);
    }

}
