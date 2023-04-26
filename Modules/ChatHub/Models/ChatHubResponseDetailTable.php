<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;

class ChatHubResponseDetailTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_response_detail';
    protected $primaryKey = 'response_detail_id';
    protected $fillable = [
        'brand','sub_brand', 'sku', 'attribute', 'response_content', 'response_element_id',
        'response_status', 'created_at', 'type', 'title', 'type_message', 'type_message', 'type_link', 'template_type'];

    public function _getList(array &$filter = []){
        $select = $this->orderBy('response_detail_id','ASC');
        if(isset($filter['data_time'])){
            $time=Carbon::createFromFormat('m/d/Y', $filter['data_time'])->format('Y-m-d');
            $select->where($this->table.'.created_at', '>=', $time)->where($this->table.'.created_at', '<=', $time . ' 23:59:59');
            unset($filter['data_time']);
        }
        return $select;
    }
    public function store($data){
        // dd($data);
        $insert = $this->create($data);
        return $insert->response_detail_id;
    }
    public function remove($response_detail_id){
        $this->where('response_detail_id', '=', $response_detail_id)->delete();
    }

    /**
     * Lấy thông tin chi tiết của 1 config response
     *
     * @param array $params
     * @param $id
     * @return mixed
     */
    public function getDetail(array &$params, $id){
        $oSelect = $this->from($this->table.' as rpd')
//            ->select('response_detail_id','response_id','brand_name', 'sub_brand', 'sku_name', 'attribute', 'response_content')
            ->select('response_detail_id','response_id','brand_name', 'sub_brand', 'sku_name', 'attribute','attribute_name', 'response_content', 'rpd.type')
            ->leftJoin('chathub_brand as b', 'b.entities','=','rpd.brand')
            ->leftJoin('chathub_sub_brand as sb', 'sb.entities','=','rpd.sub_brand')
            ->leftJoin('chathub_sku as sku', 'sku.entities','=','rpd.sku')
            ->leftJoin('chathub_attribute as attr', 'attr.entities','=','rpd.attribute');
        if($id != 'all'){
            $oSelect->where('response_id', $id);
        }
        if(isset($params['brand']) && $params['brand'])
        {
            $oSelect->where('rpd.brand',$params['brand']);
        }
        unset($params['brand']);

        if(isset($params['sub_brand']) && $params['sub_brand'])
        {
            $oSelect->where('rpd.sub_brand',$params['sub_brand']);
        }
        unset($params['sub_brand']);

        if(isset($params['sku']) && $params['sku'])
        {
            $oSelect->where('rpd.sku',$params['sku']);
        }
        unset($params['sku']);

        if(isset($params['attribute']) && $params['attribute'])
        {
            $oSelect->where('rpd.attribute',$params['attribute']);
        }
        unset($params['attribute']);

        $page    = (int) ($params['page'] ?? 1);
        $display = (int) ($params['perpage'] ?? PAGING_ITEM_PER_PAGE);
        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    public function getType($id){
        $oSelect = $this->where('response_id', $id)->select('type')->first();
        return $oSelect;
    }
}
