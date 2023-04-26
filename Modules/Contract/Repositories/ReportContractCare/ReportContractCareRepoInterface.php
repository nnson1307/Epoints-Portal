<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/23/2021
 * Time: 11:28 AM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\ReportContractCare;


interface ReportContractCareRepoInterface
{
    public function getDataViewIndex($input);
    public function getDepartment($input);
    public function getStaff($input);
    public function getChart($input);
}