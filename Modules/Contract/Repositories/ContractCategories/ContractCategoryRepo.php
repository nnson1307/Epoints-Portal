<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 8/23/2021
 * Time: 10:09 AM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ContractCategories;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Admin\Models\RoleGroupTable;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractCategoryConfigStatusDefaultTable;
use Modules\Contract\Models\ContractCategoryConfigTabTable;
use Modules\Contract\Models\ContractCategoryFilesTable;
use Modules\Contract\Models\ContractCategoryRemindMapMethodTable;
use Modules\Contract\Models\ContractCategoryRemindMapReceiverTable;
use Modules\Contract\Models\ContractCategoryRemindTable;
use Modules\Contract\Models\ContractCategoryRemindTypeTable;
use Modules\Contract\Models\ContractCategoryStatusApproveTable;
use Modules\Contract\Models\ContractCategoryStatusNotifyTable;
use Modules\Contract\Models\ContractCategoryStatusTable;
use Modules\Contract\Models\ContractCategoryStatusUpdateTable;
use Modules\Contract\Models\ContractCategoryTabDefaultTable;

class ContractCategoryRepo implements ContractCategoryRepoInterface
{
    private $contractCategoryTable;
    private $contractCategoryFiles;
    public function __construct(ContractCategoriesTable $contractCategoriesTable,
                                ContractCategoryFilesTable $contractCategoryFiles)
    {
        $this->contractCategoryTable = $contractCategoriesTable;
        $this->contractCategoryFiles = $contractCategoryFiles;
    }

    /**
     * ds loại hợp đồng có/không filter
     *
     * @param array $filter
     * @return mixed
     */
    public function listContractCategory(array $filter = [])
    {
        return $this->contractCategoryTable->getList($filter);
    }

    /**
     * delete loại hợp đồng
     *
     * @param $id
     * @return mixed
     */
    public function deleteContractCategory($id)
    {
        $data = [
            'is_deleted' => 1
        ];
        return $this->contractCategoryTable->updateContractCategory($data, $id);
    }

    /**
     * save information contract category
     *
     * @param $data
     * @return array
     */
    public function submitCreateContractCategoryAction($data)
    {
        try{
            DB::beginTransaction();
            $dataInsertCC = [
//                'contract_category_code' => $data['contract_category_code'],
                'contract_category_name' => $data['contract_category_name'],
                'contract_code_format' => $data['contract_code_format'],
                'type' => $data['type'],
                'is_actived' => $data['is_actived'],
                'is_deleted' => 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            $contractCategoryId = $this->contractCategoryTable->createContractCategory($dataInsertCC);
            $this->contractCategoryTable->updateContractCategory([
                'contract_category_code' => 'LHD' . $contractCategoryId
            ], $contractCategoryId);
            if(isset($data['contract_category_list_files'])){
                $dataInsertCCF = [];
                foreach ($data['contract_category_list_files'] as $key => $item) {
                    $name = $data['contract_category_list_name_files'][$key];
                    $dataInsertCCF[] = [
                        'contract_category_id' => $contractCategoryId,
                        'link' => $item,
                        'name' => $name,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                }
                $this->contractCategoryFiles->insertListFile($dataInsertCCF);
            }
            DB::commit();
            return [
                'error' => false,
                'contract_category_id' => $contractCategoryId,
                'message' => __('Thêm thông tin chung loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'contract_category_id' => '',
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * View create contract category
     *
     * @return array
     */
    public function dataViewCreate()
    {
        $mCcTabDefault = new ContractCategoryTabDefaultTable();
        $mCcStatusDefault = new ContractCategoryConfigStatusDefaultTable();
        $mRoleGroup = new RoleGroupTable();
        $tabGeneral = $mCcTabDefault->getTabDefaultByType('general');
        $tabPartner = $mCcTabDefault->getTabDefaultByType('partner');
        $tabPayment = $mCcTabDefault->getTabDefaultByType('payment');
        $tabStatus = $mCcStatusDefault->getList();
        $lstRoleGroup = $mRoleGroup->getOptionActive();
        return [
            'tabGeneral' => $tabGeneral,
            'tabPartner' => $tabPartner,
            'tabPayment' => $tabPayment,
            'tabStatus' => $tabStatus,
            'lstRoleGroup' => $lstRoleGroup,
        ];
    }


    /**
     * save tab general, partner, payment by contract_category_id
     *
     * @param $data
     * @return array
     */
    public function submitCreateTabAction($data)
    {
        try{
            $type = 'add';
            if(isset($data['type']) != ''){
                $type = $data['type'];
                unset($data['type']);
            }
            $mCCCT = new ContractCategoryConfigTabTable();
            $mContractCategoryTabDefault = new ContractCategoryTabDefaultTable();
            DB::beginTransaction();
            if(isset($data['contract_category_id'])){
                if($data['contract_category_id'] == '' || $data['contract_category_id'] == 0){
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy loại hợp đồng, kiểm tra lưu loại hợp đồng!')
                    ];
                }
            }
            $ccID = $data['contract_category_id'];
            $tab = $data['tab'];
            $mCCCT->deleteConfigTab($ccID, $tab);
            $listTabDefault = $mContractCategoryTabDefault->getTabDefaultByType($tab);
            $dataInsertCCCT = [];
            if(count($listTabDefault) > 0){
                foreach ($listTabDefault as $key => $item) {
                    $dataInsertCCCT[] = [
                        'contract_category_id' => $ccID,
                        'tab' => $tab,
                        'key' => $item['key'],
                        'type' => $item['type'],
                        'key_name' => $item['key_name'],
                        'is_default' => 1,
                        'is_show' => $item['is_show'],
                        'is_validate' => $item['is_validate'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        "number_col" => $item['number_col']
                    ];
                }
            }
            if(isset($data['arrCustom'])){
                foreach ($data['arrCustom'] as $key => $item) {
                    $dataInsertCCCT[] = [
                        'contract_category_id' => $ccID,
                        'tab' => $tab,
                        'key' => $item['key'],
                        'type' => $item['type'],
                        'key_name' => $item['name'],
                        'is_default' => 0,
                        'is_show' => $item['is_show'],
                        'is_validate' => $item['is_validate'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        "number_col" => 4
                    ];
                }
            }
            $mCCCT->saveConfigTab($dataInsertCCCT);
            DB::commit();
            if($type == 'add'){
                return [
                    'error' => false,
                    'message' => __('Thêm thông tin loại hợp đồng thành công')
                ];
            }
            else{
                return [
                    'error' => false,
                    'message' => __('Chỉnh sửa thông tin loại hợp đồng thành công')
                ];
            }
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * save tab status by contract_category_id
     *
     * @param $data
     * @return array
     */
    public function submitStatusTabAction($data)
    {
        try{
            $mCcStatusUpdate = new ContractCategoryStatusUpdateTable();
            $mCcStatusApprove = new ContractCategoryStatusApproveTable();
            $mCcStatus = new ContractCategoryStatusTable();
            DB::beginTransaction();
            if(isset($data['contract_category_id'])){
                if($data['contract_category_id'] == '' || $data['contract_category_id'] == 0){
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy loại hợp đồng, kiểm tra lưu loại hợp đồng!')
                    ];
                }
            }
            $CCID = $data['contract_category_id'];
            if(isset($data['arrCustom']) && count($data['arrCustom']) > 0){
                $mCcStatus->deleteStatusTab($CCID);
                // save expect approve by, status update
                $arrStatus = [];
                $arrStatusCode = [];
                $arrStatusCodeByName = [];
                foreach ($data['arrCustom'] as $item) {
                    $statusCategory = [
                        'contract_category_id' => $CCID,
                        'status_name' => $item['status_name'],
                        'default_system' => $item['default_system'],
                        'is_approve' => $item['is_approve'],
                        'is_edit_contract' => $item['is_edit_contract'],
                        'is_deleted_contract' => $item['is_deleted_contract'],
                        'is_reason' => $item['is_reason'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    $statusID = $mCcStatus->createStatusTab($statusCategory);
                    $statusCode = 'STC_' . date('dmY') . $statusID;
                    $updateStatusCode = [
                        'status_code' => $statusCode
                    ];
                    $arrStatusCodeByName[$item['status_name']] = $statusCode;
                    $arrStatusCode[] = $statusCode;
                    $mCcStatus->updateStatusTab($updateStatusCode, $statusID);
                }
                // save approve by, status update
                foreach ($data['arrCustom'] as $key => $value) {
                    $dataInsertCodeUpdate = [];
                    $dataInsertCodeApprove = [];
                    if(isset($value['status_name_update']) && count($value['status_name_update']) > 0){
                        foreach ($value['status_name_update'] as $k => $v) {
                            $dataInsertCodeUpdate[] = [
                                'status_code' => $arrStatusCode[$key],
                                'status_code_update' => $arrStatusCodeByName[$v]
                            ];
                        }
                    }
                    if(isset($value['approve_by']) && count($value['approve_by']) > 0){
                        foreach ($value['approve_by'] as $k => $v) {
                            $dataInsertCodeApprove[] = [
                                'status_code' => $arrStatusCode[$key],
                                'approve_by' => $v
                            ];
                        }
                    }
                    $mCcStatusUpdate->insertStatusUpdate($dataInsertCodeUpdate);
                    $mCcStatusApprove->insertStatusApprove($dataInsertCodeApprove);
                }

            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Thêm trạng thái loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * return view add remind (popup)
     *
     * @param $data
     * @return array
     */
    public function getViewAddRemind($data)
    {
        $categoryId = $data['contract_category_id'];
        $mRemindType = new ContractCategoryRemindTypeTable();
        $mCcTabDefault = new ContractCategoryTabDefaultTable();
        $mCcConfigTab = new ContractCategoryConfigTabTable();
        $optionDateTabGeneral = $mCcConfigTab->getKeyNameByTypeData($categoryId, 'general', 'date');
        $optionReceiverBy = $mCcConfigTab->getKeyNameByKey($categoryId, 'general', '_by');
        $optionTabGeneral = $mCcConfigTab->getListKeyByTabAndCategory($categoryId, 'general');
        $optionRemindType = $mRemindType->getListRemindType();
        $html = \View::make('contract::contract-category.pop.create_remind',[
            'optionRemindType' => $optionRemindType,
            'optionDateTabGeneral' => $optionDateTabGeneral,
            'optionReceiverBy' => $optionReceiverBy,
            'optionTabGeneral' => $optionTabGeneral,
        ])->render();
        return [
            'html' => $html
        ];
    }

    /**
     * return popup edit remind
     *
     * @param $data
     * @return array
     */
    public function getViewEditRemind($data)
    {
        $categoryId = $data['contract_category_id'];
        $mCcRemind = new ContractCategoryRemindTable();
        $mReceiverMap = new ContractCategoryRemindMapReceiverTable();
        $mMethodMap = new ContractCategoryRemindMapMethodTable();
        $mRemindType = new ContractCategoryRemindTypeTable();
        $mCcTabDefault = new ContractCategoryTabDefaultTable();
        $mCcConfigTab = new ContractCategoryConfigTabTable();
        $optionDateTabGeneral = $mCcConfigTab->getKeyNameByTypeData($categoryId, 'general', 'date');
        $optionReceiverBy = $mCcConfigTab->getKeyNameByKey($categoryId, 'general', '_by');
        $optionTabGeneral = $mCcConfigTab->getListKeyByTabAndCategory($categoryId, 'general');
        $optionRemindType = $mRemindType->getListRemindType();
        $item = $mCcRemind->getItem($data['contract_category_remind_id']);
        $lstReceiver = $mReceiverMap->getReceiver($data['contract_category_remind_id']);
        $lstMethod = $mMethodMap->getMethod($data['contract_category_remind_id']);
        $lstReceiver = count($lstReceiver) > 0 ? $lstReceiver->pluck('receiver_by')->toArray() : [];
        $lstMethod = count($lstMethod) > 0 ? $lstMethod->pluck('remind_method')->toArray() : [];
        $html = \View::make('contract::contract-category.pop.edit_remind',[
            'optionRemindType' => $optionRemindType,
            'optionDateTabGeneral' => $optionDateTabGeneral,
            'optionReceiverBy' => $optionReceiverBy,
            'optionTabGeneral' => $optionTabGeneral,
            'item' => $item,
            'lstReceiver' => $lstReceiver,
            'lstMethod' => $lstMethod,
        ])->render();
        return [
            'html' => $html
        ];
    }

    /**
     * save 1 remind in category
     *
     * @param $data
     * @return array
     */
    public function submitRemindTabAction($data)
    {
        try{
            $mCcRemind = new ContractCategoryRemindTable();
            $mCCRemindReceiver = new ContractCategoryRemindMapReceiverTable();
            $mCCRemindMethod = new ContractCategoryRemindMapMethodTable();
            $mCcRemindType = new ContractCategoryRemindTypeTable();
            DB::beginTransaction();
            if(isset($data['contract_category_id'])){
                if($data['contract_category_id'] == '' || $data['contract_category_id'] == 0){
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy loại hợp đồng, kiểm tra lưu loại hợp đồng!')
                    ];
                }
            }
            $CCID = $data['contract_category_id'];
            $getLimitRemindType = $mCcRemindType->getItem($data['remind_type']);
            $dataRemind = $mCcRemind->getRemindByCategoryAndType($CCID, $data['remind_type']);
            if($getLimitRemindType['limit'] == 1 && $dataRemind != null){
                DB::rollback();
                return [
                    'error' => true,
                    'message' => __('Loại nhắc nhở này chỉ được tạo 1 lần')
                ];
            }
                // save expect receiver_by, remind_method
            $dataRemind = [
                'contract_category_id' => $CCID,
                'remind_type' => $data['remind_type'],
                'title' => $data['title'],
                'content' => $data['content'],
                'recipe' => $data['recipe'],
                'unit_value' => $data['unit_value'],
                'unit' => $data['unit'],
                'compare_unit' => $data['compare_unit'],
                'is_actived' => $data['is_actived'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            $remindId = $mCcRemind->createRemind($dataRemind);
            // save approve by, status update
            if(count($data['receiver_by']) > 0){
                $dataInsertRemindReceiver= [];
                foreach ($data['receiver_by'] as $key => $value) {
                    $dataInsertRemindReceiver[] = [
                        'contract_category_remind_id' => $remindId,
                        'receiver_by' => $value
                    ];
                }
                $mCCRemindReceiver->insertMapReceiver($dataInsertRemindReceiver);
            }
            if(count($data['remind_method']) > 0){
                $dataInsertRemindMethod= [];
                foreach ($data['remind_method'] as $key => $value) {
                    $dataInsertRemindMethod[] = [
                        'contract_category_remind_id' => $remindId,
                        'remind_method' => $value
                    ];
                }
                $mCCRemindMethod->insertMapReceiver($dataInsertRemindMethod);
            }
            DB::commit();
            return [
                'error' => false,
                'remind_id' => $remindId,
                'message' => __('Thêm nhắc nhở loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * edit 1 remind
     *
     * @param $data
     * @return array
     */
    public function submitEditRemindAction($data)
    {
        try{
            $mCcRemind = new ContractCategoryRemindTable();
            $mCCRemindReceiver = new ContractCategoryRemindMapReceiverTable();
            $mCCRemindMethod = new ContractCategoryRemindMapMethodTable();
            $mCcRemindType = new ContractCategoryRemindTypeTable();
            DB::beginTransaction();
            if(isset($data['contract_category_id'])){
                if($data['contract_category_id'] == '' || $data['contract_category_id'] == 0){
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy loại hợp đồng, kiểm tra lưu loại hợp đồng!')
                    ];
                }
            }
            $CCID = $data['contract_category_id'];
            $getLimitRemindType = $mCcRemindType->getItem($data['remind_type']);
            $dataRemind = $mCcRemind->getRemindByCategoryAndType($CCID, $data['remind_type'], $data['contract_category_remind_id']);
            if($getLimitRemindType['limit'] == 1 && $dataRemind != null){
                DB::rollback();
                return [
                    'error' => true,
                    'message' => __('Loại nhắc nhở này chỉ được tạo 1 lần')
                ];
            }
                // save expect receiver_by, remind_method
            $dataRemind = [
                'contract_category_id' => $CCID,
                'remind_type' => $data['remind_type'],
                'title' => $data['title'],
                'content' => $data['content'],
                'recipe' => $data['recipe'],
                'unit_value' => $data['unit_value'],
                'unit' => $data['unit'],
                'compare_unit' => $data['compare_unit'],
                'is_actived' => $data['is_actived'],
                'updated_by' => Auth::id()
            ];
            $remindId = $mCcRemind->updateRemind($dataRemind, $data['contract_category_remind_id']);
            // save approve by, status update
            if(count($data['receiver_by']) > 0){
                $dataInsertRemindReceiver= [];
                foreach ($data['receiver_by'] as $key => $value) {
                    $dataInsertRemindReceiver[] = [
                        'contract_category_remind_id' => $data['contract_category_remind_id'],
                        'receiver_by' => $value
                    ];
                }
                $mCCRemindReceiver->deleteMapReceiverByRemindId($data['contract_category_remind_id']);
                $mCCRemindReceiver->insertMapReceiver($dataInsertRemindReceiver);
            }
            if(count($data['remind_method']) > 0){
                $dataInsertRemindMethod= [];
                foreach ($data['remind_method'] as $key => $value) {
                    $dataInsertRemindMethod[] = [
                        'contract_category_remind_id' => $data['contract_category_remind_id'],
                        'remind_method' => $value
                    ];
                }
                $mCCRemindMethod->deleteMapReceiverByRemindId($data['contract_category_remind_id']);
                $mCCRemindMethod->insertMapReceiver($dataInsertRemindMethod);
            }
            DB::commit();
            return [
                'error' => false,
                'remind_id' => $remindId,
                'message' => __('Thêm nhắc nhở loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * remove 1 remind (update is_deleted)
     *
     * @param $data
     * @return array
     */
    public function removeRemindAction($data)
    {
        $mCcRemind = new ContractCategoryRemindTable();
        try{
            $updated = [
                'is_deleted' => 1,
            ];
            $mCcRemind->removeRemind($updated, $data['contract_category_remind_id']);
            return [
                'error' => false,
                'message' => __('Xoá nhắc nhở thành công')
            ];
        }
        catch (\Exception $ex){
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * Load list status of category to define notify
     *
     * @param $data
     * @return mixed
     */
    public function loadStatusNotify($data)
    {
        $mCcStatus = new ContractCategoryStatusTable();
        return $mCcStatus->getOptionByCategory($data['contract_category_id'])->toArray();
    }

    public function submitNotifyTabAction($data)
    {
        try{
            $mCcStatusNotify = new ContractCategoryStatusNotifyTable();
            DB::beginTransaction();
            if(isset($data['contract_category_id'])){
                if($data['contract_category_id'] == '' || $data['contract_category_id'] == 0){
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy loại hợp đồng, kiểm tra lưu loại hợp đồng!')
                    ];
                }
            }
            $CCID = $data['contract_category_id'];
            if(isset($data['arrCustom']) && count($data['arrCustom']) > 0){
                $mCcStatusNotify->deleteNotifyTab($CCID);
                $arrNotify = [];
                foreach ($data['arrCustom'] as $item) {
                    $notify = [
                        'contract_category_id' => $CCID,
                        'status_code' => $item['status_code'],
                        'content' => $item['content'],
                        'is_created_by' => $item['is_created_by'],
                        'is_performer_by' => $item['is_performer_by'],
                        'is_signer_by' => $item['is_signer_by'],
                        'is_follow_by' => $item['is_follow_by'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    $arrNotify[] = $notify;
                }
                $mCcStatusNotify->insertNotifyTab($arrNotify);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Thêm thông báo loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * render modal chang content notify
     *
     * @param $data
     * @return array
     */
    public function modalChangeContentNotify($data)
    {
        $content = $data['content'];
        $checkContractCode = str_contains($content, 'contract_code') ? 1 : 0;
        $checkStatusCode = str_contains($content, 'status_code') ? 1 : 0;
        $html = \View::make('contract::contract-category.pop.change_content_notify',[
            'status_code' => $data['status_code'],
            'content' => $content,
            'checkContractCode' => $checkContractCode,
            'checkStatusCode' => $checkStatusCode,
        ])->render();
        return [
            'html' => $html
        ];
    }

    /**
     * view edit
     *
     * @param $id
     * @return array
     */
    public function dataViewEdit($id)
    {
        $mCcTabDefault = new ContractCategoryTabDefaultTable();
        $mCcStatusDefault = new ContractCategoryConfigStatusDefaultTable();
        $mContractCategory = new ContractCategoriesTable();
        $mCcStatus = new ContractCategoryStatusTable();
        $mCCStatusUpdate = new ContractCategoryStatusUpdateTable();
        $mCcStatusApprove = new ContractCategoryStatusApproveTable();
        $mCcStatusNotify = new ContractCategoryStatusNotifyTable();
        $mCcRemind = new ContractCategoryRemindTable();
        $mCcRemindMethod = new ContractCategoryRemindMapMethodTable();
        $mCcRemindReceiver = new ContractCategoryRemindMapReceiverTable();
        $itemContractCategory = $mContractCategory->getItem($id);
        $mRoleGroup = new RoleGroupTable();
        $mCcConfigTab = new ContractCategoryConfigTabTable();
        $checkHaveGeneral = 1;

        $tabGeneral = $mCcConfigTab->getListKeyByTabAndCategory($id,'general');
        if(count($tabGeneral) == 0){
            $checkHaveGeneral = 0;
            $tabGeneral = $mCcTabDefault->getTabDefaultByType('general');
            foreach ($tabGeneral as $key => $value) {
                $tabGeneral[$key]['is_default'] = 1;
            }
        }
        $countSttGeneral = isset(array_count_values(array_column($tabGeneral, 'is_default'))[0]) ? array_count_values(array_column($tabGeneral, 'is_default'))[0] : 0;

        $tabPartner = $mCcConfigTab->getListKeyByTabAndCategory($id,'partner');
        if(count($tabPartner) == 0){
            $tabPartner = $mCcTabDefault->getTabDefaultByType('partner');
            foreach ($tabPartner as $key => $value) {
                $tabPartner[$key]['is_default'] = 1;
            }
        }
        $countSttPartner = isset(array_count_values(array_column($tabPartner, 'is_default'))[0]) ? array_count_values(array_column($tabPartner, 'is_default'))[0] : 0;

        $tabPayment = $mCcConfigTab->getListKeyByTabAndCategory($id,'payment');
        if(count($tabPayment) == 0){
            $tabPayment = $mCcTabDefault->getTabDefaultByType('payment');
            foreach ($tabPayment as $key => $value) {
                $tabPayment[$key]['is_default'] = 1;
            }
        }
        $countSttPayment = isset(array_count_values(array_column($tabPayment, 'is_default'))[0]) ? array_count_values(array_column($tabPayment, 'is_default'))[0] : 0;
        // get status
        $checkHaveStatus = 1;
        $tabStatus = $mCcStatus->getOptionByCategory($id);
        $arrStatusUpdate = [];
        $arrStatusApprove = [];
        if(count($tabStatus) > 0){
            foreach ($tabStatus as $item) {
                $lstStatusUpdate = $mCCStatusUpdate->getDetailStatusUpdate($item['status_code']);
                $lstStatusApprove = $mCcStatusApprove->getDetailStatusApprove($item['status_code']);
                $approve = [];
                $update = [];
                foreach ($lstStatusUpdate as $key => $value){
                    $update[] = [
                        'status_code_update' => $value['status_code_update'],
                        'status_name' => $value['status_name'],
                    ];
                }
                foreach ($lstStatusApprove as $key => $value){
                    $approve[] = $value['approve_by'];
                }
                $arrStatusUpdate[$item['status_code']] = $update;
                $arrStatusApprove[$item['status_code']] = $approve;
            }
        } else{
            $tabStatus = $mCcStatusDefault->getList();
            $checkHaveStatus = 0;
        }
        $lstRoleGroup = $mRoleGroup->getOptionActive();

        // get notify
        $lstNotify = $mCcStatusNotify->getListNotifyByCategory($id);

        // get remind
        $tabRemind = $mCcRemind->getRemindByCategory($id);
        $countRemind = count($tabRemind);
        $arrTabRemind = [];
        foreach ($tabRemind as $key => $value) {
            // get receiver
            $textReceiver = '';
            $dataReceiver = $mCcRemindReceiver->getReceiverHaveName($value['contract_category_remind_id']);
            if($dataReceiver != null){
                $textReceiver = $dataReceiver['receiver_name'];
                if(str_contains('created_by', $dataReceiver['receiver_by'])){
                    if($textReceiver != ''){
                        $textReceiver .= ',' . __('Người tạo');
                    }
                    else{
                        $textReceiver = __('Người tạo');
                    }
                }
                if(str_contains('updated_by', $dataReceiver['receiver_by'])){
                    if($textReceiver != ''){
                        $textReceiver .= ',' . __('Người cập nhật');
                    }
                    else{
                        $textReceiver = __('Người cập nhật');
                    }
                }
            }
            // get method
            $textMethod = '';
            $dataMethod = $mCcRemindMethod->getMethodGroupConcat($value['contract_category_remind_id']);
            if($dataMethod != null){
                $arrMethod = explode(',',$dataMethod['remind_method']);
                foreach ($arrMethod as $key1 => $value1) {
                   if($value1 == 'staff_notify'){
                       $textMethod .= __("Thông báo") . ($key1 + 1 != count($arrMethod) ? ',' : '');
                   }
                   if($value1 == 'email'){
                       $textMethod .= __("Email") . ($key1 + 1 != count($arrMethod) ? ',' : '');
                   }
                }
            }
            $recipeText = $value['recipe'] == '<' ? "Trước" : "";
            $unit_text = '';
            switch ($value['unit']){
                case 'D': $unit_text = __('Ngày'); break;
                case 'W': $unit_text = __('Tuần'); break;
                case 'M': $unit_text = __('Tháng'); break;
                case 'Q': $unit_text = __('Quý'); break;
                case 'Y': $unit_text = __('Năm'); break;
            }
            $stringTimeSend = $recipeText . ' ' . $value['unit_value'] . ' ' . $unit_text . ' ' . $value['compare_unit_text'];
            $itemRemind = [
              'remind_id' => $value['contract_category_remind_id'],
              'number_remind' => $countRemind - $key,
              'remind_type' => $value['remind_type_text'],
              'title' => $value['title'],
              'content' => $value['content'],
              'time_send' => $stringTimeSend,
              'receiver_by' => $textReceiver,
              'remind_method' => $textMethod,
              'is_actived' => $value['is_actived'],
            ];
            $arrTabRemind[] = $itemRemind;
        }

        return [
            'item' => $itemContractCategory,
            'contract_category_id' => $id,
            'checkHaveGeneral' => $checkHaveGeneral,
            'tabGeneral' => $tabGeneral,
            'countSttGeneral' => $countSttGeneral,
            'tabPartner' => $tabPartner,
            'countSttPartner' => $countSttPartner,
            'tabPayment' => $tabPayment,
            'countSttPayment' => $countSttPayment,
            'tabStatus' => $tabStatus,
            'checkHaveStatus' => $checkHaveStatus,
            'arrStatusUpdate' => $arrStatusUpdate,
            'arrStatusApprove' => $arrStatusApprove,
            'lstRoleGroup' => $lstRoleGroup,
            'lstNotify' => $lstNotify,
            'arrTabRemind' => $arrTabRemind,
            'countRemind' => $countRemind,
        ];
    }

    /**
     * save change contract category
     *
     * @param $data
     * @return array
     */
    public function submitEditContractCategoryAction($data)
    {
        try{
            DB::beginTransaction();
            $dataInsertCC = [
//                'contract_category_code' => $data['contract_category_code'],
                'contract_category_name' => $data['contract_category_name'],
                'contract_code_format' => $data['contract_code_format'],
                'type' => $data['type'],
                'is_actived' => $data['is_actived'],
                'is_deleted' => 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            $contractCategoryId =$data['contract_category_id'];
            $this->contractCategoryTable->updateContractCategory($dataInsertCC, $contractCategoryId);

            if(isset($data['contract_category_list_files'])){
                $dataInsertCCF = [];
                foreach ($data['contract_category_list_files'] as $key => $item) {
                    $name = $data['contract_category_list_name_files'][$key];
                    $dataInsertCCF[] = [
                        'contract_category_id' => $contractCategoryId,
                        'link' => $item,
                        'name' => $name,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                }
                $this->contractCategoryFiles->deleteFile($contractCategoryId);
                $this->contractCategoryFiles->insertListFile($dataInsertCCF);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa thông tin chung loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    /**
     * change status contract category
     *
     * @param $id
     * @return array
     */
    public function submitChangeStatusAction($data)
    {
        try{
            DB::beginTransaction();
            $dataInsertCC = [
                'is_actived' => $data['is_actived'],
                'updated_by' => Auth::id()
            ];
            $contractCategoryId =$data['contract_category_id'];
            $this->contractCategoryTable->updateContractCategory($dataInsertCC, $contractCategoryId);
            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa thông tin chung loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }

    public function submitEditStatusTabAction($data)
    {
        try{
            $mCcStatusUpdate = new ContractCategoryStatusUpdateTable();
            $mCcStatusApprove = new ContractCategoryStatusApproveTable();
            $mCcStatus = new ContractCategoryStatusTable();
            DB::beginTransaction();
            if(isset($data['contract_category_id'])){
                if($data['contract_category_id'] == '' || $data['contract_category_id'] == 0){
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy loại hợp đồng, kiểm tra lưu loại hợp đồng!')
                    ];
                }
            }
            $CCID = $data['contract_category_id'];
            if(isset($data['arrCustom']) && count($data['arrCustom']) > 0){
                $mCcStatus->deleteStatusTab($CCID);
                // save expect approve by, status update
                $arrStatus = [];
                $arrStatusCode = [];
                $arrStatusCodeByName = [];
                foreach ($data['arrCustom'] as $item) {
                    if(isset($item['status_code']) != ''){
                        $statusCategory = [
                            'contract_category_id' => $CCID,
                            'status_name' => $item['status_name'],
                            'status_code' => $item['status_code'],
                            'default_system' => $item['default_system'],
                            'is_approve' => $item['is_approve'],
                            'is_edit_contract' => $item['is_edit_contract'],
                            'is_deleted_contract' => $item['is_deleted_contract'],
                            'is_reason' => $item['is_reason'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $arrStatusCodeByName[$item['status_name']] = $item['status_code'];
                        $arrStatusCode[] = $item['status_code'];
                        $statusID = $mCcStatus->createStatusTab($statusCategory);
                    }
                    else{
                        $statusCategory = [
                            'contract_category_id' => $CCID,
                            'status_name' => $item['status_name'],
                            'default_system' => $item['default_system'],
                            'is_approve' => $item['is_approve'],
                            'is_edit_contract' => $item['is_edit_contract'],
                            'is_deleted_contract' => $item['is_deleted_contract'],
                            'is_reason' => $item['is_reason'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $statusID = $mCcStatus->createStatusTab($statusCategory);
                        $statusCode = 'STC_' . date('dmY') . $statusID;
                        $updateStatusCode = [
                            'status_code' => $statusCode
                        ];
                        $arrStatusCodeByName[$item['status_name']] = $statusCode;
                        $arrStatusCode[] = $statusCode;
                        $mCcStatus->updateStatusTab($updateStatusCode, $statusID);
                    }

                }
                // save approve by, status update
                foreach ($data['arrCustom'] as $key => $value) {
                    $dataInsertCodeUpdate = [];
                    $dataInsertCodeApprove = [];
                    $mCcStatusUpdate->deleteStatusCodeUpdate($arrStatusCode[$key]);
                    $mCcStatusApprove->deleteStatusCodeApprove($arrStatusCode[$key]);
                    if(isset($value['status_name_update']) && count($value['status_name_update']) > 0){
                        foreach ($value['status_name_update'] as $k => $v) {
                            $dataInsertCodeUpdate[] = [
                                'status_code' => $arrStatusCode[$key],
                                'status_code_update' => $arrStatusCodeByName[$v]
                            ];
                        }
                    }
                    if(isset($value['approve_by']) && count($value['approve_by']) > 0){
                        foreach ($value['approve_by'] as $k => $v) {
                            $dataInsertCodeApprove[] = [
                                'status_code' => $arrStatusCode[$key],
                                'approve_by' => $v
                            ];
                        }
                    }
                    $mCcStatusUpdate->insertStatusUpdate($dataInsertCodeUpdate);
                    $mCcStatusApprove->insertStatusApprove($dataInsertCodeApprove);
                }

            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa trạng thái loại hợp đồng thành công')
            ];
        }
        catch (\Exception $ex){
            DB::rollback();
            return [
                'error' => true,
                'message' => __($ex->getMessage())
            ];
        }
    }
}