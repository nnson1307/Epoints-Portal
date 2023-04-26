<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\CustomerLead\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLeadTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead";
    protected $primaryKey = "customer_lead_id";
    protected $fillable = [
        "customer_lead_id",
        "customer_lead_code",
        'ch_customer_id',
        "full_name",
        "email",
        "phone",
        "gender",
        "birthday",
        "address",
        "avatar",
        "tag_id",
        "pipeline_code",
        "journey_code",
        "customer_type",
        "hotline",
        "fanpage",
        "zalo",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "tax_code",
        "representative",
        "employ_qty",
        "customer_source",
        "bussiness_id",
        "website",
        "business_clue",
        "is_convert",
        "convert_object_type",
        "convert_object_code",
        "assign_by",
        "sale_id",
        "business_id",
        "date_revoke",
        "province_id",
        "district_id",
        "deal_code",
        "custom_1",
        "custom_2",
        "custom_3",
        "custom_4",
        "custom_5",
        "custom_6",
        "custom_7",
        "custom_8",
        "custom_9",
        "custom_10",
        "number_row",
        "id_google_sheet",
        "allocation_date",
        "date_last_care"
    ];

    const NOT_DELETE = 0;
    const BUSINESS = "business";

    /**
     * Danh sách KH tiềm năng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.birthday",
                "{$this->table}.address",
                "{$this->table}.avatar",
                "{$this->table}.customer_type",
                "{$this->table}.created_at",
                "{$this->table}.is_convert",
                "{$this->table}.convert_object_type",
                "{$this->table}.convert_object_code",
                "{$this->table}.assign_by",
                "{$this->table}.sale_id",
                "{$this->table}.date_revoke",
                "{$this->table}.created_by",
                "{$this->table}.customer_source",
                "cpo_pipelines.pipeline_name",
                "cpo_journey.journey_name",
                "s.full_name as owner_name",
                "ss.full_name as sale_name",
                "cs.customer_source_name as customer_source_name",
                // DB::raw("(SELECT COUNT(*) FROM manage_work where manage_work.customer_id = {$this->table}.customer_lead_id and manage_work.manage_work_customer_type = 'lead' and manage_work.manage_status_id not in (6,7)) as total_work"),
                // DB::raw("GROUP_CONCAT(
                // CONCAT(DATE_FORMAT(cpo_customer_care.created_at,'%H:%i:%s %d/%m/%Y'),', ',
                //        CASE 
                //        WHEN cpo_customer_care.care_type = 'call' THEN '" . __('Gọi') . "'
                //        WHEN cpo_customer_care.care_type = 'chat' THEN '" . __('Trò chuyện') . "'
                //        ELSE '" . __('Email') . "' 
                //        END
                //        ,', ',
                //        cpo_customer_care.content) SEPARATOR '\n'
                // ) as content_care")
                );
        if (isset($filter['search']) && $filter['search'] != "") {
            $ds->leftJoin("cpo_customer_care", "cpo_customer_care.customer_lead_code", "{$this->table}.customer_lead_code");
        }
            
        $ds->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })

            //            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("staffs as s", "s.staff_id", "=", "cpo_pipelines.owner_id")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "{$this->table}.sale_id")
            ->leftJoin("customer_sources as cs", "cs.customer_source_id", "=", "{$this->table}.customer_source")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            //            ->where("{$this->table}.is_convert", 0)
            ->orderBy("{$this->table}.customer_lead_id", "desc")
            ->groupBy("{$this->table}.customer_lead_code");

        // phân quyền theo user
        //1. User là người tạo lead nếu lead đó chưa phân bổ cho ai
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $userLogin = Auth()->id();

                $query->whereRaw("{$this->table}.sale_id IS NULL and {$this->table}.created_by = $userLogin ")
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.assign_by",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
            unset($filter['user_id']);
        }

        // filter theo người tạo
        if (isset($filter['content']) && !empty($filter['content'])) {
            $ds->where("cpo_customer_care.content", $filter['content']);
            unset($filter['content']);
        }

        // filter theo người tạo
        if (isset($filter['created_by']) && !empty($filter['created_by'])) {
            $ds->where("{$this->table}.created_by", $filter['created_by']);
            unset($filter['created_by']);
        }

        // filter theo pipeline
        if (isset($filter['pipeline_code']) && !empty($filter['pipeline_code'])) {
            $ds->where("{$this->table}.pipeline_code", $filter['pipeline_code']);
            unset($filter['pipeline_code']);
        }

        // filter theo hành trình
        if (isset($filter['journey_code']) && !empty($filter['journey_code'])) {
            $ds->where("{$this->table}.journey_code", $filter['journey_code']);
            unset($filter['journey_code']);
        }

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_lead_code", 'like', '%' . $search . '%')
                    ->orWhere("cpo_customer_care.content", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        // filter trạng thái: đã phân công, chưa phân công
        if (isset($filter['assign']) &&  $filter['assign'] != "") {
            $assign = $filter['assign'];
            if ($assign == "assigned") {
                $ds->where("{$this->table}.sale_id", "<>", null);
            } elseif ($assign == "not_assign") {
                $ds->where("{$this->table}.sale_id", "=", null);
            }
            unset($filter['assign']);
        }
        // filter tag
        if (isset($filter["customer_tag"])) {
            $ds->whereIn("{$this->table}.customer_lead_code", $filter['customer_tag']);

            unset($filter['customer_tag']);
        }

        //filter người chăm sóc
        if (isset($filter['sale_id']) && !empty($filter['sale_id'])) {
            $ds->where("{$this->table}.sale_id", $filter['sale_id']);
            unset($filter['sale_id']);
        }

        //filter theo loại khách hàng
        if (isset($filter['customer_type']) && !empty($filter['customer_type'])) {
            $ds->where("{$this->table}.customer_type", $filter['customer_type']);
            unset($filter['customer_type']);
        }

        // filter tình trạng chuyển đổi
        if (isset($filter['is_convert']) && !empty($filter['is_convert'])) {
            $ds->where("{$this->table}.is_convert", $filter['is_convert']);
            unset($filter['is_convert']);
        }

        // filter ngày phân bổ
        if (isset($filter["allocation_date"]) && $filter["allocation_date"] != "") {
            $arr_filter = explode(" - ", $filter["allocation_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.allocation_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            unset($filter['allocation_date']);
        }

        return $ds;
    }

    /**
     * Thêm khách hàng tiềm năng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_lead_id;
    }

    /**
     * Lấy thông tin KH tiềm năng
     *
     * @param $customerLeadId
     * @return mixed
     */
    public function getInfo($customerLeadId)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.birthday",
                "{$this->table}.address",
                "{$this->table}.avatar",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.customer_type",
                "{$this->table}.hotline",
                "{$this->table}.fanpage",
                "{$this->table}.zalo",
                "{$this->table}.website",
                "{$this->table}.employ_qty",
                "cpo_journey.position as journey_position",
                "{$this->table}.tax_code",
                "staff.full_name as assign_by",
                "{$this->table}.representative",
                "{$this->table}.customer_source",
                "{$this->table}.business_clue",
                "{$this->table}.branch_code",
                "{$this->table}.is_convert",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.deal_code",
                "{$this->table}.note",
                "source.customer_source_name as source_name",
                "cpo_pipelines.owner_id",
                "cpo_pipelines.pipeline_name",
                "bussiness.name as business_name",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10",
                "{$this->table}.date_last_care",
                "{$this->table}.allocation_date",
                "{$this->table}.date_revoke",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
            )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("bussiness", "bussiness.id", "=", "{$this->table}.business_id")
            ->leftJoin("staffs as staff", "staff.staff_id", "=", "{$this->table}.assign_by")
            ->leftJoin("customer_sources as source", "source.customer_source_id", "=", "{$this->table}.customer_source")
            ->leftJoin("cpo_journey", "cpo_journey.journey_code", "=", "{$this->table}.journey_code")
            ->where("customer_lead_id", $customerLeadId)
            ->first();
    }

    /**
     * Chỉnh sửa KH tiềm năng
     *
     * @param array $data
     * @param $customerLeadId
     * @return mixed
     */
    public function edit(array $data, $customerLeadId)
    {
        return $this->where("customer_lead_id", $customerLeadId)->update($data);
    }
    public function updateByCode(array $data, $customerLeadCode)
    {
        return $this->where("customer_lead_code", $customerLeadCode)->update($data);
    }

    /**
     * Lấy thông tin KH tiềm năng theo pipeline
     *
     * @param array $filter
     * @return mixed
     */
    public function getCustomerByPipeline($filter = [])
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/backend/images/image-user.png';
        $manage_type_work_id = isset($filter['search_manage_type_work_id']) ? $filter['search_manage_type_work_id'] : null;
        $res = $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.note",
                "{$this->table}.customer_type",
                DB::raw("(CASE
                    WHEN  cpo_customer_lead.avatar = '' THEN '$imageDefault'
                    WHEN  cpo_customer_lead.avatar IS NULL THEN '$imageDefault'
                    ELSE  cpo_customer_lead.avatar
                    END
                ) as avatar"),
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.date_last_care",
                "{$this->table}.tag_id",
                "ss.full_name as sale_name",
                "care_type as manage_type_work_id"
                // 'manage_work.manage_type_work_id',
                // DB::raw("(SELECT COUNT(*) FROM manage_work where manage_work.customer_id = {$this->table}.customer_lead_id and manage_work.manage_work_customer_type = 'lead' and manage_work.manage_status_id not in (6,7)) as total_work")
            )
            ->leftJoin("cpo_customer_care", "cpo_customer_care.customer_lead_code", "{$this->table}.customer_lead_code")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "{$this->table}.sale_id")
            // ->leftJoin('manage_work', function ($sql) use ($manage_type_work_id) {
            //     $sql->on('manage_work.customer_id', $this->table . '.customer_lead_id')
            //         ->where('manage_work.manage_work_customer_type', 'lead');
            //     if ($manage_type_work_id != null) {
            //         $sql->where('manage_work.manage_type_work_id', $manage_type_work_id);
            //     }
            // })
            // ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->where("{$this->table}.pipeline_code", $filter['pipeline_code'])
            ->where("{$this->table}.is_convert", 0)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        // filter
        //        if ($userId != null) {
        //            $res->where("cpo_pipelines.owner_id", "=", $userId)
        //                ->orWhere("{$this->table}.assign_by",  "=", $userId);
        //        }

        // phân quyền theo user
        //1. User là người tạo lead nếu lead đó chưa phân bổ cho ai
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $res->where(function ($query) {
                $userLogin = Auth()->id();

                $query->whereRaw("{$this->table}.sale_id IS NULL and {$this->table}.created_by = $userLogin ")
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.assign_by",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
        }

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $res->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_lead_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%');
            });
        }

        //filter theo loại khách hàng
        if (isset($filter['customer_type']) && !empty($filter['customer_type'])) {
            $res->where("{$this->table}.customer_type", $filter['customer_type']);
        }

        if (isset($filter['select_manage_type_work_id']) && !empty($filter['select_manage_type_work_id'])) {

            $res->where("care_type", (int)$filter['select_manage_type_work_id']);
        }

        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $res->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        } else {
            $res->whereBetween("{$this->table}.created_at", [Carbon::now()->subMonths(1)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')]);
        }
        if (isset($filter["journey_code"]) &&  $filter["journey_code"] != "") {
            $res->where("journey_code", $filter["journey_code"]);
        }
        // $res->groupBy("{$this->table}.customer_lead_id")->orderBy("customer_lead_id", "desc");
        // $page    = (int) ($filters['page'] ?? 1);
        // $display = (int) ($filters['perpage'] ?? 10);
        // return $res->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        return $res->groupBy("{$this->table}.customer_lead_id")->orderBy("customer_lead_id", "desc")->get();
    }

    /**
     * Kiểm tra hành trình đã được sử dụng trong customer lead hay chưa
     *
     * @param $pipelineCode
     * @return mixed
     */
    public function checkJourneyBeUsed($pipelineCode)
    {
        return $this->select('pipeline_code')
            ->where('pipeline_code', $pipelineCode)
            ->where('is_deleted', 0)->first();
    }

    /**
     * Lấy option đầu mối doanh nghiệp
     *
     * @return mixed
     */
    public function getOptionBusiness()
    {
        return $this
            ->select(
                "customer_lead_id",
                "customer_lead_code",
                "full_name"
            )
            ->where("customer_type", self::BUSINESS)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Danh sách lead export excel
     *
     * @param $filter
     * @return mixed
     */
    public function getAllCustomerLead($filter)
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.birthday",
                "{$this->table}.address",
                "{$this->table}.avatar",
                "{$this->table}.customer_type",
                "{$this->table}.created_at",
                "{$this->table}.is_convert",
                "{$this->table}.convert_object_type",
                "{$this->table}.convert_object_code",
                "{$this->table}.branch_code",
                "{$this->table}.customer_source",
                "{$this->table}.business_clue",
                "{$this->table}.fanpage",
                "{$this->table}.zalo",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.hotline",
                "{$this->table}.assign_by",
                "{$this->table}.sale_id",
                "{$this->table}.date_revoke",
                "cpo_journey.journey_name",
                "customer_sources.customer_source_name",
                "s.full_name as assign_name",
                "ss.full_name as sale_name",
                "province.name as province_name",
                "district.name as district_name",
                DB::raw("GROUP_CONCAT(
                CONCAT(DATE_FORMAT(cpo_customer_care.created_at,'%H:%i:%s %d/%m/%Y'),', ',
                       CASE 
                       WHEN cpo_customer_care.care_type = 'call' THEN '" . __('Gọi') . "'
                       WHEN cpo_customer_care.care_type = 'chat' THEN '" . __('Trò chuyện') . "'
                       ELSE '" . __('Email') . "' 
                       END
                       ,', ',
                       cpo_customer_care.content) SEPARATOR '\n'
                ) as content_care")
            )
            //            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            //            ->join("cpo_journey", "cpo_journey.journey_code", "=", "{$this->table}.journey_code")
            ->leftJoin("cpo_customer_care", "cpo_customer_care.customer_lead_code", "{$this->table}.customer_lead_code")
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->join("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources", "customer_sources.customer_source_id", "=", "{$this->table}.customer_source")
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.assign_by")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "{$this->table}.sale_id")
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            //            ->where("{$this->table}.is_convert", 0)
            ->orderBy("{$this->table}.customer_lead_id", "desc")
            ->groupBy("{$this->table}.customer_lead_code");

        // phân quyền theo user
        //1. User là người tạo lead nếu lead đó chưa phân bổ cho ai
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $userLogin = Auth()->id();

                $query->whereRaw("{$this->table}.sale_id IS NULL and {$this->table}.created_by = $userLogin ")
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.assign_by",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
        }

        // filter theo người tạo
        if (isset($filter['created_by']) && !empty($filter['created_by'])) {
            $ds->where("{$this->table}.created_by", $filter['created_by']);
        }

        // filter theo pipeline
        if (isset($filter['pipeline_code']) && !empty($filter['pipeline_code'])) {
            $ds->where("{$this->table}.pipeline_code", $filter['pipeline_code']);
        }

        // filter theo hành trình
        if (isset($filter['journey_code']) && !empty($filter['journey_code'])) {
            $ds->where("{$this->table}.journey_code", $filter['journey_code']);
        }

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_lead_code", 'like', '%' . $search . '%')
                    ->orWhere("cpo_customer_care.content", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        // filter trạng thái: đã phân công, chưa phân công
        if (isset($filter['assign']) &&  $filter['assign'] != "") {
            $assign = $filter['assign'];
            if ($assign == "assigned") {
                $ds->where("{$this->table}.sale_id", "<>", null);
            } elseif ($assign == "not_assign") {
                $ds->where("{$this->table}.sale_id", "=", null);
            }
        }
        // filter tag
        if (isset($filter["customer_tag"])) {
            $ds->whereIn("{$this->table}.customer_lead_code", $filter['customer_tag']);
        }

        //filter người chăm sóc
        if (isset($filter['sale_id']) && !empty($filter['sale_id'])) {
            $ds->where("{$this->table}.sale_id", $filter['sale_id']);
            unset($filter['sale_id']);
        }

        //filter theo loại khách hàng
        if (isset($filter['customer_type']) && !empty($filter['customer_type'])) {
            $ds->where("{$this->table}.customer_type", $filter['customer_type']);
            unset($filter['customer_type']);
        }

        // filter tình trạng chuyển đổi
        if (isset($filter['is_convert']) && !empty($filter['is_convert'])) {
            $ds->where("{$this->table}.is_convert", $filter['is_convert']);
            unset($filter['is_convert']);
        }

        // filter ngày phân bổ
        if (isset($filter["allocation_date"]) && $filter["allocation_date"] != "") {
            $arr_filter = explode(" - ", $filter["allocation_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.allocation_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            unset($filter['allocation_date']);
        }

        return $ds->get();
    }

    /**
     * Lấy số lượng hành trình theo từng nguồn
     *
     * @param $customerSourceId
     * @param $pipelineCode
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getQuantityJourneyByCS($customerSourceId, $pipelineCode, $startTime, $endTime)
    {
        $kq = $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code",
            "{$this->table}.full_name",
            "{$this->table}.journey_code",
            DB::raw("count(journey_code) as quantity"),
            "{$this->table}.customer_source"
        )
            ->where("{$this->table}.pipeline_code", $pipelineCode)
            ->where("{$this->table}.customer_source", $customerSourceId)
            ->groupBy("{$this->table}.journey_code");

        // filter ngày tạo
        if ($startTime != null &&  $endTime != null) {
            $kq->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $kq->get();
    }

    /**
     * Lấy số lượng hành trình theo nhân viên
     *
     * @param $staffId
     * @param $pipelineCode
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getQuantityJourneyByStaff($staffId, $pipelineCode, $startTime, $endTime)
    {
        $kq = $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code",
            "{$this->table}.full_name",
            "{$this->table}.journey_code",
            DB::raw("count(journey_code) as quantity"),
            "{$this->table}.sale_id"
        )
            ->where("{$this->table}.pipeline_code", $pipelineCode)
            ->where("{$this->table}.sale_id", $staffId)
            ->groupBy("{$this->table}.journey_code");
        // filter ngày tạo
        if ($startTime != null &&  $endTime != null) {
            $kq->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $kq->get();
    }

    /**
     * Lấy số lượng hành trình theo chuyển đổi thành công từ lead lên deal (is_convert)
     *
     * @param $customerSourceId
     * @param $pipelineCode
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getQuantityJourneyConverted($customerSourceId, $pipelineCode, $startTime, $endTime)
    {
        $kq = $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code",
            "{$this->table}.full_name",
            "{$this->table}.journey_code",
            DB::raw("count(journey_code) as quantity"),
            "{$this->table}.created_by"
        )
            ->where("{$this->table}.pipeline_code", $pipelineCode)
            ->where("{$this->table}.customer_source", $customerSourceId)
            ->where("{$this->table}.is_convert", 1)
            ->groupBy("{$this->table}.journey_code");
        // filter ngày tạo
        if ($startTime != null &&  $endTime != null) {
            $kq->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $kq->get();
    }

    /**
     * @param $phone
     * @param $id
     * @return mixed
     * Kiểm tra số điện thoại đã tồn tại chưa
     */
    public function testPhone($phone, $id)
    {
        return $this->where(function ($query) use ($phone) {
            $query->where('phone', '=', $phone);
        })->where('customer_lead_id', '<>', $id)
            ->where('is_deleted', 0)->first();
    }

    /**
     * danh sách lead chưa phân bổ có phân trang, filter
     *
     * @param $filter
     * @return mixed
     */
    public function listLeadNotAssignYet($filter)
    {

        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);
        $ds = $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code",
            "{$this->table}.full_name",
            "{$this->table}.address",
            "{$this->table}.customer_type",
            "{$this->table}.created_at",
            "{$this->table}.is_convert",
            "{$this->table}.assign_by",
            "{$this->table}.sale_id",
            "{$this->table}.date_revoke",
            "{$this->table}.created_by",
            "{$this->table}.customer_source",
            "cpo_pipelines.pipeline_name",
            "cpo_pipelines.time_revoke_lead",
            "cpo_journey.journey_name",
            "cs.customer_source_name as customer_source_name"
        )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->join("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources as cs", "cs.customer_source_id", "=", "{$this->table}.customer_source")
            ->where("{$this->table}.sale_id", "=", null)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_convert", 0)
            ->orderBy("{$this->table}.customer_lead_id", "desc");

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_lead_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['customer_source']) && $filter['customer_source'] != "") {
            $ds->where("{$this->table}.customer_source", "=", $filter['customer_source']);
            unset($filter['customer_source']);
        }
        if (isset($filter['pipeline_code_']) && $filter['pipeline_code_'] != "") {
            $ds->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code_']);
            unset($filter['pipeline_code_']);
        }
        if (isset($filter['journey_code_']) && $filter['journey_code_'] != "") {
            $ds->where("{$this->table}.journey_code", "=", $filter['journey_code_']);
            unset($filter['journey_code_']);
        }
        // filter theo người tạo
        if (isset($filter['user_id'])) {
            $ds->where("{$this->table}.created_by", "=", $filter['user_id']);
            unset($filter['user_id']);
        }
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Danh sách lead chưa phân bổ không phân trang, có filter
     *
     * @param $filter
     * @return mixed
     */
    public function listLeadNotAssignYetNotPaginate($filter)
    {
        $ds = $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code",
            "{$this->table}.full_name",
            "{$this->table}.address",
            "{$this->table}.customer_type",
            "{$this->table}.created_at",
            "{$this->table}.is_convert",
            "{$this->table}.assign_by",
            "{$this->table}.sale_id",
            "{$this->table}.date_revoke",
            "{$this->table}.created_by",
            "{$this->table}.customer_source",
            "cpo_pipelines.pipeline_name",
            "cpo_pipelines.time_revoke_lead",
            "cpo_journey.journey_name",
            "cs.customer_source_name as customer_source_name"
        )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->join("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources as cs", "cs.customer_source_id", "=", "{$this->table}.customer_source")
            ->where("{$this->table}.sale_id", "=", null)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_convert", 0)
            ->orderBy("{$this->table}.customer_lead_id", "desc");

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_lead_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['customer_source']) && $filter['customer_source'] != "") {
            $ds->where("{$this->table}.customer_source", "=", $filter['customer_source']);
            unset($filter['customer_source']);
        }
        return $ds->get()->toArray();
    }

    /**
     * Cập nhật lead theo sale id, những lead chưa được chuyển đổi
     *
     * @param array $data
     * @param $saleId
     * @return mixed
     */
    public function editWithStaffId(array $data, $saleId)
    {
        return $this->where("sale_id", $saleId)
            ->where("is_convert", 0)
            ->update($data);
    }

    /**
     * Lấy lead code theo tên khách hàng (đang dùng cho đầu mối doanh nghiệp khi import)
     *
     * @param $nameCustomer
     * @return mixed
     */
    public function getLeadByNameCustomer($nameCustomer)
    {
        return $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code"
        )->where("full_name", $nameCustomer)->first();
    }

    /**
     * Lấy tên lead theo code
     *
     * @param $leadCode
     * @return mixed
     */
    public function getLeadNameByCode($leadCode)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone"
            )
            ->where("customer_lead_code", $leadCode)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }

    /**
     * Ds lead
     *
     * @param $filter
     * @return mixed
     */
    public function getListCustomerLead(&$filter)
    {
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
            "{$this->table}.full_name",
            "{$this->table}.phone",
            "{$this->table}.email",
            "{$this->table}.address",
            "{$this->table}.gender",
            "{$this->table}.created_at"
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sale_id");

        if (isset($filter["time"]) &&  $filter["time"] != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (isset($filter['source_id']) != '') {
            $select->where("{$this->table}.customer_source", "=", $filter['source_id']);
        }
        if (isset($filter['staff_id']) != '') {
            if ($filter['staff_id'] == 'no_staff') {
                $select->whereNotNull("{$this->table}.sale_id");
            } else {
                $select->where("{$this->table}.sale_id", "=", $filter['staff_id']);
            }
            $select->where("staffs.is_deleted", self::NOT_DELETE);
        }

        if (isset($filter['journey_code']) != '') {
            $select->where("{$this->table}.journey_code", "=", $filter['journey_code']);
        }

        if (isset($filter['pipeline_code']) != '') {
            $select->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
        }
        if (isset($filter['is_convert']) != '') {
            $select->where("{$this->table}.is_convert", "=", 1);
        }
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export ds lead
     *
     * @param $filter
     * @return mixed
     */
    public function getListCustomerLeadExport(&$filter)
    {
        $select = $this->select(
            "{$this->table}.full_name",
            "{$this->table}.phone",
            "{$this->table}.email",
            "{$this->table}.address",
            "{$this->table}.gender",
            "{$this->table}.created_at"
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sale_id");
        if (isset($filter["time"]) && $filter["time"] != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (isset($filter['source_id']) != '') {
            $select->where("{$this->table}.customer_source", "=", $filter['source_id']);
        }

        if (isset($filter['staff_id']) != '') {
            if ($filter['staff_id'] == 'no_staff') {
                $select->whereNotNull("{$this->table}.sale_id");
            } else {
                $select->where("{$this->table}.sale_id", "=", $filter['staff_id']);
            }
            $select->where("staffs.is_deleted", self::NOT_DELETE);
        }

        if (isset($filter['journey_code']) != '') {
            $select->where("{$this->table}.journey_code", "=", $filter['journey_code']);
        }

        if (isset($filter['pipeline_code']) != '') {
            $select->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
        }
        if (isset($filter['is_convert']) != '') {
            $select->where("{$this->table}.is_convert", "=", 1);
        }
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->get();
    }

    /**
     * Lấy journey code theo pipeline code và tên của journey
     *
     * @param $pipelineCode
     * @param $name
     * @return mixed
     */
    public function getJourneyCodeByName($pipelineCode)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code",
                "journey_updated",
                "position",
                "default_system"
            )
            ->where("is_deleted", 0)
            ->where("pipeline_code", $pipelineCode)
            ->where("default_system", "new")
            ->first();
    }


    public function getCustomerLeadByLeadCode($leadCode)
    {
        $data = $this->select(
            "{$this->table}.customer_lead_id",
            "{$this->table}.customer_lead_code",
            "{$this->table}.ch_customer_id",
            "{$this->table}.full_name",
            "{$this->table}.customer_type",
            "{$this->table}.phone",
            "{$this->table}.email",
            "{$this->table}.gender",
            "{$this->table}.address",
            "customer_sources.customer_source_name",
            "cpo_tag.name as tag_name",
            "cpo_tag.tag_id as tag_id",
            "cpo_pipelines.pipeline_name",
            "cpo_journey.journey_name",
            "{$this->table}.business_clue",
            "{$this->table}.fanpage",
            "{$this->table}.zalo",
            "{$this->table}.avatar",
            "{$this->table}.tax_code",
            "{$this->table}.representative"
        )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources", "customer_sources.customer_source_id", "{$this->table}.customer_source")
            ->leftJoin("cpo_tag", "cpo_tag.tag_id", "{$this->table}.tag_id")
            ->where("customer_lead_code", $leadCode)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->first();
        return $data;
    }
    public function getCustomerLeadByPhone($phone)
    {
        $data = $this
            ->where("phone", $phone)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->first();
        return $data;
    }

    public function getCustomerLeadSearch($data)
    {
        $select = $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.full_name",
                "{$this->table}.phone",
                "{$this->table}.avatar",
                "{$this->table}.address",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.customer_lead_code"
            )

            ->where(function ($query) use ($data) {
                $query->where('full_name', 'like', '%' . $data . '%')
                    ->orWhere('phone', 'like', '%' . $data . '%');
            })
            ->where("{$this->table}.is_deleted", 0);
        //        if (Auth::user()->is_admin != 1) {
        //            $select->where('branch_id', Auth::user()->branch_id);
        //        }

        return $select->paginate(6);
    }

    public function getListCustomerLeadCampaign($filter)
    {
        $select = $this->select(
            "{$this->table}.full_name",
            "{$this->table}.customer_lead_id",
            "{$this->table}.email",
            "{$this->table}.phone",
            DB::raw("(CASE WHEN {$this->table}.customer_type = 'business' THEN '" . __('Doanh nghiệp') . "'
                                WHEN {$this->table}.customer_type = 'personal' THEN '" . __('Cá nhân') . "'
                            ELSE '' END) as customer_type"),
            "customer_sources.customer_source_name",
            "cpo_pipelines.pipeline_name",
            "cpo_journey.journey_name",
            DB::raw("IFNULL((CASE WHEN {$this->table}.sale_id is null THEN '" . __('Chưa phân công') . "' 
                            ELSE '" . __('Đã phân công') . "' END),'1') as sale_status"),
            DB::raw("IFNULL(staffs.full_name, '') as sale_name"),
            //            "staffs.full_name as sale_name",
            "{$this->table}.created_at"
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources", "customer_sources.customer_source_id", "{$this->table}.customer_source")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_convert", 0);

        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("staffs.full_name", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['customer_source_id']) != '') {
            $select->where("{$this->table}.customer_source", "=", $filter['customer_source_id']);
        }
        if (isset($filter['customer_source']) != '') {
            $select->where("{$this->table}.customer_source", "=", $filter['customer_source']);
        }
        if (isset($filter['journey_code']) != '') {
            $select->where("{$this->table}.journey_code", "=", $filter['journey_code']);
        }
        if (isset($filter['pipeline_code']) != '') {
            $select->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
        }
        if (isset($filter['type_customer']) != '') {
            $select->where("{$this->table}.customer_type", "=", $filter['type_customer']);
        }
        if (isset($filter['sale_status']) != '') {
            if ($filter['sale_status'] == 1) {
                $select->whereNotNull("{$this->table}.sale_id"); // đã phân công -> sale id not null
            } else {
                $select->whereNull("{$this->table}.sale_id"); // chưa phân công -> sale id  null
            }
        }
        if (isset($filter['listCustomerAdd']) != '') {
            $arrEmail = $filter['listCustomerAdd'];
            if (count($arrEmail) != 0) {
                $select->whereNotIn("{$this->table}.email", $arrEmail);
            }
        }
        if (isset($filter['listCustomerAddPhone']) != '') {
            $arrPhone = $filter['listCustomerAddPhone'];
            if (count($arrPhone) != 0) {
                $select->whereNotIn("{$this->table}.phone", $arrPhone);
            }
        }
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->get();
    }

    public function getCustomerApproach($filter)
    {
        $data = $this->select(
            DB::raw("COUNT(cpo_customer_lead.is_convert) as sum_lead_convert")
        )
            ->leftJoin("staffs", "staffs.staff_id", "cpo_customer_lead.sale_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->where("{$this->table}.is_convert", 1)
            ->where("{$this->table}.convert_object_type", "=", "customer");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.updated_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != "") {
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != "") {
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != "") {
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }

    /**
     * get number lead not in arr lead code
     *
     * @param $filter
     * @param array $listLeadCode
     * @return mixed
     */
    public function getCustomerApproachRejectListLead($filter, $listLeadCode = [])
    {
        $data = $this->select(
            DB::raw("COUNT(cpo_customer_care.customer_care_id) as sum_lead")
        )
            ->leftJoin("cpo_customer_care", "cpo_customer_care.customer_lead_code", "cpo_customer_lead.customer_lead_code")
            ->leftJoin("staffs", "staffs.staff_id", "cpo_customer_lead.sale_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("cpo_customer_care.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (count($listLeadCode) > 0) {
            $data->whereNotIn("cpo_customer_lead.customer_lead_code", $listLeadCode);
        }
        if (isset($filter['department_id']) != "") {
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != "") {
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != "") {
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        $data->groupBy("cpo_customer_care.customer_lead_code");
        return $data->get()->toArray();
    }

    /**
     * get number customer lead convert to customer
     *
     * @param $filter
     * @param $staffId
     * @return mixed
     */
    public function getCustomerApproachByStaff($filter, $staffId)
    {
        $data = $this->select(
            DB::raw("COUNT(cpo_customer_lead.is_convert) as sum_lead_convert")
        )
            ->leftJoin("staffs", "staffs.staff_id", "cpo_customer_lead.sale_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->where("{$this->table}.is_convert", 1)
            ->where("{$this->table}.convert_object_type", "=", "customer");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.updated_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != "") {
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != "") {
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if ($staffId != "") {
            $data->where("{$this->table}.sale_id", $staffId);
        }
        return $data->first();
    }
    public function getListAssignByStaff($filter, $staffId)
    {
        $data = $this->select(
            DB::raw("COUNT(cpo_customer_lead.customer_lead_code) as sum_lead_assign")
        )
            ->leftJoin("staffs", "staffs.staff_id", "cpo_customer_lead.sale_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.updated_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != "") {
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != "") {
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if ($staffId != "") {
            $data->where("{$this->table}.sale_id", $staffId);
        }
        return $data->first();
    }

    /**
     * chart rate convert
     *
     * @param $filter
     * @return mixed
     */
    public function getDataChartRateConvert($filter)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT({$this->table}.updated_at,'%d/%m/%Y') as created_group"),
            DB::raw("SUM(IF(cpo_customer_lead.is_convert = 1, 1, 0)) as total")
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sale_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->where("cpo_customer_lead.convert_object_type", "=", "customer")
            ->groupBy(DB::raw("DATE_FORMAT({$this->table}.updated_at,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.updated_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != "") {
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != "") {
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != "") {
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }
    public function getDataChartRateLead($filter)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT({$this->table}.updated_at,'%d/%m/%Y') as created_group"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_code) as total")
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sale_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->groupBy(DB::raw("DATE_FORMAT({$this->table}.updated_at,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.updated_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != "") {
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != "") {
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != "") {
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }

    public function getAllListCustomerLead()
    {
        return $this
            ->select(
                'customer_lead_id',
                'customer_lead_code',
                'full_name',
                DB::raw('CONCAT((CASE WHEN customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",IFNULL(full_name,""),"_",IFNULL(email,""),"_",IFNULL(phone,"")) as customer_name')
            )
            ->where('is_convert', 0)
            ->orderBy('customer_lead_id', 'DESC')
            ->get();
    }

    public function getAllListCustomerLeadWorkManagement()
    {
        return $this
            ->select(
                'customer_lead_id as id',
                'customer_lead_code as code',
                'full_name',
                DB::raw('CONCAT((CASE WHEN customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",IFNULL(full_name,""),"_",IFNULL(phone,""),"_",IFNULL(email,"")) as customer_name')
            )
            ->where('is_convert', 0)
            ->orderBy('customer_lead_id', 'DESC')
            ->get();
    }

    public function getInfoLeadLog($customerLeadId)
    {
        return $this
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.address",
                "{$this->table}.avatar",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.customer_type",
                "{$this->table}.hotline",
                "{$this->table}.fanpage",
                "{$this->table}.zalo",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.customer_source",
                "{$this->table}.business_clue",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10"
            )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", "cpo_journey.journey_code", "=", "{$this->table}.journey_code")
            ->where("customer_lead_id", $customerLeadId)
            ->first();
    }

    /**
     * Kiểm tra phone googleSheet đổ data về 
     * @param [string] $phone
     * @param [array] fillters
     * @return mixed
     */

    public function checkPhoneUnique($phone, $filters = [])
    {
        $queryResult =  $this->where('phone', '=', $phone)
            ->where('is_deleted', 0);
        if (isset($filters['condition_phone'])) {
            $result =  $this->where('phone', '=', $phone)
                ->where('id_google_sheet', $filters['condition_phone']['id_google_sheet'])
                ->where('number_row', $filters['condition_phone']['number_row'])->first();
            if ($result) {
                $queryResult->where('customer_lead_id', '<>', $result->customer_lead_id);
            }
        }
        return $queryResult->first();
    }

    public function getListLeadPaginate($filter = [])
    {

        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);

        $oSelect = $this
            ->join('staffs', 'staffs.staff_id', $this->table . '.sale_id')
            ->select(
                $this->table . '.*',
                'staffs.full_name'
            );

        if (isset($filter['pipeline'])) {
            $oSelect = $oSelect->where('pipeline_code', $filter['pipeline']);
        }

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_customer_lead.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['customer_source_id'])) {
            $oSelect = $oSelect->where('cpo_customer_lead.customer_source', $filter['customer_source_id']);
        }

        if (isset($filter['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $filter['department_id']);
        }

        if (isset($filter['staff_id'])) {
            $oSelect = $oSelect->where('staffs.staff_id', $filter['staff_id']);
        }


        return $oSelect
            ->where($this->table . '.is_deleted', 0)
            ->groupBy($this->table . '.sale_id')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getTotalJourney($saleId, $pipeline)
    {
        return $this
            ->where($this->table . '.is_deleted', 0)
            ->where('sale_id', $saleId)
            ->where('pipeline_code', $pipeline)
            ->get();
    }

    public function getTotalJourneySource($customerSourceId, $pipeline, $filter = [])
    {
        $oSelect = $this
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.sale_id')
            ->where($this->table . '.is_deleted', 0)
            ->where('customer_source', $customerSourceId)
            ->where('pipeline_code', $pipeline);

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_customer_lead.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['customer_source_id'])) {
            $oSelect = $oSelect->where('cpo_customer_lead.customer_source', $filter['customer_source_id']);
        }

        if (isset($filter['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $filter['department_id']);
        }

        if (isset($filter['staff_id'])) {
            $oSelect = $oSelect->where('staffs.staff_id', $filter['staff_id']);
        }

        return $oSelect->get();
    }

    public function getCustomerByCode($customerCode)
    {
        return $this
            ->where('customer_lead_code', $customerCode)
            ->first();
    }

    public function getListLeadPipeline($ownerId)
    {
        $oSelect = $this
            ->leftJoin('cpo_pipelines', 'cpo_pipelines.pipeline_code', $this->table . '.pipeline_code')
            ->where('cpo_pipelines.owner_id', $ownerId)
            ->orWhere($this->table . '.sale_id', $ownerId)
            ->get();

        return $oSelect;
    }

    public function getListLead($filter = [])
    {
        $oSelect = $this
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.sale_id');

        if (isset($filter['pipeline_code'])) {
            $oSelect = $oSelect->where('pipeline_code', $filter['pipeline_code']);
        }

        if (isset($filter['is_convert'])) {
            $oSelect = $oSelect->where('is_convert', $filter['is_convert']);
        }

        if (isset($filter['convert_object_type'])) {
            $oSelect = $oSelect->where('convert_object_type', $filter['convert_object_type']);
        }

        if (isset($filter['is_convert_fail'])) {
            $oSelect = $oSelect->where('journey_code', 'like', '%_FAIL%');
        }

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_customer_lead.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['customer_source_id'])) {
            $oSelect = $oSelect->where('cpo_customer_lead.customer_source', $filter['customer_source_id']);
        }

        if (isset($filter['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $filter['department_id']);
        }

        if (isset($filter['staff_id'])) {
            $oSelect = $oSelect->where('staffs.staff_id', $filter['staff_id']);
        }

        return $oSelect
            ->where($this->table . '.is_deleted', 0)
            ->get();
    }

    /**
     * lấy journey code
     * @param $customerLeadId
     * @return mixed
     */
    public function getInfoLeadJourneyCodeLog($customerLeadId)
    {
        return $this
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.note",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.address",
                "{$this->table}.avatar",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.customer_type",
                "{$this->table}.hotline",
                "{$this->table}.fanpage",
                "{$this->table}.zalo",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.customer_source",
                "{$this->table}.business_clue",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10"
            )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", "cpo_journey.journey_code", "=", "{$this->table}.journey_code")
            ->where("customer_lead_id", $customerLeadId)
            ->first();
    }

    /**
     * Check phone unique
     */
    /**
     * Lấy thông tin KH tiềm năng
     *
     * @param $customerLeadId
     * @return mixed
     */
    public function checkPhoneIsExist($phone, $customerLeadId = null)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.birthday",
                "{$this->table}.address",
                "{$this->table}.avatar"
            )
            ->where("{$this->table}.is_deleted", 0)
            ->where("phone", $phone)
            ->where(function ($query) use ($customerLeadId) {
                if ($customerLeadId != null) {
                    $query->where("{$this->table}.customer_lead_id", "!=", $customerLeadId);
                }
            })
            ->first();
    }

    public function updateById($customerLeadId, $data){
        return $this->where('customer_lead_id', $customerLeadId)->update($data);
    }
}
