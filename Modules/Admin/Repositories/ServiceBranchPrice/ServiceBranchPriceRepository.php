<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/13/2018
 * Time: 10:05 AM
 */

namespace Modules\Admin\Repositories\ServiceBranchPrice;

use Modules\Admin\Models\ServiceBranchPriceTable;

class ServiceBranchPriceRepository implements ServiceBranchPriceRepositoryInterface
{
    protected $service_branch_price;
    protected $timestamps = true;

    public function __construct(ServiceBranchPriceTable $service_branch_prices)
    {
        $this->service_branch_price = $service_branch_prices;
    }

    /**
     * Lấy danh sách giá dịch vụ chi nhánh
     *
     * @param array $filters
     * @return mixed
     */
    public function getList(array $filters = [])
    {
        return $this->service_branch_price->getList($filters);
    }

    public function list($filters = [], $id, array $listId = [])
    {
        return $this->service_branch_price->getListBr($filters, $id, $listId);
    }

    public function remove($id)
    {
        $this->service_branch_price->remove($id);
    }

    public function updateOrCreate(array $data, array $add)
    {
        // TODO: Implement updateOrCreate() method.
        return $this->service_branch_price->updateOrCreate($data, $add);
    }

    /**
     * add service_branch_price
     */
    public function add(array $data)
    {
        return $this->service_branch_price->add($data);
    }

    public function addWhenEdit(array $data)
    {
        return $this->service_branch_price->addWhenEdit($data);
    }

    public function deleteWhenEdit(array $data, $id)
    {
        $this->service_branch_price->deleteWhenEdit($data, $id);
    }

    /*
     * edit service_branch_price
     */
    public function edit(array $data, $id)
    {
        return $this->service_branch_price->edit($data, $id);
    }

    /*
     * Xoá dịch vụ theo service
     */
    public function deleteByService($serviceId)
    {
        return $this->service_branch_price->deleteByService($serviceId);
    }

    /*
     *  update or add
     */

    public function getItem($id)
    {
        return $this->service_branch_price->getItem($id);
    }

    public function getItemEditSv($id_sv, $id_branch)
    {
        // TODO: Implement getItemEditSv() method.
        return $this->getItemEditSv($id_sv, $id_branch);
    }

    public function getSelectBranch($id)
    {
        $get = $this->service_branch_price->getSelectBranch($id);
        $array = [];
        foreach ($get as $item) {
            $array[] = $item['branch_Id'];
        }
        return $array;
    }

    public function getServiceBranchPrice()
    {
        return $this->service_branch_price->getServiceBranchPrice();
    }

    public function getServiceBranchPriceByBranchId($id)
    {
        return $this->service_branch_price->getServiceBranchPriceByBranchId($id);
    }

    public function editConfigPrice(array $values,array $week,array $month,array $year, $branchId)
    {
         $this->service_branch_price->editConfigPrice($values,$week,$month,$year, $branchId);
    }

    public function getItemBranch($branch, $categoryId, $search, $page)
    {
        // TODO: Implement getItemBranch() method.
        return $this->service_branch_price->getItemBranch($branch, $categoryId, $search, $page);
    }

    public function getItemIdBranch($id, $branch)
    {
        // TODO: Implement getItemIdBranch() method.
        return $this->service_branch_price->getItemIdBranch($id, $branch);
    }

    public function getItemBranchSearch($search, $branch)
    {
        // TODO: Implement getItemBranchSearch() method.
        return $this->service_branch_price->getItemBranchSearch($search, $branch);
    }

    public function getListServiceDetail($id, array $filters = [])
    {
        // TODO: Implement getListServiceDetail() method.
        return $this->service_branch_price->getListServiceDetail($id, $filters);
    }
    public function getOptionService($branch)
    {
        $array = array();
        foreach ($this->service_branch_price->getOptionService($branch) as $item) {
            $array[$item['service_id']] = $item['service_name'];
        }
        return $array;
    }
//    public function getOptionServiceCategory()
//    {
//        $array=array();
//        foreach ($this->service_branch_price->getOptionServiceBranchPrice() as $item)
//        {
//            $array[$item['service_branch_price_id']]=$item['branch_id'];
//
//        }
//        return $array;
//    }

    // them ngay 18/9/2020
    /**
     * Lay chi tiet gia cua dich vu theo chi nhanh
     *
     * @param $branchId
     * @param $serviceId
     */
    public function getItemByBranchIdAndServiceId($branchId, $serviceId)
    {
        return $this->service_branch_price->getItemByBranchIdAndServiceId($branchId, $serviceId);
    }
}