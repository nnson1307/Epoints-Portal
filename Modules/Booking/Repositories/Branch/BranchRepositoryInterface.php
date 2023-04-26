<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\Booking\Repositories\Branch;

use Illuminate\Http\Request;

interface BranchRepositoryInterface
{
    public function getBranch(array $filters = []);
    public function getListBrand(array $filters = []);
}