<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/30/2019
 * Time: 6:48 PM
 */

namespace Modules\Admin\Repositories\SmsCampaign;


interface SmsCampaignRepositoryInterface
{
    public function list2();

    public function add(array $data);

    public function getOptionCustomerCare();

    public function remove($id);

    public function getItem($id);

    //Dữ liệu danh sách chiến dịch.
    public function getListIndex();

    //Filter
    public function getListCampaign(array $filter = []);

    //Kiểm tra trùng tên chiến dịch.
    public function checkSlugName($slug,$id);

    public function edit(array $data, $id);
    public function popupCreateDeal($input);
    public function popupEditDeal($input);
    public function searchCustomerLeadFilter($input);
}