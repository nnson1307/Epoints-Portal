<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 3/24/2021
 * Time: 4:40PM
 */
namespace Modules\ChatHub\Repositories\Response;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\ChatHub\Models\ChatHubAttributeTable;
use Modules\ChatHub\Models\ChatHubBrandTable;
use Modules\ChatHub\Models\ChatHubResponseContentTable;
use Modules\ChatHub\Models\ChatHubResponseDetailTable;
use Modules\ChatHub\Models\ChatHubResponseTable;
use Modules\ChatHub\Models\ChatHubSkuTable;
use Modules\ChatHub\Models\ChatHubSubBrandTable;

class ResponseRepository implements ResponseRepositoryInterface
{
    protected $response;
    public function __construct(
        ChatHubResponseTable $response
    )
    {
        $this->response = $response;
    }

    /**
     * Danh sách config response
     *
     * @param null $filters
     * @return mixed
     */
    public function getList($filters = null){
        return $this->response->getList($filters);
    }

    /**
     * View thêm config response mới
     *
     * @return mixed
     */
    public function getDataCreateAction(){
        $mBrand = new ChatHubBrandTable();
        $data['arrBrand'] = $mBrand->getOptionChatHubBrand();

        $mSubBrand = new ChatHubSubBrandTable();
        $data['arrSubBrand'] = $mSubBrand->getActive()->toArray();

        $mSku = new ChatHubSkuTable();
        $data['arrSku'] = $mSku->getActive()->toArray();

        $mAttribute = new ChatHubAttributeTable();
        $data['arrAttribute'] = $mAttribute->getActive()->toArray();

        $mResponseContent = new ChatHubResponseContentTable();
        $data['arrResponseContent'] = $mResponseContent->getActive()->toArray();

        $type =
            [
                'default' => 'default',
                'reply_after' => 'reply_after',
                'config_off_bot' => 'config_off_bot',
                'config_on_bot' => 'config_on_bot'
            ];
        $data['type'] = $type;
        return $data;
    }

    /**
     * Thêm config response
     *
     * @param $arrParams
     */
    public function storeAction($arrParams){
        $response_content = $arrParams['response_content'];
        $arrResponse = [
            'response_name' => $arrParams['response_name'],
            'response_content' => $response_content
        ];


        if(isset($arrParams['entities']['brand']))
        {
            $mBrand = new ChatHubBrandTable();
            $data['brand'] = $mBrand->whereIn('brand_id',$arrParams['entities']['brand'])->pluck('entities' ,'brand_id')->toArray();
            $arrResponse['brand'] = implode(';',$arrParams['entities']['brand']);
        }
        else
        {
            $arrResponse['brand'] = null;
        }

        if(isset($arrParams['entities']['sub_brand']))
        {
            $mSubBrand = new ChatHubSubBrandTable();
            $data['sub_brand'] = $mSubBrand->whereIn('sub_brand_id',$arrParams['entities']['sub_brand'])->pluck('entities' ,'sub_brand_id');
            $arrResponse['sub_brand'] = implode(';',$arrParams['entities']['sub_brand']);
        }
        else
        {
            $arrResponse['sub_brand'] = null;
        }

        if(isset($arrParams['entities']['sku']))
        {
            $mSku = new ChatHubSkuTable();
            $data['sku'] = $mSku->whereIn('sku_id',$arrParams['entities']['sku'])->pluck('entities' ,'sku_id');
            $arrResponse['sku'] = implode(';',$arrParams['entities']['sku']);
        }
        else
        {
            $arrResponse['sku'] = null;
        }

        if(isset($arrParams['entities']['attribute']))
        {
            $mAttr = new ChatHubAttributeTable();
            $data['attribute'] = $mAttr->whereIn('attribute_id',$arrParams['entities']['attribute'])->pluck('entities' ,'attribute_id');
            $arrResponse['attribute'] = implode(';',$arrParams['entities']['attribute']);
        }
        else
        {
            $arrResponse['attribute'] = null;
        }
        $mResponse = new ChatHubResponseTable();
        $mResponseDetail = new ChatHubResponseDetailTable();

        DB::beginTransaction();

        $idResponse = $mResponse->insertGetId($arrResponse);


        $i = 0;
        $listKey = $listValue = $arrInsert = [];

        if(isset($arrParams['entities']))
        {
            $listKey = collect($arrParams['entities'])->keys()->toArray();
            $listValue = $this->parseArray(collect($arrParams['entities'])->values()->toArray());
        }

        $i = 0;

        $mResponseContent = new ChatHubResponseContentTable();
        $arrResponseContent = $mResponseContent->where('response_content_id',$arrParams['response_content'])->first()->toArray();

        try
        {
            foreach ($listValue as $value)
            {
                $arrInsert[$i] = [
                    'response_id'   =>$idResponse,
                    'response_content_id' =>$arrResponseContent['response_content_id'],
                    'response_content' =>$arrResponseContent['response_content'],
                    'type_message' =>$arrResponseContent['type_message'],
                    // 'link' =>$arrResponseContent['link'],
                    // 'type_link' =>$arrResponseContent['type_link'],
                    'template_type' =>$arrResponseContent['template_type'],
                    'type' => $arrParams['type'],
                    'created_at' => Carbon::now()
                ];

                foreach ($value as $key => $item)
                {
                    $arrInsert[$i][$listKey[$key]] = $data[$listKey[$key]][$item];
                }

                if(count($arrInsert) > 500)
                {
                    dd($arrInsert);
                    $mResponseDetail->insert($arrInsert);
                    $arrInsert = [];
                    $i = 0;
                }
                $i++;
            }
            $mResponseDetail->insert($arrInsert);
        }
        catch (\Exception $ex)
        {
            DB::rollback();
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            die;
//            printf('Một số parameter đã tồn tại. vui lòng kiểm tra lại');die;
        }
        DB::commit();

//        return redirect()->route('admin.response');
    }

    /**
     * View chỉnh sửa config response
     *
     * @param $id
     * @return mixed
     */
    public function getDateEditAction($id)
    {
        $mResponse = new ChatHubResponseTable();
        $data['arrDetail'] = $mResponse->where('response_id', $id)->first()->toArray();

        $mBrand = new ChatHubBrandTable();
        $data['arrBrand'] = $mBrand->get()->toArray();

        $mSubBrand = new ChatHubSubBrandTable();
        $data['arrSubBrand'] = $mSubBrand->get()->toArray();

        $mSku = new ChatHubSkuTable();
        $data['arrSku'] = $mSku->get()->toArray();

        $mAttribute = new ChatHubAttributeTable();
        $data['arrAttribute'] = $mAttribute->get()->toArray();

        $mResponseContent = new ChatHubResponseContentTable();
        $data['arrResponseContent'] = $mResponseContent->get()->toArray();

        $mResponseDetail = new ChatHubResponseDetailTable();
        $data['arrResponseDetail'] = $mResponseDetail->getType($id);
        $data['type'] =
            [
                'default' => 'default',
                'reply_after' => 'reply_after',
                'config_off_bot' => 'config_off_bot',
                'config_on_bot' => 'config_on_bot'
            ];

        $data['arrDetail']['brand'] = $this->convertKeyToKey(explode(';', $data['arrDetail']['brand']));
        $data['arrDetail']['sub_brand'] = $this->convertKeyToKey(explode(';', $data['arrDetail']['sub_brand']));
        $data['arrDetail']['sku'] = $this->convertKeyToKey(explode(';', $data['arrDetail']['sku']));
        $data['arrDetail']['attribute'] = $this->convertKeyToKey(explode(';', $data['arrDetail']['attribute']));

        //previous url
        if(isset($_SERVER['HTTP_REFERER'])){
            $data['preurl'] = $_SERVER['HTTP_REFERER'];
        } else {
            $data['preurl'] = route('admin.response.detail-all');
        }
        return $data;
    }

    /**
     * Lưu chỉnh sửa config response
     *
     * @param $arrParams
     * @param $id
     */
    public function updateAction($arrParams, $id)
    {
        $response_content = $arrParams['response_content'];

        $arrResponse = [
            'response_name' => $arrParams['response_name'],
            'response_content' => $response_content
        ];

        $data = [];
        if(isset($arrParams['entities']['brand']))
        {
            $mBrand = new ChatHubBrandTable();
            $data['brand'] = $mBrand->whereIn('brand_id',$arrParams['entities']['brand'])->pluck('entities' ,'brand_id')->toArray();
            $arrResponse['brand'] = implode(';',$arrParams['entities']['brand']);
        }
        else
        {
            $arrResponse['brand'] = null;
        }

        if(isset($arrParams['entities']['sub_brand']))
        {
            $mSubBrand = new ChatHubSubBrandTable();
            $data['sub_brand'] = $mSubBrand->whereIn('sub_brand_id',$arrParams['entities']['sub_brand'])->pluck('entities' ,'sub_brand_id');
            $arrResponse['sub_brand'] = implode(';',$arrParams['entities']['sub_brand']);
        }
        else
        {
            $arrResponse['sub_brand'] = null;
        }

        if(isset($arrParams['entities']['sku']))
        {
            $mSku = new ChatHubSkuTable();
            $data['sku'] = $mSku->whereIn('sku_id',$arrParams['entities']['sku'])->pluck('entities' ,'sku_id');
            $arrResponse['sku'] = implode(';',$arrParams['entities']['sku']);
        }
        else
        {
            $arrResponse['sku'] = null;
        }

        if(isset($arrParams['entities']['attribute']))
        {
            $mAttr = new ChatHubAttributeTable();
            $data['attribute'] = $mAttr->whereIn('attribute_id',$arrParams['entities']['attribute'])->pluck('entities' ,'attribute_id');
            $arrResponse['attribute'] = implode(';',$arrParams['entities']['attribute']);
        }
        else
        {
            $arrResponse['attribute'] = null;
        }

        $mResponse = new ChatHubResponseTable();
        $mResponseDetail = new ChatHubResponseDetailTable();

        $mResponseContent = new ChatHubResponseContentTable();
        $arrResponseContent = $mResponseContent->where('response_content_id',$arrParams['response_content'])->first()->toArray();

        DB::beginTransaction();

        try
        {
            $mResponse->where('response_id', $id)->update($arrResponse);

            $mResponseDetail->where('response_id', $id)->delete();

            $i = 0;
            $listKey = $listValue = $arrInsert = [];

            if(isset($arrParams['entities']))
            {
                $listKey = collect($arrParams['entities'])->keys()->toArray();

                $listValue = $this->parseArray(collect($arrParams['entities'])->values()->toArray());
            }

            $i = 0;

            foreach ($listValue as $value)
            {

                $arrInsert[$i] = [
                    'response_id'   =>$id,
                    'response_content_id' =>$arrResponseContent['response_content_id'],
                    'response_content' =>$arrResponseContent['response_content'],
                    'type_message' =>$arrResponseContent['type_message'],
                    'template_type' =>$arrResponseContent['template_type'],
                    'type' => $arrParams['type'],
                    'created_at' => Carbon::now()
                ];

                foreach ($value as $key => $item)
                {
                    $arrInsert[$i][$listKey[$key]] = $data[$listKey[$key]][$item];
                }

                if(count($arrInsert) > 500)
                {
                    $mResponseDetail->insert($arrInsert);
                    $arrInsert = [];
                    $i = 0;
                }
                $i++;
            }
            $mResponseDetail->insert($arrInsert);
        }
        catch (\Exception $ex)
        {
            DB::rollback();
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            die;
        }
        DB::commit();
    }

    public function parseArray($main_array)
    {
        $count_arr = count($main_array);

        if($count_arr == 1)
        {
            $arrResult = [];
            $i = 0;
            foreach ($main_array[0] as $item)
            {
                $arrResult[$i] = [$item];
                $i++;
            }

            return $arrResult;
        }

        $arr_result = [];
        $start_arr = $main_array[0];
        $temp = $main_array[0];

        for($i=1;$i<$count_arr;$i++){
            foreach($temp as $key1 => $value1){
                foreach($main_array[$i] as $key2=>$value2){
                    $arr_result[] = [$value1,$value2];
                }
            }
            $temp= $arr_result;
        }

        foreach($arr_result as $key=>&$value){
            if(is_array($value)){
                $value = $this->arrayFlatten($value);
            }
        }

        foreach($arr_result as $key=>&$value){
            if(count($value) < $count_arr){
                unset($arr_result[$key]);
            }
        }

        return array_values($arr_result);
    }
    private function arrayFlatten(array $array) {
        $flatten = array();
        array_walk_recursive($array, function($value) use(&$flatten) {
            $flatten[] = $value;
        });

        return $flatten;
    }
    private function convertKeyToKey($arrItem){
        $arrResult = [];

        foreach ($arrItem as $item)
        {
            $arrResult[$item] = $item;
        }

        return $arrResult;

    }
}
