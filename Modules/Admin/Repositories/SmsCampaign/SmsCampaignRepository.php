<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/30/2019
 * Time: 6:48 PM
 */

namespace Modules\Admin\Repositories\SmsCampaign;

use Modules\Admin\Models\SmsCampaignTable;
use Modules\Admin\Models\SmsDealDetailTable;
use Modules\Admin\Models\SmsDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\PipelineTable;

class SmsCampaignRepository implements SmsCampaignRepositoryInterface
{
    protected $smsCampaign;
    protected $timestamps = true;

    public function __construct(SmsCampaignTable $smsCampaign)
    {
        $this->smsCampaign = $smsCampaign;
    }

    public function list2()
    {
        return $this->smsCampaign->getlist();
    }

    public function add(array $data)
    {
        return $this->smsCampaign->add($data);
    }

    public function getOptionCustomerCare()
    {
        $data = $this->smsCampaign->getOptionCustomerCare();
        $array = [];
        foreach ($data as $key => $value) {
            $array[$value['campaign_id']] = $value['campaign_name'];
        }
        return $array;
    }

    public function remove($id)
    {
        return $this->smsCampaign->remove($id);
    }

    public function getItem($id)
    {
        return $this->smsCampaign->getItem($id);
    }

    //Dữ liệu danh sách chiến dịch.
    public function getListIndex()
    {
        return $this->smsCampaign->getListIndex();
    }

    public function getListCampaign(array $filter = [])
    {
        return $this->smsCampaign->getListCampaign($filter);
    }

    //Kiểm tra trùng tên chiến dịch.
    public function checkSlugName($slug,$id)
    {
        return $this->smsCampaign->checkSlugName($slug,$id);
    }

    public function edit(array $data, $id){
        return $this->smsCampaign->edit($data,$id);
    }

    public function popupCreateDeal($input)
    {
        $mPipeline = new PipelineTable();
        $optionPipeline = $mPipeline->getOption('DEAL');

        $html = \View::make('admin::marketing.sms.campaign.popup-create-deal', [
            "optionPipeline" => $optionPipeline,
        ])->render();

        return [
            'html' => $html
        ];
    }
    public function popupEditDeal($input)
    {
        $mPipeline = new PipelineTable();
        $mJourney = new JourneyTable();
        $mEmailDeal = new SmsDealTable();
        $mEmailDealDetail = new SmsDealDetailTable();
        $item = $mEmailDeal->getItem($input['campaign_id']);
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);
        $listObject = $mEmailDealDetail->getList($item['sms_deal_id']);


        $html = \View::make('admin::marketing.sms.campaign.popup-edit-deal', [
            "optionPipeline" => $optionPipeline,
            "optionJourney" => $optionJourney,
            "listObject" => $listObject,
            "item" => $item,
        ])->render();

        return [
            'html' => $html
        ];
    }

    public function searchCustomerLeadFilter($input){
        $mCustomerLead = new CustomerLeadTable();
        $data = $mCustomerLead->getListCustomerLeadCampaign($input);
        return $data;
    }
}