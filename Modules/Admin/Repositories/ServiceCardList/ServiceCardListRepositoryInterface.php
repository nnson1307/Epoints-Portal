<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/16/2018
 * Time: 11:45 AM
 */

namespace Modules\Admin\Repositories\ServiceCardList;


interface ServiceCardListRepositoryInterface
{
    public function list($filter = []);

    public function add(array $data);

    public function edit(array $data, $id);

    public function getServiceCardListDetail($id);

    public function getAllByServiceCard($service_card_id, $filter = []);

    public function getUnuseCard($service_card_list_id, $branch_id);

    public function getInUseCard($filter = []);

    public function getCodeOrder($branch_id, $id);

    public function getAllServiceCardList();

    public function getAll();

    public function getByNameType($name, $type);

    public function searchCard($code);

    public function searchActiveCard($code, $branch);

    public function filterCardSold($cardType, $keyWord, $status, $branch, $staffActived, $startTime, $endTime);

    public function getDetailByCode($code);

    public function getItemDetailCustomer($id);

    public function getServiceCardListByOrderCode($orderCode);
}