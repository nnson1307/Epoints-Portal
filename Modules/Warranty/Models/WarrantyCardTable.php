<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 11:26 AM
 */

namespace Modules\Warranty\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class WarrantyCardTable extends Model
{
    use ListTableTrait;
    protected $table = "warranty_card";
    protected $primaryKey = "warranty_card_id";
    protected $fillable = [
        'warranty_card_id',
        'warranty_card_code',
        'customer_code',
        'warranty_packed_code',
        'date_actived',
        'date_expired',
        'quota',
        'warranty_percent',
        'warranty_value',
        'description',
        'object_type',
        'object_type_id',
        'object_code',
        'object_serial',
        'object_note',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'order_code',
    ];

    const ACTIVE = 'actived';
    const FINISH = 'finish';

    public function _getList($filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.customer_code",
                "{$this->table}.warranty_packed_code",
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.quota",
                "{$this->table}.warranty_percent",
                "{$this->table}.warranty_value",
                "{$this->table}.description",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                "{$this->table}.object_note",
                "{$this->table}.created_at",
                "{$this->table}.status",
                "{$this->table}.order_code"
            )
            ->orderBy("{$this->table}.warranty_card_id", "desc");
        // filter mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('warranty_card_code', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $select;
    }

    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    public function edit($data, $warrantyCardId)
    {
        return $this->where('warranty_card_id', $warrantyCardId)->update($data);
    }

    /**
     * Cập nhật thẻ bảo hành theo code
     *
     * @param $data
     * @param $warrantyCardCode
     * @return mixed
     */
    public function editByCode($data, $warrantyCardCode)
    {
        return $this->where('warranty_card_code', $warrantyCardCode)->update($data);
    }

    /**
     * Lấy option phiếu bảo hành của khách hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function getWarrantyCard($filter = [])
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        $ds = $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.customer_code",
                "customers.full_name as customer_name",
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.quota",
                DB::raw("count(maintenance.warranty_code) as count_using"),
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                "{$this->table}.warranty_value",
                "{$this->table}.warranty_percent"
            )
            ->join("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin('maintenance', function ($join) {
                $join->on("maintenance.warranty_code", "=", "{$this->table}.warranty_card_code")
                    ->on("maintenance.status", "=", DB::raw("'finish'"));
            })
            ->where("{$this->table}.status", self::ACTIVE)
            ->where(function ($query) use ($dateNow) {
                $query->whereNull("{$this->table}.date_expired")
                    ->orWhereDate("{$this->table}.date_expired", ">=", $dateNow);
            })
            ->where("{$this->table}.customer_code", $filter['customer_code'])
            ->groupby("{$this->table}.warranty_card_code")
            ->orderBy("{$this->table}.warranty_card_id", "desc");

        if ($filter['object_type'] != null && $filter['object_type_id'] != null) {
            $ds->where("{$this->table}.object_type", $filter['object_type'])
                ->where("{$this->table}.object_type_id", $filter['object_type_id']);
        }

        //Filter keyword
        if (isset($filter['search_keyword']) && $filter['search_keyword'] != null) {
            $ds->where("{$this->table}.warranty_card_code", "LIKE", "%" . $filter['search_keyword'] . "%");
        }

        return $ds->get();
    }


    /**
     * Lấy thông tin phiếu bảo hành
     *
     * @param $warrantyCode
     * @return mixed
     */
    public function getInfo($warrantyCode)
    {
        return $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.customer_code",
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.quota",
                "customers.full_name as customer_name",
                "customers.phone1 as phone",
                "customers.address",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_price",
                "{$this->table}.object_serial",
                "{$this->table}.object_note",
                "{$this->table}.warranty_value",
                "{$this->table}.warranty_percent",
                "customers.customer_id",
                DB::raw("count(maintenance.warranty_code) as count_using")
            )
            ->join("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin('maintenance', function ($join) {
                $join->on("maintenance.warranty_code", "=", "{$this->table}.warranty_card_code")
                    ->on("maintenance.status", "=", DB::raw("'finish'"));
            })
            ->where("{$this->table}.warranty_card_code", $warrantyCode)
            ->groupby("{$this->table}.warranty_card_code")
            ->first();
    }

    /**
     * Lấy thông tin phiếu bảo hành điện tử theo id
     *
     * @param $warrantyCardId
     * @return mixed
     */
    public function getInfoById($warrantyCardId)
    {
        $select = $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.customer_code",
                "{$this->table}.warranty_packed_code",
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.quota",
                "{$this->table}.warranty_percent",
                "{$this->table}.warranty_value",
                "{$this->table}.description",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                "{$this->table}.object_note",
                "{$this->table}.created_at",
                "{$this->table}.status",
                "{$this->table}.order_code",
                "customers.full_name as customer_name",
                "customers.phone1 as customer_phone",
                "warranty_packed.packed_name",
                "warranty_packed.packed_code",
                "warranty_packed.warranty_packed_id as packed_id",
                DB::raw("count(maintenance.warranty_code) as count_using")
            )
            ->join("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->join("warranty_packed", "warranty_packed.packed_code", "=", "{$this->table}.warranty_packed_code")
            ->leftJoin('maintenance', function ($join) {
                $join->on("maintenance.warranty_code", "=", "{$this->table}.warranty_card_code")
                    ->on("maintenance.status", "=", DB::raw("'finish'"));
            })
            ->groupby("{$this->table}.warranty_card_id")
            ->where("{$this->table}.warranty_card_id", $warrantyCardId);
        return $select->first();
    }
}
