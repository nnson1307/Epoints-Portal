<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\Admin\Repositories\Branch;
use Illuminate\Http\Request;

interface BranchRepositoryInterface
{
    /**
     * Get Store list
     *
     * @param array $filters
     */
    public function list(array $filters=[]);

    /**
     * Thêm chi nhánh
     *
     * @param $input
     * @return mixed
     */
    public function add($input);

    public function getBranch(array $listId = []);

    public function customerGroup();

    public function remove($id);

    /**
     * Chỉnh sửa chi nhánh
     *
     * @param $input
     * @return mixed
     */
    public function edit($input);
    public function getItem($id);
    public function getNameBranch($id);
    public function testName($name,$id);
    //search where in branch.
    public function searchWhereIn(array $branch);

    public function getBranchOption();

    public function changeStatus($data,$id);
}