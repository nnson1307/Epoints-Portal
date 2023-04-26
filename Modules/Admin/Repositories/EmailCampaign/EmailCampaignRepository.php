<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 30/1/2019
 * Time: 09:41
 */

namespace Modules\Admin\Repositories\EmailCampaign;


use Modules\Admin\Models\EmailCampaignTable;
use Modules\Admin\Models\EmailDealDetailTable;
use Modules\Admin\Models\EmailDealTable;
use Modules\CustomerLead\Models\BranchTable;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\OrderSourceTable;
use Modules\CustomerLead\Models\OrderTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Models\TagTable;

class EmailCampaignRepository implements EmailCampaignRepositoryInterface
{
    protected $email_campaign;
    protected $timestamps = true;

    public function __construct(EmailCampaignTable $email_campaigns)
    {
        $this->email_campaign = $email_campaigns;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->email_campaign->getList($filters);
    }
    public function listNew()
    {
        // TODO: Implement listNew() method.
        return $this->email_campaign->getlist();
    }
    public function getLog()
    {
        // TODO: Implement getLog() method.
        return $this->email_campaign->getLog();
    }
    public function getListCampaign(array $filter = [])
    {
        // TODO: Implement getListCampaign() method.
        return $this->email_campaign->getListCampaign($filter);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->email_campaign->add($data);
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        // TODO: Implement testName() method.
        return $this->email_campaign->testName($name, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        // TODO: Implement remove() method.
        return $this->email_campaign->remove($id);
    }

    /**
     * @return array
     */
    public function getOption()
    {
        // TODO: Implement getOption() method.
        $array = array();
        foreach ($this->email_campaign->getOption() as $item) {
            $array[$item['campaign_id']] = $item['campaign_name'];

        }
        return $array;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->email_campaign->getItem($id);
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->email_campaign->edit($data, $id);
    }

    public function popupCreateDeal($input)
    {
        $mPipeline = new PipelineTable();
        $optionPipeline = $mPipeline->getOption('DEAL');

        $html = \View::make('admin::marketing.email.popup-create-deal', [
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
        $mEmailDeal = new EmailDealTable();
        $mEmailDealDetail = new EmailDealDetailTable();
        $item = $mEmailDeal->getItem($input['email_campaign_id']);
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);
        $listObject = $mEmailDealDetail->getList($item['email_deal_id']);


        $html = \View::make('admin::marketing.email.popup-edit-deal', [
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