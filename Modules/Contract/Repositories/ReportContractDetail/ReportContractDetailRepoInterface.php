<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/26/2021
 * Time: 9:20 AM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\ReportContractDetail;


interface ReportContractDetailRepoInterface
{
    public function getDataViewIndex($input);
    public function getListData($input);
    public function exportExcel($input);
}