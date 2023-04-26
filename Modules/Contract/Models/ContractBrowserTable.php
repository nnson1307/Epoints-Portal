<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/11/2021
 * Time: 11:00
 */

namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ContractBrowserTable extends Model
{
    use ListTableTrait;
    protected $table = "contract_browse";
    protected $primaryKey = "contract_browse_id";
    protected $fillable = [
        "contract_browse_id",
        "contract_id",
        "status_code_now",
        "status_code_new",
        "request_by",
        "status",
        "reason_refuse",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Lấy ds HĐ cần duyệt
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.contract_browse_id",
                "{$this->table}.contract_id",
                "contracts.contract_category_id",
                "sf.full_name as request_name",
                "{$this->table}.status",
                "{$this->table}.status_code_now",
                "{$this->table}.status_code_new",
                "st_now.status_name as status_name_now",
                "st_new.status_name as status_name_new",
                "contracts.contract_name",
                "contracts.contract_no",
                "contracts.contract_code",
                "{$this->table}.created_at"
            )
            ->join("contracts", "contracts.contract_id", "=", "{$this->table}.contract_id")
            ->join("contract_category_status as st_now", "st_now.status_code", "=", "{$this->table}.status_code_now")
            ->join("contract_category_status as st_new", "st_new.status_code", "=", "{$this->table}.status_code_new")
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.request_by")
            ->where("contracts.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.contract_browse_id", "desc");

        //Filter thông tin tìm kiếm
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("contracts.contract_name", 'like', '%' . $search . '%')
                    ->orWhere("contracts.contract_code", 'like', '%' . $search . '%');
            });

            unset($filter['search']);
        }

        //Filter ngày yêu cầu
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            unset($filter["created_at"]);
        }

        return $ds;
    }

    /**
     * Thêm yêu cầu duyệt
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_browse_id;
    }

    /**
     * Chỉnh sửa yêu cầu duyệt
     *
     * @param array $data
     * @param $browserId
     * @return mixed
     */
    public function edit(array $data, $browserId)
    {
        return $this->where("contract_browse_id", $browserId)->update($data);
    }

    /**
     * Lấy thông tin yêu cầu duyệt
     *
     * @param $browserId
     * @return mixed
     */
    public function getInfo($browserId)
    {
        return $this
            ->select(
                "contract_browse_id",
                "contract_id",
                "status_code_now",
                "status_code_new",
                "request_by",
                "status",
                "updated_by"
            )
            ->where("contract_browse_id", $browserId)
            ->first();
    }
    public function getInfoByContract($contractId, $statusCode)
    {
        return $this
            ->select(
                "contract_browse_id",
                "contract_id",
                "status_code_now",
                "status_code_new",
                "request_by",
                "status",
                "updated_by"
            )
            ->where("contract_id", $contractId)
            ->where("status_code_now", $statusCode)
            ->first();
    }
}