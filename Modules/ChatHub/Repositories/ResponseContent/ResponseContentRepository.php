<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 3/24/2021
 * Time: 11:00 AM
 */
namespace Modules\ChatHub\Repositories\ResponseContent;

use Illuminate\Http\Request;
use Modules\ChatHub\Models\ChatHubBrandTable;
use Modules\ChatHub\Models\ChatHubResponseContentTable;
use Modules\ChatHub\Models\ChatHubResponseDetailElementTable;
use Modules\ChatHub\Models\ChatHubResponseDetailTable;
use Modules\ChatHub\Models\ChatHubResponseElementTable;

class ResponseContentRepository implements ResponseContentRepositoryInterface
{
    protected $response_content;
    public function __construct(
        ChatHubResponseContentTable $response_content
    )
    {
        $this->response_content = $response_content;
    }

    /**
     * Lấy thông tin tất cả response content
     *
     * @param null $filters
     * @return mixed
     */
    public function getList($filters = null){
        return $this->response_content->getList($filters);
    }

    /**
     * Xoá hẳn 1 response content
     *
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        return $this->response_content->remove($id);
    }

    /**
     * Lấy dữ liệu trả về View chỉnh sửa response content
     *
     * @param $id
     * @return array
     */
    public function getDataViewEdit($id)
    {
        $chatHubBrand = new ChatHubBrandTable();
        $resDetailElement = new ChatHubResponseDetailElementTable();
        $resElement = new ChatHubResponseElementTable();
        $brand = $chatHubBrand->getOptionChatHubBrand();
        $data = $this->response_content->getDataViewEdit($id);
        $detailElement = $resDetailElement->getDetailElementByContent($id);
        $element = [];
        foreach($detailElement as $value){
                $element[] = $resElement->getById($value['response_element_id'])->toArray();
        }
        return [
            'brand' => $brand,
            'data' => $data,
            'element' => $element,
        ];
    }

    /**
     * Lưu chỉnh sửa 1 response content
     *
     * @param $item
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUpdate($item, $id)
    {
        try{
            if(isset($item['response_element_id'])){

                $item['type_message']='template';
            }else{
                    $item['type_message']='default';
            }
            if(isset($item['response_element_id'])){
                $mResponseDetailElement = new ChatHubResponseDetailElementTable();
                $mResponseDetail = new ChatHubResponseDetailTable();
                // lấy các response content của các detail element cũ
                $detail=$mResponseDetail->where('response_content_id', $item['response_content_id'])->first();
                // xoá các response content của detail element cũ
                $mResponseDetailElement->where('response_content_id', $item['response_content_id'])->delete();
                foreach($item['response_element_id'] as $i){
                    $insert=[
                        'response_element_id' => $i,
                        'response_content_id' => $item['response_content_id']
                    ];
                    $mResponseDetailElement->where('response_detail_id', $detail['response_detail_id'])->insert($insert);
                }
            }
            unset($item['response_element_id']);
            $this->response_content->saveUpdate($item,$id);
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                'ex_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Thêm 1 response content
     *
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertData($item)
    {
        try{
            $itemInsert = $item;
            unset($itemInsert['response_element_id']);
            $response_content_id = $this->response_content->insertData($itemInsert);
            if(isset($item['response_element_id'])){

                $item['type_message']='template';
            }else{
                $item['type_message']='default';
            }
            if(isset($item['response_element_id'])){
                $mResponseDetailElement = new ChatHubResponseDetailElementTable();
                $mResponseDetail = new ChatHubResponseDetailTable();
                // lấy các response content của các detail element cũ
                $detail=$mResponseDetail->where('response_content_id', $response_content_id)->first();
                foreach($item['response_element_id'] as $i){
                    $insert=[
                        'response_element_id' => $i,
                        'response_content_id' => $response_content_id
                    ];
                    $mResponseDetailElement->where('response_detail_id', $detail['response_detail_id'])->insert($insert);
                }
            }
            unset($item['response_element_id']);
            return response()->json([
                'error' => false,
                'message' => __('Thêm thành công')
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message' => __('Thêm thất bại'),
                'ex_message' => $e->getMessage()
            ]);
        }
    }
}
