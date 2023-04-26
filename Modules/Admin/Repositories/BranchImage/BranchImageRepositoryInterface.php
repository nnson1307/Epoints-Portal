<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 13/3/2019
 * Time: 18:16
 */

namespace Modules\Admin\Repositories\BranchImage;


interface BranchImageRepositoryInterface
{
    public function add(array $data);

    public function getItem($id);

    public function remove($id);
}