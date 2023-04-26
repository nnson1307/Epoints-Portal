<?php


namespace Modules\BookingWeb\Repositories\Service;


interface ServiceRepositoryInterface
{
    public function list(array $data = []);
    public function getService(array $data= []);
    public function getServiceDetailGroup(array $data=[]);

}