<?php
namespace Modules\CallCenter\Http\Controllers;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CallCenter\Repositories\CallCenter\CallCenterRepoInterface;
use Modules\CallCenter\Models\CustomerRequestAttributeTable;

class CallCenterController extends Controller
{
    protected $callCenter;

    public function __construct(
        CallCenterRepoInterface $callCenter
    )
    {
        $this->callCenter = $callCenter;
    }

    /**
     * Danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $data = $this->callCenter->getListCustomerRequest();
        return view('call-center::call-center.index', [
            'LIST' => $data['LIST'],
            'optionConfigShow' => $data['optionConfigShow']
        ]);
    }

     /**
     * Danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getList(Request $request)
    {
        $filter = $request->only(['page', 'display','search','created_at']);
        $data = $this->callCenter->getListCustomerRequest($filter);
        return view('call-center::call-center.list', [
            'LIST' => $data['LIST'],
            'optionConfigShow' => $data['optionConfigShow']
        ]);
    }

     /**
     * Show modal search
     *
     * @return mixed
     */
    public function showModalSearchCustomerAction(Request $request)
    {
        $html = \View::make('call-center::call-center.pop.form-search', [])->render();
        return response()->json([
            'html' => $html
        ]);
    }

    public function searchCustomerAction(Request $request){

        $data = $this->callCenter->searchCustomer($request->keyWord);
        if($data['LIST'] != null){
            $html = \View::make('call-center::call-center.pop.list-customer', [
                'LIST' => $data['LIST'],
                'optionConfigShow' => $data['optionConfigShow']
            ])->render(); 
        }else {
            $html = \View::make('call-center::call-center.pop.form-not-data', [
                'keyWork' => $request->keyWord
            ])->render();
        }
        
        return response()->json([
            'html' => $html
        ]);
    }

    public function createCustomerRequestNotInfoAction(Request $request){
        $data = $this->callCenter->createCustomerRequestNotInfo($request);
        return $data;
    }

    public function createCustomerRequestAction(Request $request){
        $data = $this->callCenter->createCustomerRequest($request);
        return $data;
    }

    
     /**
     * Show modal info
     *
     * @return mixed
     */
    public function showModalCustomerInfoSuccessAction(Request $request)
    {
        
     
        // $object_request = $request->object_request ?? null; 
        $customer_request_id = $request->customer_request_id ?? null;
        $html = '';
        $view = '';

        $dataDeal = null;
        $dataContract = null;

        /**
         * Lấy config show thông tin custom
         */
        $customerRequestAttribute = new CustomerRequestAttributeTable();
        $requestAttribute = $customerRequestAttribute->getOptionCreate();

        /**
         * Lấy thông tin tiêp nhận
         */
        $object_request = $this->callCenter->getInfoCustomerRequest($customer_request_id);
        $object_id = $object_request['object_id'];
        $object_type = $object_request['object_type'];
       
        if($object_id != ''){
            $data = null;
            if($object_type == 'customer_lead'){
                $data = $this->callCenter->getInfoCustomerLead($object_id);
                $address = $data['address'];
                if($data['ward_name'] != ''){
                    $address =  $address == '' ? '' :  $address.', ' . $data['ward_name'];
                }
                if($data['district_name'] != ''){
                    $address =  $address . ', ' . $data['district_name'];
                }
                if($data['province_name'] != ''){
                    $address =  $address . ', ' . $data['province_name'];
                }
                $data['address'] = $address;
                $view = 'call-center::call-center.pop.form-info-customer-lead-success';
                $filterDeal = [
                    'customer_code' => $data['customer_lead_code']
                ];
                $dataDeal = $this->callCenter->getListDealLeadDetail($filterDeal);
            }else {
                $data = $this->callCenter->getInfoCustomer($object_id);
                $address = $data['address'];
                if($data['ward_name'] != ''){
                    $address =  $address == '' ? '' :  $address.', ' . $data['ward_name'];
                }
                if($data['district_name'] != ''){
                    $address =  $address . ', ' . $data['district_name'];
                }
                if($data['province_name'] != ''){
                    $address =  $address . ', ' . $data['province_name'];
                }
                $data['address'] = $address;
                $filterDeal = [
                    'customer_code' => $data['customer_code']
                ];
                $dataDeal = $this->callCenter->getListDealLeadDetail($filterDeal);
                $dataContract = $this->callCenter->getListContract($object_id);
                $view = 'call-center::call-center.pop.form-info-customer-success';
            }
           
            $html = \View::make($view, [
                'data' => $data,
                'object_id' => $object_id,
                'object_type' => $object_type,
                'object_request' => $object_request,
                "dataDeal" => $dataDeal,
                "dataContract" => $dataContract,
                'request_attribute' => $requestAttribute
            ])->render();
        }
       
        return response()->json([
            'html' => $html
        ]);
    }

     /**
     * Show modal info
     *
     * @return mixed
     */
    public function showModalCustomerInfoAction(Request $request)
    {
        $object_id = $request->object_id ?? '';
        $object_type = $request->object_type ?? '';
        $phone = '';
        $html = '';
        $view = '';

        if(preg_match('/^[0-9]{10}+$/', $request->phone ?? '')) {
            $phone = $request->phone ?? '';
        }
        /**
         * Lấy config show thông tin custom
         */
        $customerRequestAttribute = new CustomerRequestAttributeTable();
        $requestAttribute = $customerRequestAttribute->getOptionCreate();
      
        if($object_id != ''){
            $data = null;
            if($object_type == 'customer_lead'){
                $data = $this->callCenter->getInfoCustomerLead($object_id);
                $address = $data['address'];
                if($data['ward_name'] != ''){
                    $address =  $address == '' ? '' :  $address.', ' . $data['ward_name'];
                }
                if($data['district_name'] != ''){
                    $address =  $address . ', ' . $data['district_name'];
                }
                if($data['province_name'] != ''){
                    $address =  $address . ', ' . $data['province_name'];
                }
                $data['address'] = $address;
                $view = 'call-center::call-center.pop.form-info-customer-lead';
            }else {
                $data = $this->callCenter->getInfoCustomer($object_id);
                $address = $data['address'];
                if($data['ward_name'] != ''){
                    $address =  $address == '' ? '' :  $address.', ' . $data['ward_name'];
                }
                if($data['district_name'] != ''){
                    $address =  $address . ', ' . $data['district_name'];
                }
                if($data['province_name'] != ''){
                    $address =  $address . ', ' . $data['province_name'];
                }
                $data['address'] = $address;
                $view = 'call-center::call-center.pop.form-info-customer';
            }
            $html = \View::make($view, [
                'data' => $data,
                'object_id' => $object_id,
                'object_type' => $object_type,
                'phone' => $phone,
                'request_attribute' => $requestAttribute
            ])->render();
        }else {
            $optionProvice = $this->callCenter->getOptionProvince();
            $optionPipeline = $this->callCenter->getOptionPipeline();
            $optionStaff = $this->callCenter->getOptionStaff();
            $optionSource = $this->callCenter->loadCustomerSource();
           
            $html = \View::make('call-center::call-center.pop.form-info', [
                'optionProvince' => $optionProvice,
                'optionPipeline' => $optionPipeline,
                'optionStaff' => $optionStaff,
                'optionSource' => $optionSource,
                'object_id' => $object_id,
                'object_type' => $object_type,
                'phone' => $phone,
                'request_attribute' => $requestAttribute
            ])->render();
        }
       
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Load danh sách hành trình theo pipeline code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadOptionJourney(Request $request)
    {
        $pipelineCode = $request->pipeline_code;
        $data = $this->callCenter->loadOptionJourney($pipelineCode);
        return response()->json($data);
    }
}