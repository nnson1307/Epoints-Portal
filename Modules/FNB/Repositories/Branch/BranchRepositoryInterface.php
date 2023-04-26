<?php


namespace Modules\FNB\Repositories\Branch;


interface BranchRepositoryInterface
{
    public function getAllBranch();

    /**
     * lấy danh sách chi nhánh có phân trang
     * @param $data
     * @return mixed
     */
    public function getAllBranchPagination(array $filter = []);

    public function getBranch(array $listId = []);

    public function getItem($id);
}