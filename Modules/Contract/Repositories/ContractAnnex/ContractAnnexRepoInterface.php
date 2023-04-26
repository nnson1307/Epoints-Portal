<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:10 AM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\ContractAnnex;


interface ContractAnnexRepoInterface
{
    public function getPopupAddAnnex($data);
    public function submitSaveAnnex($data);
    public function actionContinueAnnex($data);
    public function getViewEditContractAnnex($data);
    public function submitEditContractAnnex($data);
    public function storeAnnexGood($input);
    public function submitUpdateAnnex($data);
    public function actionContinueUpdateAnnex($data);
    public function deleteAnnex($data);
    public function getDataViewDetail($contractAnnexId);
}