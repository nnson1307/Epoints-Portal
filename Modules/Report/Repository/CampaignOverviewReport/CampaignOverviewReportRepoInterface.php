<?php


namespace Modules\Report\Repository\CampaignOverviewReport;


interface CampaignOverviewReportRepoInterface
{
    public function filterAction($data);
    public function filterIIAction($data);

}