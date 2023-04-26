<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ChatHub\Http\Requests\Template\StoreRequest;
use Modules\ChatHub\Http\Requests\Template\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Modules\ChatHub\Models\ChatHubResponseButtonTable;
use Modules\ChatHub\Models\ChatHubResponseContentTable;
use Modules\ChatHub\Models\ChatHubResponseElementTable;

class TemplateController extends Controller
{
    protected $button;
    protected $template;
    protected $response_content;
    public function __construct(
        ChatHubResponseButtonTable $button,
        ChatHubResponseElementTable $template,
        ChatHubResponseContentTable $response_content
    ) {
        $this->button = $button;
        $this->template=$template;
        $this->response_content = $response_content;
    }
//    public function index(Request $request){
//        $arrResponse = $this->template->getAll($request->all());
//        $params = $request->all();
//        $stt = intval($request->get('page', 1));
//        $stt = (($stt ? $stt : 1) - 1) * 25 +1;
//        return view('admin.config-template.index',[
//            'object' => $arrResponse,
//            'params' => $params,
//            'stt' => $stt
//        ]);
//    }
    public function add(Request $request){
        $response_content_id = $request['response_content_id'];
        $button = $this->button->all();
        return view('admin.config-template.add',[
            'button'=>$button,
            'response_content_id'=> $response_content_id
        ]);
    }
    public function create(StoreRequest $request){
        try{
            return $this->template->create($request);
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function edit(Request $request){
        $template=$this->template->getById($request['id']);
        return view('chathub::config-template.popup-edit',[
            'template'=>$template,
        ]);
    }
    public function update(UpdateRequest $request){
        try{
            $this->template->updateTemplate($request);
            return $request['response_element_id'];
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function deleteAction(Request $request){   
        try{
            $data=$request->all();
            $this->template->where('response_element_id', '=', $data['key'])->delete();
            DB::table('chatbot_response_element_button')->where('response_element_id','=', $data['key'])->delete();
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function popupEditTypeTemplate(Request $request){
        $response_content_id = $request['response_content_id'];
        $response_content = $this->response_content->getById($response_content_id);
        return view('chathub::config-template.popup-edit-type-template',[
            'response_content'=>$response_content
        ]);
    }
    public function editTypeTemplate(Request $request){
        $data = $request->all();
        $response_content_id = $data['response_content_id'];
        unset($data['response_content_id']);
        $this->response_content->updateType($response_content_id, $data);
        return $data['template_type'];
    }
}
