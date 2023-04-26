<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 14:37
 */

namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractTable extends Model
{
    use ListTableTrait;
    protected $table = "contracts";
    protected $primaryKey = "contract_id";
    protected $fillable = [
        "contract_id",
        "contract_category_id",
        "contract_name",
        "contract_code",
        "contract_no",
        "contract_form",
        "sign_date",
        "performer_by",
        "effective_date",
        "expired_date",
        "warranty_start_date",
        "warranty_end_date",
        "content",
        "note",
        "status_code",
        "is_value_goods",
        "is_renew",
        "number_day_renew",
        "is_created_ticket",
        "status_code_created_ticket",
        "ticket_code",
        "reason_remove",
        "is_deleted",
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
        "custom_11",
        "custom_12",
        "custom_13",
        "custom_14",
        "custom_15",
        "custom_16",
        "custom_17",
        "custom_18",
        "custom_19",
        "custom_20",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "is_browse"
    ];

    const NOT_DELETED = 0;

    const STATUS_CANCEL = 'cancel';
    const STATUS_LIQUIDATED = 'liquidated';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';

    /**
     * load động ds hợp đồng
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $lang = app()->getLocale();
        $dsFilterTag = $this->select("{$this->table}.contract_id")
            ->leftJoin("contract_tag_map", "contract_tag_map.contract_id", "contracts.contract_id")
            ->where('contracts.is_deleted', self::NOT_DELETED)->groupBy("contracts.contract_id");
        if (isset($filter['contract_tag_id']) != "") {
            $dsFilterTag->where("contract_tag_map.tag_id", $filter['contract_tag_id']);
        }
        $dsFilterTag = $dsFilterTag->get()->toArray();
        unset($filter['contract_tag_id']);
        $ds = $this->select(
            "{$this->table}.contract_id",
            "{$this->table}.contract_category_id",
            "contract_categories.contract_category_name",
            "{$this->table}.contract_name",
            "{$this->table}.contract_code",
            "{$this->table}.contract_no",
            "{$this->table}.sign_date",
            "{$this->table}.performer_by",
            "departments.department_name",
            "staff_title.staff_title_name",
            "staff_performer.full_name as staff_performer_name",
            "staff_created_by.full_name as staff_created_by_name",
            "staff_updated_by.full_name as staff_updated_by_name",
            "customer_groups.group_name",
            "{$this->table}.effective_date",
            "{$this->table}.expired_date",
            "{$this->table}.warranty_start_date",
            "{$this->table}.warranty_end_date",
            "{$this->table}.content",
            "{$this->table}.note",
            "{$this->table}.status_code",
            "contract_category_status.status_name",
            "contract_category_status.is_edit_contract",
            "contract_category_status.is_deleted_contract",
            "contract_category_status.is_reason",
            "contract_category_status.is_show",
            "{$this->table}.is_renew",
            "{$this->table}.number_day_renew",
            "{$this->table}.is_created_ticket",
            "{$this->table}.status_code_created_ticket",
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
            "{$this->table}.custom_11",
            "{$this->table}.custom_12",
            "{$this->table}.custom_13",
            "{$this->table}.custom_14",
            "{$this->table}.custom_15",
            "{$this->table}.custom_16",
            "{$this->table}.custom_17",
            "{$this->table}.custom_18",
            "{$this->table}.custom_19",
            "{$this->table}.custom_20",
            "contract_partner.address",
            "contract_partner.email",
            "contract_partner.phone",
            "contract_partner.tax_code",
            "contract_partner.representative",
            "contract_partner.hotline",
            "contract_partner.staff_title as staff_title_name",
            "contract_partner.partner_object_type as partner_object_type",
            DB::raw("(CASE WHEN contract_partner.partner_object_type != 'supplier' THEN customers.full_name
                                ELSE suppliers.supplier_name END) as partner_name"),
            "contract_payment.total_amount",
            "contract_payment.tax",
            "contract_payment.discount",
            "contract_payment.last_total_amount",
            "payment_method.payment_method_name_$lang as payment_method_name",
            "payment_units.name as payment_unit_name",
            "{$this->table}.is_browse"
//            DB::raw("GROUP_CONCAT(contract_files.file_name) as list_file_name"),
//            DB::raw("GROUP_CONCAT(contract_files.link) as list_link"),
//            DB::raw("GROUP_CONCAT(contract_goods.object_name) as list_object_name")
        )
            ->leftJoin('contract_categories', 'contract_categories.contract_category_id', '=', "{$this->table}.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "{$this->table}.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->leftJoin('staffs as staff_performer', 'staff_performer.staff_id', '=', "{$this->table}.performer_by")
            ->leftJoin('staffs as staff_created_by', 'staff_created_by.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as staff_updated_by', 'staff_updated_by.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('departments', 'departments.department_id', '=', "staff_performer.department_id")
            ->leftJoin('branches', 'branches.branch_id', '=', "staff_performer.branch_id")
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', "staff_performer.staff_title_id")
            ->leftJoin('contract_partner', 'contract_partner.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_payment', 'contract_payment.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_follow_map', 'contract_follow_map.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_sign_map', 'contract_sign_map.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin("customers", function($join){
                $join->on("customers.customer_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "!=", DB::raw("'supplier'"));
            })
            ->leftJoin("customer_groups", function($join){
                $join->on("customer_groups.customer_group_id", "=", "customers.customer_group_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'customer'"));
            })
            ->leftJoin("suppliers", function($join){
                $join->on("suppliers.supplier_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'supplier'"));
            })
            ->leftJoin("payment_method", "payment_method.payment_method_id", "contract_payment.payment_method_id")
            ->leftJoin("payment_units", "payment_units.payment_unit_id", "contract_payment.payment_unit_id")
//            ->leftJoin("contract_files", "contract_files.contract_id", "contracts.contract_id")
            ->leftJoin("contract_goods", "contract_goods.contract_id", "contracts.contract_id")
            ->where('contracts.is_deleted', self::NOT_DELETED);
        $ds->whereIn("contracts.contract_id",$dsFilterTag);
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.contract_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.contract_no", 'like', '%' . $search . '%');
                if(json_decode(Cookie::get('arrSearch')) != null){
                    foreach (json_decode(Cookie::get('arrSearch')) as $key => $value) {
                        $value = (array)$value;
                        switch($value['key']){
                            case 'contract_name':
                                $query->orWhere("{$this->table}.contract_name", 'like', '%' . $search . '%');
                                break;
                            case 'content':
                                $query->orWhere("{$this->table}.content", 'like', '%' . $search . '%');
                                break;
                            case 'customer_name':
                                $query->orWhere("customers.full_name", 'like', '%' . $search . '%')
                                ->orWhere("suppliers.supplier_name", 'like', '%' . $search . '%');
                                break;
                            case 'tax_code':
                                $query->orWhere("{contract_partner.tax_code", 'like', '%' . $search . '%');
                                break;
                            case 'address':
                                $query->orWhere("contract_partner.address", 'like', '%' . $search . '%');
                                break;
                            case 'phone':
                                $query->orWhere("contract_partner.phone", 'like', '%' . $search . '%');
                                break;
                            case 'email':
                                $query->orWhere("contract_partner.email", 'like', '%' . $search . '%');
                                break;
                            case 'goods':
                                $query->orWhere("contract_goods.object_name", 'like', '%' . $search . '%');
                                break;
                        }
                    }
                }
            });
        }
        unset($filter['search']);
        if (isset($filter['contract_category_id']) != "") {
            $ds->where("{$this->table}.contract_category_id", $filter['contract_category_id']);
        }
        unset($filter['contract_category_id']);
        if (isset($filter['status_code']) != "") {
            $ds->where("{$this->table}.status_code", $filter['status_code']);
        }
        unset($filter['status_code']);
        if (isset($filter['customer_group_id']) != "") {
            $ds->where("customer_groups.customer_group_id", $filter['customer_group_id']);
        }
        unset($filter['customer_group_id']);
        if (isset($filter['staff_id']) != "") {
            $ds->where("staff_performer.staff_id", $filter['staff_id']);
        }
        unset($filter['staff_id']);
        if (isset($filter['staff_title_id']) != "") {
            $ds->where("staff_title.staff_title_id", $filter['staff_title_id']);
        }
        unset($filter['staff_title_id']);
        if (isset($filter['department_id']) != "") {
            $ds->where("departments.department_id", $filter['department_id']);
        }
        unset($filter['department_id']);
        if (isset($filter['payment_method_id']) != "") {
            $ds->where("payment_method.payment_method_id", $filter['payment_method_id']);
        }
        unset($filter['payment_method_id']);
        if (isset($filter['tax']) != "") {
            $ds->where("contract_payment.tax", $filter['tax']);
        }
        unset($filter['tax']);
        if (isset($filter['compare_total_amount']) != "" && isset($filter['total_amount']) != "") {
            $ds->where("contract_payment.total_amount", "'{$filter['compare_total_amount']}'", $filter['total_amount']);
        }
        unset($filter['compare_total_amount'], $filter['total_amount']);
        if (isset($filter["expired_date"]) != "") {
            $arr_filter = explode(" - ", $filter["expired_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.expired_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"]);
        if (isset($filter["effective_date"]) != "") {
            $arr_filter = explode(" - ", $filter["effective_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.effective_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["effective_date"]);
        if (isset($filter["sign_date"]) != "") {
            $arr_filter = explode(" - ", $filter["sign_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.sign_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["sign_date"]);
        if (isset($filter["warranty_start_date"]) != "") {
            $arr_filter = explode(" - ", $filter["warranty_start_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.warranty_start_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["warranty_start_date"]);
        if (isset($filter["warranty_end_date"]) != "") {
            $arr_filter = explode(" - ", $filter["warranty_end_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.warranty_end_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["warranty_end_date"]);
        // filter phân quyền data
        $userId = \auth()->id();
        $branchId = Auth::user()->branch_id;
        $mStaff = new \Modules\Admin\Models\StaffTable();
        $departmentId = $mStaff->getDetail($userId)['department_id'];
        if (isset($filter['role_data']) && $filter['role_data'] != "") {
            if($filter['role_data'] == 'all'){

            }
            else if($filter['role_data'] == 'branch'){
                $ds->where(function($query)use($branchId, $userId){
                    $query->where("staff_performer.branch_id", $branchId)
                        ->orWhere("staff_performer.staff_id", $userId)
                        ->orWhere("staff_created_by.staff_id", $userId)
                        ->orWhere("contract_follow_map.follow_by", $userId)
                        ->orWhere("contract_sign_map.sign_by", $userId);
                });
            }
            else if($filter['role_data'] == 'department'){
                $ds->where(function($query)use($departmentId, $userId){
                    $query->where("staff_performer.department_id", $departmentId)
                        ->orWhere("staff_performer.staff_id", $userId)
                        ->orWhere("staff_created_by.staff_id", $userId)
                        ->orWhere("contract_follow_map.follow_by", $userId)
                        ->orWhere("contract_sign_map.sign_by", $userId);
                });
            }
        }
        else{
            // filter phân quyền data owner
            $ds->where(function($query)use($userId){
                $query->where("staff_performer.staff_id", $userId)
                    ->orWhere("staff_created_by.staff_id", $userId)
                    ->orWhere("contract_follow_map.follow_by", $userId)
                    ->orWhere("contract_sign_map.sign_by", $userId);
            });
        }
        unset($filter['role_data']);
        $ds->groupBy("contracts.contract_id")->orderBy("{$this->table}.created_at",'desc');
        return $ds;
    }


    /**
     * ds chi tiết báo cáo hợp đồng
     *
     * @param array $filter
     * @return mixed
     */
    public function getListDetail(&$filter = [])
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $lang = app()->getLocale();
        $ds = $this->select(
            "{$this->table}.contract_id",
            "{$this->table}.contract_category_id",
            "contract_categories.contract_category_name",
            "contract_categories.type",
            "{$this->table}.contract_name",
            "{$this->table}.contract_code",
            "{$this->table}.contract_no",
            "{$this->table}.sign_date",
            "{$this->table}.performer_by",
            "staff_performer.full_name as staff_performer_name",
            "{$this->table}.effective_date",
            "{$this->table}.expired_date",
            "{$this->table}.warranty_start_date",
            "{$this->table}.warranty_end_date",
            "{$this->table}.content",
            "{$this->table}.note",
            "{$this->table}.status_code",
            "contract_category_status.status_name",
            "contract_partner.partner_object_type as partner_object_type",
            DB::raw("(CASE WHEN contract_partner.partner_object_type != 'supplier' THEN customers.full_name
                                ELSE suppliers.supplier_name END) as partner_name"),
            "contract_payment.total_amount",
            "contract_payment.tax",
            "contract_payment.discount",
            "contract_payment.last_total_amount",
            "payment_method.payment_method_name_$lang as payment_method_name",
            "payment_units.name as payment_unit_name",
            "{$this->table}.is_browse"
        )
            ->leftJoin('contract_categories', 'contract_categories.contract_category_id', '=', "{$this->table}.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "{$this->table}.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->leftJoin('staffs as staff_performer', 'staff_performer.staff_id', '=', "{$this->table}.performer_by")
            ->leftJoin('staffs as staff_created_by', 'staff_created_by.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as staff_updated_by', 'staff_updated_by.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('contract_partner', 'contract_partner.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_payment', 'contract_payment.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin("customers", function($join){
                $join->on("customers.customer_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "!=", DB::raw("'supplier'"));
            })
            ->leftJoin("customer_groups", function($join){
                $join->on("customer_groups.customer_group_id", "=", "customers.customer_group_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'customer'"));
            })
            ->leftJoin("suppliers", function($join){
                $join->on("suppliers.supplier_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'supplier'"));
            })
            ->leftJoin("payment_method", "payment_method.payment_method_id", "contract_payment.payment_method_id")
            ->leftJoin("payment_units", "payment_units.payment_unit_id", "contract_payment.payment_unit_id")
//            ->leftJoin("contract_files", "contract_files.contract_id", "contracts.contract_id")
            ->leftJoin("contract_goods", "contract_goods.contract_id", "contracts.contract_id")
            ->where('contracts.is_deleted', self::NOT_DELETED);
        if (isset($filter['contract_category_id']) && $filter['contract_category_id'] != "") {
            $ds->where("{$this->table}.contract_category_id", $filter['contract_category_id']);
        }
        unset($filter['contract_category_id']);

        if (isset($filter['status_code']) && $filter['status_code']!= "") {
            $ds->where("{$this->table}.status_code", $filter['status_code']);
        }
        unset($filter['status_code']);

        if(isset($filter['partner_object']) && $filter['partner_object'] != ''){
            $arr_filter = explode("_", $filter["partner_object"]);
            $ds->where("contract_partner.partner_object_type", $arr_filter[0]);
            $ds->where("contract_partner.partner_object_id", $arr_filter[1]);
        }
        unset($filter['partner_object']);

        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"]);
        if (isset($filter["expired_date"]) && $filter["expired_date"] != "") {
            $arr_filter = explode(" - ", $filter["expired_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.expired_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"]);

        if (isset($filter["warranty_end_date"]) && $filter["warranty_end_date"] != "") {
            $arr_filter = explode(" - ", $filter["warranty_end_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.warranty_end_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["warranty_end_date"]);

        $ds->groupBy("contracts.contract_id")->orderBy("{$this->table}.expired_date",'desc');

        return $ds->paginate($display, $columns = ['*'], $pageName = 'page',
            $page);
    }

    /**
     * export ds chi tiết báo cáo hợp đồng
     *
     * @param array $filter
     * @return mixed
     */
    public function getListDetailExport(&$filter = [])
    {
        $lang = app()->getLocale();
        $ds = $this->select(
            "{$this->table}.contract_id",
            "{$this->table}.contract_category_id",
            "contract_categories.contract_category_name",
            "contract_categories.type",
            "{$this->table}.contract_name",
            "{$this->table}.contract_code",
            "{$this->table}.contract_no",
            "{$this->table}.sign_date",
            "{$this->table}.performer_by",
            "staff_performer.full_name as staff_performer_name",
            "{$this->table}.effective_date",
            "{$this->table}.expired_date",
            "{$this->table}.warranty_start_date",
            "{$this->table}.warranty_end_date",
            "{$this->table}.content",
            "{$this->table}.note",
            "{$this->table}.status_code",
            "contract_category_status.status_name",
            "contract_partner.partner_object_type as partner_object_type",
            DB::raw("(CASE WHEN contract_partner.partner_object_type != 'supplier' THEN customers.full_name
                                ELSE suppliers.supplier_name END) as partner_name"),
            "contract_payment.total_amount",
            "contract_payment.tax",
            "contract_payment.discount",
            "contract_payment.last_total_amount",
            "payment_method.payment_method_name_$lang as payment_method_name",
            "payment_units.name as payment_unit_name",
            "{$this->table}.is_browse"
        )
            ->leftJoin('contract_categories', 'contract_categories.contract_category_id', '=', "{$this->table}.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "{$this->table}.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->leftJoin('staffs as staff_performer', 'staff_performer.staff_id', '=', "{$this->table}.performer_by")
            ->leftJoin('staffs as staff_created_by', 'staff_created_by.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as staff_updated_by', 'staff_updated_by.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('contract_partner', 'contract_partner.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_payment', 'contract_payment.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin("customers", function($join){
                $join->on("customers.customer_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "!=", DB::raw("'supplier'"));
            })
            ->leftJoin("customer_groups", function($join){
                $join->on("customer_groups.customer_group_id", "=", "customers.customer_group_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'customer'"));
            })
            ->leftJoin("suppliers", function($join){
                $join->on("suppliers.supplier_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'supplier'"));
            })
            ->leftJoin("payment_method", "payment_method.payment_method_id", "contract_payment.payment_method_id")
            ->leftJoin("payment_units", "payment_units.payment_unit_id", "contract_payment.payment_unit_id")
//            ->leftJoin("contract_files", "contract_files.contract_id", "contracts.contract_id")
            ->leftJoin("contract_goods", "contract_goods.contract_id", "contracts.contract_id")
            ->where('contracts.is_deleted', self::NOT_DELETED);
        if (isset($filter['contract_category_id']) && $filter['contract_category_id'] != "") {
            $ds->where("{$this->table}.contract_category_id", $filter['contract_category_id']);
        }
        unset($filter['contract_category_id']);

        if (isset($filter['status_code']) && $filter['status_code']!= "") {
            $ds->where("{$this->table}.status_code", $filter['status_code']);
        }
        unset($filter['status_code']);

        if(isset($filter['partner_object']) && $filter['partner_object'] != ''){
            $arr_filter = explode("_", $filter["partner_object"]);
            $ds->where("contract_partner.partner_object_type", $arr_filter[0]);
            $ds->where("contract_partner.partner_object_id", $arr_filter[1]);
        }
        unset($filter['partner_object']);

        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"]);
        if (isset($filter["expired_date"]) && $filter["expired_date"] != "") {
            $arr_filter = explode(" - ", $filter["expired_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.expired_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"]);

        if (isset($filter["warranty_end_date"]) && $filter["warranty_end_date"] != "") {
            $arr_filter = explode(" - ", $filter["warranty_end_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.warranty_end_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["warranty_end_date"]);

        $ds->groupBy("contracts.contract_id")->orderBy("{$this->table}.expired_date",'desc');

        return $ds->get();
    }

    public function getExport(&$filter = [])
    {
        $lang = app()->getLocale();
        $dsFilterTag = $this->select("{$this->table}.contract_id")
            ->leftJoin("contract_tag_map", "contract_tag_map.contract_id", "contracts.contract_id")
            ->where('contracts.is_deleted', self::NOT_DELETED)->groupBy("contracts.contract_id");
        if (isset($filter['contract_tag_id']) != "") {
            $dsFilterTag->where("contract_tag_map.tag_id", $filter['contract_tag_id']);
        }
        $dsFilterTag = $dsFilterTag->get()->toArray();
        unset($filter['contract_tag_id']);
        $ds = $this->select(
            "{$this->table}.contract_id",
            "{$this->table}.contract_category_id",
            "contract_categories.contract_category_name",
            "{$this->table}.contract_name",
            "{$this->table}.contract_code",
            "{$this->table}.contract_no",
            "{$this->table}.sign_date",
            "{$this->table}.performer_by",
            "departments.department_name",
            "staff_title.staff_title_name",
            "staff_performer.full_name as staff_performer_name",
            "staff_created_by.full_name as staff_created_by_name",
            "staff_updated_by.full_name as staff_updated_by_name",
            "customer_groups.group_name",
            "{$this->table}.effective_date",
            "{$this->table}.expired_date",
            "{$this->table}.warranty_start_date",
            "{$this->table}.warranty_end_date",
            "{$this->table}.content",
            "{$this->table}.note",
            "{$this->table}.status_code",
            "contract_category_status.status_name",
            "contract_category_status.is_edit_contract",
            "contract_category_status.is_deleted_contract",
            "contract_category_status.is_reason",
            "contract_category_status.is_show",
            "{$this->table}.is_renew",
            "{$this->table}.number_day_renew",
            "{$this->table}.is_created_ticket",
            "{$this->table}.status_code_created_ticket",
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
            "{$this->table}.custom_11",
            "{$this->table}.custom_12",
            "{$this->table}.custom_13",
            "{$this->table}.custom_14",
            "{$this->table}.custom_15",
            "{$this->table}.custom_16",
            "{$this->table}.custom_17",
            "{$this->table}.custom_18",
            "{$this->table}.custom_19",
            "{$this->table}.custom_20",
            "contract_partner.address",
            "contract_partner.email",
            "contract_partner.phone",
            "contract_partner.tax_code",
            "contract_partner.representative",
            "contract_partner.hotline",
            "contract_partner.staff_title as staff_title_name",
            "contract_partner.partner_object_type as partner_object_type",
            DB::raw("(CASE WHEN contract_partner.partner_object_type != 'supplier' THEN customers.full_name
                                ELSE suppliers.supplier_name END) as partner_name"),
            "contract_payment.total_amount",
            "contract_payment.tax",
            "contract_payment.discount",
            "contract_payment.last_total_amount",
            "payment_method.payment_method_name_$lang as payment_method_name",
            "payment_units.name as payment_unit_name"
//            DB::raw("GROUP_CONCAT(contract_files.file_name) as list_file_name"),
//            DB::raw("GROUP_CONCAT(contract_files.link) as list_link"),
//            DB::raw("GROUP_CONCAT(contract_goods.object_name) as list_object_name")
        )
            ->leftJoin('contract_categories', 'contract_categories.contract_category_id', '=', "{$this->table}.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "{$this->table}.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->leftJoin('staffs as staff_performer', 'staff_performer.staff_id', '=', "{$this->table}.performer_by")
            ->leftJoin('staffs as staff_created_by', 'staff_created_by.staff_id', '=', "{$this->table}.created_by")
            ->leftJoin('staffs as staff_updated_by', 'staff_updated_by.staff_id', '=', "{$this->table}.updated_by")
            ->leftJoin('departments', 'departments.department_id', '=', "staff_performer.department_id")
            ->leftJoin('branches', 'branches.branch_id', '=', "staff_performer.branch_id")
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', "staff_performer.staff_title_id")
            ->leftJoin('contract_partner', 'contract_partner.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_payment', 'contract_payment.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_follow_map', 'contract_follow_map.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin('contract_sign_map', 'contract_sign_map.contract_id', '=', "{$this->table}.contract_id")
            ->leftJoin("customers", function($join){
                $join->on("customers.customer_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "!=", DB::raw("'supplier'"));
            })
            ->leftJoin("customer_groups", function($join){
                $join->on("customer_groups.customer_group_id", "=", "customers.customer_group_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'customer'"));
            })
            ->leftJoin("suppliers", function($join){
                $join->on("suppliers.supplier_id", "=", "contract_partner.partner_object_id")
                    ->where("contract_partner.partner_object_type", "=", DB::raw("'supplier'"));
            })
            ->leftJoin("payment_method", "payment_method.payment_method_id", "contract_payment.payment_method_id")
            ->leftJoin("payment_units", "payment_units.payment_unit_id", "contract_payment.payment_unit_id")
//            ->leftJoin("contract_files", "contract_files.contract_id", "contracts.contract_id")
            ->leftJoin("contract_goods", "contract_goods.contract_id", "contracts.contract_id")
            ->where('contracts.is_deleted', self::NOT_DELETED);
        $ds->whereIn("contracts.contract_id",$dsFilterTag);
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.contract_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.contract_no", 'like', '%' . $search . '%');
                if(json_decode(Cookie::get('arrSearch')) != null){
                    foreach (json_decode(Cookie::get('arrSearch')) as $key => $value) {
                        $value = (array)$value;
                        switch($value['key']){
                            case 'contract_name':
                                $query->orWhere("{$this->table}.contract_name", 'like', '%' . $search . '%');
                                break;
                            case 'content':
                                $query->orWhere("{$this->table}.content", 'like', '%' . $search . '%');
                                break;
                            case 'customer_name':
                                $query->orWhere("customers.full_name", 'like', '%' . $search . '%')
                                    ->orWhere("suppliers.supplier_name", 'like', '%' . $search . '%');
                                break;
                            case 'tax_code':
                                $query->orWhere("{contract_partner.tax_code", 'like', '%' . $search . '%');
                                break;
                            case 'address':
                                $query->orWhere("contract_partner.address", 'like', '%' . $search . '%');
                                break;
                            case 'phone':
                                $query->orWhere("contract_partner.phone", 'like', '%' . $search . '%');
                                break;
                            case 'email':
                                $query->orWhere("contract_partner.email", 'like', '%' . $search . '%');
                                break;
                            case 'goods':
                                $query->orWhere("contract_goods.object_name", 'like', '%' . $search . '%');
                                break;
                        }
                    }
                }
            });
        }
        unset($filter['search']);
        if (isset($filter['contract_category_id']) != "") {
            $ds->where("{$this->table}.contract_category_id", $filter['contract_category_id']);
        }
        unset($filter['contract_category_id']);
        if (isset($filter['status_code']) != "") {
            $ds->where("{$this->table}.status_code", $filter['status_code']);
        }
        unset($filter['status_code']);
        if (isset($filter['customer_group_id']) != "") {
            $ds->where("customer_groups.customer_group_id", $filter['customer_group_id']);
        }
        unset($filter['customer_group_id']);
        if (isset($filter['staff_id']) != "") {
            $ds->where("staff_performer.staff_id", $filter['staff_id']);
        }
        unset($filter['staff_id']);
        if (isset($filter['staff_title_id']) != "") {
            $ds->where("staff_title.staff_title_id", $filter['staff_title_id']);
        }
        unset($filter['staff_title_id']);
        if (isset($filter['department_id']) != "") {
            $ds->where("departments.department_id", $filter['department_id']);
        }
        unset($filter['department_id']);
        if (isset($filter['payment_method_id']) != "") {
            $ds->where("payment_method.payment_method_id", $filter['payment_method_id']);
        }
        unset($filter['payment_method_id']);
        if (isset($filter['tax']) != "") {
            $ds->where("contract_payment.tax", $filter['tax']);
        }
        unset($filter['tax']);
        if (isset($filter['compare_total_amount']) != "" && isset($filter['total_amount']) != "") {
            $ds->where("contract_payment.total_amount", "'{$filter['compare_total_amount']}'", $filter['total_amount']);
        }
        unset($filter['compare_total_amount'], $filter['total_amount']);
        if (isset($filter["expired_date"]) != "") {
            $arr_filter = explode(" - ", $filter["expired_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.expired_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["expired_date"]);
        if (isset($filter["effective_date"]) != "") {
            $arr_filter = explode(" - ", $filter["effective_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.effective_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["effective_date"]);
        if (isset($filter["sign_date"]) != "") {
            $arr_filter = explode(" - ", $filter["sign_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.sign_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["sign_date"]);
        if (isset($filter["warranty_start_date"]) != "") {
            $arr_filter = explode(" - ", $filter["warranty_start_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.warranty_start_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["warranty_start_date"]);
        if (isset($filter["warranty_end_date"]) != "") {
            $arr_filter = explode(" - ", $filter["warranty_end_date"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.warranty_end_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        unset($filter["warranty_end_date"]);
        // filter phân quyền data
        $userId = \auth()->id();
        $branchId = Auth::user()->branch_id;
        $mStaff = new \Modules\Admin\Models\StaffTable();
        $departmentId = $mStaff->getDetail($userId)['department_id'];
        if (isset($filter['role_data']) != "") {
            if($filter['role_data'] == 'all'){

            }
            else if($filter['role_data'] == 'branch'){
                $ds->where(function($query)use($branchId, $userId){
                    $query->where("staff_performer.branch_id", $branchId)
                        ->orWhere("staff_performer.staff_id", $userId)
                        ->orWhere("staff_created_by.staff_id", $userId)
                        ->orWhere("contract_follow_map.follow_by", $userId)
                        ->orWhere("contract_sign_map.sign_by", $userId);
                });
            }
            else if($filter['role_data'] == 'department'){
                $ds->where(function($query)use($departmentId, $userId){
                    $query->where("staff_performer.department_id", $departmentId)
                        ->orWhere("staff_performer.staff_id", $userId)
                        ->orWhere("staff_created_by.staff_id", $userId)
                        ->orWhere("contract_follow_map.follow_by", $userId)
                        ->orWhere("contract_sign_map.sign_by", $userId);
                });
            }
        }
        else{
            // filter phân quyền data owner
            $ds->where(function($query)use($userId){
                $query->where("staff_performer.staff_id", $userId)
                    ->orWhere("staff_created_by.staff_id", $userId)
                    ->orWhere("contract_follow_map.follow_by", $userId)
                    ->orWhere("contract_sign_map.sign_by", $userId);
            });
        }
        unset($filter['role_data']);
        $ds->groupBy("contracts.contract_id")->orderBy("{$this->table}.created_at",'desc');
        return $ds->get();
    }

    /**
     * ds tên fìle theo contract
     *
     * @param $contractId
     * @return mixed
     */
    public function getListFileNameOfContract($contractId)
    {
        $ds = $this->select(
            DB::raw("GROUP_CONCAT(contract_file_details.file_name) as list_file_name"),
            DB::raw("GROUP_CONCAT(contract_file_details.link) as list_link")
        )
        ->leftJoin("contract_files", "contract_files.contract_id", "contracts.contract_id")
        ->leftJoin("contract_file_details", "contract_files.contract_file_id", "contract_file_details.contract_file_id")
            ->where('contracts.contract_id', $contractId)
            ->groupBy("contracts.contract_id");
        return $ds->first();
    }
    public function getListGoodOfContract($contractId)
    {
        $ds = $this->select(
            DB::raw("GROUP_CONCAT(contract_goods.object_name) as list_object_name")
        )
            ->leftJoin("contract_goods", "contract_goods.contract_id", "contracts.contract_id")
            ->where('contracts.contract_id', $contractId)
            ->groupBy("contracts.contract_id");
        return $ds->first();
    }

    /**
     * Thêm HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_id;
    }

    /**
     * Cập nhật HĐ
     *
     * @param array $data
     * @param $contractId
     * @return mixed
     */
    public function edit(array $data, $contractId)
    {
        return $this->where("contract_id", $contractId)->update($data);
    }

    /**
     * Lấy thông tin HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function getInfo($contractId)
    {
        return $this
            ->select(
                "{$this->table}.contract_id",
                "{$this->table}.contract_category_id",
                "{$this->table}.contract_name",
                "{$this->table}.contract_code",
                "{$this->table}.contract_no",
                "{$this->table}.sign_date",
                "{$this->table}.performer_by",
                "{$this->table}.effective_date",
                "{$this->table}.expired_date",
                "{$this->table}.warranty_start_date",
                "{$this->table}.warranty_end_date",
                "{$this->table}.content",
                "{$this->table}.note",
                "{$this->table}.status_code",
                "{$this->table}.is_value_goods",
                "{$this->table}.is_renew",
                "{$this->table}.number_day_renew",
                "{$this->table}.is_created_ticket",
                "{$this->table}.status_code_created_ticket",
                "{$this->table}.ticket_code",
                "{$this->table}.is_deleted",
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
                "{$this->table}.custom_11",
                "{$this->table}.custom_12",
                "{$this->table}.custom_13",
                "{$this->table}.custom_14",
                "{$this->table}.custom_15",
                "{$this->table}.custom_16",
                "{$this->table}.custom_17",
                "{$this->table}.custom_18",
                "{$this->table}.custom_19",
                "{$this->table}.custom_20",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "ctc.contract_category_name",
                "ctc.type",
                "st.status_name",
                "{$this->table}.is_browse"
            )
            ->join("contract_categories as ctc", "ctc.contract_category_id", "=", "{$this->table}.contract_category_id")
            ->join("contract_category_status as st", "st.status_code", "=", "{$this->table}.status_code")
            ->where("{$this->table}.contract_id", $contractId)
            ->first();
    }
    public function getInfoByCode($contractCode)
    {
        return $this
            ->select(
                "{$this->table}.contract_id",
                "{$this->table}.contract_category_id",
                "{$this->table}.contract_name",
                "{$this->table}.contract_code",
                "{$this->table}.contract_no",
                "{$this->table}.sign_date",
                "{$this->table}.performer_by",
                "{$this->table}.effective_date",
                "{$this->table}.expired_date",
                "{$this->table}.warranty_start_date",
                "{$this->table}.warranty_end_date",
                "{$this->table}.content",
                "{$this->table}.note",
                "{$this->table}.status_code",
                "{$this->table}.is_value_goods",
                "{$this->table}.is_renew",
                "{$this->table}.number_day_renew",
                "{$this->table}.is_created_ticket",
                "{$this->table}.status_code_created_ticket",
                "{$this->table}.ticket_code",
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
                "{$this->table}.custom_11",
                "{$this->table}.custom_12",
                "{$this->table}.custom_13",
                "{$this->table}.custom_14",
                "{$this->table}.custom_15",
                "{$this->table}.custom_16",
                "{$this->table}.custom_17",
                "{$this->table}.custom_18",
                "{$this->table}.custom_19",
                "{$this->table}.custom_20",
                "ctc.contract_category_name",
                "ctc.type",
                "st.status_name",
                "{$this->table}.is_browse"
            )
            ->join("contract_categories as ctc", "ctc.contract_category_id", "=", "{$this->table}.contract_category_id")
            ->join("contract_category_status as st", "st.status_code", "=", "{$this->table}.status_code")
            ->where("{$this->table}.contract_code", $contractCode)
            ->first();
    }

    /**
     * get all contract expire this day running
     *
     * @return mixed
     */
    public function getContractExpire()
    {
        $dateNow = Carbon::now()->subDays(1)->format('Y-m-d');
        $ds = $this->select("contracts.contract_id")
            ->leftJoin("contract_categories", "contract_categories.contract_category_id", "contracts.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "{$this->table}.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->where("contracts.is_deleted", 0)
            ->where("contract_categories.type", "sell")
            ->where(function($join)use($dateNow){
                $join->where(function($subJoin1)use($dateNow){
                    $subJoin1
                        ->where("contracts.is_renew", 1)
                        ->whereNotNull("contracts.expired_date")
                        ->whereBetween("contracts.expired_date", [$dateNow . " 00:00:00", $dateNow . " 23:59:59"]);
                })
                    ->orWhere(function($subJoin2)use($dateNow){
                        $subJoin2
                            ->where("contract_category_status.default_system", "liquidated")
                            ->whereBetween("contracts.updated_at", [$dateNow . " 00:00:00", $dateNow . " 23:59:59"]);
                    });
            });
        return $ds->get();
    }

    /**
     * get all contract soon expire this day running
     *
     * @return mixed
     */
    public function getContractSoonExpire()
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $ds = $this->select("contracts.contract_id")
            ->leftJoin("contract_categories", "contract_categories.contract_category_id", "contracts.contract_category_id")
            ->leftJoin("contract_category_status", function($join){
                $join->on("contract_category_status.status_code", "=", "{$this->table}.status_code")
                    ->on("contract_category_status.contract_category_id", "=", "contract_categories.contract_category_id");
            })
            ->where("contracts.is_deleted", 0)
            ->where("contract_categories.type", "sell")
            ->where("contracts.is_renew", 1)
            ->whereNotNull("contracts.expired_date")
            ->whereBetween(DB::raw("DATE_SUB(contracts.expired_date, INTERVAL contracts.number_day_renew DAY)"),
                [$dateNow . " 00:00:00", $dateNow . " 23:59:59"]);
        return $ds->get();
    }

    /**
     * Kiểm tra số HĐ đã tồn tại chưa
     *
     * @param $contractNo
     * @return mixed
     */
    public function checkExistContractNo($contractNo)
    {
        return $this
            ->where("contract_no", $contractNo)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    public function getOverviewAllContract()
    {
        $ds = $this->select(
            "contracts.contract_id",
            "contract_payment.last_total_amount"
        )
            ->leftJoin("contract_payment", "contract_payment.contract_id", "contracts.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contract_category_status.default_system", "<>",  self::STATUS_CANCEL);
        return $ds->get()->toArray();
    }

    public function getOverviewValidated()
    {
        $ds = $this->select(
            "contracts.contract_id",
            "contract_payment.last_total_amount"
        )
            ->leftJoin("contract_payment", "contract_payment.contract_id", "contracts.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contract_category_status.default_system", "=", self::STATUS_PROCESSING);
        return $ds->get()->toArray();
    }

    public function getOverviewLiquidated()
    {
        $ds = $this->select(
            "contracts.contract_id",
            "contract_payment.last_total_amount"
        )
            ->leftJoin("contract_payment", "contract_payment.contract_id", "contracts.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contract_category_status.default_system", "=", self::STATUS_LIQUIDATED);
        return $ds->get()->toArray();
    }

    public function getOverviewWaitingLiquidation()
    {
        $ds = $this->select(
            "contracts.contract_id",
            "contract_payment.last_total_amount"
        )
            ->leftJoin("contract_payment", "contract_payment.contract_id", "contracts.contract_id")
            ->leftJoin("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->whereNotNull("contracts.effective_date")
            ->where("contracts.expired_date", "<", Carbon::now())
            ->whereNotIn("contract_category_status.default_system", [self::STATUS_LIQUIDATED, self::STATUS_CANCEL, self::STATUS_DRAFT, self::STATUS_PENDING]);

        return $ds->get()->toArray();
    }

    public function getListContractByCustomer($customerId)
    {
        $ds = $this->select(
            "contracts.contract_id",
            "contracts.contract_code",
            "contracts.contract_name",
            "contract_categories.contract_category_name",
            "contract_categories.type",
            "contract_category_status.status_name",
            "contract_payment.last_total_amount"
        )
            ->join("contract_partner",function($join){
                $join->on("contract_partner.contract_id",'=', "contracts.contract_id")
                    ->where("contract_partner.partner_object_type", "<>", "supplier");
            })
            ->join("contract_payment", "contract_payment.contract_id", "contracts.contract_id")
            ->join("contract_categories", "contract_categories.contract_category_id", "contracts.contract_category_id")
            ->join("contract_category_status", "contract_category_status.status_code", "contracts.status_code")
            ->where("contracts.is_deleted", 0)
            ->where("contract_partner.partner_object_id", $customerId);
        return $ds->get()->toArray();
    }

    /**
     * Lấy tất cả hợp đồng
     *
     * @return mixed
     */
    public function getAllContract()
    {
        return $this->select("*")->get();
    }
}