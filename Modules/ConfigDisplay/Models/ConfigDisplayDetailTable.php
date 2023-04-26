<?php

/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:11 AM
 * @author nhandt
 */


namespace Modules\ConfigDisplay\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Modules\ConfigDisplay\Models\SurveyTable;
use Modules\ConfigDisplay\Models\ProductTable;
use Modules\ConfigDisplay\Models\PromotionMasterTable;
use Modules\ConfigDisplay\Models\ConfigCategoryDetailTable;
use Modules\ConfigDisplay\Models\NewTable;

class ConfigDisplayDetailTable extends Model
{

    protected $table = "config_display_detail";
    protected $primaryKey = "id_config_display_detail";
    protected $fillable = [
        "id_config_display_detail",
        "main_title",
        "sub_title",
        "action_name",
        "destination",
        "destination_detail",
        "position",
        "params_action",
        "image",
        "status",
        "id_config_display",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const POSITION_DEFAULT = 1;

    // start query //
    public function getListCore(array $filters = [])
    {
        $select = $this->select(
            "{$this->table}.id_config_display_detail",
            "{$this->table}.main_title",
            "{$this->table}.sub_title",
            "{$this->table}.action_name",
            "{$this->table}.destination",
            "{$this->table}.destination_detail",
            "{$this->table}.position",
            "{$this->table}.params_action",
            "{$this->table}.image",
            "{$this->table}.status",
            "{$this->table}.id_config_display",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            'sr.survey_name',
            'ccd.name as category_config_name',
            'ccd.key_destination as category_config_key',
            "pro.product_name",
            "prm.promotion_name",
            "news.title_vi"
        );
        if (!empty($filters['dateStart'])) {
            $select->whereDate("{$this->table}.created_at", Carbon::createFromFormat('d/m/Y', $filters['dateStart'])->format('Y-m-d'));
        }
        if (isset($filters['id'])) {
            $select->where("{$this->table}.id_config_display", $filters['id']);
        }


        $select->leftJoin("survey as sr", "sr.survey_id", '=', "{$this->table}.destination_detail")
            ->leftJoin("config_category_destination as ccd", "ccd.key_destination", '=', "{$this->table}.destination")
            ->leftJoin("products as pro", "pro.product_id", '=', "{$this->table}.destination_detail")
            ->leftJoin("promotion_master as prm", "prm.promotion_id", '=', "{$this->table}.destination_detail")
            ->leftJoin("news", "news.new_id", '=', "{$this->table}.destination_detail");
        $select->orderBy("{$this->table}.status", 'DESC');
        $select->orderBy("{$this->table}.position", 'ASC');
        return $select;
    }
    public function getListNew(array $filter = [])
    {
        $select = $this->getListCore($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filter['id']);
        unset($filter['dateStart']);
        unset($filter['perpage']);
        unset($filter['page']);
        if ($filter) {
            // filter list
            foreach ($filter as $key => $val) {
                if (trim($val) == '' || trim($val) == null) {
                    continue;
                }
                if (strpos($key, 'keyword_') !== false) {
                    $select->where(str_replace('$', '.', str_replace('keyword_', '', $key)), 'like', '%' . $val . '%');
                } elseif (strpos($key, 'sort_') !== false) {
                    $select->orderBy(str_replace('$', '.', str_replace('sort_', '', $key)), $val);
                } else {
                    $select->where(str_replace('$', '.', $key), $val);
                }
            }
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy vị trí hiển thị lớn nhất của item cấu hình hiển thị
     * @param int $id
     * @return mixed
     */
    public function getPositionMax($id)
    {
        return $this->where("id_config_display", $id)
            ->orderBy("position", 'DESC')
            ->select("position")
            ->first();
    }
    /**
     * lấy 1 record có vị trí nhỏ hơn hoặc bằng vị trí hiện tại
     * @param $id 
     * @param $position 
     * @return mixed
     */

    public function getPositionPre($id, $position)
    {
        return $this->where("id_config_display", $id)
            ->where("position", "<=", $position)
            ->whereNotNull("position")
            ->where("status", self::IS_ACTIVE)
            ->orderBy("position", "DESC")
            ->first();
    }

    /**
     * lấy tất cả các record có vị trí lớn hơn trừ chính nó
     * @param $idConfigDisplay
     * @param $idConfigDisplayDetail
     * @param $position
     * @return mixed
     */

    public function getPositionNextCondition(
        $idConfigDisplay,
        $idConfigDisplayDetail,
        $position
    ) {
        return $this->where("id_config_display", $idConfigDisplay)
            ->where("position", ">=", $position)
            ->where("status", self::IS_ACTIVE)
            ->where("id_config_display_detail", "<>", $idConfigDisplayDetail)
            ->orderBy("position", "ASC")
            ->get();
    }

    /**
     * lấy vị trí nhỏ hơn không phải chính nó
     * @param $idConfigDisplay
     * @param $idConfigDisplayDetail
     * @param $position
     * @return mixed
     */
    public function getPositionConditionFirst(
        $idConfigDisplay,
        $idConfigDisplayDetail,
        $position
    ) {
        return $this->where("id_config_display", $idConfigDisplay)
            ->where("position", "<", $position)
            ->whereNotNull("position")
            ->where("id_config_display_detail", "<>", $idConfigDisplayDetail)
            ->where("status", self::IS_ACTIVE)
            ->orderBy("position", "DESC")
            ->first();
    }

    /**
     * lấy tất record có vị trí nhỏ hơn hoặc bằng vị trí hiện tại
     * @param $id 
     * @param $position 
     * @return mixed
     */

    public function getPositionNext($id, $position)
    {
        return $this->where("id_config_display", $id)
            ->where("position", ">=", $position)
            ->where("status", self::IS_ACTIVE)
            ->orderBy("position", "ASC")
            ->get();
    }

    /**
     * Lấy item theo vị trí 
     * @param $id_config_display
     * @param $position
     * @param mixed
     */

    public function getItemByPosition($id_config_display, $position)
    {
        return $this->where("id_config_display", $id_config_display)
            ->where("position", $position)
            ->first();
    }

    // end query //

    // START ORM //

    public function survey()
    {
        return $this->belongsTo(SurveyTable::class, 'destination_detail');
    }

    public function categoryDestination()
    {
        return $this->belongsTo(ConfigCategoryDetailTable::class, 'destination', 'key_destination');
    }

    public function promotion()
    {
        return $this->belongsTo(PromotionMasterTable::class, 'destination_detail');
    }

    public function product()
    {
        return $this->belongsTo(ProductTable::class, 'destination_detail');
    }

    public function post()
    {
        return $this->belongsTo(NewTable::class, 'destination_detail');
    }
}
