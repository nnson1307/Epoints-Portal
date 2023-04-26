<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 30/1/2019
 * Time: 09:41
 */

namespace Modules\Admin\Repositories\EmailCampaign;


interface EmailCampaignRepositoryInterface
{
    public function list(array $filters=[]);
    public function listNew();
    public function getLog();
    public function add(array $data);
    public function testName($name,$id);
    public function remove($id);
    public function getOption();
    public function getItem($id);
    public function edit(array $data,$id);
    public function getListCampaign(array $filter = []);
    public function popupCreateDeal($input);
    public function popupEditDeal($input);
    public function searchCustomerLeadFilter($input);
}