<?php

namespace Modules\ReportSale\Models;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CustomerGroupsTable extends Model
{
    protected $table = "customer_groups";
    protected $primaryKey = "customer_group_id";

    const NOT_DELETED = 0;

    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     */
    public function getOption($branchId = null)
    {

        $select = $this->select('customer_group_id', 'group_name')
            ->where("is_deleted", self::NOT_DELETED);

        return $select->get()->toArray();
    }
}