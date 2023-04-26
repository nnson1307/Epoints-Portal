<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/4/2020
 * Time: 2:55 PM
 */

namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class RatingLogTable extends Model
{
    use ListTableTrait;
    protected $table = "rating_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "object",
        "object_value",
        "rating_by",
        "rating_value",
        "comment",
        "created_at",
        "updated_at",
        "is_show"
    ];

    const PRODUCT = "product";
    const SERVICE = "service";
    const SERVICE_CARD = "service_card";
    const ARTICLE = "article";

    /**
     * Danh sách đánh giả của khách hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.id",
                "{$this->table}.object",
                "{$this->table}.object_value",
                "{$this->table}.rating_value",
                "{$this->table}.comment",
                "customers.full_name",
                "{$this->table}.created_at",
                "lh.customer_appointment_code",
                "sp.product_child_name as product_name",
                "news.title_vi",
                "km.code as voucher_code",
                "dv.service_name",
                "{$this->table}.is_show"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.rating_by")
            ->leftJoin("customer_appointments as lh", "lh.customer_appointment_id", "=", "{$this->table}.object_value")
            ->leftJoin("product_childs as sp", "sp.product_child_id", "=", "{$this->table}.object_value")
            ->leftJoin("news", "news.new_id", "=", "{$this->table}.object_value")
            ->leftJoin("vouchers as km", "km.voucher_id", "=", "{$this->table}.object_value")
            ->leftJoin("services as dv", "dv.service_id", "=", "{$this->table}.object_value")
            ->whereIn("{$this->table}.object", [self::PRODUCT, self::SERVICE, self::SERVICE_CARD, self::ARTICLE])
            ->orderBy("{$this->table}.id", "desc");

        //Filter tên khách hàng
        if (isset($filter["search"])) {
            $search = $filter["search"];
            $ds->where(function ($query) use ($search) {
                $query->where("customers.full_name", "like", "%" . $search . "%");
            });
        }

        //Filter tên ngày tạo
        if (isset($filter["created_at"])) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
            $ds->whereBetween("{$this->table}.created_at", [$startTime, $endTime]);
        }

        return $ds;
    }

    /**
     * Chỉnh sửa đánh giá KH
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("id", $id)->update($data);
    }
}