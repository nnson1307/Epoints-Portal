<?php

namespace Modules\Warranty\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceTable extends Model
{
    use ListTableTrait;
    protected $table = 'services';
    protected $primaryKey = 'service_id';
    protected $fillable = [
        'service_id', 'service_name', 'service_category_id', 'service_code', 'is_sale', 'price_standard', 'service_type',
        'time', 'have_material', 'description', 'created_by', 'updated_by', 'created_at', 'updated_at',
        'is_actived', 'detail_description', 'service_avatar', 'slug', 'type_refer_commission', 'refer_commission_value',
        'type_staff_commission', 'staff_commission_value'
    ];
    const NOT_SURCHARGE = 0; // Không phải dịch vụ phụ thu

    /**
     * Build query table
     * @return mixed
     */
    protected function _getList($filter = [])
    {
        $ds = $this
            ->leftJoin('service_categories as cate', 'cate.service_category_id', '=', 'services.service_category_id')
            ->leftJoin('service_materials as mate', function ($join) {
                $join->on('mate.service_id', '=', 'services.service_id')
                    ->where('mate.is_deleted', 0);
            })
            ->select
            (
                'services.service_id as service_id',
                'services.service_avatar as service_avatar',
                'services.service_name as service_name',
                'services.service_code as service_code',
                'services.time as time',
                'services.service_category_id as category_id',
                'services.is_actived as is_actived',
                'services.created_at as created_at',
                'services.updated_at as updated_at',
                'mate.service_id as service_id_mate',
                'cate.name as name',
                'services.price_standard',
                \DB::raw('IF(mate.is_deleted = "0", COUNT(mate.service_material_id), "0") as  number')
            )
            ->where('services.is_deleted', 0)
            ->groupBy('services.service_id')
            ->orderBy('services.service_id', 'desc');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('services.created_at', [$startTime, $endTime]);
        }
        if (isset($filter['search']) != '') {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('services.service_name', 'like', '%' . $search . '%')
                    ->orWhere('services.service_code', 'like', '%' . $search . '%')
                    ->where('services.is_deleted', 0);
            });

        }
        return $ds;
    }

    /**
     * Lấy thông tin dịch vụ
     *
     * @param $serviceCode
     * @return mixed
     */
    public function getService($serviceCode)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "price_standard"
            )
            ->where("service_code", $serviceCode)
            ->first();
    }


    /**
     * Lấy list dịch vụ ở phiếu bảo trì
     *
     * @param array $filter
     * @return mixed
     */
    public function getListService($filter = [])
    {
        $ds = $this
            ->leftJoin('service_categories as cate', 'cate.service_category_id', '=', 'services.service_category_id')
            ->leftJoin('service_materials as mate', function ($join) {
                $join->on('mate.service_id', '=', 'services.service_id')
                    ->where('mate.is_deleted', 0);
            })
            ->select
            (
                'services.service_id as service_id',
                'services.service_avatar as service_avatar',
                'services.service_name as service_name',
                'services.service_code as service_code',
                'services.time as time',
                'services.service_category_id as category_id',
                'services.is_actived as is_actived',
                'services.created_at as created_at',
                'services.updated_at as updated_at',
                'mate.service_id as service_id_mate',
                'cate.name as name',
                'services.price_standard',
                \DB::raw('IF(mate.is_deleted = "0", COUNT(mate.service_material_id), "0") as  number')
            )
            ->where('services.is_deleted', 0)
            ->where('services.is_surcharge', self::NOT_SURCHARGE) // Không phải dịch vụ phụ thu
            ->groupBy('services.service_id')
            ->orderBy('services.service_id', 'desc');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('services.created_at', [$startTime, $endTime]);
        }
        if (isset($filter['search']) != '') {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('services.service_name', 'like', '%' . $search . '%')
                    ->orWhere('services.service_code', 'like', '%' . $search . '%')
                    ->where('services.is_deleted', 0);
            });

        }

        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);

        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

}