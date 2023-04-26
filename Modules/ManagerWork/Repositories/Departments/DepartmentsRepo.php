<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\Departments;

use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Models\Customers;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManagerCommentTable;
use Modules\ManagerWork\Models\ManagerDocumentFileTable;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\ManagerWork\Models\ManageTagsTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Models\TypeWorkTable;

class DepartmentsRepo implements DepartmentsInterface
{
    protected $mDepartments;
    public function __construct(DepartmentTable $departmentTable){
        $this->mDepartments = $departmentTable;
    }
    public function getName(){
        return (collect($this->mDepartments->getAll())->pluck("department_name","department_id")->toArray());
    }

}