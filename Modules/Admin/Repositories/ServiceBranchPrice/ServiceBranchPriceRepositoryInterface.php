<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/13/2018
 * Time: 10:05 AM
 */

namespace Modules\Admin\Repositories\ServiceBranchPrice;


interface ServiceBranchPriceRepositoryInterface
{
    public function list($filters = [], $id, array $listId = []);

    public function add(array $data);

    public function updateOrCreate(array $data, array $add);

    public function remove($id);

    public function edit(array $data, $id);

    public function deleteByService($serviceId);

    public function getItem($id);

    public function getItemEditSv($id_sv, $id_branch);

    public function getSelectBranch($id);

    public function addWhenEdit(array $data);

    public function deleteWhenEdit(array $data, $id);

    public function getList(array $filters = []);

    public function getServiceBranchPrice();

    public function getServiceBranchPriceByBranchId($id);

    public function editConfigPrice(array $values,array $week,array $month,array $year, $branchId);

    public function getItemBranch($branch, $categoryId, $search, $page);

    public function getItemIdBranch($id, $branch);

    public function getListServiceDetail($id, array $filters = []);

    public function getItemBranchSearch($search, $branch);

    public function getOptionService($branch);
//    public function getOptionServiceBranchPrice();

// them ngay 19/9/2020
    /**
     * Lay chi tiet gia cua dich vu theo chi nhanh
     *
     * @param $branchId
     * @param $serviceId
     * @return mixed
     */
    public function getItemByBranchIdAndServiceId($branchId, $serviceId);
}