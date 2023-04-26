<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 30/1/2019
 * Time: 09:48
 */

namespace Modules\Admin\Repositories\EmailCampaignDetail;


use Modules\Admin\Models\EmailCampaignDetailTable;

class EmailCampaignDetailRepository implements EmailCampaignDetailRepositoryInterface
{
    protected $email_campaign_detail;
    protected $timestamps = true;
    public function __construct(EmailCampaignDetailTable $email_campaign_details)
    {
        $this->email_campaign_detail=$email_campaign_details;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->email_campaign_detail->add($data);
    }
}