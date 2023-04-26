<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepositoryInterface;
use Carbon\Carbon;
use Validator;

class TimeWorkingStaffsController extends Controller
{

    protected $repo;

    public function __construct(
        TimeWorkingStaffsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}
