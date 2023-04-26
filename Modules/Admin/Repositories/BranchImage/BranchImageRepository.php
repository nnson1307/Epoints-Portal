<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 13/3/2019
 * Time: 18:16
 */

namespace Modules\Admin\Repositories\BranchImage;

use Modules\Admin\Models\BranchImageTable;

class BranchImageRepository implements BranchImageRepositoryInterface
{
    protected $branch_image;
    protected $timestamps = true;

    public function __construct(BranchImageTable $branch_images)
    {
        $this->branch_image = $branch_images;
    }

    public function add(array $data)
    {
        return $this->branch_image->add($data);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->branch_image->getItem($id);
    }
    public function remove($name)
    {
        return $this->branch_image->remove($name);
    }
}