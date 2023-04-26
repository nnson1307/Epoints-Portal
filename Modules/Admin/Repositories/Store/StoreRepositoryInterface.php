<?php
namespace Modules\Admin\Repositories\Store;

use Illuminate\Http\Request;

interface StoreRepositoryInterface
{
    /**
     * Get Store list
     *
     * @param array $filters
     */
    public function list(array $filters=[]);

    /**
     * Add Store
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**

     * Delete Store
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit Store
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);
    public function getItem($id);

    public function getStoreOption() ;
    
    /**
     * Export Excel store
     *
     **/
    public function exportExcel(array $array,$title);

    /**
     * Import Excel store
     *
     **/

    public function uploadExcel(Request $request);

}