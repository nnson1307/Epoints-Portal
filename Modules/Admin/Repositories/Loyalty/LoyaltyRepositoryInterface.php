<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 4:43 PM
 */

namespace Modules\Admin\Repositories\Loyalty;


interface LoyaltyRepositoryInterface
{
    public function plusPointEvent(array $data = []);
}