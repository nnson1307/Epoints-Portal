<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/03/2018
 * Time: 2:40 PM
 */
namespace Modules\Admin\Repositories\OrderDeliveryStatus;

interface OrderDeliveryStatusRepositoryInterface
{

    public function list(array $filters = []);


    /**
     * Delete user
     *
     * @param number $id
     */
    public function remove($id);


    /**
     * Add user
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);

    public function edit(array $data,$id);

    public function getItem($id);

//
//    public function getListProvinceOptions();
//

}