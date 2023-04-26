<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 12:04 PM
 */

namespace Modules\Booking\Repositories\SpaInfo;


interface SpaInfoRepositoryInterface
{
    public function getItem($id);
    public function getIntroduction();

}