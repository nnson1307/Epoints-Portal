<?php
namespace Modules\Admin\Repositories\Tax;

interface TaxRepositoryInterface
{
    /**
     * Get Tax list
     * @param array $filters
     */
    public function list(array $filters=[]);

    /**
     * Add Tax
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Delete Tax
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit Tax
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);
    public function getItem($id);
    /**
     * Export Tax
     **/
    public function export();
}