<?php

namespace Modules\Admin\Repositories\ProductLabel;

/**
 * Product label repository interface
 *
 * @author ledangsinh
 * @since march 13, 2018
 */

interface ProductLabelRepositoryInterface
{
    /**
     * Get product label list
     *
     * @param array $filters
     */
    public function list(array $filters=[]);

    /**
     * Add product label
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Edit product label
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data,$id);

    /**
     * Remove product label
     * @param $id
     * @return number
     */
    public function remove($id);

    /**
     * Get item
     * @param $id
     * @return mixed
     */
    public function getItem($id);
}