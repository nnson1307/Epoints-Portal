<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\Brand\BrandRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\Brand\BrandRepositoryInterface;
use Auth;


class BrandController extends Controller
{
    protected $brand;
    public function __construct(
        BrandRepositoryInterface $brand
    ) {
        $this->brand = $brand;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $brand=$this->brand->getList($filters);
            return view('chathub::brand.index',[
                'LIST' => $brand
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $brand=$this->brand->getList($filters);
        return view('chathub::brand.list',
            [
                'LIST' => $brand,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            return view('chathub::brand.add');
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $brand=$this->brand->getBrand($data['brand_id']);
            return view('chathub::brand.edit',[
                'brand'=>$brand
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(BrandRequest $request){
        try{
            $this->brand->create($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::brand.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(BrandRequest $request){
        try{
            $this->brand->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::brand.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->brand->delete($data['brand_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::brand.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}