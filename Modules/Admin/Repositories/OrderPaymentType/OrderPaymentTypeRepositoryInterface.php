<?php
namespace Modules\Admin\Repositories\OrderPaymentType;

/**
 * Order Payment Type RepositoryInterface
 * @author thach
 * @since   2018
 */
interface OrderPaymentTypeRepositoryInterface
{
    /**
     * Get list item
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete
     *
     * @param number $id
     */
    public function remove($id);
    /**
     * Add
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);
    /**
     * Update OR ADD
     * @param array $data
     * @return number
     */
    public function save(array $data, $id);
    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

} 