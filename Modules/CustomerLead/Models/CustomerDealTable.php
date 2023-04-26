<?php


namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerDealTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";
    protected $fillable = [
        "deal_id",
        "deal_code",
        "deal_name",
        "type_customer",
        "customer_code",
        "contract_code",
        "branch_code",
        "total",
        "discount",
        "amount",
        "probability",
        "owner",
        "sale_id",
        "date_revoke",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "pipeline_code",
        "journey_code",
        "deal_description",
        "order_source_id",
        "voucher_code",
        "discount_member",
        "customer_contact_code",
        "is_deleted",
        "closing_date",
        "closing_due_date",
        "reason_lose_code",
        "tag",
        "deal_type_code",
        "deal_type_object_id",
        "phone",
    ];

    const NOT_DELETE = 0;
    const RECEIPT_STATUS = ['paid', 'part-paid'];
    const ORDER_STATUS = ['paysuccess', 'pay-half'];

    /**
     * Danh sách customer deal
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.customer_code",
                "{$this->table}.branch_code",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.probability",
                "{$this->table}.owner",
                "{$this->table}.sale_id",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.deal_description",
                "{$this->table}.order_source_id",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.customer_contact_code",
                "{$this->table}.is_deleted",
                "{$this->table}.closing_date",
                "{$this->table}.closing_due_date",
                "{$this->table}.reason_lose_code",
                "{$this->table}.tag",
                "{$this->table}.created_at",
                "cpo_pipelines.pipeline_name",
                "cpo_journey.journey_name",
                "staffs.full_name as owner_name",
                "ss.full_name as sale_name",
                "customers.full_name as customer_name",
                DB::raw("(SELECT COUNT(*) FROM manage_work where manage_work.customer_id = {$this->table}.deal_id and manage_work.manage_work_customer_type = 'deal' and manage_work.manage_status_id not in (6,7)) as total_work")
            )
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "cpo_deals.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })

            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.deal_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $query->where("{$this->table}.created_by", Auth()->id())
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.owner",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
            unset($filter['user_id']);
        }
        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where('deal_code', 'like', '%' . $search . '%')
                        ->orWhere('deal_name', 'like', '%' . $search . '%')
                        ->orWhere('customers.full_name', 'like', '%' . $search . '%')
                        ->orWhere('staffs.full_name', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if(isset($filter['pipeline_code']) != ''){
            $ds->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
            unset($filter['pipeline_code']);
        }
        if(isset($filter['journey_code']) != ''){
            $ds->where("{$this->table}.journey_code", "=", $filter['journey_code']);
            unset($filter['journey_code']);
        }
        if(isset($filter['branch_code']) != ''){
            $ds->where("{$this->table}.branch_code", "=", $filter['branch_code']);
            unset($filter['branch_code']);
        }
        if(isset($filter['order_source_id']) != ''){
            $ds->where("{$this->table}.order_source_id", "=", $filter['order_source_id']);
            unset($filter['order_source_id']);
        }
        if (isset($filter["closing_date"]) &&  $filter["closing_date"] != "") {
            $arr_filter = explode(" - ", $filter["closing_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.closing_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['closing_date']);
        }
        if (isset($filter["closing_due_date"]) &&  $filter["closing_due_date"] != "") {
            $arr_filter = explode(" - ", $filter["closing_due_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.closing_due_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['closing_due_date']);
        }
        if(isset($filter['owner']) != ''){
            $ds->where("{$this->table}.owner", "=", $filter['owner']);
            unset($filter['owner']);
        }
        if(isset($filter['value']) != '' && isset($filter['compare']) != ''){
            $filter['value'] = (float)str_replace(',', '', $filter['value']);
            $ds->where("{$this->table}.amount", $filter['compare'], $filter['value']);
        }
        unset($filter['value']);
        unset($filter['compare']);
        return $ds;
    }

    public function getListFromOncall(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.customer_code",
                "{$this->table}.branch_code",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.probability",
                "{$this->table}.owner",
                "{$this->table}.sale_id",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.deal_description",
                "{$this->table}.order_source_id",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.customer_contact_code",
                "{$this->table}.is_deleted",
                "{$this->table}.closing_date",
                "{$this->table}.closing_due_date",
                "{$this->table}.reason_lose_code",
                "{$this->table}.tag",
                "{$this->table}.created_at",
                "cpo_pipelines.pipeline_name",
                "cpo_journey.journey_name",
                "staffs.full_name as owner_name",
                "ss.full_name as sale_name",
                "customers.full_name as customer_name",
                DB::raw("(SELECT COUNT(*) FROM manage_work where manage_work.customer_id = {$this->table}.deal_id and manage_work.manage_work_customer_type = 'deal' and manage_work.manage_status_id not in (6,7)) as total_work")
            )
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("cpo_customer_lead", "cpo_customer_lead.customer_lead_code", "=", "cpo_deals.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "cpo_deals.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })

            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.deal_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $query->where("{$this->table}.created_by", Auth()->id())
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.owner",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
            unset($filter['user_id']);
        }
        if(isset($filter['oncall_type']) && isset($filter['oncall_code'])){
            $ds->where("{$this->table}.type_customer", $filter['oncall_type'])
                ->where("{$this->table}.customer_code", $filter['oncall_code']);
        }
        // filter ngày tạo
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Thêm deal
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->deal_id;
    }

    /**
     * Cap nhat deal
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function edit($id, array $data)
    {
        return $this->where("deal_id", $id)->update($data);
    }

    public function editWithStaffId(array $data, $saleId)
    {
        return $this->where("sale_id", $saleId)
            ->update($data);
    }
    /**
     * Chi tiết deal theo id
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->select(
            "cpo_deals.deal_id",
            "cpo_deals.deal_code",
            "cpo_deals.deal_name",
            "cpo_deals.customer_code",
            "cpo_deals.contract_code",
            "cpo_deals.phone",
            "cpo_deals.type_customer",
            "cpo_deals.branch_code",
            "cpo_deals.total",
            "cpo_deals.discount",
            "cpo_deals.amount",
            "cpo_deals.probability",
            "cpo_deals.owner",
            "cpo_deals.sale_id",
            "cpo_deals.date_revoke",
            "cpo_deals.pipeline_code",
            "cpo_deals.journey_code",
            "cpo_deals.deal_description",
            "cpo_deals.order_source_id",
            "cpo_deals.voucher_code",
            "cpo_deals.discount_member",
            "cpo_deals.customer_contact_code",
            "cpo_deals.is_deleted",
            "cpo_deals.closing_date",
            "cpo_deals.closing_due_date",
            "cpo_deals.reason_lose_code",
            "cpo_deals.tag",
            "cpo_deals.created_at",
            "cpo_deals.updated_at",
            "cpo_deals.deal_type_code",
            "cpo_deals.deal_type_object_id",

            "staffs.full_name as owner_name",
            "order_sources.order_source_name",
            "cpo_pipelines.pipeline_name",
            "cpo_journey.journey_id as journey_id",
            "cpo_journey.position as journey_position",
            "cpo_customer_lead.customer_type",
            DB::raw("(CASE WHEN cpo_deals.type_customer = 'lead' THEN cpo_customer_lead.full_name
                   ELSE customers.full_name END) as customer_full_name"),
            DB::raw("(CASE WHEN cpo_deals.type_customer = 'lead' THEN cpo_customer_lead.representative
                   ELSE customer_contacts.full_address END) as full_address"),
            DB::raw("(CASE WHEN cpo_deals.type_customer = 'lead' THEN cpo_customer_lead.customer_lead_id
                   ELSE customers.customer_id END) as customer_id_join"),
            "branches.branch_name as branch_name",
            DB::raw('(CASE WHEN cpo_deals.type_customer = "lead" THEN CONCAT((CASE WHEN cpo_customer_lead.customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",IFNULL(cpo_customer_lead.full_name,""),"_",IFNULL(cpo_customer_lead.email,""),"_",IFNULL(cpo_customer_lead.phone,""))
                   ELSE CONCAT((CASE WHEN customers.customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",IFNULL(customers.full_name,""),"_",IFNULL(customers.email,""),"_",IFNULL(customers.phone1,"")) END) as customer_full_join')
        )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "cpo_deals.pipeline_code")
            ->leftJoin("cpo_journey", "cpo_journey.journey_code", "=", "cpo_deals.journey_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("order_sources", "order_sources.order_source_id", "=", "cpo_deals.order_source_id")
            ->leftJoin("customers", "customers.customer_code", "=", "cpo_deals.customer_code")
            ->leftJoin("cpo_customer_lead", "cpo_customer_lead.customer_lead_code", "=", "cpo_deals.customer_code")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "cpo_deals.customer_contact_code")
            ->leftJoin("cpo_tag", "cpo_tag.tag_id", "=", "cpo_deals.tag")
            ->leftJoin("branches", "branches.branch_code", "=", "cpo_deals.branch_code")
            ->where('cpo_deals.deal_id', $id)->first();
    }

    public function getCustomerByPipeline($pipelineCode)
    {
//        $imageDefault = 'http://' . request()->getHttpHost() . '/static/backend/images/image-user.png';

        return $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.total",
                "{$this->table}.amount",
                "{$this->table}.customer_code",
                "{$this->table}.branch_code",
//                DB::raw("(CASE
//                    WHEN  cpo_customer_lead.avatar = '' THEN '$imageDefault'
//                    WHEN  cpo_customer_lead.avatar IS NULL THEN '$imageDefault'
//                    ELSE  cpo_customer_lead.avatar
//                    END
//                ) as avatar"),
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
//                "{$this->table}.closing_date",
                DB::raw("DATE_FORMAT(closing_date,'%d/%m/%Y') as closing_date"),
                "cpo_journey.default_system",
                "cpo_journey.position"
            )
            ->leftJoin("cpo_journey", "cpo_journey.journey_code", "=", "{$this->table}.journey_code")
            ->where("{$this->table}.pipeline_code", $pipelineCode)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("deal_id", "desc")
            ->get();
    }


    public function getCustomerByFilterKanban(&$filter)
    {
        $manage_type_work_id = isset($filter['search_manage_type_work_id']) ? $filter['search_manage_type_work_id'] : null;

//        $imageDefault = 'http://' . request()->getHttpHost() . '/static/backend/images/image-user.png';
        $data =  $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.total",
                "{$this->table}.amount",
                "{$this->table}.phone",
                "{$this->table}.customer_code",
                "{$this->table}.branch_code",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.deal_description",
                "{$this->table}.date_last_care",
                "{$this->table}.type_customer",
                "{$this->table}.tag",
                "s.full_name as owner_name",
                "cpo_customer_lead.full_name as lead_name",
                "cpo_customer_lead.customer_type as lead_customer_type",
                "customers.full_name as full_name",
                "customers.customer_type as customer_type",
//                "{$this->table}.closing_date",
                DB::raw("DATE_FORMAT(closing_date,'%d/%m/%Y') as closing_date"),
                "cpo_journey.default_system",
                "cpo_journey.position",
                'manage_work.manage_type_work_id',
                DB::raw("(SELECT COUNT(*) FROM manage_work where manage_work.customer_id = {$this->table}.deal_id and manage_work.manage_work_customer_type = 'deal' and manage_work.manage_status_id not in (6,7)) as total_work")
            )
            ->leftJoin('manage_work', function($sql) use ($manage_type_work_id){
                $sql->on('manage_work.customer_id',$this->table.'.deal_id')
                    ->where('manage_work.manage_work_customer_type','deal');
                if ($manage_type_work_id != null){
                    $sql->where('manage_work.manage_type_work_id',$manage_type_work_id);
                }

            })

            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.owner")
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("cpo_customer_lead", "cpo_customer_lead.customer_lead_code", "=", "{$this->table}.customer_code")
            ->leftJoin("cpo_deal_care", "cpo_deal_care.deal_id", "=", "{$this->table}.deal_id")
            ->where("{$this->table}.pipeline_code", $filter['pipeline_code'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if(isset($filter['search']) != ''){
            $search = $filter['search'];
            $data->where(function($query) use($search){
                $query->where("cpo_customer_lead.full_name", 'like', '%'.$search.'%')
                    ->orWhere("customers.full_name", 'like', '%'.$search.'%');
            });
        }
        if(!empty($filter['type_customer'])){
            $data->where("{$this->table}.type_customer", $filter['type_customer']);
        }

        if(!empty($filter['order_source_id'])){
            $data->where("{$this->table}.order_source_id", (int)$filter['order_source_id']);
        }
        if (isset($filter["closing_date"]) &&  $filter["closing_date"] != "") {
            $arr_filter = explode(" - ", $filter["closing_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.closing_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if(isset($filter['branch_code']) != ''){
            $data->where("{$this->table}.branch_code", $filter['branch_code']);
        }
        if(isset($filter['care_type']) != ''){
            $data->where("cpo_deal_care.care_type", $filter['care_type']);
        }

        if (isset($filter['select_manage_type_work_id']) && !empty($filter['select_manage_type_work_id'])) {
            $data->where("manage_work.manage_type_work_id", $filter['select_manage_type_work_id']);
        }

        return $data->orderBy("deal_id", "desc")->groupBy("deal_id")->get();
    }

    public function getQuantityJourneyByStaff($staffId, $pipelineCode, $startTime, $endTime)
    {
        $kq = $this->select(
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.deal_name",
            "{$this->table}.journey_code",
            DB::raw("count(journey_code) as quantity"),
            "{$this->table}.owner"
        )
            ->where("{$this->table}.pipeline_code", $pipelineCode)
            ->where("{$this->table}.owner", $staffId)
            ->groupBy("{$this->table}.journey_code");
        // filter ngày tạo
        if ($startTime != null &&  $endTime != null) {
            $kq->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $kq->get();
    }

    public function getListCustomerDeal(&$filter)
    {
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.deal_name",
            "{$this->table}.created_at",
            "staffs.full_name"
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.owner");

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
            if($filter['staff_id'] == 'no_staff'){
                $select->whereNotNull("{$this->table}.owner");
            }
            else{
                $select->where("{$this->table}.owner", "=", $filter['staff_id']);
            }
            $select->where("staffs.is_deleted", self::NOT_DELETE);
        }

        if (isset($filter['journey_code']) != '') {
            $select->where("{$this->table}.journey_code", "=", $filter['journey_code']);
        }

        if (isset($filter['pipeline_code']) != '') {
            $select->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
        }
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getListCustomerDealExport(&$filter)
    {
        $select = $this->select(
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.deal_name",
            "{$this->table}.created_at",
            "staffs.full_name"
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.owner");

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
            if($filter['staff_id'] == 'no_staff'){
                $select->whereNotNull("{$this->table}.owner");
            }
            else{
                $select->where("{$this->table}.owner", "=", $filter['staff_id']);
            }
            $select->where("staffs.is_deleted", self::NOT_DELETE);
        }

        if (isset($filter['journey_code']) != '') {
            $select->where("{$this->table}.journey_code", "=", $filter['journey_code']);
        }

        if (isset($filter['pipeline_code']) != '') {
            $select->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
        }
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->get();
    }
    function getDealForPayment($id)
    {
        return $this->select(
            "cpo_deals.deal_id",
            "cpo_deals.deal_code",
            "cpo_deals.deal_name",
            "cpo_deals.customer_code",
            "cpo_deals.branch_code",
            "cpo_deals.total",
            "cpo_deals.discount",
            "cpo_deals.amount",
            "cpo_deals.probability",
            "cpo_deals.owner",
            "cpo_deals.pipeline_code",
            "cpo_deals.journey_code",
            "cpo_deals.deal_description",
            "cpo_deals.order_source_id",
            "cpo_deals.voucher_code",
            "cpo_deals.discount_member",
            "cpo_deals.customer_contact_code",
            "cpo_deals.is_deleted",
            "cpo_deals.closing_date",
            "cpo_deals.closing_due_date",
            "cpo_deals.reason_lose_code",
            "cpo_deals.tag",
            "cpo_deals.created_at",
            "cpo_deals.updated_at",

            "staffs.full_name as owner_name",
            "customers.full_name as customer_full_name",
            "customers.customer_id as customer_id",
            "customers.customer_avatar as customer_avatar",
            "customers.phone1 as customer_phone",
            "customer_contacts.full_address",
            "member_levels.name as member_level_name",
            "member_levels.member_level_id"
        )
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("customers", "customers.customer_code", "=", "cpo_deals.customer_code")
            ->leftJoin("member_levels", "member_levels.member_level_id", "=", "customers.member_level_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "cpo_deals.customer_contact_code")
            ->where('cpo_deals.deal_id', $id)->first();
    }
    function getDealOfLeadForPayment($id)
    {
        return $this->select(
            "cpo_deals.deal_id",
            "cpo_deals.deal_code",
            "cpo_deals.deal_name",
            "cpo_deals.customer_code",
            "cpo_deals.branch_code",
            "cpo_deals.total",
            "cpo_deals.discount",
            "cpo_deals.amount",
            "cpo_deals.probability",
            "cpo_deals.owner",
            "cpo_deals.pipeline_code",
            "cpo_deals.journey_code",
            "cpo_deals.deal_description",
            "cpo_deals.order_source_id",
            "cpo_deals.voucher_code",
            "cpo_deals.discount_member",
            "cpo_deals.customer_contact_code",
            "cpo_deals.is_deleted",
            "cpo_deals.closing_date",
            "cpo_deals.closing_due_date",
            "cpo_deals.reason_lose_code",
            "cpo_deals.tag",
            "cpo_deals.created_at",
            "cpo_deals.updated_at",

            "staffs.full_name as owner_name",
            "customers.full_name as customer_full_name",
            "customers.customer_id as customer_id",
            "customers.customer_avatar as customer_avatar",
            "customers.phone1 as customer_phone",
            "customer_contacts.full_address",
            "member_levels.name as member_level_name"
        )
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("customers", "customers.phone1", "=", "cpo_deals.phone")
            ->leftJoin("member_levels", "member_levels.member_level_id", "=", "customers.member_level_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "cpo_deals.customer_contact_code")
            ->where('cpo_deals.deal_id', $id)->first();
    }

    /**
     * Lấy thông tin deal theo mã deal
     *
     * @param $dealCode
     * @return mixed
     */
    public function getDealByCode($dealCode)
    {
        return $this->select(
            "cpo_deals.phone",
            "cpo_deals.deal_id",
            "cpo_deals.deal_code",
            "cpo_deals.deal_name",
            "cpo_deals.customer_code",
            "cpo_deals.branch_code",
            "cpo_deals.total",
            "cpo_deals.discount",
            "cpo_deals.amount",
            "cpo_deals.probability",
            "cpo_deals.owner",
            "cpo_deals.pipeline_code",
            "cpo_deals.journey_code",
            "cpo_deals.deal_description",
            "cpo_deals.order_source_id",
            "cpo_deals.voucher_code",
            "cpo_deals.discount_member",
            "cpo_deals.customer_contact_code",
            "cpo_deals.is_deleted",
            "cpo_deals.closing_date",
            "cpo_deals.closing_due_date",
            "cpo_deals.reason_lose_code",
            "cpo_deals.tag",
            "cpo_deals.created_at",
            "cpo_deals.deal_type_code",
            "cpo_deals.deal_type_object_id"
        )
            ->where("cpo_deals.deal_code", $dealCode)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }

    /**
     * Cập nhật deal theo mã deal
     *
     * @param $dealCode
     * @param array $data
     * @return mixed
     */
    public function editByCode($dealCode, array $data)
    {
        return $this->where("deal_code", $dealCode)->update($data);
    }
    /**
     * Tuỳ chọn deal
     *
     * @return mixed
     */
    public function getOptionCpoDeal()
    {
        $data = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_name"
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        return $data->get()->toArray();
    }
    public function getInfoCustomerDeal($id)
    {
        return $this->select(
            "cpo_customer_lead.customer_lead_code",
            "cpo_customer_lead.full_name",
            "cpo_customer_lead.customer_type",
            "cpo_customer_lead.hotline",
            "cpo_customer_lead.tax_code",
            "cpo_customer_lead.representative",
            "cpo_customer_lead.gender",
            "cpo_customer_lead.phone",
            "cpo_customer_lead.address",
            "cpo_customer_lead.province_id",
            "cpo_customer_lead.district_id",
            "cpo_customer_lead.email"
        )
            ->leftJoin("cpo_customer_lead", "cpo_customer_lead.customer_lead_code", "=", "cpo_deals.customer_code")
            ->where('cpo_deals.deal_id', $id)->first();
    }

    /**
     * Tab ds deal phân trangg (KHTN detail)
     *
     * @param $filter
     * @return mixed
     */
    public function getListDealLeadDetail(&$filter)
    {
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? 6);
        $select = $this->select(
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.amount",
            "{$this->table}.closing_date",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("IFNULL((GROUP_CONCAT(cpo_deal_details.object_name)),".__("'Không có sản phẩm'").") as product"),
            "cpo_pipelines.pipeline_name",
            "cpo_journey.journey_name",
            "staffs.full_name as full_name"
        )
            ->leftJoin("cpo_deal_details", "cpo_deal_details.deal_code", "=", "{$this->table}.deal_code")
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.created_by")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->where("{$this->table}.customer_code", $filter['customer_lead_code']);

        $select->groupBy("{$this->table}.deal_code");
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Get data chart revenue campaign overview report
     *
     * @param $filter
     * @param string $dealType
     * @return mixed
     */
    public function getRevenueCampaignOverview($filter, $dealType = '')
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y') as created_group"),
            DB::raw("SUM(receipts.amount_paid) as revenue")
        )
        ->join("orders", "orders.deal_code", "{$this->table}.deal_code")
        ->join("receipts", "receipts.order_id", "=", "orders.order_id")
        ->whereIn("receipts.status", self::RECEIPT_STATUS)
        ->whereIn("orders.process_status", self::ORDER_STATUS)
        ->groupBy(DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($dealType) != ''){
            $data->where("{$this->table}.deal_type_code", $dealType);
        }
        return $data->get()->toArray();
    }

    /**
     * số lương lead,KH có deal thành công
     *
     * @param $filter
     * @param string $dealType
     * @return mixed
     */
    public function getTypeCustomerDealSuccess($filter, $dealType = '')
    {
        $data = $this->select(
            DB::raw("SUM(IF({$this->table}.type_customer = 'lead', 1, 0)) as total_lead"),
            DB::raw("SUM(IF({$this->table}.type_customer = 'customer', 1, 0)) as total_customer")
        )
            ->join("orders", "orders.deal_code", "{$this->table}.deal_code")
            ->join("receipts", "receipts.order_id", "=", "orders.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "receipts.staff_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->where("receipts.status", 'paid')
            ->where("orders.process_status", 'paysuccess');
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if($dealType != ''){
            $data->where("{$this->table}.deal_type_code", $dealType);
        }
        if(isset($filter['option_sms']) != '' && $dealType == 'sms'){
            $data->where("{$this->table}.deal_type_object_id", $filter['option_sms']);
        }
        if(isset($filter['option_email']) != '' && $dealType == 'email'){
            $data->where("{$this->table}.deal_type_object_id", $filter['option_email']);
        }
        if(isset($filter['option_notify']) != '' && $dealType == 'notification'){
            $data->where("{$this->table}.deal_type_object_id", $filter['option_notify']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->first();
    }

    /**
     * tổng chi phí và doanh thu trong time filter
     *
     * @param $filter
     * @param $type
     * @return mixed
     */
    public function getRevenueAndCostByType($filter, $type)
    {
        $arr_filter = explode(" - ", $filter["time"]);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d 00:00:00');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d 23:59:59');
        $data = [];
        if($type == 'sms'){
            $data = $this->select(
                DB::raw("SUM(p.revenue) as revenue"),
                DB::raw("SUM(sms_campaign.cost) as cost")
            )->from(DB::raw("(SELECT
                    `cpo_deals`.`deal_type_object_id`, 
                    SUM(case when 
                    `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                    and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                    THEN  receipts.amount_paid 
                    ELSE 0 END) AS revenue
                FROM
                    `cpo_deals`
                    INNER JOIN `orders` ON `orders`.`deal_code` = `cpo_deals`.`deal_code`
                    INNER JOIN `receipts` ON `receipts`.`order_id` = `orders`.`order_id`
                WHERE
                    `cpo_deals`.`deal_type_code` = 'sms'
                    AND `receipts`.`created_at` between '$startTime' and '$endTime'
		            group by `cpo_deals`.`deal_type_object_id` ) as p"))
                ->rightJoin("sms_campaign", "sms_campaign.campaign_id", "p.deal_type_object_id")
                ->where("sms_campaign.status", "=", "sent");
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('sms_campaign.time_sent', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
            if(isset($filter['option_sms']) != ''){
                $data->where("sms_campaign.campaign_id", $filter['option_sms']);
            }
        }
        elseif($type == 'email'){
            $data = $this->select(
                DB::raw("SUM(p.revenue) as revenue"),
                DB::raw("SUM(email_campaign.cost) as cost")
            )->from(DB::raw("(SELECT
                    `cpo_deals`.`deal_type_object_id`,
                    SUM(case when 
                    `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                    and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                    THEN  receipts.amount_paid 
                    ELSE 0 END) AS revenue
                FROM
                    `cpo_deals`
                    INNER JOIN `orders` ON `orders`.`deal_code` = `cpo_deals`.`deal_code`
                    INNER JOIN `receipts` ON `receipts`.`order_id` = `orders`.`order_id`
                WHERE
                    `cpo_deals`.`deal_type_code` = 'email'
                    AND `receipts`.`created_at` between '$startTime' and '$endTime'
		            group by `cpo_deals`.`deal_type_object_id` ) as p"))
                ->rightJoin("email_campaign", "email_campaign.campaign_id", "p.deal_type_object_id")
                ->where("email_campaign.status", "=", "sent");
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('email_campaign.time_sent', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
            if(isset($filter['option_email']) != ''){
                $data->where("email_campaign.campaign_id", $filter['option_email']);
            }
        }
        else{
            $data = $this->select(
                DB::raw("SUM(p.revenue) as revenue"),
                DB::raw("SUM(notification_template.cost) as cost")
            )->from(DB::raw("(SELECT
                    `cpo_deals`.`deal_type_object_id`, 
                    SUM(case when 
                    `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                    and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                    THEN  receipts.amount_paid 
                    ELSE 0 END) AS revenue
                FROM
                    `cpo_deals`
                    INNER JOIN `orders` ON `orders`.`deal_code` = `cpo_deals`.`deal_code`
                    INNER JOIN `receipts` ON `receipts`.`order_id` = `orders`.`order_id`
                WHERE
                    `cpo_deals`.`deal_type_code` = 'notification'
                    AND `receipts`.`created_at` between '$startTime' and '$endTime'
		            group by `cpo_deals`.`deal_type_object_id` ) as p"))
                ->rightJoin("notification_template", "notification_template.notification_template_id", "p.deal_type_object_id")
                ->where("notification_template.send_status", "=", "sent");
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('notification_template.send_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
            if(isset($filter['option_notify']) != ''){
                $data->where("notification_template.notification_template_id", $filter['option_notify']);
            }
        }
        return $data->first();
    }

    public function getRevenuePerformanceByType($filter)
    {
        $arr_filter = explode(" - ", $filter["time"]);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d 00:00:00');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d 23:59:59');
        $data = $this->select(
            DB::raw("SUM(receipts.amount_paid) AS revenue")
        )
            ->join("orders", "orders.deal_code", "{$this->table}.deal_code")
            ->join("receipts", "receipts.order_id", "=", "orders.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "receipts.staff_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereIn("receipts.status", self::RECEIPT_STATUS)
            ->whereIn("orders.process_status", self::ORDER_STATUS)
            ->whereNotNull("{$this->table}.type_customer");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->first();
    }

    /**
     * Ds doanh thu và chi phí của từng chiến dịch
     *
     * @param $filter
     * @param $type
     * @return mixed
     */
    public function getEachRevenueAndCostByType($filter, $type)
    {
        if($type == 'sms'){
            $data = $this->select(
                "{$this->table}.deal_type_object_id",
                DB::raw("SUM(case when 
                            `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                            and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                            THEN  receipts.amount_paid 
                            ELSE 0 END) AS revenue"),
                "sms_campaign.cost",
                "sms_campaign.name"
            )
                ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
                ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
                ->rightJoin("sms_campaign", function($join)use($type){
                    $join->on("sms_campaign.campaign_id", "=", "{$this->table}.deal_type_object_id")
                        ->on("{$this->table}.deal_type_code", "=", DB::raw("'$type'"))
                        ->where("sms_campaign.status", "=", "sent");
                })
                ->groupBy("sms_campaign.campaign_id");
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('sms_campaign.time_sent', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
                $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
        }
        elseif($type == 'email'){
            $data = $this->select(
                "{$this->table}.deal_type_object_id",
                DB::raw("SUM(case when 
                            `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                            and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                            THEN  receipts.amount_paid 
                            ELSE 0 END) AS revenue"),
                "email_campaign.cost",
                "email_campaign.name"
            )
                ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
                ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
                ->rightJoin("email_campaign", function($join)use($type){
                    $join->on("email_campaign.campaign_id", "=", "{$this->table}.deal_type_object_id")
                        ->on("{$this->table}.deal_type_code", "=", DB::raw("'$type'"))
                        ->where("email_campaign.status", "=", "sent");
                })
                ->groupBy("email_campaign.campaign_id");
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('email_campaign.time_sent', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
                $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
        }
        else{
            $data = $this->select(
                "{$this->table}.deal_type_object_id",
                DB::raw("SUM(case when 
                            `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                            and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                            THEN  receipts.amount_paid 
                            ELSE 0 END) AS revenue"),
                "notification_template.cost",
                "notification_template.title as name"
            )
                ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
                ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
                ->rightJoin("notification_template", function($join)use($type){
                    $join->on("notification_template.notification_template_id", "=", "{$this->table}.deal_type_object_id")
                        ->on("{$this->table}.deal_type_code", "=", DB::raw("'$type'"))
                        ->where("notification_template.send_status", "=", "sent");
                })
                ->leftJoin("notification_detail", "notification_detail.notification_detail_id", "notification_template.notification_detail_id")
                ->groupBy("notification_template.notification_template_id");
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('notification_template.send_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
                $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
        }
        return $data->get()->toArray();
    }

    /**
     * Ds slđh và doanh thu theo từng ngày của từng chiến dịch
     *
     * @param $filter
     * @param $type
     * @return mixed
     */
    public function getOrderAndRevenueByType($filter, $type)
    {
        if($type == 'sms'){
            $data = $this->select(
                DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y') as created_group"),
                DB::raw("SUM(case when 
                            `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                            and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                            THEN  receipts.amount_paid 
                            ELSE 0 END) AS revenue"),
                DB::raw("SUM(IF(orders.deal_code != '',1,0)) as count_order")
            )
                ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
                ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
                ->leftJoin("sms_campaign", "sms_campaign.campaign_id", "{$this->table}.deal_type_object_id")
                ->where("{$this->table}.deal_type_code", $type)
                ->where("sms_campaign.status", "=", "sent")
                ->groupBy(DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y')"));
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('sms_campaign.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
            if(isset($filter['option_sms']) != '' && $type == 'sms'){
                $data->where("{$this->table}.deal_type_object_id", $filter['option_sms']);
            }
        }
        elseif($type == 'email'){
            $data = $this->select(
                DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y') as created_group"),
                DB::raw("SUM(case when 
                            `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                            and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                            THEN  receipts.amount_paid 
                            ELSE 0 END) AS revenue"),
                DB::raw("SUM(IF(orders.deal_code != '',1,0)) as count_order")
            )
                ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
                ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
                ->leftJoin("email_campaign", "email_campaign.campaign_id", "{$this->table}.deal_type_object_id")
                ->where("{$this->table}.deal_type_code", $type)
                ->where("email_campaign.status", "=", "sent")
                ->groupBy(DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y')"));
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('email_campaign.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
            if(isset($filter['option_email']) != '' && $type == 'email'){
                $data->where("{$this->table}.deal_type_object_id", $filter['option_email']);
            }
        }
        else{
            $data = $this->select(
                DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y') as created_group"),
                DB::raw("SUM(case when 
                            `receipts`.`status` IN ( 'paid', 'part-paid' ) 
                            and `orders`.`process_status` IN ( 'paysuccess', 'pay-half' ) 
                            THEN  receipts.amount_paid 
                            ELSE 0 END) AS revenue"),
                DB::raw("SUM(IF(orders.deal_code != '',1,0)) as count_order")
            )
                ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
                ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
                ->leftJoin("notification_template", "notification_template.notification_template_id", "{$this->table}.deal_type_object_id")
                ->leftJoin("notification_detail", "notification_detail.notification_detail_id", "notification_template.notification_detail_id")
                ->where("{$this->table}.deal_type_code", $type)
                ->where("notification_template.send_status", "=", "sent")
                ->groupBy(DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y')"));
            if (isset($filter["time"]) != "") {
                $arr_filter = explode(" - ", $filter["time"]);
                $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
                $data->whereBetween('notification_detail.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            }
            if(isset($filter['option_notify']) != '' && $type == 'notification'){
                $data->where("{$this->table}.deal_type_object_id", $filter['option_notify']);
            }
        }
        return $data->get()->toArray();
    }

    /**
     * Dữ liệu của cho biểu đồ tổng doanh thu theo phòng ban
     *
     * @param $filter
     * @param $dealType
     * @return mixed
     */
    public function getRevenueByLeadAndCustomer($filter, $dealType)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y') as created_group"),
            DB::raw("SUM(receipts.amount_paid) as revenue")
        )
            ->leftJoin("orders", "orders.deal_code", "{$this->table}.deal_code")
            ->leftJoin("receipts", "receipts.order_id", "orders.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "receipts.staff_id")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereIn("receipts.status", self::RECEIPT_STATUS)
            ->whereIn("orders.process_status", self::ORDER_STATUS)
            ->groupBy(DB::raw("DATE_FORMAT(receipts.created_at,'%d/%m/%Y')"));
        if ($dealType != ''){
            $data->where("{$this->table}.type_customer", $dealType);
        }
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('receipts.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }

    /**
     * get all deal not assign
     *
     * @param $filter
     * @return mixed
     */
    public function listDealNotAssignYet($filter)
    {
        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);
        $ds = $this->select
        (
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.deal_name",
            DB::raw("(CASE WHEN cpo_deals.type_customer = 'lead' THEN cpo_customer_lead.full_name
                   ELSE customers.full_name END) as full_name"),
            "cpo_pipelines.pipeline_name",
            "cpo_pipelines.time_revoke_lead",
            "cpo_journey.journey_name"
        )
            ->leftJoin("customers", "customers.customer_code", "=", "cpo_deals.customer_code")
            ->leftJoin("cpo_customer_lead", "cpo_customer_lead.customer_lead_code", "=", "cpo_deals.customer_code")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->where("{$this->table}.sale_id", "=", null)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.deal_id", "desc");

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("customers.full_name", 'like', '%' . $search . '%')
                    ->orWhere("cpo_customer_lead.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.deal_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.deal_name", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['pipeline_code']) && $filter['pipeline_code'] != "") {
            $ds->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
            unset($filter['pipeline_code']);
        }
        if (isset($filter['journey_code']) && $filter['journey_code'] != "") {
            $ds->where("{$this->table}.journey_code", "=", $filter['journey_code']);
            unset($filter['journey_code']);
        }
        // filter theo người tạo
        if (isset($filter['user_id'])) {
            $ds->where("{$this->table}.created_by", "=", $filter['user_id']);
            unset($filter['user_id']);
        }
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    public function listDealNotPaging($filter)
    {
        $ds = $this->select
        (
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.deal_name",
            DB::raw("(CASE WHEN cpo_deals.type_customer = 'lead' THEN cpo_customer_lead.full_name
                   ELSE customers.full_name END) as full_name"),
            "cpo_pipelines.pipeline_name",
            "cpo_pipelines.time_revoke_lead",
            "cpo_journey.journey_name"
        )
            ->leftJoin("customers", "customers.customer_code", "=", "cpo_deals.customer_code")
            ->leftJoin("cpo_customer_lead", "cpo_customer_lead.customer_lead_code", "=", "cpo_deals.customer_code")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->where("{$this->table}.sale_id", "=", null)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.deal_id", "desc");

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("customers.full_name", 'like', '%' . $search . '%')
                    ->orWhere("cpo_customer_lead.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.deal_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.deal_name", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['pipeline_code']) && $filter['pipeline_code'] != "") {
            $ds->where("{$this->table}.pipeline_code", "=", $filter['pipeline_code']);
            unset($filter['pipeline_code']);
        }
        if (isset($filter['journey_code']) && $filter['journey_code'] != "") {
            $ds->where("{$this->table}.journey_code", "=", $filter['journey_code']);
            unset($filter['journey_code']);
        }
        // filter theo người tạo
        if (isset($filter['user_id'])) {
            $ds->where("{$this->table}.created_by", "=", $filter['user_id']);
            unset($filter['user_id']);
        }
        return $ds->get()->toArray();
    }

    /**
     * Danh sách tất cả customer deal
     *
     * @param array $filter
     * @return mixed
     */
    public function getAll($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.customer_code",
                "{$this->table}.branch_code",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.probability",
                "{$this->table}.owner",
                "{$this->table}.sale_id",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.deal_description",
                "{$this->table}.order_source_id",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.customer_contact_code",
                "{$this->table}.is_deleted",
                "{$this->table}.closing_date",
                "{$this->table}.closing_due_date",
                "{$this->table}.reason_lose_code",
                "{$this->table}.tag",
                "{$this->table}.created_at",
                "cpo_pipelines.pipeline_name",
                "cpo_journey.journey_name",
                "staffs.full_name as owner_name",
                "ss.full_name as sale_name",
                "customers.full_name as customer_name",
                DB::raw("(SELECT COUNT(*) FROM manage_work where manage_work.customer_id = {$this->table}.deal_id and manage_work.manage_work_customer_type = 'deal' and manage_work.manage_status_id not in (6,7)) as total_work")
            )
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "cpo_deals.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })

            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.deal_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $query->where("{$this->table}.created_by", Auth()->id())
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.owner",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
            unset($filter['user_id']);
        }
        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where('deal_code', 'like', '%' . $search . '%')
                    ->orWhere('deal_name', 'like', '%' . $search . '%')
                    ->orWhere('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('staffs.full_name', 'like', '%' . $search . '%');
            });
        }


        return $ds->get();
    }

    public function getListLeadPaginate($filter = []){
        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);

        $oSelect = $this
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.sale_id')
            ->select(
                $this->table.'.*',
                'staffs.full_name'
            );

        if (isset($filter['pipeline'])){
            $oSelect = $oSelect->where('pipeline_code',$filter['pipeline']);
        }

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_customer_lead.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where('staffs.department_id',$filter['department_id']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where('staffs.staff_id',$filter['staff_id']);
        }

        return $oSelect
            ->where($this->table.'.is_deleted',0)
            ->whereNotNull($this->table.'.sale_id')
            ->groupBy($this->table.'.sale_id')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getTotalJourney($saleId,$pipeline,$filter=[]){
        $oSelect = $this
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.sale_id')
            ->where($this->table.'.is_deleted',0)
            ->where('sale_id',$saleId)
            ->where('pipeline_code',$pipeline);

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_customer_lead.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where('staffs.department_id',$filter['department_id']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where('staffs.staff_id',$filter['staff_id']);
        }

        return $oSelect->get();
    }

    /**
     * Lấy danh sách log theo deal
     */
    public function getListDeal($filter = []){
        $oSelect = $this
            ->leftJoin('staffs','staffs.staff_id','cpo_deals.sale_id')
            ->where('cpo_deals.is_deleted',0);

        if (isset($filter['pipeline_code'])) {
            $oSelect = $oSelect->where('pipeline_code', $filter['pipeline_code']);
        }

        if (isset($filter['time'])) {
            $time = explode(' - ', $filter['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("cpo_deals.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['department_id'])){
            $oSelect = $oSelect->where('staffs.department_id',$filter['department_id']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where('staffs.staff_id',$filter['staff_id']);
        }

        if (isset($filter['is_convert_contract'])){
            $oSelect = $oSelect
                ->join('orders','orders.order_id',$this->table.'.order_id')
                ->where('orders.process_status',$filter['convert_contract_type']);
        }

        return $oSelect->get();
    }

    /**
     * Kiểm tra nhân viên
     * @param $data
     * @return mixed
     */
    public function checkStaff($data){
        return $this
            ->where('customer_code',$data['customer_code'])
            ->where('sale_id','<>',$data['staff'])
            ->first();
    }
}