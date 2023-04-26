<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/2/2021
 * Time: 3:04 PM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ContractCare;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractCareTable;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractGoodsTable;
use Modules\Contract\Models\ContractPartnerTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\CustomerTable;
use Modules\Contract\Models\StaffTable;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\StaffsTable;

class ContractCareRepo implements ContractCareRepoInterface
{
    protected $contractCare;

    public function __construct(ContractCareTable $contractCare)
    {
        $this->contractCare = $contractCare;
    }

    public function getDataViewIndex(&$filter)
    {
        $mContractCategory = new ContractCategoriesTable();
        $mStaff = new StaffTable();
        $optionCategory = $mContractCategory->getOption();
        $optionStaff = $mStaff->getOption();
        $data = $this->contractCare->getList($filter);
        $arrContractExpire = [];
        if (session()->get('contract-expire-temp')) {
//            $arrContractExpire = session()->get('contract-expire-temp');
            session()->forget('contract-expire-temp');
        }
        $arrContractSoonExpire = [];
        if (session()->get('contract-soon-expire-temp')) {
//            $arrContractExpire = session()->get('contract-soon-expire-temp');
            session()->forget('contract-soon-expire-temp');
        }
        return [
            'LIST' => $data,
            'optionCategory' => $optionCategory,
            'optionStaff' => $optionStaff,
            'arrContractExpire' => $arrContractExpire,
            'arrContractSoonExpire' => $arrContractSoonExpire,
        ];
    }

    public function getList(&$filter)
    {
        return $this->contractCare->getList($filter);
    }

    public function chooseAllExpireAction($data)
    {
        //Get session main
        $arrContractExpire = [];
        if (session()->get('contract-expire')) {
            $arrContractExpire = session()->get('contract-expire');
        }
        //Get session temp
        $arrContractExpireTemp = [];
        if (session()->get('contract-expire-temp')) {
            $arrContractExpireTemp = session()->get('contract-expire-temp');
        }
        //Merge những cái vừa check vào array temp (cái đã check)
        $arrContractExpireNew = [];
        if (count($data['arr_check']) > 0) {
            foreach ($data['arr_check'] as $v) {
                $arrContractExpireNew[$v['contract_code']] = [
                    'contract_code' => $v['contract_code'],
                ];
            }
        }
        //Merge 2 array temp + new
        $arrContractExpireTempNew = array_merge($arrContractExpireTemp, $arrContractExpireNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrContractExpireTempNew, $arrContractExpire);
        //Lưu session temp mới
        session()->forget('contract-expire-temp');
        session()->put('contract-expire-temp', $arrResult);
        return session()->get('contract-expire-temp');
    }

    public function chooseExpireAction($data)
    {
        //Get session main
        $arrContractExpire = [];
        if (session()->get('contract-expire')) {
            $arrContractExpire = session()->get('contract-expire');
        }
        //Get session temp
        $arrContractExpireTemp = [];
        if (session()->get('contract-expire-temp')) {
            $arrContractExpireTemp = session()->get('contract-expire-temp');
        }
        //Merge vào array temp
        $arrContractExpireNew = [
            $data['contract_code'] => [
                'contract_code' => $data['contract_code'],
            ]
        ];
        //Merge 2 array temp + new
        $arrContractExpireTempNew = array_merge($arrContractExpireTemp, $arrContractExpireNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrContractExpireTempNew, $arrContractExpire);
        //Lưu session temp mới
        session()->forget('contract-expire-temp');
        session()->put('contract-expire-temp', $arrResult);
        return session()->get('contract-expire-temp');
    }

    public function unChooseAllExpireAction($data)
    {
        //Get session 9
        $arrContractExpire = [];
        if (session()->get('contract-expire')) {
            $arrContractExpire = session()->get('contract-expire');
        }
        //Get session temp
        $arrContractExpireTemp = [];
        if (session()->get('contract-expire-temp')) {
            $arrContractExpireTemp = session()->get('contract-expire-temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrContractExpireTemp, $arrContractExpire);
        $arrRemoveContractExpireTemp = [];
        //Unset phần tử
        if (count($data['arr_un_check']) > 0) {
            foreach ($data['arr_un_check'] as $v) {
                $arrRemoveContractExpireTemp [] = $v['contract_code'];
                unset($arrResult[$v['contract_code']]);
            }
        }
        //Lưu session temp mới
        session()->forget('contract-expire-temp');
        session()->put('contract-expire-temp', $arrResult);
        //Get session remove temp
        if (session()->get('remove_contract_expire')) {
            $arrRemoveContractExpireTemp = session()->get('remove_contract_expire');
        }
        //Lưu session remove temp
        session()->forget('remove_contract_expire');
        session()->put('remove_contract_expire', $arrRemoveContractExpireTemp);
        return session()->get('contract-expire-temp');
    }

    public function unChooseExpireAction($data)
    {//Get session 9
        $arrContractExpire = [];
        if (session()->get('contract-expire')) {
            $arrContractExpire = session()->get('contract-expire');
        }
        //Get session temp
        $arrContractExpireTemp = [];
        if (session()->get('contract-expire-temp')) {
            $arrContractExpireTemp = session()->get('contract-expire-temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrContractExpireTemp, $arrContractExpire);
        //Unset phần tử
        unset($arrResult[$data['contract_code']]);
        //Lưu session temp mới
        session()->forget('contract-expire-temp');
        session()->put('contract-expire-temp', $arrResult);
        //Get session remove temp
        $arrRemoveContractExpireTemp = [];
        if (session()->get('remove_contract_expire')) {
            $arrRemoveContractExpireTemp = session()->get('remove_contract_expire');
        }
        //Lưu session remove temp
        $arrRemoveContractExpireTemp [] = $data['contract_code'];
        session()->forget('remove_contract_expire');
        session()->put('remove_contract_expire', $arrRemoveContractExpireTemp);
        return session()->get('contract-expire-temp');
    }

    public function chooseAllSoonExpireAction($data)
    {
        //Get session main
        $arrContractSoonExpire = [];
        if (session()->get('contract-soon-expire')) {
            $arrContractSoonExpire = session()->get('contract-soon-expire');
        }
        //Get session temp
        $arrContractSoonExpireTemp = [];
        if (session()->get('contract-soon-expire-temp')) {
            $arrContractSoonExpireTemp = session()->get('contract-soon-expire-temp');
        }
        //Merge những cái vừa check vào array temp (cái đã check)
        $arrContractSoonExpireNew = [];
        if (isset($data['arr_check']) && count($data['arr_check']) > 0) {
            foreach ($data['arr_check'] as $v) {
                $arrContractSoonExpireNew[$v['contract_code']] = [
                    'contract_code' => $v['contract_code'],
                ];
            }
        }
        //Merge 2 array temp + new
        $arrContractSoonExpireTempNew = array_merge($arrContractSoonExpireTemp, $arrContractSoonExpireNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrContractSoonExpireTempNew, $arrContractSoonExpire);
        //Lưu session temp mới
        session()->forget('contract-soon-expire-temp');
        session()->put('contract-soon-expire-temp', $arrResult);
        return session()->get('contract-soon-expire-temp');
    }

    public function chooseSoonExpireAction($data)
    {
        //Get session main
        $arrContractSoonExpire = [];
        if (session()->get('contract-soon-expire')) {
            $arrContractSoonExpire = session()->get('contract-soon-expire');
        }
        //Get session temp
        $arrContractSoonExpireTemp = [];
        if (session()->get('contract-soon-expire-temp')) {
            $arrContractSoonExpireTemp = session()->get('contract-soon-expire-temp');
        }
        //Merge vào array temp
        $arrContractSoonExpireNew = [
            $data['contract_code'] => [
                'contract_code' => $data['contract_code'],
            ]
        ];
        //Merge 2 array temp + new
        $arrContractSoonExpireTempNew = array_merge($arrContractSoonExpireTemp, $arrContractSoonExpireNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrContractSoonExpireTempNew, $arrContractSoonExpire);
        //Lưu session temp mới
        session()->forget('contract-soon-expire-temp');
        session()->put('contract-soon-expire-temp', $arrResult);
        return session()->get('contract-soon-expire-temp');
    }

    public function unChooseAllSoonExpireAction($data)
    {
        //Get session 9
        $arrContractSoonExpire = [];
        if (session()->get('contract-soon-expire')) {
            $arrContractSoonExpire = session()->get('contract-soon-expire');
        }
        //Get session temp
        $arrContractSoonExpireTemp = [];
        if (session()->get('contract-soon-expire-temp')) {
            $arrContractSoonExpireTemp = session()->get('contract-soon-expire-temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrContractSoonExpireTemp, $arrContractSoonExpire);
        $arrRemoveContractSoonExpireTemp = [];
        //Unset phần tử
        if (count($data['arr_un_check']) > 0) {
            foreach ($data['arr_un_check'] as $v) {
                $arrRemoveContractSoonExpireTemp [] = $v['contract_code'];
                unset($arrResult[$v['contract_code']]);
            }
        }
        //Lưu session temp mới
        session()->forget('contract-soon-expire-temp');
        session()->put('contract-soon-expire-temp', $arrResult);
        //Get session remove temp
        if (session()->get('remove_contract_soon-expire')) {
            $arrRemoveContractSoonExpireTemp = session()->get('remove_contract_soon-expire');
        }
        //Lưu session remove temp
        session()->forget('remove_contract_soon-expire');
        session()->put('remove_contract_soon-expire', $arrRemoveContractSoonExpireTemp);
        return session()->get('contract-soon-expire-temp');
    }

    public function unChooseSoonExpireAction($data)
    {//Get session 9
        $arrContractSoonExpire = [];
        if (session()->get('contract-soon-expire')) {
            $arrContractSoonExpire = session()->get('contract-soon-expire');
        }
        //Get session temp
        $arrContractSoonExpireTemp = [];
        if (session()->get('contract-soon-expire-temp')) {
            $arrContractSoonExpireTemp = session()->get('contract-soon-expire-temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrContractSoonExpireTemp, $arrContractSoonExpire);
        //Unset phần tử
        unset($arrResult[$data['contract_code']]);
        //Lưu session temp mới
        session()->forget('contract-soon-expire-temp');
        session()->put('contract-soon-expire-temp', $arrResult);
        //Get session remove temp
        $arrRemoveContractSoonExpireTemp = [];
        if (session()->get('remove_contract_soon-expire')) {
            $arrRemoveContractSoonExpireTemp = session()->get('remove_contract_soon-expire');
        }
        //Lưu session remove temp
        $arrRemoveContractSoonExpireTemp [] = $data['contract_code'];
        session()->forget('remove_contract_soon-expire');
        session()->put('remove_contract_soon-expire', $arrRemoveContractSoonExpireTemp);
        return session()->get('contract-soon-expire-temp');
    }

    public function dataViewPopup($input)
    {
        $mPipeline = new PipelineTable();
        $mStaff = new StaffsTable();
        $amount = 0;
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionStaff = $mStaff->getStaffOption();
        $type = isset($input['type']) ? $input['type'] : 'expire';
        if ($type == 'expire') {
            $amount = count(session()->get('contract-expire-temp'));
        } else {
            $amount = count(session()->get('contract-soon-expire-temp'));
        }
        $html = \View::make('contract::contract-care.pop.create-deal', [
            "optionPipeline" => $optionPipeline,
            "optionStaff" => $optionStaff,
            "amount" => $amount,
            "type" => $type
        ])->render();

        return [
            'html' => $html
        ];
    }

    public function submitCreateDeal($data)
    {
        try {
            DB::beginTransaction();
            $mCustomerDeal = new CustomerDealTable();
            $mDealDetail = new CustomerDealDetailTable();
            $mContract = new ContractTable();
            $mContractGoods = new ContractGoodsTable();
            $mContractPartner = new ContractPartnerTable();
            $mCustomer = new CustomerTable();

            $type = isset($data['type']) ? $data['type'] : 'expire';

            $dealTypeCode = '';
            $arrContract = [];

            if ($type == 'expire') {
                $arrContract = session()->get('contract-expire-temp');
                $dealTypeCode = "contract_expire";
            } else {
                $arrContract = session()->get('contract-soon-expire-temp');
                $dealTypeCode = "contract_soon_expire";
            }
            foreach ($arrContract as $key => $value) {
                $dataContract = $mContract->getInfoByCode($value['contract_code']);
                // update status contract care to process
                $this->contractCare->updateDataByContract([
                    'status' => 'in_care'
                ], $dataContract['contract_id']);
                // get info partner
                $dataPartner = $mContractPartner->getPartnerByContract($dataContract['contract_id']);
                $dataCustomer = null;
                if ($dataPartner['partner_object_type'] != 'supplier') {
                    $dataCustomer = $mCustomer->getInfoById($dataPartner['partner_object_id']);
                }
                $dataCreate = [
                    "deal_name" => $data['deal_name'],
                    "owner" => $data['staff'],
                    "pipeline_code" => $data['pipeline_code'],
                    "journey_code" => $data['journey_code'],
                    "closing_date" => Carbon::createFromFormat('d/m/Y', $data['end_date_expected'])->format('Y-m-d'),
                    "deal_type_code" => $dealTypeCode,
                    "deal_type_object_id" => $dataContract['contract_id'],
                    "type_customer" => 'customer',
                    "phone" => $dataPartner['customer_phone'],
                    "customer_code" => $dataCustomer != null ? $dataCustomer['customer_code'] : ''
                ];
                $dealId = $mCustomerDeal->add($dataCreate);
                // update deal_code
                $dealCode = 'DEALS_' . date('dmY') . sprintf("%02d", $dealId);
                $mCustomerDeal->edit($dealId, ['deal_code' => $dealCode]);
                // get goods of contract
                $dataGoods = $mContractGoods->getList($dataContract)->toArray();
                $totalAmount = 0;
                // insert goods of deal
                if (count($dataGoods) > 0) {
                    foreach ($dataGoods as $k => $v) {
                        $v['price'] = (float)str_replace(',', '', $v['price']);
                        $v['amount'] = (float)str_replace(',', '', $v['amount']);
                        $v['discount'] = (float)str_replace(',', '', $v['discount']);
                        $v['deal_code'] = $dealCode;
                        $v['created_by'] = Auth::id();
                        $dealDetailId = $mDealDetail->add($v);
                        $totalAmount += $v['amount'];
                    }
                }
                $mCustomerDeal->edit($dealId, ['amount' => $totalAmount]);
            }

            DB::commit();

            return [
                'error' => false,
                'message' => __('Tạo deal từ hợp đồng thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

}