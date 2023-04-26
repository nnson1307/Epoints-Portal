<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/2/2021
 * Time: 3:02 PM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\ContractCare;


interface ContractCareRepoInterface
{
    public function getDataViewIndex(&$filter);
    public function getList(&$filter);
    public function chooseAllExpireAction($data);
    public function chooseExpireAction($data);
    public function unChooseAllExpireAction($data);
    public function unChooseExpireAction($data);
    public function chooseAllSoonExpireAction($data);
    public function chooseSoonExpireAction($data);
    public function unChooseAllSoonExpireAction($data);
    public function unChooseSoonExpireAction($data);
    public function dataViewPopup($input);
    public function submitCreateDeal($data);
}