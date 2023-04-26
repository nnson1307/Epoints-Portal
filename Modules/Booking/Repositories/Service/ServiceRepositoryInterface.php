<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 4:23 PM
 */

namespace Modules\Booking\Repositories\Service;


interface ServiceRepositoryInterface
{
    public function getService(array $filter = []);

    public function getListService(array $filter = []);

    public function getServiceDetail($id);

    public function getServiceDetailGroup($id);

    public function bookingGetService(array $filter = []);

    public function bookingGetAllService(array $filter = []);
}