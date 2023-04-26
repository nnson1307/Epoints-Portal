<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/26/2021
 * Time: 3:07 PM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\ReportContractRevenue;


interface ReportContractRevenueRepoInterface
{
    public function getDataViewIndex($input);
    public function getChart($input);
    public function getListData($input);
    public function getListDataExport($input);
}