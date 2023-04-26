<?php
namespace Modules\User\Repositories\User;


/**
 * User Repository interface
 *  
 * @author isc-daidp
 * @since Feb 23, 2018
 */
interface UserRepositoryInterface
{    
    /**
     * Get User list
     * 
     * @param array $filters
     */
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

    /**
     * get user
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * Chuyển sang trang đổi mk mới
     * @param $token
     * @return mixed
     */
    public function resetPassword($token);

    /**
     * Submit đổi mk mới
     * @param $params
     * @return mixed
     */
    public function submitNewPassword($params);
}