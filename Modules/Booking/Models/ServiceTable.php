<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 12:45 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = "services";

    protected $fillable = ['service_id', 'service_name', 'slug',
        'service_category_id', 'service_code', 'service_avatar',
        'price_standard', 'is_sale', 'service_type', 'time',
        'have_material', 'description', 'created_by', 'updated_by',
        'created_at', 'updated_at', 'is_deleted', 'is_actived', 'detail_description'
    ];

    public function getService($filter)
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
            'service_id', 'service_name',
            'service_category_id', 'service_code',
            'service_avatar', 'price_standard', 'is_sale',
            'service_type', 'time', 'description', 'detail_description'
        )
            ->where('is_deleted', 0)
            ->where('is_actived', 1);
        if (isset($filter['service_category_id']) && $filter['service_category_id'] != null) {
            $select->whereIn("service_category_id", $filter['service_category_id']);
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getServiceList($filter)
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
            'service_id', 'service_name',
            'service_category_id', 'service_code',
            'service_avatar', 'price_standard', 'is_sale',
            'service_type', 'time', 'description', 'detail_description'
        )
            ->where('is_deleted', 0)
            ->where('is_actived', 1);
        if (isset($filter['service_category_id']) && $filter['service_category_id'] != null) {
            if ($filter['service_category_id'] != 'all'){
                $select->where("service_category_id", $filter['service_category_id']);
            }
        }
        $select->orderBy('updated_at', 'desc');
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getServiceDetail($id)
    {
        $select = $this->select(
            'service_id', 'service_name',
            'service_category_id', 'service_code',
            'service_avatar', 'price_standard', 'is_sale',
            'service_type', 'time', 'description', 'detail_description'
        )
            ->where('service_id', $id)
            ->where('is_deleted', 0)
            ->where('is_actived', 1);
        return $select->first();
    }

    public function getServiceDetailGroup($id)
    {
        $select = $this->select(
            'services.service_id', 'services.service_name',
            'services.service_category_id', 'services.service_code',
            'services.service_avatar', 'services.price_standard', 'services.is_sale',
            'services.service_type', 'services.time', 'services.description', 'services.detail_description','service_categories.name as service_category_name'
        )
            ->join('service_categories' , 'service_categories.service_category_id','services.service_category_id')
            ->where('services.service_id', $id)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1);
        return $select->first();
    }

    public function bookingGetService($filter)
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $select = $this->select(
            'services.service_id', 'services.service_name',
            'services.service_category_id', 'services.service_code',
            'services.service_avatar', 'services.is_sale',
            'services.service_type', 'services.time', 'services.description',
            'services.detail_description', 'service_branch_prices.old_price',
            'service_branch_prices.new_price', 'service_branch_prices.branch_id'
        )
            ->leftJoin('service_branch_prices', 'service_branch_prices.service_id', '=', 'services.service_id')
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1)
            ->where('service_branch_prices.is_actived', 1)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.branch_id', $filter['branch_id']);
        if (isset($filter['service_id']) && $filter['service_id'] != null) {
            $select->where('services.service_id', $filter['service_id']);
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function bookingGetAllService($filter)
    {
        $select = $this->select(
            'services.service_id', 'services.service_name',
            'services.service_category_id', 'services.service_code',
            'services.service_avatar', 'services.is_sale',
            'services.service_type', 'services.time', 'services.description',
            'services.detail_description', 'service_branch_prices.old_price',
            'service_branch_prices.new_price', 'service_branch_prices.branch_id'
        )
            ->leftJoin('service_branch_prices', 'service_branch_prices.service_id', '=', 'services.service_id')
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1)
            ->where('service_branch_prices.is_actived', 1)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.branch_id', $filter['branch_id']);
        return $select->get();
    }

}