<?php
namespace Modules\Admin\Repositories\ProductOrigin;

interface ProductOriginRepositoryInterface
{
    /**
     * Get Product Origin list
     *
     * @param array $filters
     */
    public function list(array $filters=[]);

    /**
     * Delete product origin
     *
     * @param number $id
     */
    public function remove($id);


    /**
     * Add product origin
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Edit product origin
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);
    public function getEdit($id);
}
