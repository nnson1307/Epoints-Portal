<?php


namespace Modules\FNB\Repositories\Branch;


use Modules\FNB\Models\BranchTable;

class BranchRepository implements BranchRepositoryInterface
{
    private $branch;

    public function __construct(BranchTable $branch)
    {
        $this->branch = $branch;
    }

    /**
     * Lấy danh sách branch
     * @return mixed
     */
    public function getAllBranch(){
        return $this->branch->getAll();
    }

    /**
     * Lấy danh sách chi nhánh có phân trang
     * @param $data
     * @return mixed|void
     */
    public function getAllBranchPagination(array $filter = [])
    {
        return $this->branch->getList($filter);
    }

    public function getBranch(array $listId = [])
    {

        $array = array();
        foreach ($this->branch->getBranch($listId) as $item) {
            $array[$item['branch_id']] = $item['branch_name'];

        }
        return $array;
    }

    public function getItem($id)
    {
        return $this->branch->getItem($id);
    }
}