<?php

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\Auth;

class ServiceBranchPriceTable extends Model
{
    use ListTableTrait;
    protected $table = 'service_branch_prices';
    protected $primaryKey = 'service_branch_price_id';
    protected $fillable = [
        'service_branch_price_id', 'branch_id', 'service_id', 'old_price', 'new_price', 'is_actived', 'created_at',
        'updated_at', 'created_by', 'updated_by', 'is_deleted','price_week','price_month','price_year'
    ];

    protected function _getList()
    {
        return $this->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->leftJoin('service_categories', 'service_categories.service_category_id', '=', 'services.service_category_id')
            ->where('service_branch_prices.is_deleted', 0)
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.is_actived as is_actived',
                'service_branch_prices.created_at as created_at',
                'service_branch_prices.updated_at as updated_at',
                'service_branch_prices.created_by as created_by',
                'service_branch_prices.updated_by as updated_by',
                'service_branch_prices.service_id as service_id',
                'branches.branch_name as branch_name',
                'services.service_name as service_name',
                'services.price_standard as price_standard',
                'service_categories.name as name');
    }

    public function getListBr($filter, $id, array $listId = [])
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        $ds = self::leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
                'service_branch_prices.is_actived as is_actived',
                'service_branch_prices.created_at as created_at',
                'service_branch_prices.updated_at as updated_at',
                'service_branch_prices.created_by as created_by',
                'service_branch_prices.updated_by as updated_by',
                'branches.branch_name as branch_name')
            ->where('service_branch_prices.is_deleted', 0)
            ->where('branches.is_deleted', 0)
            ->where('service_branch_prices.service_id', $id);

        if ($listId != null) {
            $ds->whereIn('service_branch_prices.branch_id', $listId);
        }

        if (isset($filter["search_branch"]) && $filter["search_branch"] != "") {
            $ds->where("service_branch_prices.branch_id", $filter["search_branch"]);
        }
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function add(array $data)
    {

//        $data['branch_id']['is_actived'] = ($data['branch_id']['is_actived']) ? $data['branch_id']['is_actived'] : 0;
        $add = $this->create($data);
        return $add->service_branch_price_id;
    }

    public function updateOrCreate(array $data, array $add)
    {
        $a = $this->updateOrCreate($data, $add);
        return $a;
    }

    public function addWhenEdit(array $data)
    {

        $add = $this->create($data);
        return $add->service_branch_price_id;
    }

    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->updated(['is_deleted' => 1]);
    }

    public function edit(array $data, $id_sv)
    {
        return $this->where('service_branch_price_id', $id_sv)->update($data);
    }

    public function deleteByService($serviceId)
    {
        $this->where('service_id', $serviceId)->delete();
    }

    public function getItem($id)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
                'service_branch_prices.is_actived as is_actived',
                'service_branch_prices.created_at as created_at',
                'service_branch_prices.updated_at as updated_at',
                'service_branch_prices.created_by as created_by',
                'service_branch_prices.updated_by as updated_by',
                'branches.branch_name as branch_name',
                'service_branch_prices.service_id as branch_service_id')
            ->where('service_branch_prices.service_id', $id)
            ->where('branches.is_deleted', 0)
            ->where('service_branch_prices.is_deleted', 0)->get()->toArray();
        return $ds;
    }

    public function getItemBranch($branch, $categoryId, $search, $page)
    {
        $ds = $this
            ->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select(
                'service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
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
                'services.service_code',
                'services.is_surcharge'
            )
            ->where('service_branch_prices.branch_id', $branch)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.is_actived', 1)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1);

        //Filter nhóm dịch vụ
        if (isset($categoryId) && $categoryId != 'all') {
            $ds->where("services.service_category_id", $categoryId);
        }

        //Search
        if (isset($search) && $search != null) {
            $ds->where('services.service_name', 'like', '%' . $search . '%');
        }
        $page    = (int) ($page ?? 1);
        $display = (int) ($filters['perpage'] ?? 12);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);

    }

    public function getItemIdBranch($id, $branch)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
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
                'services.service_code')
            ->where('service_branch_prices.service_id', $id)
            ->where('service_branch_prices.branch_id', $branch)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.is_actived', 1)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1)
            ->get();
        return $ds;
    }

    public function getItemBranchSearch($search, $branch)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
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
                'services.service_code',
                'services.is_surcharge'
            )
            ->where('services.service_name', 'like', '%' . $search . '%')
            ->where('service_branch_prices.branch_id', $branch)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.is_actived', 1)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1)
            ->get();
        return $ds;
    }

    public function getItemEditSv($id_sv, $id_branch)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
                'service_branch_prices.is_actived as is_actived',
                'service_branch_prices.created_at as created_at',
                'service_branch_prices.updated_at as updated_at',
                'service_branch_prices.created_by as created_by',
                'service_branch_prices.updated_by as updated_by',
                'branches.branch_name as branch_name',
                'service_branch_prices.service_id as branch_service_id')
            ->where('service_branch_prices.service_id', $id_sv)
            ->and('service_branch_prices.branch_id', $id_branch)
            ->where('service_branch_prices.is_deleted', 0)->get();
        return $ds;
    }
    /**
     * @param array $filter
     * @return mixed
     */
//    public function getListBr(array $filter = [])
//    {
//        $select  = $this->_getListBr($filter);
//        $page    = (int) ($filter['page'] ?? 1);
//        $display = (int) ($filter['display'] ?? PAGING_ITEM_PER_PAGE);
//        // search term
//        if (!empty($filter['search_type']) && !empty($filter['search_keyword']))
//        {
//            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
//        }
//        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display']);
//
//        // filter list
//        foreach ($filter as $key => $val)
//        {
//            if (trim($val) == '') {
//                continue;
//            }
//
//            $select->where(str_replace('$', '.', $key), $val);
//        }
//
//        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
//    }

    /**
     * @param $id
     * @return mixed
     */
    public function getSelectBranch($id)
    {
        $list = $this->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select(
                'service_branch_prices.branch_id as branch_Id',
                'branches.branch_name as branch_Name'
            )->where('service_branch_prices.service_id', $id)->where('service_branch_prices.is_deleted', 0)->get()->toArray();
        return $list;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function deleteWhenEdit(array $data, $id)
    {
        $remove = $this->where('service_branch_prices.service_branch_price_id', $id)->update($data);
        return $remove;
    }

    public function getServiceBranchPrice()
    {
        $ds = $this->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->selectRaw('service_branch_prices.service_branch_price_id,
                        service_branch_prices.branch_id,
                        service_branch_prices.service_id,
                        service_branch_prices.new_price')
            ->get();
        return $ds;
    }

    public function getServiceBranchPriceByBranchId($id)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->leftJoin('service_categories', 'service_categories.service_category_id', '=', 'services.service_category_id')
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.branch_id', $id)
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
                'service_branch_prices.is_actived as is_actived',
                'service_branch_prices.service_id as service_id',
                'branches.branch_name as branch_name',
                'services.service_name as service_name',
                'services.price_standard as price_standard',
                'service_categories.name as name','price_week','price_month','price_year')->get();

        return $ds;
    }

    public function editConfigPrice(array $values,array $week,array $month,array $year, $branchId)
    {
        $check = $this->where('service_id', $values[0])
            ->where('branch_id', $branchId)
            ->first();
        if ($check != null) {
            $serviceBranchPrice = $this->where('service_id', $values[0])
                ->where('branch_id', $branchId)
                ->update([
                    'new_price' => $values[2],
                    'price_week' => $week[2],
                    'price_month' => $month[2],
                    'price_year' => $year[2],
                    'is_actived' => ($values[3] == 'true') ? 1 : 0,
                    'updated_by' => Auth::id(),
                    'is_deleted' => 0,
                ]);
        } else {
            if ($values[3] == 'true') {
                $serviceBranchPrice = $this->create([
                    'branch_id' => $branchId,
                    'service_id' => $values[0],
                    'old_price' => $values[1],
                    'new_price' => $values[2],
                    'price_week' => $week[2],
                    'price_month' => $month[2],
                    'price_year' => $year[2],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'is_actived' => 1,
                    'is_deleted' => 0,
                ]);
            }

        }

    }

    public function listPagingServiceDetail($id, &$filter = [])
    {
        $ds = $this->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select('service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
                'service_branch_prices.is_actived as is_actived',
                'service_branch_prices.created_at as created_at',
                'service_branch_prices.updated_at as updated_at',
                'service_branch_prices.created_by as created_by',
                'service_branch_prices.updated_by as updated_by',
                'service_branch_prices.service_id as service_id',
                'branches.branch_name as branch_name')
            ->where('branches.is_deleted', 0)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.service_id', $id);
        return $ds;
    }

    public function getListServiceDetail($id, array $filter = [])
    {
        $select = $this->listPagingServiceDetail($id, $filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display']);

        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

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

    /**
     * Lay chi tiet gia cua dich vu theo chi nhanh
     *
     * @param $branchId
     * @param $serviceId
     * @return mixed
     */
    public function getItemByBranchIdAndServiceId($branchId, $serviceId)
    {
        $ds = $this
            ->leftJoin('services', 'services.service_id', '=', 'service_branch_prices.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_branch_prices.branch_id')
            ->select(
                'service_branch_prices.branch_id as branch_id',
                'service_branch_prices.service_branch_price_id as service_branch_price_id',
                'service_branch_prices.old_price as old_price',
                'service_branch_prices.new_price as new_price',
                'service_branch_prices.price_week as price_week',
                'service_branch_prices.price_month as price_month',
                'service_branch_prices.price_year as price_year',
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
                'services.service_code',
                'service_branch_prices.price_week',
                'service_branch_prices.price_month',
                'service_branch_prices.price_year'
            )
            ->where('service_branch_prices.service_id', $serviceId)
            ->where('service_branch_prices.is_deleted', 0)
            ->where('service_branch_prices.is_actived', 1)
            ->where('services.is_deleted', 0)
            ->where('services.is_actived', 1)
            ->first();

        if (Auth::user()->is_admin != 1) {
            $ds->where('service_branch_prices.branch_id', $branchId);
        }

        return $ds;
    }

    /**
     * Lấy giá bán của mấy service
     *
     * @param $idBranch
     * @param null $search
     * @return mixed
     */
    public function getServicesPrice($idBranch, $search = null)
    {
        $oSelect = $this->select(
                        'sv.service_id',
                        'sv.service_name',
                        'sv.service_avatar',
                        "{$this->table}.new_price"
                    )
                    ->join('services as sv', function ($join) use ($idBranch) {
                        $join->on('sv.service_id', "{$this->table}.service_id")
                             ->where("{$this->table}.branch_id", $idBranch)
                             ->where("{$this->table}.is_actived", 1)
                             ->where("{$this->table}.is_deleted", 0);
                    })
                    ->where('sv.is_deleted', 0)
                    ->where('sv.is_actived', 1)
                    ->where('sv.is_surcharge', 0);

        if (! empty($search)) {
            $oSelect->where('sv.service_name', 'like', '%' . $search . '%');
        }

        return $oSelect->get();
    }
}