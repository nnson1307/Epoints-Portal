<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/1/2019
 * Time: 14:01
 */

namespace Modules\Admin\Repositories\CustomerBranchMoney;


interface CustomerBranchMoneyRepositoryInterface
{
    public function add(array $data);
    public function edit(array $data,$id,$branch);
    public function getPriceBranch($id,$branch);
}