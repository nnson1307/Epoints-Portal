<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\ChatHub\Http\Requests\Button\StoreRequest;
use Modules\ChatHub\Http\Requests\Button\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Modules\ChatHub\Models\ChatHubResponseButtonTable;

class ButtonController extends Controller
{
    protected $button;
    public function __construct(
        ChatHubResponseButtonTable $button
    ) {
        $this->button = $button;
    }
//    public function index(Request $request){
//        $arrResponse = $this->button->getAll($request->all());
//
//        $params = $request->all();
//        $stt = intval($request->get('page', 1));
//        $stt = (($stt ? $stt : 1) - 1) * 25 +1;
//        return view('admin.button.index',[
//            'object' => $arrResponse,
//            'params' => $params,
//            'stt' => $stt
//        ]);
//    }
//    public function add(Request $request){
//        return view('admin.button.add');
//    }
    public function create(StoreRequest $request){
        try{
            $data=$request->all();
            $id=$this->button->create($data);
            return $id;
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
//    public function edit(Request $request){
//        $button=$this->button->getById($request['id']);
//        return view('admin.button.edit',[
//            'button'=>$button
//        ]);
//    }
    public function update(UpdateRequest $request){
        try{
            $data=$request->all();
            $this->button->updateButton($data);
            return $data['response_button_id'];
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function deleteAction(Request $request){ 
        try{
            $data=$request->all();
            $this->button->where('response_button_id', '=', $data['response_button_id'])->delete();
            DB::table('chathub_response_element_button')->where('response_button_id','=', $data['response_button_id'])->delete();
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
}
