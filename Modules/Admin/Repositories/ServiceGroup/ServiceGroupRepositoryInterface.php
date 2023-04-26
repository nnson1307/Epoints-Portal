<?php

namespace Modules\Admin\Repositories\ServiceGroup;

/**
 * User Repository interface
 *  
 * @author thach
 * @since   2018
 */
interface ServiceGroupRepositoryInterface
{    
    /**
     * get services group list
     * 
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete ServiceGroup
     * 
     * @param number $id
     */
    public function remove($id);
    /**
     * Add ServiceGroup
     * @param array $data
     * @return number
     */
    public function add(array $data);
   /*
    * update services group
    * @param array $data , $id
    * @return number
    */
    public function edit(array $data, $id);
    /*
    * update or add services group
    * @param array $data , $id
    * @return number
    */
    public function save(array $data, $id);
    public function getItem($id);
    public function checkValueIsset($id, $params);
} 