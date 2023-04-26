<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\SubBrand\SubBrandRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\SubBrand\SubBrandRepositoryInterface;
use Auth;


class SubBrandController extends Controller
{
    protected $sub_brand;
    public function __construct(
        SubBrandRepositoryInterface $sub_brand
    ) {
        $this->sub_brand = $sub_brand;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $sub_brand=$this->sub_brand->getList($filters);
            return view('chathub::sub_brand.index',[
                'LIST' => $sub_brand
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $sub_brand=$this->sub_brand->getList($filters);
        return view('chathub::sub_brand.list',
            [
                'LIST' => $sub_brand,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            return view('chathub::sub_brand.add');
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $sub_brand=$this->sub_brand->getSubBrand($data['sub_brand_id']);
            return view('chathub::sub_brand.edit',[
                'sub_brand'=>$sub_brand
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(SubBrandRequest $request){
        try{
            $this->sub_brand->create($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::sub_brand.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(SubBrandRequest $request){
        try{
            $this->sub_brand->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::sub_brand.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->sub_brand->delete($data['sub_brand_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::sub_brand.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}