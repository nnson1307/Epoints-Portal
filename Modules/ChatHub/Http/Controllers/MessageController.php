<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Http\Requests\Message\StoreRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\CustomerRegisterTable;
use Modules\ChatHub\Repositories\Message\MessageRepositoryInterface;
use Auth;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadCustomDefineTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\ProvinceTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Models\TagTable;


class MessageController extends Controller
{
    protected $message;
    public function __construct(
        MessageRepositoryInterface $message
    ) {
        $this->message = $message;
    }
    public function indexAction(Request $request, $channelSelect =null){
        try{
            $listChannel=$this->message->getListChannel(Auth::id());
            if(isset($listChannel[0])){
                $request['type_reading'] == isset($request['reading_type']) ? $request['reading_type'] : '';
                if($request['channelSelect']){
                    $listCustomer=$this->message->getListCustomer($request['channelSelect'], $request);
                }else{
                    $listCustomer=$this->message->getListCustomer($listChannel[0]['channel_id'], $request);
                }

                $currentCustomer = isset($listCustomer[0]) ? $listCustomer[0] : null;
                $filterMessage = [
                    'customer_id' => @$currentCustomer['customer_id'],
                    'channel_id' =>  $request['channelSelect'] ? $request['channelSelect'] : $listChannel[0]['channel_id'],
                    'type_reading' =>  $request['type_reading'] ? $request['type_reading'] : '',
                    'order_by' => 'desc'
                ];
                $listMessage = $this->message->getListMessage($filterMessage)->reverse()->toArray();
                $data = [
                    'currentChannel' => $listChannel[0]->toArray(),
                    'lastMessage' => array_shift($listMessage),
                    'currentCustomer' => $currentCustomer,
                    'listChannel'=> $listChannel,
                    'listCustomer'=>$listCustomer,
                    'listMessage' => $listMessage,
                    'channelSelect'=>$request['channelSelect']?$request['channelSelect']:$listChannel[0]['channel_id'],
                    'reading_type'=>$request['reading_type']?$request['reading_type']:''
                ];
                return view('chathub::message.index',$data);
            }else{
                return view('chathub::message.index',[
                    'listChannel'=> null,
                    'listCustomer'=>null
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function editForm(Request $request){
        try{
            $data=$request->all();
            $customer=$this->message->getCustomer($data['customer_id'], $data['channel_id']);
            return view('chathub::message.popup.form',[
                'customer'=>$customer
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getFormDeal(Request $request){
        try{
            $mPipeline = new PipelineTable();
            $optionPipeline = $mPipeline->getOption('DEAL');
            return view('chathub::message.popup.create-deal',[
                'optionPipeline'=>$optionPipeline
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function createDeal(Request $request){
        return $this->message->createDeal($request->all());
    }
    public function getFormLead(Request $request){
        try{

            $mTag = new TagTable();
            $mPipeline = new PipelineTable();
            $mCustomerSource = new CustomerSourceTable();
            $mStaff = new StaffsTable();
            $mProvince = new ProvinceTable();
            $mCustomerLead = new CustomerLeadTable();
            $input = $request->all();
            $mCustomerRegister = new CustomerRegisterTable();
            $dataCustomerRegister = $mCustomerRegister->getCustomer($input['customer_id']);
            $dataCustomerLead = [];
            if($dataCustomerRegister != null && $dataCustomerRegister['phone'] != ''){
                $dataCustomerLead = $mCustomerLead->getCustomerLeadByPhone($dataCustomerRegister["phone"]);
            }
            $optionTag = $mTag->getOption();
            $optionPipeline = $mPipeline->getOption('CUSTOMER');
            $optionSource = $mCustomerSource->getOption();
            //Option đầu mối doanh nghiệp
            $optionBusiness = $mCustomerLead->getOptionBusiness();
            //Option nhân viên (người được phân bổ)
            $optionStaff = $mStaff->getStaffOption();
            //Lấy option tỉnh/ thành
            $optionProvince = $mProvince->getOptionProvince();
            //Lấy cấu hình thông tin kèm theo của KH
            $mCustomerDefine = new CustomerLeadCustomDefineTable();
            $customDefine = $mCustomerDefine->getDefine();

            $html = \View::make('chathub::message.popup.create-or-update-lead', [
                "optionTag" => $optionTag,
                "optionPipeline" => $optionPipeline,
                "optionSource" => $optionSource,
                "optionBusiness" => $optionBusiness,
                "optionStaff" => $optionStaff,
                "optionProvince" => $optionProvince,
                'customDefine' => $customDefine,
                'dataCustomerLead' => $dataCustomerLead
            ])->render();

            return [
                'html' => $html
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function checkExistLead(Request $request){
        return $this->message->checkExistLead($request->all());
    }
    public function createOrUpdateLead(Request $request){
        return $this->message->createOrUpdateLead($request->all());
    }
    //lấy danh sách tin nhắn với customer
    public function getMessage(Request $request){
        try{
            $data = $request->all();

            $filterMessage = [
                'customer_id' => $data['customer_id'],
                'channel_id' =>  $data['channel_id'],
            ];

            $listMessage = $this->message->getListMessage($filterMessage);
            //dd($listMessage->toArray());
            return $listMessage;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    //thêm customer khi scroll tới cuối
    public function addCustomer(Request $request){
        try{
            $data = $request->all();
            $addCustomer = $this->message->addCustomer($data['customer'], $data['channel_id'], $data);
            return $addCustomer;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    //gửi tin nhắn
    public function sentMessage(Request $request){
        try{        
            $data = $request->all();
            $token = $this->message->getToken($data['channel_id']);
            $customer_id= $this->message->getSocialId($data['customer_id']);
            // dd($data['channel_id']);
            $channel = $this->message->getChannel($data['channel_id']);
            if($channel['service_id']==1){

                if(isset($data['arrayImage'])){
                    $this->message->sentImage($customer_id,$data['arrayImage'], $token);
                }
                if(isset($data['arrayFile'])){
                    $this->message->sentFile($customer_id,$data['arrayFile'], $token);
                }
                if(isset($data['mess'])){
                    $this->message->sentMessage($customer_id,$data['mess'], $token);
                }
            }else{
                if(isset($data['arrayImage'])){
                    $this->message->sentImageZalo($customer_id,$data['arrayImage'], $token);
                }
                if(isset($data['arrayFile'])){
                    $this->message->sentFileZalo($customer_id,$data['arrayFile'], $token);
                }
                if(isset($data['mess'])){
                    $this->message->sentMessageZalo($customer_id,$data['mess'], $token);
                }
            }
            return $channel;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
    //thêm tin nhắn khi scroll top
    public function addMessage(Request $request){
        try{
            $data = $request->all();
            $message = $this->message->addMessage($data['channel_id'], $data['customer_id'], $data['message_id']);
            return $message;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function seenMessage(Request $request){
        try{
            $data = $request->all();
            $this->message->seenMessage($data['customer_id'], $data['channel_id']);

        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function chooseChannel(Request $request){
        try{
            $data = $request->all();
            $listCustomer=$this->message->getListCustomer($data['channel_id'], $data);
            return $listCustomer;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function updateCustomer(StoreRequest $request){
        try{
            $data = $request->all();
            $this->message->updateCustomer($data);
            return response()->json([
                'error' => false,
                'message' => __('chathub::message.index.UPĐATE_SUCCESS')
            ]);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    // của em trong module chathub à uh a xem code ti
    public function uploadsImageAction(Request $request){
        try{
            $time = Carbon::now();
            // Requesting the file from the form
            $image = $request->file('file');
            // Getting the extension of the file
            $extension = $image->getClientOriginalExtension();
            //tên của hình ảnh
            $filename = $image->getClientOriginalName();
            //$filename = time() . str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . time() . "." . $extension;
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

    public function deleteImageAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

    public function getElement(Request $request){
        $data=$request->all();

        return view('chathub::message.popup.template',[
            'detail'=>$response_element
        ]);
    }


}
