<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/15/2021
 * Time: 3:32 PM
 * @author nhandt
 */

namespace Modules\Contract\Repositories\RoleData;


interface ContractRoleDataRepoInterface
{
    public function dataViewIndex();
    public function submitConfigAction($data);
}