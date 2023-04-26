<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\ResponseButton\StoreRequest;
use Modules\ChatHub\Http\Requests\ResponseButton\UpdateRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\ResponseButton\ResponseButtonRepositoryInterface;
use Auth;


class ResponseButtonController extends Controller
{
    protected $response_button;
    public function __construct(
        ResponseButtonRepositoryInterface $response_button
    ) {
        $this->response_button = $response_button;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $response_button=$this->response_button->getList($filters);
            return view('chathub::response_button.index',[
                'LIST' => $response_button
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $response_button=$this->response_button->getList($filters);
        return view('chathub::response_button.list',
            [
                'LIST' => $response_button,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            return view('chathub::response_button.add');
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $response_button=$this->response_button->getResponseButton($data['response_button_id']);
            return view('chathub::response_button.edit',[
                'response_button'=>$response_button
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(StoreRequest $request){
        try{
            $this->response_button->create($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_button.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(UpdateRequest $request){
        try{
            $this->response_button->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_button.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->response_button->delete($data['response_button_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_button.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}