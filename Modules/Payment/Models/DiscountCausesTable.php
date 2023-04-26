<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 10:35 AM
 */

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;

class DiscountCausesTable extends Model
{
    use ListTableTrait;
    protected $table = "discount_causes";
    protected $primaryKey = "discount_causes_id";
    protected $fillable = [
        'discount_causes_id', 'discount_causes_name_vi', 'discount_causes_name_en','is_active','is_delete',
        'created_by', 'updated_by','created_at', 'updated_at'
    ];
    const IS_ACTIVE = 1;
    const IS_DELETE = 0;

    /**
     * Danh sách phiếu bảo hành điện tử
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this->select(
            "{$this->table}.discount_causes_id",
            "{$this->table}.discount_causes_name_vi",
            "{$this->table}.discount_causes_name_en",
            "{$this->table}.is_active")
            ->where("is_delete", self::IS_DELETE);
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.discount_causes_name_vi", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.discount_causes_name_en", 'like', '%' . $search . '%');
            });
        }
        return $ds;
    }

    /**
     * Thêm loại phí phát sinh
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->discount_causes_id;
    }

    /**
     * Lấy thông tin 1 loại phí phát sinh
     *
     * @param $discountCausesId
     * @return mixed
     */
    public function getInfo($discountCausesId)
    {
        return $this
            ->select(
                "{$this->table}.discount_causes_id",
                "{$this->table}.discount_causes_name_vi",
                "{$this->table}.discount_causes_name_en",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.discount_causes_id", $discountCausesId)
            ->first();
    }

    /**
     * Chỉnh sửa loại phí phát sinh
     *
     * @param array $data
     * @param $discountCausesId
     * @return mixed
     */
    public function edit(array $data, $discountCausesId)
    {
        return $this->where("discount_causes_id", $discountCausesId)->update($data);
    }
    /**
     * Lấy option lý do giảm giá
     *
     * @return mixed
     */
    public function getDiscountCauses()
    {
        $lang = Config::get('app.locale');

        return $this
            ->select(
                "discount_causes_id",
                "discount_causes_name_$lang as discount_causes_name"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->get();
    }

    /**
     * Xoá lý do giảm giá
     *
     * @param $discountCausesId
     * @return mixed
     */
    public function deleteDiscountCauses($discountCausesId)
    {
        return $this->where($this->primaryKey, $discountCausesId)->update(['is_delete' => 1]);
    }
}