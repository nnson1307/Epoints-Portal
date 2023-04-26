<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/11/2021
 * Time: 14:16
 */

namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class RatingOrderLogTable extends Model
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

    const ORDER = "order";

    /**
     * Danh sách đánh giả đơn hàng của khách hàng
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
                "{$this->table}.created_at",
                "{$this->table}.is_show",
                "customers.full_name",
                "dh.order_code",
                DB::raw('count(rating_log_image.rating_log_image_id) as total_image')
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.rating_by")
            ->leftJoin("orders as dh", "dh.order_id", "=", "{$this->table}.object_value")
            ->leftJoin("rating_log_suggest", "rating_log_suggest.rating_log_id", "=", "{$this->table}.id")
            ->leftJoin("rating_log_image", "rating_log_image.rating_log_id", "=", "{$this->table}.id")
            ->where("{$this->table}.object", self::ORDER)
            ->groupBy("{$this->table}.id")
            ->orderBy("{$this->table}.id", "desc");

        //Filter tên khách hàng + mã đơn hàng
        if (isset($filter["search"])) {
            $search = $filter["search"];
            $ds->where(function ($query) use ($search) {
                $query->where("customers.full_name", "like", "%" . $search . "%")
                    ->orWhere("dh.order_code", "like", "%" . $search . "%");
            });
        }

        //Filter tên ngày tạo
        if (isset($filter["created_at"])) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            unset($filter["created_at"]);
        }

        //Filter có bình luận or hình ảnh/video
        if (isset($filter["check_rating"]) && $filter['check_rating'] == 'comment') {
            $ds->whereNotNull("{$this->table}.comment")
                ->where("{$this->table}.comment", "<>", "");
        } else if (isset($filter["check_rating"]) && $filter['check_rating'] == "image_video") {
            $ds->having(DB::raw('count(rating_log_image.rating_log_image_id)'), '>', 0);
        }

        unset($filter["check_rating"]);

        return $ds;
    }

    /**
     * Lấy thông tin đánh giá đơn hàng
     *
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        return $this
            ->select(
                "{$this->table}.id",
                "{$this->table}.object",
                "{$this->table}.object_value",
                "{$this->table}.rating_value",
                "{$this->table}.comment",
                "{$this->table}.created_at",
                "{$this->table}.is_show",
                "customers.full_name",
                "customers.phone1 as phone",
                "customers.email",
                "customers.customer_avatar",
                "dh.order_code",
                "dh.total",
                "dh.discount",
                "dh.amount",
                "dh.tranport_charge",
                "dh.discount_member",
                "dh.order_id"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.rating_by")
            ->leftJoin("orders as dh", "dh.order_id", "=", "{$this->table}.object_value")
            ->where("{$this->table}.id", $id)
            ->first();
    }
}