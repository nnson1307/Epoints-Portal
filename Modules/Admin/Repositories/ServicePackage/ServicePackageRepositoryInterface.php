<?php
namespace Modules\Admin\Repositories\ServicePackage;

/**
 * User Repository interface
 *  
 * @author thach
 * @since   2018
 */
interface ServicePackageRepositoryInterface
{    
    /**
     * Get Admin list
     * 
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete Admin
     * 
     * @param number $id
     */
    public function remove($id);
    /**
     * Add Admin
     * @param array $data
     * @return number
     */
    public function add(array $data);
    public function edit(array $data, $id);

    public function save(array $data, $id);

    public function getItem($id);

} 