<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 8/23/2021
 * Time: 10:08 AM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\ContractCategories;


interface ContractCategoryRepoInterface
{
    public function listContractCategory(array $filter = []);
    public function deleteContractCategory($id);
    public function submitCreateContractCategoryAction($data);
    public function dataViewCreate();
    public function submitCreateTabAction($data);
    public function submitStatusTabAction($data);
    public function submitEditRemindAction($data);
    public function getViewAddRemind($data);
    public function getViewEditRemind($data);
    public function submitRemindTabAction($data);
    public function removeRemindAction($data);
    public function loadStatusNotify($data);
    public function submitNotifyTabAction($data);
    public function modalChangeContentNotify($data);
    public function dataViewEdit($id);
    public function submitEditContractCategoryAction($id);
    public function submitChangeStatusAction($data);
    public function submitEditStatusTabAction($data);
}