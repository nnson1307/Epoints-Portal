<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\ResponseDetail\StoreRequest;
use Modules\ChatHub\Http\Requests\ResponseDetail\UpdateRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\ResponseDetail\ResponseDetailRepositoryInterface;
use Modules\ChatHub\Repositories\ResponseElement\ResponseElementRepositoryInterface;
use Modules\ChatHub\Repositories\Brand\BrandRepositoryInterface;
use Modules\ChatHub\Repositories\Sku\SkuRepositoryInterface;
use Modules\ChatHub\Repositories\SubBrand\SubBrandRepositoryInterface;
use Modules\ChatHub\Repositories\Attribute\AttributeRepositoryInterface;
use Auth;


class ResponseDetailController extends Controller
{
    protected $response_detail;
    protected $response_element;
    protected $brand;
    protected $sku;
    protected $sub_brand;
    protected $attribute;
    
    public function __construct(
        ResponseDetailRepositoryInterface $response_detail,
        ResponseElementRepositoryInterface $response_element,
        BrandRepositoryInterface $brand,
        SkuRepositoryInterface $sku,
        SubBrandRepositoryInterface $sub_brand,
        AttributeRepositoryInterface $attribute
    ) {
        $this->response_detail = $response_detail;
        $this->response_element = $response_element;
        $this->brand = $brand;
        $this->sku = $sku;
        $this->sub_brand = $sub_brand;
        $this->attribute = $attribute;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $response_detail=$this->response_detail->getList($filters);
            return view('chathub::response_detail.index',[
                'LIST' => $response_detail
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $response_detail=$this->response_detail->getList($filters);
        return view('chathub::response_detail.list',
            [
                'LIST' => $response_detail,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            $response_element=$this->response_element->getActive();
            $brand=$this->brand->getActive();
            $sku=$this->sku->getActive();
            $sub_brand=$this->sub_brand->getActive();
            $attribute=$this->attribute->getActive();
            return view('chathub::response_detail.add',[
                'brand'=>$brand,
                'sub_brand'=>$sub_brand,
                'sku'=>$sku,
                'attribute'=>$attribute,
                'response_element'=>$response_element
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $response_detail=$this->response_detail->getResponseDetail($data['response_detail_id']);
            return view('chathub::response_detail.edit',[
                'response_detail'=>$response_detail
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(Request $request){
        try{
            $data=$request->all();
            if($data['response_element_id']==null && $data['response_content']==null){
                return response()->json([
                    'error' => true,
                    'message' => __('chathub::response_detail.create.ELEMENT_CONTENT_REQUIRED')
                ]);
            }
            $this->response_detail->create($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_detail.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(Request $request){
        try{
            $this->response_detail->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_detail.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->response_detail->delete($data['response_detail_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_detail.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}