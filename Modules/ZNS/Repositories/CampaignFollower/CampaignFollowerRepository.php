<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ZNS\Repositories\CampaignFollower;

use Carbon\Carbon;
use Modules\ZNS\Models\ZaloCampaignFollowerTable;
use Modules\ZNS\Models\CustomerTable;
use Modules\ZNS\Models\BranchTable;
use Modules\ZNS\Models\TemplateTable;
use Modules\ZNS\Models\ListParramsTable;
use Modules\ZNS\Models\ZaloCustomerCareTable;
use Modules\ZNS\Models\ZaloLogFollowerTable;


class CampaignFollowerRepository implements CampaignFollowerRepositoryInterface
{
    /**
     * @var ZNSTable
     */
    protected $campaign;
    protected $timestamps = true;

    public function __construct(ZaloCampaignFollowerTable $campaign)
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
            'template_option' => $mTemplateTable->getNameFollower(),
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
            $list_customer_send = app()->get(ZaloLogFollowerTable::class);
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
            'template_option' => $mTemplateTable->getNameFollower(),
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
                $mLogTable = app()->get(ZaloLogFollowerTable::class);

                $mLogTable->removeByZnsCampaignFollowerId($zns_campaign_id);
                foreach ($params['customer_id'] as $user_id) {
                    $data = [
                        'zalo_campaign_follower_id' => $zns_campaign_id,
                        'user_id' => $user_id,
                        'is_actived' => 0,
                        'time_sent' => $params['time_send'],
                        'created_by' => \Auth::id(),
                        'sent_by' => \Auth::id(),
                        'status' => 'new',
                        'template_id' => $params['zns_template_id'],
                    ];
                    $mLogTable->add($data);
                }
            }

            return [
                'status' => 1,
                'message' => __("Thêm chiến dịch thành công"),
            ];
        }
        return [
            'status' => 0,
            'message' => __("Thêm chiến dịch thất bại"),
        ];
    }

    public function cloneView($id)
    {
        $mBranchTable = app()->get(BranchTable::class);
        $branch = $mBranchTable->getName();
        $mTemplateTable = app()->get(TemplateTable::class);
        $item = $this->campaign->getItem($id);
        $list_customer_send = app()->get(ZaloLogFollowerTable::class);
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
            $zalo_campaign_follower_id = $this->campaign->duplicateRowWithNewId($id);
            $mLogTable = app()->get(ZaloLogFollowerTable::class);
            $list_log = $mLogTable->getItemByCampaignFollowerId($id);
            if($list_log){
                foreach ($list_log as $value){
                    unset($value['zalo_log_follower_id']);
                    $value['zalo_campaign_follower_id'] = $zalo_campaign_follower_id;
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
            'updated_by' => \Auth::id(),
            'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
            'status' => 'new',
        ];
        $zalo_campaign_follower_id = $params['zalo_campaign_follower_id'];
        unset($params['zns_campaign_follower_id']);
        unset($params['check_type']);
        $check = $this->campaign->edit($campaign, $zalo_campaign_follower_id);
        if ($check) {
            $mLogTable = app()->get(ZaloLogFollowerTable::class);
            $mLogTable->removeByZnsCampaignFollowerId($zalo_campaign_follower_id);
            if (isset($params['customer_id']) && $params['customer_id']) {
                foreach ($params['customer_id'] as $user_id) {
                    $data = [
                        'zalo_campaign_follower_id' => $zalo_campaign_follower_id,
                        'user_id' => $user_id,
                        'is_actived' => 0,
                        'time_sent' => $params['time_send'],
                        'created_by' => \Auth::id(),
                        'sent_by' => \Auth::id(),
                        'status' => 'new',
                        'template_id' => $params['zns_template_id'],
                    ];
                    $mLogTable->add($data);
                }
            }
            return [
                'status' => 1,
                'message' => __("Chỉnh sửa chiến dịch thành công"),
            ];
        }
        return [
            'status' => 0,
            'message' => __("Chỉnh sửa chiến dịch thất bại"),
        ];
    }

    public function showListCustomer($params)
    {
        $type = $params['type'];
        $data['is_filter'] = (isset($params['filter']) && $params['filter'] == 1) ? 1 : 0;

        if ($params['type'] == 'add-customer') {
            $keyword = isset($params['keyword']) ? $params['keyword'] : '';
            $id_customer_checked = isset($params['id_customer_checked']) && $params['id_customer_checked'] != "" ? explode(",",$params['id_customer_checked']) : [];

            $list_customer = $this->searchCustomerFollower($keyword,$id_customer_checked);
            $result = [];
            foreach ($list_customer as $item) {
                $result[] = [
                    'zalo_customer_care_id' => $item['zalo_customer_care_id'],
                    'full_name' => $item['full_name'],
                    'zalo_user_id' => $item['zalo_user_id'],
                    'avatar' => $item['avatar'],
                    'is_hide' => $item['is_hide'],
                    'is_checked' => 0
                ];
            }

            $data['html'] = view('zns::campaign_follower.modal.add-customer', [
                'list_customer' => $result,
                'params' => $params,
                'id_customer_checked' => $id_customer_checked,
            ])->render();

            $data['status'] = 1;
        }


        return $data;
    }

    public function searchCustomerFollower($data,$id_customer_checked)
    {
        $mZaloCustomerCareTable = app()->get(ZaloCustomerCareTable::class);
        return $mZaloCustomerCareTable->searchCustomerFollower($data,$id_customer_checked);
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
            $mZaloLogFollowerTable = app()->get(ZaloLogFollowerTable::class);
            $mZaloLogFollowerTable->removeByZnsCampaignId($id);
            return 1;
        }
        return 0;
    }
}