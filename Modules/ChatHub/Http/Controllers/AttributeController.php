<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\Attribute\AttributeRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\Attribute\AttributeRepositoryInterface;
use Auth;


class AttributeController extends Controller
{
    protected $attribute;
    public function __construct(
        AttributeRepositoryInterface $attribute
    ) {
        $this->attribute = $attribute;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();

            $attribute=$this->attribute->getList($filters);
            
            return view('chathub::attribute.index',[
                'LIST' => $attribute
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $attribute=$this->attribute->getList($filters);
        return view('chathub::attribute.list',
            [
                'LIST' => $attribute,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            return view('chathub::attribute.add');
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $attribute=$this->attribute->getAttribute($data['attribute_id']);
            return view('chathub::attribute.edit',[
                'attribute'=>$attribute
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(AttributeRequest $request){
        try{
            $this->attribute->create($request->all());
            
            return response()->json([
                'error' => false,
                'message' => __('chathub::attribute.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(AttributeRequest $request){
        try{
            $this->attribute->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::attribute.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->attribute->delete($data['attribute_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::attribute.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}