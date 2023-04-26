<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/29/2019
 * Time: 2:52 PM
 */

namespace Modules\Admin\Repositories\BrandName;


interface BrandNameRepositoryInterFace
{
    public function getOption();

    public function getItem($id);
}