<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */

namespace Modules\ChatHub\Repositories\Message;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubChannelTable;
use Modules\ChatHub\Models\CustomerRegisterTable;
use Modules\ChatHub\Models\ChatHubConversationTable;
use Modules\Admin\Models\StaffTable;
use Facebook\Facebook as Facebook;
use Modules\CustomerLead\Models\CustomerContactTable;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerEmailTable;
use Modules\CustomerLead\Models\CustomerFanpageTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerPhoneTable;
use Modules\CustomerLead\Models\CustomerTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\MapCustomerTagTable;
use Modules\CustomerLead\Models\PipelineTable;
use Zalo\Zalo;
use Zalo\FileUpload\ZaloFile;
use Modules\ChatHub\Models\ChatHubMessageTable;
use MyCore\Storage\Azure\UploadFileToAzureStorage;

class MessageRepository implements MessageRepositoryInterface
{
    protected $channel;
    protected $customer;
    protected $message;
    protected $staff;
    protected $fb;
    protected $conversation;
    public function __construct(
        ChatHubChannelTable $channel,
        StaffTable $staff,
        Facebook $fb,
        CustomerRegisterTable $customer,
        ChatHubMessageTable $message,
        ChatHubConversationTable $conversation
    )
    {
        $this->channel = $channel;
        $this->staff=$staff;
        $this->fb=$fb;
        $this->customer=$customer;
        $this->message=$message;
        $this->conversation=$conversation;
    }

    public function uploadManager(){
        return new UploadFileToAzureStorage();
    }
    // lấy danh sách channel subcribed theo id user
    public function getListChannel($id){
        return $this->channel->getListSubcribed($id);
    }
    //lấy danh sách customer theo id channel
    public function getListCustomer($channel_id, $filter=null){
        return $this->customer->getList($channel_id , $filter);
    }
    //lấy danh sách message của customer với channel
    public function getListMessage($filter){
        $this->conversation->seenMessage($filter);
        // dd($this->message->getList($customer_id, $channel_id));
        return $this->message->getList($filter);
    }
    //thêm customer khi croll tới cuối
    public function addCustomer($customer, $channel_id, $filter=null){
        return $this->customer->addCustomer($customer, $channel_id, $filter);
    }
    //Lấy token
    public function getToken($channel_id){
         return $token= $this->channel->getToken($channel_id);
    }
    //lấy thông tin channel
    public function getChannel($channel_id){
        return $this->channel->getChannel($channel_id);
    }
    // gửi tin nhắn facebook
    public function sentMessage($customer_id, $message, $token){
        $this->fb->post("/me/messages",
            [
                "recipient"=>[
                    'id'=> $customer_id
                ], 
                "message"=>[
                    'text'=> $message
                ],
                "access_token" => $token
            ]);
    }
    public function sentImage($customer_id, $image, $token){
        foreach($image as $item){
            $this->fb->post("/me/messages",
            [
                "recipient"=>[
                    'id'=> $customer_id
                ], 
                "message"=>[
                    'attachment'=>[
                        'type'=>'image',
                        'payload'=>[
                            // 'url'=>'https://vcdn-ngoisao.vnecdn.net/2019/05/10/trinh-2-1557452033-7311-1557452719.png'
                            'url'=>$item
                        ]
                    ]
                ],
                "access_token" => $token
            ]);
        }
        
    }
    public function sentFile($customer_id, $file, $token){
        foreach($file as $item){
            $this->fb->post("/me/messages",
            [
                "recipient"=>[
                    'id'=> $customer_id
                ], 
                "message"=>[
                    'attachment'=>[
                        'type'=>'file',
                        'payload'=>[
                            // 'url'=>'https://cdn.fbsbx.com/v/t59.2708-21/119423073_2213776888765698_3044981566718637580_n.docx/1712726.docx?_nc_cat=104&_nc_sid=0cab14&_nc_ohc=qDI3syF3dFcAX_BsGoV&_nc_ht=cdn.fbsbx.com&oh=5c4ea5f34d1809cccd466d351fbd440a&oe=5F8B1A74'
                            'url'=>$item
                        ]
                    ]
                ],
                "access_token" => $token
            ]);
        }
        
    }

    // gửi tin nhắn zalo
    public function sentMessageZalo($customer_id, $message, $token){
        $config = array(
            'app_id' => '1396758012695840025',
            'app_secret' => 'UcQIiUhziHy5u3LVeiXf',
            'callback_url' => 'https://matthews.piospa.com/chat-hub/message'
        );
        $zalo = new Zalo($config);
        $data = [
                "recipient"=>[
                    'user_id'=> $customer_id
                ], 
                "message"=>[
                    'text'=> $message
                ],
            ];
        // send request
        $zalo->post("https://openapi.zalo.me/v2.0/oa/message", $token, $data);
    }
    public function sentImageZalo($customer_id, $image, $token){
        $config = array(
            'app_id' => '1396758012695840025',
            'app_secret' => 'UcQIiUhziHy5u3LVeiXf',
            'callback_url' => 'https://matthews.piospa.com/chat-hub/message'
        );
        $zalo = new Zalo($config);

        foreach($image as $item){   
            
            // $data = array('file' => new ZaloFile('https://vcdn-ngoisao.vnecdn.net/2019/05/10/trinh-2-1557452033-7311-1557452719.png'));
            $data = array('file' => new ZaloFile($item));
            $response = $zalo->post('https://openapi.zalo.me/v2.0/oa/upload/image', $token, $data);
            $result = $response->getDecodedBody(); 
            // $msgBuilder = new MessageBuilder('media');
            // $msgBuilder->withUserId('494021888309207992');
            // $msgBuilder->withText('Message Image');
            // $msgBuilder->withAttachment('cb2ab1696b688236db79');
            $data = [
                "recipient"=>[
                    'user_id'=> $customer_id
                ], 
                "message"=>[
                    'attachment'=> [
                        "type"=> "template",
                        "payload"=> [
                            "template_type"=> "media",
                            "elements"=> [[
                                "media_type"=> "image",
                                "attachment_id"=> $result['data']['attachment_id']
                            ]]
                        ]
                    ]
                ]
            ];
            // send request
            $zalo->post("https://openapi.zalo.me/v2.0/oa/message", $token, $data);
        }
        
    }
    public function sentFileZalo($customer_id, $file, $token){
        $config = array(
            'app_id' => '1396758012695840025',
            'app_secret' => 'UcQIiUhziHy5u3LVeiXf',
            'callback_url' => 'https://matthews.piospa.com/chat-hub/message'
        );
        $zalo = new Zalo($config);
        foreach($file as $item){
            // $data = array('file' => new ZaloFile('http://localhost/piospa.com/public/temp_upload/16020567532447102020_BYNNDEHQM8.docx'));
            $data = array('file' => new ZaloFile($item));
            $response = $zalo->post('https://openapi.zalo.me/v2.0/oa/upload/file', $token, $data);
            $result = $response->getDecodedBody();
            $data = [
                "recipient"=>[
                    'user_id'=> $customer_id
                ], 
                "message"=>[
                    'attachment'=>[
                        'type'=>'file',
                        'payload'=>[
                            'token'=>$result['data']['token']
                            // 'url'=>$item
                        ]
                    ]
                ]
            ];
            // send request
            $zalo->post("https://openapi.zalo.me/v2.0/oa/message", $token, $data);
        }
        
    }

    public function getSocialId($customer_id){
        return $this->customer->getSocialId($customer_id);
    }
    //lấy thông tin customer
    public function getCustomer($customer_id, $channel_id){
        return $this->customer->getCustomer($customer_id, $channel_id);
    }
    //thêm message khi scroll top
    public function addMessage($channel_id, $customer_id, $message_id){
        // dd($this->message->addMessage($channel_id, $customer_id, $message_id));
        return $this->message->addMessage($channel_id, $customer_id, $message_id);
    }
    public function seenMessage($customer_id, $channel_id){
        $this->conversation->seenMessage($customer_id, $channel_id);
    }
    // CÁI NÀY DÙNG ĐỂ QUẢN LÍ NGUỒN KHÁCH HÀNG
    //
    //update customer
    public function updateCustomer($data){
        $update=[
//            'address'=>$data['address'],
            'gender'=>$data['gender'],
            'phone'=>$data['phone'],
            'email'=>$data['email']
        ];
        $this->customer->updateCustomer($data['id'],$update);
    }

    public function uploadImage($file){
        $result = $this->uploadManager()->doUpload($file);
        return $result['public_path'];
    }

    public function createDeal($input)
    {
        try{
            $mCustomerRegister = new CustomerRegisterTable();
            $dataCustomerRegister = $mCustomerRegister->getCustomer($input['customer_id']);
            $dealCustomerCode = '';
            if($dataCustomerRegister != null && $dataCustomerRegister['phone'] != ''){
                // check exist customer
                $mCustomer = new CustomerTable();
                $dataCustomer = $mCustomer->getCustomerByPhone($dataCustomerRegister['phone']);
                if($dataCustomer != null){
                    $dealCustomerCode = $dataCustomer['customer_code'];
                }
                else{
                    $mCustomerLead = new CustomerLeadTable();
                    $dataCustomerLead = $mCustomerLead->getCustomerLeadByPhone($dataCustomerRegister['phone']);
                    if($dataCustomerLead != null){
                        $dealCustomerCode = $dataCustomer['customer_lead_code'];
                    }
                    else{
                        return response()->json([
                            'error' => true,
                            'message' => __('Người dùng chưa tồn tại trong hệ thống, không thể tạo deal')
                        ]);
                    }
                }
                // check exist deal
            }
            else{
                return response()->json([
                    'error' => true,
                    'message' => __('Thông tin người dùng không đủ')
                ]);
            }
            $mCustomerDeal = new CustomerDealTable();
            $dataDeal = [
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'],
                'amount' => (float)str_replace(',', '', $input['amount']),
                'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                'created_by' => \Illuminate\Support\Facades\Auth::id(),
                'customer_code' => $dealCustomerCode,
                'phone' => $dataCustomerRegister['phone']
            ];
            $dealId = $mCustomerDeal->add($dataDeal);
            // update deal_code
            $dealCode = 'DEALS_' . date('dmY') . sprintf("%02d", $dealId);
            $mCustomerDeal->edit($dealId, ['deal_code' => $dealCode]);
            // insert deal_detail, order detail
            if (isset($input['arrObject'])) if ($input['arrObject'] != null) {
                $mDealDetail = new CustomerDealDetailTable();
                foreach ($input['arrObject'] as $key => $value) {
                    $value['price'] = (float)str_replace(',', '', $value['price']);
                    $value['amount'] = (float)str_replace(',', '', $value['amount']);
                    $value['discount'] = (float)str_replace(',', '', $value['discount']);
                    $value['deal_code'] = $dealCode;
                    $value['created_by'] = Auth::id();
                    $dealDetailId = $mDealDetail->add($value);
                }
            }
            return response()->json([
                'error' => false,
                'message' => __('Bạn vừa tạo deal thành công, mã deal:') . ' ' .$dealCode
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    function array_has_dupes($array)
    {
        return count($array) !== count(array_unique($array));
    }

    public function checkExistLead($input){
        $mCustomerLead = new CustomerLeadTable();
        $dataCustomerLead = $mCustomerLead->getCustomerLeadByPhone($input["phone"]);
        if($dataCustomerLead != null){
            return response()->json([
                "error" => true,
            ]);
        }
        return response()->json([
            "error" => false,
        ]);
    }
    public function createOrUpdateLead($input)
    {
        $mMapCustomerTag = new MapCustomerTagTable();
        $mJourney = new JourneyTable();
        $mCustomerPhone = new CustomerPhoneTable();
        $mCustomerEmail = new CustomerEmailTable();
        $mCustomerFanpage = new CustomerFanpageTable();
        $mCustomerContact = new CustomerContactTable();
        $mPipeline = new PipelineTable();
        $mCustomerLead = new CustomerLeadTable();
        $mCustomer = new CustomerTable();
        $mCustomerRegister = new CustomerRegisterTable();
        DB::beginTransaction();
        try {

            $dataCustomerRegister = $mCustomerRegister->getCustomer($input['customer_id']);
            if($dataCustomerRegister != null && $dataCustomerRegister['phone'] != ''){
                $dataCustomer = $mCustomer->getCustomerByPhone($dataCustomerRegister['phone']);
                if($dataCustomer != null){
                    DB::rollback();
                    return response()->json([
                        "error" => true,
                        "message" => __("Số điện thoại đã là khách hàng của hệ thống, vui lòng kiểm tra lại"),
                    ]);
                }
            }
            $dataCustomerLead = $mCustomerLead->getCustomerLeadByPhone($input["phone"]);
            // check sdt đã tồn tại là khách hàng hệ thống

            $arrInsertPhone = [];
            $arrInsertEmail = [];
            $arrInsertFanpage = [];
            $arrInsertContact = [];

            //Kiểm tra phone + phone attack có trùng nhau ko
            $arrPhone = [$input["phone"]];
            if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                foreach ($input['arrPhoneAttack'] as $v) {
                    $arrPhone [] = $v['phone'];
                }

                //Check unique phone
                if ($this->array_has_dupes($arrPhone) == true) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Số điện thoại đã trùng vui lòng kiểm tra lại'),
                    ]);
                }
            }
            //Kiểm tra email + email attack có trùng nhau ko
            $arrEmail = [$input["email"]];
            if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                foreach ($input['arrEmailAttack'] as $v) {
                    $arrEmail [] = $v['email'];
                }

                //Check unique email
                if ($this->array_has_dupes($arrEmail) == true) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Email đã trùng vui lòng kiểm tra lại'),
                    ]);
                }
            }
            //Kiểm tra fanpage + fanpage attack có trùng nhau ko
            $arrFanpage = [$input["fanpage"]];
            if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                foreach ($input['arrFanpageAttack'] as $v) {
                    $arrEmail [] = $v['fanpage'];
                }

                //Check unique email
                if ($this->array_has_dupes($arrFanpage) == true) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Fan page đã trùng vui lòng kiểm tra lại'),
                    ]);
                }
            }

            // Nhân viên được phân bổ
            $saleId = null;
            if (isset($input['sale_id'])) {
                $saleId = $input['sale_id'];
            } else {
                $saleId = Auth()->id();
            }
            // Thời gian giữ tối đa
            $pipelineInfo = $mPipeline->getDetailByCode($input['pipeline_code']);
            $timeRevokeLead = 0;
            $timeNow = Carbon::now();
            if ($pipelineInfo['time_revoke_lead'] != null) {
                $timeRevokeLead = $pipelineInfo['time_revoke_lead'];
            }

            $data = [
                "full_name" => $input["full_name"],
                "email" => $input["email"],
                "phone" => $input["phone"],
                "gender" => $input["gender"],
                "address" => $input["address"],
                "pipeline_code" => $input["pipeline_code"],
                "journey_code" => $input["journey_code"],
                "customer_type" => $input["customer_type"],
                "hotline" => isset($input["hotline"]) ? $input["hotline"] : null,
                "fanpage" => $input["fanpage"],
                "zalo" => $input["zalo"],
                "tax_code" => isset($input["tax_code"]) ? $input["tax_code"] : null,
                "representative" => isset($input["representative"]) ? $input["representative"] : null,
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                "customer_source" => $input['customer_source'],
                "business_clue" => $input['business_clue'],
                "assign_by" => Auth()->id(),
                "sale_id" => $saleId,
                "date_revoke" => $timeNow->addDay($timeRevokeLead),
                "province_id" => $input['province_id'],
                "district_id" => $input['district_id']
            ];

                $data["avatar"] = $dataCustomerRegister != null ? $dataCustomerRegister["avatar"] : '';

            if ($input['customer_type'] == "business") {
                $data['business_clue'] = null;
            }

            if($dataCustomerLead != null){
                $customerLeadId = $dataCustomerLead['customer_lead_id'];
                $leadCode = $dataCustomerLead['customer_lead_code'];
                $mCustomerLead->edit($data, $customerLeadId);
            }
            else{
                //Insert customer lead
                $customerLeadId = $mCustomerLead->add($data);
                //Update customer_lead_code
                $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $customerLeadId);
                $mCustomerLead->edit([
                    "customer_lead_code" => $leadCode
                ], $customerLeadId);
            }

            $mMapCustomerTag->remove($leadCode);
            if (isset($input["tag_id"]) && count($input["tag_id"]) > 0) {
                foreach ($input["tag_id"] as $v) {
                    //Insert map customer lead
                    $mMapCustomerTag->add([
                        "customer_lead_code" => $leadCode,
                        "tag_id" => $v
                    ]);
                }
            }

            $mCustomerPhone->removePhone($leadCode);
            if (isset($input['arrPhoneAttack']) && count($input['arrPhoneAttack']) > 0) {
                foreach ($input['arrPhoneAttack'] as $v) {
                    $arrInsertPhone [] = [
                        'customer_lead_code' => $leadCode,
                        'phone' => $v['phone'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer phone
            $mCustomerPhone->insert($arrInsertPhone);

            $mCustomerEmail->removeEmail($leadCode);
            if (isset($input['arrEmailAttack']) && count($input['arrEmailAttack']) > 0) {
                foreach ($input['arrEmailAttack'] as $v) {
                    $arrInsertEmail [] = [
                        'customer_lead_code' => $leadCode,
                        'email' => $v['email'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer email
            $mCustomerEmail->insert($arrInsertEmail);

            $mCustomerFanpage->removeFanpage($leadCode);
            if (isset($input['arrFanpageAttack']) && count($input['arrFanpageAttack']) > 0) {
                foreach ($input['arrFanpageAttack'] as $v) {
                    $arrInsertFanpage [] = [
                        'customer_lead_code' => $leadCode,
                        'fanpage' => $v['fanpage'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer fanpage
            $mCustomerFanpage->insert($arrInsertFanpage);

            $mCustomerContact->removeContact($leadCode);
            if (isset($input['arrContact']) && count($input['arrContact']) > 0) {
                foreach ($input['arrContact'] as $v) {
                    $arrInsertContact [] = [
                        'customer_lead_code' => $leadCode,
                        'full_name' => $v['full_name'],
                        'phone' => $v['phone'],
                        'email' => $v['email'],
                        'address' => $v['address'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            //Insert customer contact
            $mCustomerContact->insert($arrInsertContact);

            $this->customer->updateCustomer($input['customer_id'],[
                'gender'=> $input['gender'],
                'phone'=> $input['phone'],
                'email'=> $input['email']
            ]);

            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Thành công")
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }
}
