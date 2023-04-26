<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/16/2019
 * Time: 4:36 PM
 */

namespace Modules\Admin\Http\Controllers;


use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;

class StatisticalStaffController extends Controller
{
    protected $staff;

    public function __construct(
        StaffRepositoryInterface $staff
    )
    {
        $this->staff = $staff;
    }

    public function indexAction()
    {
        $staff = $this->staff->getStaffOption();
        return view('admin::statistical.staff', [
            'staff' => $staff
        ]);
    }

    public function chartIndexAction()
    {
    }

    public function filterAction()
    {
    }
}