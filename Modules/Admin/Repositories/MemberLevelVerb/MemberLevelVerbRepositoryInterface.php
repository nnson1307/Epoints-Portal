<?php
/**
 * Created by PhpStorm.
 * User: Sinh
 * Date: 3/26/2018
 */

namespace Modules\Admin\Repositories\MemberLevelVerb;


interface MemberLevelVerbRepositoryInterface
{
    /**
     * Get member level verb list
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
     * Add member level verb
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Remove member level verb
     * @param $id
     * @return number
     */
   public function remove($id);

    /**
     * Edit member level verb
     * @param array $data ,$id
     * @return number
     */
    public function edit(array $data, $id);

    public function exportExcel(array $array, $title);

}