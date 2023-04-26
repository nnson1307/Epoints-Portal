<?php

namespace Modules\Report\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History extends Model
{
    //
    protected $table = 'chathub_history';
    protected $primaryKey = 'history_id';
    protected $fillable = ['log_id', 'user_id', 'image', 'log_status', 'game_date', 'created_time', 'type'];
    public $timestamps=true;

    public function getAllHistory($filter, $isPaging = true){
        $oSelect = $this->from($this->table.' as h')
                        ->join('chathub_customer', 'chathub_customer.customer_id', '=', 'h.session_id')
                        ->where('h.first_history',1);

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
            //dd($oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to])->get());
        }
        if (isset($filter['session']) && !empty($filter['session']))
        {
            $oSelect->where('session_id', $filter['session']);
        }
        if (isset($filter['name']) && !empty($filter['name']))
        {
            $oSelect->where('name','like', "%{$filter['name']}%");
        }

        if (isset($filter['ban']) && !empty($filter['ban']) && $filter['ban'] == 'banned')
        {
            $oSelect->Join('chathub_history_banned as hb', 'hb.chathub_history_session', '=', 'h.session_id');
        }
        else
        {
            $oSelect->leftJoin('chathub_history_banned as hb', 'hb.chathub_history_session', '=', 'h.session_id');
        }


        $oSelect->orderBy('history_id','desc');

        return $oSelect->paginate('25');
    }

    public function getTotalConversation($filter){

        $oSelect = $this->from($this->table.' as h');
        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        $oSelect->groupBy('session_id');

        return $oSelect->get()->count();
    }

    public function getTotalConversationShowList($filter){

        $oSelect = $this->from($this->table.' as h');
        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        $oSelect->groupBy('session_id');

        return $oSelect->paginate('50');
    }

    public function getTotalResponse($filter){
        $oSelect = $this->from($this->table.' as h');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseOnOffBot($filter){
        $oSelect = $this->from($this->table.' as h')->whereIn('query', ['GET_STARTED_PAYLOAD', 'NestleOffBot', 'NestleOnBot']);

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseBotSuccess($filter){
        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id', '=', 'h.response_detail_id')
            ->join('chathub_response as cs', 'cs.response_id', '=', 'rd.response_id')
            ->join('chathub_response_content as rc', 'rc.response_content_id', '=', 'cs.response_content')
            ->where('rc.title', 'NOT LIKE', '%' . '[NO DELETE]' . '%');
        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseShowList($filter){
        $oSelect = $this->from($this->table.' as h');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->paginate('50');
    }

    public function getTotalResponseTarget($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id', '=', 'h.response_detail_id')
            ->join('chathub_response as cs', 'cs.response_id', '=', 'rd.response_id')
            ->join('chathub_response_content as rc', 'rc.response_content_id', '=', 'cs.response_content')
            ->where('rc.response_target',1);

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseTargetShowList($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->join('chathub_response_content as rc', 'rc.response_content_id','=','rd.response_content_id')
            ->where('rc.response_target',1);

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }
        return $oSelect->paginate('50');
    }

    public function getTotalNoResponse($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->whereNotIn('query', ['GET_STARTED_PAYLOAD', 'NestleOffBot', 'NestleOnBot'])
            ->whereNotNull('rd.type')
            ->where(function($query) {
                return $query->where('h.brand', '<>', '0')
                    ->orWhere('h.sub_brand', '<>', '0')
                    ->orWhere('h.sku', '<>', '0')
                    ->orWhere('h.attribute', '<>', '0');
            });

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalNoResponseShowList($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->whereNotIn('query', ['GET_STARTED_PAYLOAD', 'NestleOffBot', 'NestleOnBot'])
            ->whereNotNull('rd.type')
            ->where(function($query) {
                return $query->where('h.brand', '<>', '0')
                    ->orWhere('h.sub_brand', '<>', '0')
                    ->orWhere('h.sku', '<>', '0')
                    ->orWhere('h.attribute', '<>', '0');
            });

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }
        $oSelect->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');
        return $oSelect->paginate('50');
    }

    public function getTotalResponseFallback($filter){

        $oSelect = $this->from($this->table.' as h')
            ->where('h.brand', '=', '0')
            ->where('h.sub_brand', '=', '0')
            ->where('h.sku', '=', '0')
            ->where('h.attribute', '=', '0')
            ->whereNotIn('query', ['GET_STARTED_PAYLOAD', 'NestleOffBot', 'NestleOnBot'])->orWhereNull('query');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('DATE(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseFallbackShowList($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', function($join){
                $join->on('rd.response_detail_id','=','h.response_detail_id')
                    ->where('h.brand', '=', '0')->where('h.sub_brand', '=', '0')
                    ->where('h.sku', '=', '0')->where('h.attribute', '=', '0');
            });

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }
        $oSelect->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');
        return $oSelect->paginate('50');
    }

    public function getTotalResponseAttrOther($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.attribute','attr_other');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseAttrOtherShowList($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.attribute','attr_other');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }
        $oSelect->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');
        return $oSelect->paginate('50');
    }

    public function getTotalResponseLeaveMessage($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.type','reply_after');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }

        return $oSelect->get()->count();
    }

    public function getTotalResponseLeaveMessageShowList($filter){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.type','reply_after');

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        if(isset($filter['query']) && $filter['query'])
        {
            $oSelect->where('h.query','like','%'.$filter['query'].'%');
        }

        if(isset($filter['content']) && $filter['content'])
        {
            $oSelect->where('h.response_content','like','%'.$filter['content'].'%');
        }

        if(isset($filter['brand']) && $filter['brand'])
        {
            $oSelect->where('h.brand',$filter['brand']);
        }

        if(isset($filter['sub_brand']) && $filter['sub_brand'])
        {
            $oSelect->where('h.sub_brand',$filter['sub_brand']);
        }

        if(isset($filter['sku']) && $filter['sku'])
        {
            $oSelect->where('h.sku',$filter['sku']);
        }

        if(isset($filter['attribute']) && $filter['attribute'])
        {
            $oSelect->where('h.attribute',$filter['attribute']);
        }
        $oSelect->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');
        return $oSelect->paginate('50');
    }



    public function getAllReportConversation($filter, $isPaging = true){
        $oSelect = $this->where('first_history',1);

        if (!empty($filter['start_date']) && !empty($filter['end_date']))
        {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(request_time)'), [$from, $to]);
        }

        $oSelect->selectRaw('date(request_time) as date');
        $oSelect->selectRaw('count(*) as total');
        $oSelect->groupBy('date');

        return $oSelect->get();
    }

    public function getAllHistoryById($sessionId)
    {
        $oSelect = $this->orderBy('history_id','desc')
            ->whereIn('session_id',$sessionId)
            ->get()->toArray();
        return $oSelect;
    }

    public function getAllByHistory($id){
        return $this->from($this->table.' as h')
            ->select('conversation', 'query', 'parameters_parse', 'brand_name',
                'sub_brand_name', 'sku_name', 'attribute_name', 'response_content', 'request_time', 'response_time')
            ->leftJoin('chathub_brand as b', 'b.entities', '=', 'h.brand')
            ->leftJoin('chathub_sub_brand as sb', 'sb.entities', '=', 'h.sub_brand')
            ->leftJoin('chathub_sku as sku', 'sku.entities', '=', 'h.sku')
            ->leftJoin('chathub_attribute as attr', 'attr.entities', '=', 'h.attribute')
            ->where('h.session_id', $id)
            ->orderBy('history_id','asc')
            ->get()->toArray();
    }

    public function getAllByHistoryFix($id, $conversation = null){
        $oSelect =  $this->from($this->table.' as h')
            ->select('h.conversation', 'h.query', 'h.parameters_parse', 'h.brand',
                'h.sub_brand', 'h.sku', 'h.attribute', 'h.response_content', 'h.request_time', 'h.response_time' , 'sb.response_content as response_content_id', 'h.type', 'b.type_message')
            ->leftJoin('chathub_response_detail as b', 'b.response_detail_id', '=', 'h.response_detail_id')
            ->leftJoin('chathub_response as sb', 'sb.response_id', '=', 'b.response_id')
            ->where('h.session_id', $id)
            ->orderBy('history_id','asc')->get();

        if($conversation){
            $oSelect->where('h.conversation', $conversation);
        }
        foreach($oSelect as $item){
            $item['template']=DB::table('chathub_response_element')
                                ->join('chathub_response_detail_element', 'chathub_response_detail_element.response_element_id', '=', 'chathub_response_element.response_element_id')
                                ->where('chathub_response_detail_element.response_content_id','=', $item['response_content_id'])->get();
            foreach($item['template'] as $it){
                $it->child= DB::table('chathub_response_element_button')->join('chathub_response_button','chathub_response_element_button.response_button_id', '=','chathub_response_button.response_button_id')
                                                                        ->where('chathub_response_element_button.response_element_id','=',$it->response_element_id)
                                                                        ->get();
            }
            
        }
        return $oSelect->toArray();
    }

    public function getTotalConversationExport(){

        $oSelect = $this->from($this->table.' as h');
        $oSelect->groupBy('session_id')
                ->select('query','brand','sub_brand','sku','attribute','response_content');
        return $oSelect->get();
    }

    public function getTotalResponseExport(){
        $oSelect = $this->from($this->table.' as h')
        ->select('query','brand','sub_brand','sku','attribute','response_content');

        return $oSelect->get();
    }

    public function getTotalResponseTargetExport(){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->join('chathub_response_content as rc', 'rc.response_content_id','=','rd.response_content_id')
            ->where('rc.response_target',1)
            ->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');

        return $oSelect->get();
    }

    public function getTotalNoResponseExport(){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.type','default')->where(function($query) {
                return $query->where('h.brand', '<>', '0')
                    ->orWhere('h.sub_brand', '<>', 0)
                    ->orWhere('h.sku', '<>', 0)
                    ->orWhere('h.attribute', '<>', 0);

            })
            ->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');

        return $oSelect->get();
    }

    public function getTotalResponseFallbackExport(){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', function($join){
                $join->on('rd.response_detail_id','=','h.response_detail_id')
                    ->where('h.brand', '=', '0')->where('h.sub_brand', '=', '0')
                    ->where('h.sku', '=', '0')->where('h.attribute', '=', '0');
            })
            ->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');

        return $oSelect->get();
    }

    public function getTotalResponseAttrOtherExport(){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.attribute','attr_other')
            ->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');

        return $oSelect->get();
    }

    public function getTotalResponseLeaveMessageExport(){

        $oSelect = $this->from($this->table.' as h')
            ->join('chathub_response_detail as rd', 'rd.response_detail_id','=','h.response_detail_id')
            ->where('rd.type','reply_after')
            ->select('h.query','h.brand','h.sub_brand','h.sku','h.attribute','h.response_content');

        return $oSelect->get();
    }
}
