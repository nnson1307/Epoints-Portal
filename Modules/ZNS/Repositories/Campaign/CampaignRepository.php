<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ZNS\Repositories\Campaign;

use Carbon\Carbon;
use Modules\ZNS\Models\CampaignTable;
use Modules\ZNS\Models\CustomerLeadTable;
use Modules\ZNS\Models\CustomerSourceTable;
use Modules\ZNS\Models\PipelineTable;
use Modules\ZNS\Models\CustomerGroupDefineDetailTable;
use Modules\ZNS\Models\CustomerTable;
use Modules\ZNS\Models\BranchTable;
use Modules\ZNS\Models\TemplateTable;
use Modules\ZNS\Models\LogTable;
use Modules\ZNS\Models\ListParramsTable;


class CampaignRepository implements CampaignRepositoryInterface
{
    /**
     * @var ZNSTable
     */
    protected $campaign;
    protected $timestamps = true;

    public function __construct(CampaignTable $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return [
            'list' => $this->campaign->getList($filters),
            'filter' => $filters,
            'campaign_type' => $this->campaign_type(),
            'campaign_status' => $this->campaign_status(),
            'params' => $filters
        ];
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->campaign->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->campaign->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->campaign->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->campaign->getItem($id);
    }

    /*
     *  danh sách loại
     */
    public function campaign_type()
    {
        return [
            'zns' => __('ZNS Template API'),
            'follower' => __('ZNS Follower API'),
            'broadcast' => __('Broadcast')
        ];
    }

    /*
    *  danh sách status
    */
    public function campaign_status()
    {
        return [
            'new' => __('Đã lên lịch'),
            'sent' => __('Đã gửi')
        ];
    }


    public function addView($params)
    {
        $mBranchTable = new BranchTable();
        $branch = $mBranchTable->getName();
        $mTemplateTable = new TemplateTable();
        return [
            'campaign_type' => $this->campaign_type(),
            'campaign_status' => $this->campaign_status(),
            'branch' => $branch,
            'template_option' => $mTemplateTable->getName(),
            'params' => $params,
        ];
    }

    public function editView($id,$is_view = false)
    {
        $item = $this->campaign->getItem($id);
        if(($item->status == "new" && !($item->is_now == 1 && $item->is_actived == 1)) || $is_view == true){
            $mBranchTable = app()->get(BranchTable::class);
            $branch = $mBranchTable->getName();
            $mTemplateTable = app()->get(TemplateTable::class);
            $list_customer_send = app()->get(LogTable::class);
            $mListParamsTable = app()->get(ListParramsTable::class);
            $list_param = $mListParamsTable->getItemByZnsTemplateIdArray($item->zns_template_id);
            $list_param = array_column($list_param, null,'value');
        }else{
            return 0;
        }
        return [
            'campaign_type' => $this->campaign_type(),
            'campaign_status' => $this->campaign_status(),
            'branch' => $branch,
            'template_option' => $mTemplateTable->getName(),
            'id' => $id,
            'item' => $item,
            'list_param' => $list_param,
            'list_customer_send' => $list_customer_send->getCustomerListByCampaignId($id),
            'mess_send_success' => $list_customer_send->messSendSuccess($id),
        ];
    }


    public function addAction($params)
    {
        $input_params = [];
        if(isset($params['params']) && count($params['params'])){
            $input_params = $params['params'];
        }

        $campaign = [
            'is_actived' => isset($params['is_actived']) ? 1 : 0,
            'zns_template_id' => $params['zns_template_id'],
            'name' => $params['name'],
            'campaign_type' => $params['campaign_type'],
            'is_now' => isset($params['check_type']) && $params['check_type'] == 1 ? 1 : 0,
            'time_sent' => isset($params['check_type']) && $params['check_type'] == 1 ? Carbon::now()->format("Y-m-d H:i:s") : $params['time_send'],
            'branch_id' => $params['branch_id'],
            'params' => json_encode($input_params),
            'created_by' => \Auth::id(),
            'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
            'status' => 'new',
        ];
        $zns_campaign_id = $this->campaign->add($campaign);
        if ($zns_campaign_id) {
            if (isset($params['customer_id']) && $params['customer_id']) {
                $mLogTable = app()->get(LogTable::class);
                $mListParramsTable = app()->get(ListParramsTable::class);
                $usable_params = $mListParramsTable->getListParams($params['zns_template_id']);

                $mLogTable->removeByZnsCampaignId($zns_campaign_id);
                $type_customer = [
                    "lead",
                    "customer"
                ];
                foreach ($type_customer as $type) {
                    if (isset($params['customer_id'][$type])) {
                        foreach ($params['customer_id'][$type] as $user_id) {
                            $data_params = $this->returnParams($usable_params, $user_id,$input_params);
                            $data = [
                                'zns_campaign_id' => $zns_campaign_id,
                                'user_id' => $user_id,
                                'phone' => $params['phone_customer'][$type][$user_id],
                                'type_customer' => $params['type_customer'][$type][$user_id],
                                'is_actived' => 0,
                                'time_sent' => $params['time_send'],
                                'created_by' => \Auth::id(),
                                'sent_by' => \Auth::id(),
                                'status' => 'new',
                                'template_id' => $params['template_id'],
                                'params' => count($data_params) > 0 ? json_encode($data_params) : null
                            ];
                            $mLogTable->add($data);
                        }
                    }
                }
            }
        }
        return [
            'status' => 1
        ];
    }

    public function cloneView($id)
    {
        $mBranchTable = app()->get(BranchTable::class);
        $branch = $mBranchTable->getName();
        $mTemplateTable = app()->get(TemplateTable::class);
        $item = $this->campaign->getItem($id);
        $list_customer_send = app()->get(LogTable::class);
        return [
            'campaign_type' => $this->campaign_type(),
            'campaign_status' => $this->campaign_status(),
            'branch' => $branch,
            'template_option' => $mTemplateTable->getName(),
            'id' => $id,
            'item' => $item,
            'list_customer_send' => $list_customer_send->getCustomerListByCampaignId($id),
        ];
    }

    public function cloneAction($id)
    {
        if ($id) {
            $zns_campaign_id = $this->campaign->duplicateRowWithNewId($id);
            $mLogTable = app()->get(LogTable::class);
            $list_log = $mLogTable->getItemByCampaignId($id);
            if($list_log){
                foreach ($list_log as $value){
                    unset($value['id']);
                    $value['zns_campaign_id'] = $zns_campaign_id;
                    $value['status'] = 'new';
                    $mLogTable->add($value);
                }
            }
        }
    }

    public function editAction($params)
    {
        $input_params = [];
        if(isset($params['params']) && count($params['params'])){
            $input_params = $params['params'];
        }
        $campaign = [
            'is_actived' => isset($params['is_actived']) ? 1 : 0,
            'zns_template_id' => $params['zns_template_id'],
            'name' => $params['name'],
            'campaign_type' => $params['campaign_type'],
            'is_now' => isset($params['check_type']) && $params['check_type'] == 1 ? 1 : 0,
            'time_sent' => isset($params['check_type']) && $params['check_type'] == 1 ? Carbon::now()->format("Y-m-d H:i:s") : $params['time_send'],
            'branch_id' => $params['branch_id'],
            'params' => json_encode($input_params),
            'updated_by' => \Auth::id(),
            'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
            'status' => 'new',
        ];
        $zns_campaign_id = $params['zns_campaign_id'];
        unset($params['zns_campaign_id']);
//        unset($params['template_id']);
        unset($params['check_type']);
        $check = $this->campaign->edit($campaign, $zns_campaign_id);
        if ($check) {
            $mLogTable = app()->get(LogTable::class);
            $mLogTable->removeByZnsCampaignId($zns_campaign_id);
            if (isset($params['customer_id']) && $params['customer_id']) {
                $mListParramsTable = app()->get(ListParramsTable::class);
                $usable_params = $mListParramsTable->getListParams($params['zns_template_id']);
                $type_customer = [
                    "lead",
                    "customer"
                ];
                foreach ($type_customer as $type) {
                    if (isset($params['customer_id'][$type])) {
                        foreach ($params['customer_id'][$type] as $user_id) {
                            $data_params = $this->returnParams($usable_params, $user_id,$input_params);
                            $data = [
                                'zns_campaign_id' => $zns_campaign_id,
                                'user_id' => $user_id,
                                'phone' => $params['phone_customer'][$type][$user_id],
                                'type_customer' => $params['type_customer'][$type][$user_id],
                                'is_actived' => 0,
                                'time_sent' => $params['time_send'],
                                'created_by' => \Auth::id(),
                                'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                                'updated_by' => \Auth::id(),
                                'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                                'sent_by' => \Auth::id(),
                                'status' => 'new',
                                'template_id' => $params['template_id'],
                                'params' => count($data_params) > 0 ? json_encode($data_params) : null
                            ];
                            $mLogTable->add($data);
                        }
                    }
                }
            }

        }
        return [
            'status' => 1
        ];
    }

    public function showListCustomer($params)
    {
        // try{
        $type = $params['type'];
        $data['is_filter'] = (isset($params['filter']) && $params['filter'] == 1) ? 1 : 0;

        if ($params['type'] == 'add-group-potential') {
            $mCustomerLead = new CustomerLeadTable();

            $mCustomerSource = new CustomerSourceTable();
            $optionCustomerSource = $mCustomerSource->getOption();

            $mPipeline = new PipelineTable();
            $optionPipeline = $mPipeline->getOption('CUSTOMER');

            unset($params['type']);
            unset($params['filter']);
            $list_customer = $mCustomerLead->getListCustomerLeadCampaign($params);
            $data['html'] = view('zns::campaign.modal.' . $type, [
                'list_customer' => $list_customer,
                'params' => $params,
                'optionCustomerSources' => $optionCustomerSource,
                "optionPipeline" => $optionPipeline,
            ])->render();
            $data['status'] = 1;
        } elseif ($params['type'] == 'add-group-define') {
            $filterTypeGroup = isset($params['filter_type_group']) ? $params['filter_type_group'] : '';
            $customerGroupFilter = isset($params['customer_group_filter']) ? $params['customer_group_filter'] : '';
            $list_customer = $this->searchCustomerGroupFilter($filterTypeGroup, $customerGroupFilter);
            $result = [];
            foreach ($list_customer as $item) {
                $result[] = [
                    'full_name' => $item['full_name'],
                    'customer_id' => $item['customer_id'],
                    'phone' => $item['phone1'],
                    'birthday' => $item['birthday'] != null ? date("d/m/Y", strtotime($item['birthday'])) : null,
                    'gender' => $item['gender'],
                    'gender_name' => $item['gender'] == 'male' ? __('Nam') : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                    'branch_name' => $item['branch_name'],
                    'is_checked' => 0
                ];
            }
            $data['html'] = view('zns::campaign.modal.' . $type, [
                'list_customer' => $result,
                'params' => $params
            ])->render();
            $data['status'] = 1;

        } elseif ($params['type'] == 'add-customer') {

            $keyword = isset($params['keyword']) ? $params['keyword'] : '';
            $birthday = isset($params['birthday']) ? $params['birthday'] : '';
            $gender = isset($params['gender']) ? $params['gender'] : '';
            $branchId = isset($params['branch']) ? $params['branch'] : '';
            if ($birthday != '') {
                $birthdayFormat = Carbon::createFromFormat('d/m/Y', $birthday)->format('Y-m-d');
            } else {
                $birthdayFormat = null;
            }

            $list_customer = $this->searchCustomerPhoneEmail($keyword, $birthdayFormat, $gender, $branchId, [], []);

            $result = [];
            foreach ($list_customer as $item) {
                $result[] = [
                    'full_name' => $item['full_name'],
                    'customer_id' => $item['customer_id'],
                    'phone' => $item['phone1'],
                    'birthday' => $item['birthday'] != null ? date("d/m/Y", strtotime($item['birthday'])) : null,
                    'gender' => $item['gender'],
                    'gender_name' => $item['gender'] == 'male' ? __('Nam') : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                    'branch_name' => $item['branch_name'],
                    'is_checked' => 0
                ];
            }
            $mBranchTable = new BranchTable();
            $branch = $mBranchTable->getName();

            $data['html'] = view('zns::campaign.modal.' . $type, [
                'list_customer' => $result,
                'params' => $params,
                'branch' => $branch,
            ])->render();

            $data['status'] = 1;
        }


        return $data;
    }

    public function searchCustomerGroupFilter($filterTypeGroup = "", $customerGroupFilter = "")
    {
        $mCustomerGroupDefineDetail = new CustomerGroupDefineDetailTable();
        $mCustomerTable = new CustomerTable();
        $inArr = [];
        // function lấy ds các điều kiện, filter dựa
        if ($filterTypeGroup == 'user_define') {
            $data = $mCustomerGroupDefineDetail->getCustomerIdInGroup($customerGroupFilter);
            foreach ($data as $item) {
                $inArr[] = $item['customer_id'];
            }
        } elseif ($filterTypeGroup == 'auto') {
            $inArr = $this->customerGroupFilter->getCustomerInGroupAuto($customerGroupFilter);
        }
        $data = $mCustomerTable->getCustomerByArrCustomerId($inArr);
        return $data;
    }

    public function searchCustomerPhoneEmail($data, $birthday, $gender, $branch, $arrPhone = [], $arrEmail = [])
    {
        $mCustomerTable = new CustomerTable();
        return $mCustomerTable->searchCustomerPhoneEmail($data, $birthday, $gender, $branch, $arrPhone, $arrEmail);
    }

    public function returnParams($listParams, $userId,$input_params = [])
    {
        //Lấy thông tin
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $data = [];
        $arr = [
            'shipping_method',
            'payment_method',
            'number',
            'date',
            'name_spa',
            'product',
        ];
        foreach ($listParams as $v) {
            switch ($v) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
            }
            if(isset($input_params[$v])){
                $data[$v] = $input_params[$v];
            }
            if (!isset($data[$v])) {
                $data[$v] = "";
            }
        }
        return $data;
    }

    /**
     * @return ZNSTable
     */
    public function removeAction($id)
    {
        if ($this->campaign->remove($id)) {
            $mLogTable = app()->get(LogTable::class);
            $mLogTable->removeByZnsCampaignId($id);
            return 1;
        }
        return 0;
    }
}