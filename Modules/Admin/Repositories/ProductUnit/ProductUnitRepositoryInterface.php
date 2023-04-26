<?php
namespace Modules\Admin\Repositories\ProductUnit;

/**
 * User Repository interface
 *  
 * @author thach
 * @since   2018
 */
interface ProductUnitRepositoryInterface
{
    public function list(array $filters =[]);


    /**
     * Delete ProductUnit
     *
     * @param number $id
     */
    public function remove($id);


    /**
     * add ProducUnit
     *
     * @param number $id
     */
    public function add(array $data);

    /**
     * getItem
     * @param $id
     * @return mixed
     */
    public function getItem($id);
}

