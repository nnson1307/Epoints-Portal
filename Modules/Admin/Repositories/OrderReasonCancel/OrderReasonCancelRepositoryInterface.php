<?php

namespace Modules\Admin\Repositories\OrderReasonCancel;
/**
 *Use Repository interface
 * @author ledangsinh
 * @since March 20, 2018
 */
interface OrderReasonCancelRepositoryInterface
{
    /**
     * Get order reason cancel list
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Get item
     * @param $id
     * @return array
     */
    public function getItem($id);

    /**
     * Add order reason cancel
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Remove order reason cancel
     * @param $id
     * @return number
     */
    public function remove($id);

    /**
     * Edit order reason cancel
     * @param array $data ,$id
     * @return number
     */
    public function edit(array $data, $id);

}