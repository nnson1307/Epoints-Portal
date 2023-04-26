<?php
namespace Modules\Admin\Repositories\ServiceType;

/**
 * User Repository interface
 *  
 * @author thach
 * @since   2018
 */
interface ServiceTypeRepositoryInterface
{    
    /**
     * Get Service Type list
     * 
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete Service Type
     * 
     * @param number $id
     */
    public function remove($id);
    /**
     * Add Service Type
     * @param array $data
     * @return number
     */
    public function add(array $data);
    public function edit(array $data, $id);

    public function save(array $data, $id);

    public function getItem($id);

    public function checkValueIsset($id, $params) ;
} 