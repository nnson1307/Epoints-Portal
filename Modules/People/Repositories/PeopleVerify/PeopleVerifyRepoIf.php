<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\People\Repositories\PeopleVerify;


interface PeopleVerifyRepoIf
{
    /**
     * Danh sách nhóm đối tượng có phân trang
     *
     * @param array $param
     * @return mixed
     */
    public function getPaginate(array $param = []);



}