<?php
/**
 * Services interface
 *
 * @author ledangsinh
 * @since march 28, 2018
 */

namespace Modules\Services\Repositories\Services;
use Illuminate\Http\Request;

interface ServicesRepositoryInterface
{
    /**
     * Get product label list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

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
    public function edit(array $data, $id);

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

    /**
     * Export excel
     * @param $type
     * @return mixed
     */
    public function exportExcel( array $array,$title);
    public function importExcelService(Request $request);
}