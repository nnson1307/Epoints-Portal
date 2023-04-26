<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\ResponseElement\StoreRequest;
use Modules\ChatHub\Http\Requests\ResponseElement\UpdateRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\ResponseElement\ResponseElementRepositoryInterface;
use Modules\ChatHub\Repositories\ResponseButton\ResponseButtonRepositoryInterface;
use Auth;


class ResponseElementController extends Controller
{
    protected $response_element;
    protected $response_button;
    public function __construct(
        ResponseElementRepositoryInterface $response_element,
        ResponseButtonRepositoryInterface $response_button
    ) {
        $this->response_element = $response_element;
        $this->response_button = $response_button;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $response_element=$this->response_element->getList($filters);
            return view('chathub::response_element.index',[
                'LIST' => $response_element
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $response_element=$this->response_element->getList($filters);
        return view('chathub::response_element.list',
            [
                'LIST' => $response_element,
                'page' => $filters['page']
            ]);
    }
    public function addAction(Request $request){
        try{
            
            $response_button=$this->response_button->getActive();
            return view('chathub::response_element.add',[
                'response_button'=> $response_button
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function editAction(Request $request){
        try{
            $data =$request->all();
            $response_element=$this->response_element->getResponseElement($data['response_element_id']);
            $response_button=$this->response_button->getActive();
            return view('chathub::response_element.edit',[
                'response_element'=>$response_element,
                'response_button'=> $response_button
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function createAction(StoreRequest $request){
        try{
            if(isset($request['response_button']))
            {
                if(count($request['response_button'])>3){
                    return response()->json([
                        'error' => true,
                        'message' => __('chathub::response_element.create.BUTTON_MAX')
                    ]);
                }
            }
            $this->response_element->create($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_element.create.ADD_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function updateAction(UpdateRequest $request){
        try{
            if(isset($request['response_button']))
            {
                if(count($request['response_button'])>3){
                    return response()->json([
                        'error' => true,
                        'message' => __('chathub::response_element.create.BUTTON_MAX')
                    ]);
                }
            }
            $this->response_element->update($request->all());
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_element.create.UPDATE_SUCCESS')
            ]);
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function deleteAction(Request $request){
        try{
            $data=$request->all();
            $this->response_element->delete($data['response_element_id']);
            return response()->json([
                'error' => false,
                'message' => __('chathub::response_element.create.DELETE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function uploadImage(Request $request){
        try{
            $time = Carbon::now();
            // Requesting the file from the form
            $image = $request->file('file');
            // Getting the extension of the file
            $extension = $image->getClientOriginalExtension();
            //tên của hình ảnh
            // $filename = $image->getClientOriginalName();
            $filename = time() . str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . time() . "." . $extension;
            // This is our upload main function, storing the image in the storage that named 'public'
            $upload_success = $image->storeAs(TEMP_PATH, $filename, 'public');
            // If the upload is successful, return the name of directory/filename of the upload.
            if ($upload_success) {
                return response()->json($upload_success, 200);
            } // Else, return error 400
            else {
                return response()->json('error', 400);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
    public function getElement(Request $request){
        $data=$request->all();
        $response_element=$this->response_element->getResponseElement($data['response_element_id']);
        return view('chathub::message.popup.template',[
            'detail'=>$response_element
        ]);
    }
}