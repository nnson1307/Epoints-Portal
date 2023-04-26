<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 19/2/2019
 * Time: 21:59
 */

namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class EmailLogTable extends Model
{
    protected $table = 'email_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'campaign_id', 'email', 'customer_name', 'email_status', 'email_type', 'content_sent',
        'created_at', 'updated_at', 'time_sent', 'time_sent_done', 'provider', 'sent_by',
        'created_by', 'updated_by','object_id','object_type','type_customer'
    ];

    const NEW = "new";

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    public function getItem($id_campaign)
    {
        $ds = $this->leftJoin('staffs as sf_add', 'sf_add.staff_id', '=', 'email_log.created_by')
            ->leftJoin('staffs as sf_sent', 'sf_sent.staff_id', '=', 'email_log.sent_by')
            ->select(
                'email_log.id', 'email_log.email',
                'email_log.customer_name',
                'email_log.email_status',
                'email_log.email_type',
                'email_log.content_sent',
                'email_log.created_at',
                'email_log.email_status',
                'email_log.time_sent',
                'email_log.created_at',
                'email_log.time_sent',
                'sf_add.full_name',
                'sf_sent.full_name as name_sent')
            ->where('email_log.campaign_id', $id_campaign)->get();
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function remove($id)
    {
        return $this->where('id', $id)->delete();
    }

    public function groupStatus($id, $status)
    {
        $ds = $this->select('email_status', DB::raw("COUNT(email_status) as number"))
            ->where('campaign_id', $id)
            ->where('email_status', $status)
            ->groupBy('email_status')->first();
        return $ds;
    }

    public function getTypeLog($type)
    {
        $ds = $this->select('id', 'email', 'customer_name', 'email_status', 'email_type')
            ->where('email_type', $type)->get();
        return $ds;
    }

    public function _getList($id, &$filter = [])
    {
        $oSelect = $this
            ->leftJoin('staffs as staff_add', 'staff_add.staff_id', '=', 'email_log.created_by')
            ->leftJoin('staffs as staff_sent', 'staff_sent.staff_id', '=', 'email_log.sent_by')
            ->select('campaign_id',
                'email_log.email',
                'email_log.customer_name',
                'email_log.email_status',
                'email_log.email_type',
                'email_log.content_sent',
                'email_log.created_at',
                'email_log.created_by',
                'email_log.time_sent',
                'email_log.time_sent_done',
                'staff_add.full_name as name_add',
                'staff_sent.full_name as name_sent')
            ->where('email_log.campaign_id', $id)
            ->orderBy('email_log.created_at','desc');
        return $oSelect;
    }

    public function getList($id, array $filter = [])
    {
        $select = $this->_getList($id, $filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        // search term
        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
        }
        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display']);

        // filter list
        foreach ($filter as $key => $val) {
            if (trim($val) == '') {
                continue;
            }

            $select->where(str_replace('$', '.', $key), $val);
        }

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getLogNotTimeSent($time_now)
    {
        $ds=$this->select('id','campaign_id','email','customer_name','email_status',
            'email_type','object_id','object_type','content_sent','time_sent')
            ->whereDate('created_at',$time_now)->get();
        return $ds;
    }

    public function getLogIsTimeSent($time_now)
    {
        $ds=$this->select('id','campaign_id','email','customer_name','email_status',
            'email_type','object_id','object_type','content_sent','time_sent')
            ->whereDate('time_sent',$time_now)->get();
        return $ds;
    }

    /**
     * Kiểm tra sms_log đã tồn tại trong chưa
     *
     * @param $emailType
     * @param $objectType
     * @param $objectId
     * @return mixed
     */
    public function checkLogExist($emailType, $objectType, $objectId)
    {
        return $this
            ->where("email_type", $emailType)
            ->where("object_type", $objectType)
            ->where("object_id", $objectId)
            ->whereDate("created_at", Carbon::now()->format('Y-m-d'))
            ->first();
    }

    /**
     * Lấy log email chăm sóc khách hàng
     *
     * @param $timeSent
     * @return mixed
     */
    public function getLogLoyalty($timeSent)
    {
        return $this
            ->select(
                "{$this->table}.id",
                "{$this->table}.email",
                "{$this->table}.customer_name",
                "{$this->table}.content_sent",
                "{$this->table}.customer_name",
                "{$this->table}.email_type",
                "{$this->table}.object_id",
                "email_config.title",
                "{$this->table}.email_status"
            )
            ->join("email_config", "email_config.key", "=", "{$this->table}.email_type")
            ->where("{$this->table}.email_status", self::NEW)
            ->where(function ($query) use ($timeSent) {
                $query->where("{$this->table}.time_sent", '<', $timeSent)
                    ->orWhere("{$this->table}.time_sent", '=', $timeSent)
                    ->orWhere("{$this->table}.time_sent", null)
                    ->orWhere("{$this->table}.time_sent", '');
            })
            ->whereNull("{$this->table}.campaign_id")
            ->get();
    }

    /**
     * Lấy log email theo chương trình marketing
     *
     * @param $campaignId
     * @return mixed
     */
    public function getLogMarketing($campaignId)
    {
        return $this
            ->where('email_status', self::NEW)
            ->where("campaign_id", $campaignId)
            ->get();
    }
    public function getCustomerApproach($filter)
    {
        $data = $this->select(
            DB::raw("SUM(IF({$this->table}.type_customer = 'lead', 1, 0)) as sum_lead"),
            DB::raw("SUM(IF({$this->table}.type_customer = 'customer', 1, 0)) as sum_customer"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_id) as sum_lead_convert")
        )
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_id", "{$this->table}.object_id")
                    ->where("{$this->table}.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->whereNotNull("{$this->table}.type_customer")
            ->whereNotNull("{$this->table}.campaign_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if(isset($filter['option_email']) != ''){
            $data->where("{$this->table}.campaign_id", $filter['option_email']);
        }
        return $data->get()->toArray();
    }
    public function getCustomerApproachPerformance($filter)
    {
        $data = $this->select(
            DB::raw("SUM(IF({$this->table}.type_customer = 'lead', 1, 0)) as sum_lead"),
            DB::raw("SUM(IF({$this->table}.type_customer = 'customer', 1, 0)) as sum_customer"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_id) as sum_lead_convert")
        )
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_id", "{$this->table}.object_id")
                    ->where("{$this->table}.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereNotNull("{$this->table}.type_customer")
            ->whereNotNull("{$this->table}.campaign_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
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
}