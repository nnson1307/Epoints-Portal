<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServiceBranchPriceTable extends Model
{
    protected $table = 'service_branch_prices';
    protected $primaryKey = 'service_branch_price_id';
    public function getItemIdBranch($id, $branch)
    {
        $select = $this->select(
            'service_branch_prices.branch_id as branch_id',
            'service_branch_prices.service_branch_price_id as service_branch_price_id',
            'service_branch_prices.old_price as old_price',
            'service_branch_prices.new_price as new_price',
            'service_branch_prices.is_actived as is_actived',
            'service_branch_prices.created_at as created_at',
            'service_branch_prices.updated_at as updated_at',
            'service_branch_prices.created_by as created_by',
            'service_branch_prices.updated_by as updated_by',
            'branches.branch_name as branch_name',
            'service_branch_prices.service_id as branch_service_id',
            'services.service_name',
            'services.service_id',
            'services.service_avatar',
            'services.service_code'
        )
            ->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->where('service_branch_prices.service_id', $id)
            ->where('service_branch_prices.branch_id', $branch)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.is_actived', 1)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1);
        return $select->get();
    }

    /**
     * Lấy option dịch vụ theo chi nhánh
     *
     * @param $branch
     * @return mixed
     */
    public function getOptionService($branch)
    {
        $ds = $this
            ->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('services.service_name', 'services.service_id')
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.is_actived', 1)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1)
            ->where('services.is_surcharge', 0)
            ->groupBy('services.service_id');
        if (Auth::user()->is_admin != 1) {
            $ds->where('service_branch_prices.branch_id', $branch);
        }
        return $ds->get();
    }
}