<?php


namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChatbotHistoryTable extends Model
{
    protected $table = 'chathub_history';
    protected $primaryKey = 'history_id';
    protected $fillable = [
        'history_id',
        'session_id',
        'source',
        'conversation',
        'conversation_next',
        'query',
        'parameters',
        'parameters_parse',
        'brand',
        'sub_brand',
        'sku',
        'attribute',
        'response_detail_id',
        'response_content',
        'response_same',
        'first_history',
        'request_time',
        'response_time'
    ];
    protected $arr_attr = [1, 2, 3, 4, 5];
    protected $dateQueryCompletion = '2020-01-15';

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function totalMessage($date_range, $limit = null, $page = null)
    {
        $ds = $this
            ->leftJoin('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->leftJoin('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->leftJoin('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->leftJoin('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->leftJoin('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->leftJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->select(
                'chathub_history.query',
                'chathub_history.response_content',
                'chathub_history.session_id',
                'chathub_history.conversation',
                'chathub_history.request_time',
                'chathub_history.response_time',
                'chathub_response_content.response_target',
                'chathub_history.brand',
                'chathub_history.sku',
                'chathub_history.attribute',
                'chathub_brand.brand_name',
                'chathub_attribute.attribute_name',
                'chathub_sku.sku_name',
                'chathub_response_content.response_forward',
                'chathub_response_detail.type',
                'chathub_attribute.type as attr_type',
                'chathub_history.type as ib_type',
                'chathub_history.post_id'
            );
//            ->whereNotIn('chathub_history.query', ['NestleOnBot', 'NestleOffBot']);
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if($limit){
            return $ds->paginate($limit, $columns = ['*'], $pageName = 'page', $page);
        }else{
            return $ds->get();
        }
        
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageBrand($date_range)
    {
        $ds = $this
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->rightJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_response_content.brand_entities')
            ->select(
                'chathub_brand.brand_name',
                DB::raw('count(chathub_history.brand) as total')
            )
//            ->whereNotIn('chathub_history.query', ['NestleOnBot', 'NestleOffBot'])
            ->groupBy('chathub_brand.brand_name');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageCompletion($date_range,$type=0)
    {
        $ds = $this->select('chathub_history.history_id')
            ->leftJoin('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->leftJoin('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->leftJoin('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->leftJoin('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->where(function($query){
                $query->whereIn('chathub_response_detail.type', ['config_on_bot', 'config_off_bot'])
                    ->orWhereNull('chathub_response_detail.type')
                    ->orWhere('chathub_response_detail.type', '=', '');
            })
            ;

        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        //$ds->limit(1);
        if($type==1){
            return $ds->count();
        }else{
            return $ds->get();
        }
        
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function totalMessageConfusion($date_range, $type = 0)
    {
        $ds = $this
            ->leftJoin('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->leftJoin('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->leftJoin('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->leftJoin('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->select(
                'chathub_history.history_id'
            )
            ->whereIn('chathub_response_detail.type', ['default', 'reply_after']);
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if($type==1){
            return $ds->count();
        }else{
            return $ds->get();
        }
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageChartCompletion($date_range, $all = true, $export = false)
    {
        $ds = $this
            ->leftJoin('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->leftJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->join('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->whereIn('chathub_response_detail.type', ['config_on_bot', 'config_off_bot'])
            ->orWhereNull('chathub_response_detail.type');
        if ($export == false) {
            $ds->select(
                'chathub_attribute.attribute_name',
                DB::raw('count(chathub_response_content.response_content_id) as total')
            )
//                ->whereDate('chathub_attribute.date_start_report', '>=', $this->dateQueryCompletion)
                ->groupBy('chathub_attribute.attribute_name')
                ->orderBy('total', 'desc');

            if ($all == false) {
                $ds->where('chathub_attribute.type', 'have_response')
                    ->limit(5);
            }
        } else {
            $ds->select(
                'chathub_history.query',
                'chathub_history.response_content',
                'chathub_history.request_time',
                'chathub_history.response_time',
                'chathub_response_content.response_target',
                'chathub_history.brand',
                'chathub_history.sku',
                'chathub_history.attribute',
                'chathub_brand.brand_name',
                'chathub_attribute.attribute_name',
                'chathub_sku.sku_name',
                'chathub_response_detail.type',
                'chathub_response_content.response_forward',
                'chathub_attribute.type as attr_type',
                'chathub_history.type as ib_type'
            );
//                ->whereDate('chathub_attribute.date_start_report', '>=', $this->dateQueryCompletion);
        }

        $ds->whereColumn('chathub_attribute.date_start_report','<=','chathub_history.request_time');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @param bool $all
     * @param bool $export
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageChartConfusion($date_range, $all = false, $export = false)
    {
        $ds = $this
            ->leftJoin('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->leftJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->join('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content');
        if ($export == true) {
            $ds->select(
                'chathub_history.query',
                'chathub_history.response_content',
                'chathub_history.request_time',
                'chathub_history.response_time',
                'chathub_response_content.response_target',
                'chathub_history.brand',
                'chathub_history.sku',
                'chathub_history.attribute',
                'chathub_brand.brand_name',
                'chathub_attribute.attribute_name',
                'chathub_sku.sku_name',
                'chathub_response_detail.type',
                'chathub_response_content.response_forward'
            )
                ->whereIn('chathub_response_detail.type', ['default', 'reply_after']);
        } else {
            $ds->select(
                'chathub_attribute.attribute_name',
                DB::raw('count(chathub_response_content.response_content_id) as total')
            )
                ->whereIn('chathub_response_detail.type', ['default', 'reply_after'])
                ->groupBy('chathub_attribute.attribute_name');
            if ($all == false) {
                $ds
                    ->whereIn('chathub_response_detail.type', ['default', 'reply_after'])
                    ->orderBy('total', 'desc')
                    ->limit(5);
            } else {
                $ds->orderBy('total', 'desc');
            }
        }
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @param $brand
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageMonth($month)
    {
        $ds = $this
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_response_content.brand_entities')
            ->select(
                DB::raw('count(chathub_history.brand) as total')
            )
            ->whereMonth('chathub_history.request_time', '=', $month->month);
        return $ds->get();
    }

    /**
     * @param $date_range
     * @param $brand
     * @param $month
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageMonthBrand($brand, $month)
    {
        $ds = $this
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_response_content.brand_entities')
            ->select(
                DB::raw('count(chathub_history.brand) as total')
            )
            ->whereMonth('chathub_history.request_time', '=', $month->month);
        if ($brand != 'all') {
            if ($brand == 'other') {
                $ds->where('chathub_response_content.brand_entities', (string)0);
            } else {
                $ds->where('chathub_response_content.brand_entities', $brand);
            }
        }
        return $ds->get();
    }


    /**
     * @param $date_range
     * @param $brand
     * @param bool $export
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUserSkuByBrand($date_range, $brand)
    {
        $ds = $this
            ->join('chathub_customer as customer', 'customer.customer_id', 'chathub_history.session_id')
            ->join('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_sku.brand_entities')
            ->leftJoin('chathub_tag as tag', 'tag.tag_id', '=', 'customer.tag_id')
            ->select(
                'customer.name',
                'customer.email',
                'customer.phone',
                'customer.gender',
                'tag.name as tag_name',
                'customer.customer_id',
                'customer.created_at as JoinedAt',
                'chathub_sku.sku_name',
                'chathub_brand.brand_name',
                'customer.customer_id'
            );
        if ($brand != 'all') {
            $ds->where('chathub_sku.brand_entities', $brand);
        }
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @param $brand
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUserAttributeByBrand($date_range, $brand)
    {
        $ds = $this
            ->join('chathub_customer as customer', 'customer.customer_id', 'chathub_history.session_id')
            ->join('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->leftJoin('chathub_tag as tag', 'tag.tag_id', '=', 'customer.tag_id')
            ->select(
//                'chathub_attribute.attribute_name',
//                DB::raw('count(customer.customer_id) as total')
                'customer.name',
                'customer.email',
                'customer.phone',
                'customer.gender',
                'tag.name as tag_name',
                'customer.customer_id',
                'customer.created_at as JoinedAt',
                'chathub_attribute.attribute_name',
                'chathub_brand.brand_name',
                'customer.customer_id'
            );
        if ($brand != 'all') {
            $ds->where('chathub_history.brand', $brand);
        }
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUniqueUserByBrand($date_range)
    {
        $ds = $this
            ->join('chathub_customer as customer', 'customer.customer_id', 'chathub_history.session_id')
            ->leftJoin('chathub_tag as tag', 'tag.tag_id', '=', 'customer.tag_id')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->select(
                'customer.name',
                'chathub_brand.brand_name',
                'customer.email',
                'customer.phone',
                'customer.gender',
                'tag.name as tag_name',
                'customer.customer_id',
                'customer.created_at as JoinedAt'
            );
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalMessageAll($date_range)
    {
        $ds = $this
            ->select(
                'session_id',
                'request_time'
            )->orderBy('request_time', 'asc');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @param bool $export
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function totalMessageAttributeOther($date_range, $export = false)
    {
        $ds = $this
            ->leftJoin('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->leftJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->join('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content');
        if ($export == true) {
            $ds->select(
                'chathub_history.query',
                'chathub_history.response_content',
                'chathub_history.request_time',
                'chathub_history.response_time',
                'chathub_response_content.response_target',
                'chathub_history.brand',
                'chathub_history.sku',
                'chathub_history.attribute',
                'chathub_brand.brand_name',
                'chathub_attribute.attribute_name',
                'chathub_sku.sku_name',
                'chathub_response_detail.type',
                'chathub_response_content.response_forward'
            )->where('chathub_history.attribute', 'attr_other');
        } else {
            $ds->select(
                'chathub_history.query',
                DB::raw('count(chathub_history.query) as total')
            )
                ->where('chathub_history.attribute', 'attr_other')
                ->groupBy('chathub_history.query');
        }
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $date_range
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function totalUserFollow($date_range)
    {
        $ds = $this
            ->join('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->join('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->join('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_response_content.brand_entities')
            ->select(
                'chathub_brand.brand_name',
                DB::raw('count(chathub_history.brand) as total')
            )
            ->groupBy('chathub_brand.brand_name');
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * @param $month
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function totalMessageStatus($month)
    {
        $ds = $this
            ->leftJoin('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->leftJoin('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->leftJoin('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->leftJoin('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->leftJoin('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->leftJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->select(
                'chathub_history.query',
                DB::raw('count(chathub_history.query) as total')
            )
            ->whereIn('chathub_history.query', ['NestleOnBot', 'NestleOffBot'])
            ->whereMonth('chathub_history.request_time', '=', $month->month)
            ->groupBy('chathub_history.query');
        return $ds->get();
    }

    /**
     * @param $date_start
     * @param $date_end
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function totalMessageStatusRange($date_start, $date_end)
    {
        $ds = $this
            ->leftJoin('chathub_attribute', 'chathub_attribute.entities', '=', 'chathub_history.attribute')
            ->leftJoin('chathub_sku', 'chathub_sku.entities', '=', 'chathub_history.sku')
            ->leftJoin('chathub_response_detail', 'chathub_response_detail.response_detail_id', '=', 'chathub_history.response_detail_id')
            ->leftJoin('chathub_response', 'chathub_response.response_id', '=', 'chathub_response_detail.response_id')
            ->leftJoin('chathub_response_content', 'chathub_response_content.response_content_id', '=', 'chathub_response.response_content')
            ->leftJoin('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->select(
                'chathub_history.query',
                DB::raw('count(chathub_history.query) as total')
            )
            ->whereIn('chathub_history.query', ['NestleOnBot', 'NestleOffBot'])
            ->whereBetween('chathub_history.request_time', [$date_start . ' 00:00:00', $date_end . ' 23:59:59'])
            ->groupBy('chathub_history.query');
        return $ds->get();
    }

    /**
     * Lấy lịch sử chat ngày hôm qua
     *
     * @param $startTime
     * @param $endTime
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function listYesterday($startTime, $endTime)
    {
        return $this
            ->join('chathub_customer as customer', 'customer.customer_id', 'chathub_history.session_id')
            ->join('chathub_brand', 'chathub_brand.entities', '=', 'chathub_history.brand')
            ->join('chathub_tag as tag', 'tag.keyword', '=', 'chathub_history.brand')
            ->select(
                'chathub_history.query',
                'customer.customer_id',
                'customer.name',
                'chathub_brand.brand_name',
                'tag.name as tag_name',
                'tag.tag_id',
                'chathub_history.request_time'
            )
            ->whereBetween('chathub_history.request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where('chathub_history.brand', '<>', '0')
            ->get();
    }

    /**
     * Lấy ds keyword theo ngày
     *
     * @param $startTime
     * @param $endTime
     * @return ChatbotHistoryTable[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getQueryHistory($startTime, $endTime)
    {
        return $this
            ->select(
                'history_id',
                'query',
                'session_id',
                'request_time'
            )
            ->whereBetween('request_time', [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->get();
    }

}
