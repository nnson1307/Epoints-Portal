<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\Sku\SkuRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\Sku\SkuRepositoryInterface;
use Auth;


class SkuController extends Controller
{
    protected $sku;
    public function __construct(
        SkuRepositoryInterface $sku
    ) {
        $this->sku = $sku;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $sku=$this->sku->getList($filters);
            return view('chathub::sku.index',[
                'LIST' => $sku
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $sku=$this->sku->getList($filters);
        return view('chathub::sku.list',
            [
                'LIST' => $sku,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            return view('chathub::sku.add');
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $sku=$this->sku->getSku($data['sku_id']);
            return view('chathub::sku.edit',[
                'sku'=>$sku
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(SkuRequest $request){
        try{
            $this->sku->create($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::sku.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(SkuRequest $request){
        try{
            $this->sku->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::sku.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->sku->delete($data['sku_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::sku.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
