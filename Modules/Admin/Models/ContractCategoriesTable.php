<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 8/23/2021
 * Time: 10:11 AM
 * @author nhandt
 */


namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractCategoriesTable extends Model
{
    protected $table = 'contract_categories';
    protected $primaryKey = 'contract_category_id';
    protected $fillable = [
        "contract_category_id",
        "contract_category_code",
        "contract_category_name",
        "contract_code_format",
        "type",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVED = 1;
    const IS_DELETED = 0;

    use ListTableTrait;
    protected function _getList(&$filter = [])
    {
        $ds = $this->select(
            "{$this->table}.contract_category_id",
            "{$this->table}.contract_category_code",
            "{$this->table}.contract_category_name",
            "{$this->table}.contract_code_format",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("(GROUP_CONCAT(contract_category_files.link)) as contract_category_link_files"),
            DB::raw("(GROUP_CONCAT(contract_category_files.name)) as contract_category_name_files"))
            ->leftJoin('contract_category_files', 'contract_category_files.contract_category_id', '=', "{$this->table}.contract_category_id")
            ->where('is_deleted', self::IS_DELETED);
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.contract_category_name", 'like', '%' . $search . '%');
            });
            unset($filter['search']);
        }
        if (isset($filter['is_actived']) != "") {
            $ds->where("{$this->table}.is_actived", $filter['is_actived']);
            unset($filter['is_actived']);
        }
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter["created_at"]);
        }
        $ds->groupBy("{$this->table}.contract_category_id");
        return $ds->orderBy('created_at','desc');
    }

    /**
     * Update loại hợp đồng
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateContractCategory($data, $id)
    {
        return $this->where("contract_category_id", $id)->update($data);
    }

    /**
     * Lấy option loại HĐ
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "contract_category_id",
                "contract_category_code",
                "contract_category_name"
            )
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::IS_DELETED)
            ->get();
    }
    public function createContractCategory($data)
    {
        return $this->create($data)->contract_category_id;
    }
    public function getItem($id)
    {
        $ds = $this->select(
            "{$this->table}.contract_category_id",
            "{$this->table}.contract_category_code",
            "{$this->table}.contract_category_name",
            "{$this->table}.type",
            "{$this->table}.contract_code_format",
            "{$this->table}.type",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.created_by",
            "{$this->table}.updated_by",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("(GROUP_CONCAT(contract_category_files.link)) as contract_category_link_files"),
            DB::raw("(GROUP_CONCAT(contract_category_files.name)) as contract_category_name_files"))
            ->leftJoin('contract_category_files', 'contract_category_files.contract_category_id', '=', "{$this->table}.contract_category_id")
            ->where('is_deleted', self::IS_DELETED)
            ->where("{$this->table}.contract_category_id", $id);
        $ds->groupBy("contract_category_files.contract_category_id");
        return $ds->first();
    }
}