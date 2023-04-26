<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\Booking\Repositories\Branch;

use Modules\Booking\Models\BranchTable;


class BranchRepository implements BranchRepositoryInterface
{
    protected $branches;
    protected $timestamps = true;

    public function __construct(BranchTable $branch)
    {
        $this->branches = $branch;
    }

    public function getBranch(array $filters = [])
    {
        return $this->branches->getBranch($filters);
    }

    public function getListBrand(array $filters = [])
    {
        // TODO: Implement getListBrand() method.
        return $this->branches->getListBrand($filters);
    }
}