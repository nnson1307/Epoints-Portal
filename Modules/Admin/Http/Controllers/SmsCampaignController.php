<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/29/2019
 * Time: 10:33 AM
 */

namespace Modules\Admin\Http\Controllers;


use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\SmsDealDetailTable;
use Modules\Admin\Models\SmsDealTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\BrandName\BrandNameRepositoryInterFace;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\Department\DepartmentRepositoryInterface;
use Modules\Admin\Repositories\SendSms\SendSmsRepository;
use Modules\Admin\Repositories\SmsCampaign\SmsCampaignRepositoryInterface;
use Modules\Admin\Repositories\SmsExportExcel\CollectionExport;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\PipelineTable;

class SmsCampaignController extends Controller
{
    protected $brandName;
    protected $smsCampaign;
    protected $branch;
    protected $customers;
    protected $staff;
    protected $smsLog;
    protected $depa;
    protected $sendSms;

    public function __construct(
        BrandNameRepositoryInterFace $brandName,
        SmsCampaignRepositoryInterface $smsCampaign,
        BranchRepositoryInterface $branch,
        CustomerRepository $customers,
        StaffRepositoryInterface $staff,
        SmsLogRepositoryInterface $smsLog,
        DepartmentRepositoryInterface $depa,
        SendSmsRepository $sendSms
    )
    {
        $this->brandName = $brandName;
        $this->smsCampaign = $smsCampaign;
        $this->branch = $branch;
        $this->customers = $customers;
        $this->staff = $staff;
        $this->smsLog = $smsLog;
        $this->depa = $depa;
        $this->sendSms = $sendSms;
    }

    //function view index
    public function indexAction()
    {
        $brandName = $this->brandName->getOption();
        $list = $this->smsCampaign->list2();

        $smsLog = $this->smsLog->getAll();

        $listSmsCampaign = $this->listSmsCampaign($list, $smsLog);
        $result = collect($listSmsCampaign)->forPage(1, 10);
        return view('admin::marketing.sms.campaign.index', [
            'FILTER' => $this->filters(),
            'brandName' => $brandName,
            'page' => 1,
            'LIST' => $result,
            'data' => $listSmsCampaign,
        ]);
    }

    private function listSmsCampaign($campaign, $smsLog)
    {
        $result = [];
        if ($campaign != null && $smsLog != null) {
            foreach ($campaign as $item) {
                $sentBy = '';
                $queryStaff = $this->staff->getItem($item['sent_by']);
                if ($queryStaff != null) {
                    $sentBy = $queryStaff->full_name;
                }
                $result[] = [
                    'campaign_id' => $item['campaign_id'],
                    'sms_campaign_name' => $item['name'],
                    'status' => $item['status'],
                    'created_by' => $item['full_name'],
                    'sent_by' => $sentBy,
                    'created_at' => $item['created_at'],
                    'time_sent' => $item['time_sent'],
                ];
            }

            foreach ($result as $key => $value) {
                $totalMessage = 0;
                $messageSuccess = 0;
                $messageError = 0;

                foreach ($smsLog as $kk => $vv) {
                    if ($value['campaign_id'] == $vv['campaign_id']) {
                        $totalMessage += 1;
                        if (!empty($vv['error_code'])) {
                            $messageError += 1;
                        } else {
                            if ($vv['sms_status'] == 'sent') {
                                $messageSuccess += 1;
                            }
                        }
                    }
                }
                $result[$key]['totalMessage'] = $totalMessage;
                $result[$key]['messageSuccess'] = $messageSuccess;
                $result[$key]['messageError'] = $messageError;
            }

            return $result;
        }
    }

    protected function filters()
    {
        $staff = $this->staff->getStaffOption();
        return [
            'created_by' => [
                'data' => (['' => __('Chọn người tạo')]) + $staff
            ],
            'sent_by' => [
                'data' => (['' => __('Chọn người gửi')]) + $staff
            ],
            'status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'new' => __('Mới'),
                    'sent' => __('Hoàn thành'),
                    'cancel' => __('Hủy')
                ]
            ],
        ];
    }

    public function listAction(Request $request)
    {
//        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'created_by', 'status']);
//        $list = $this->smsCampaign->list2($filters);
//        return view('admin::marketing.sms.campaign.list', [
//                'LIST' => $list,
//                'FILTER' => $this->filters(),
//                'page' => $filters['page']
//            ]
//        );
    }

    public function submitAddAction(Request $request)
    {
        $input = $request->all();
        $is_deal_created = $request->is_deal_created;
        $name = $request->name;
        $cost = str_replace(",", "", $request->cost);
        $content = $request->contents;
        $dateTime = $request->dateTime;
        $isNow = $request->isNow;
        $slug = str_slug($name);
        $checkSlug = $this->smsCampaign->checkSlugName($slug, 0);
        $dateTimeNow = date('Ymd');
        $branch = $request->branch;
        if ($checkSlug != null) {
            return response()->json(['error' => 'slug']);
        } else {
            $data = [
                'is_deal_created' => $is_deal_created,
                'name' => $name,
                'cost' => $cost,
                'status' => 'new',
                'content' => $content,
                'slug' => $slug,
                'code' => '',
//                'value' => $dateTime,
                'is_now' => $isNow,
                'created_by' => Auth::id(),
                'branch_id' => $branch,
            ];
            if ($isNow == 1) {
                $data['value'] = null;
            } else {
                $data['value'] = Carbon::createFromFormat('d/m/Y H:i', $dateTime)->format('Y-m-d H:i:s');
            }

            $insert = $this->smsCampaign->add($data);
            $this->smsCampaign->edit(['code' => 'CD_' . $dateTimeNow . $insert], $insert);
            if($request->is_deal_created == 1){
                $mSmsDeal = new SmsDealTable();
                $mSmsDealDetail = new SmsDealDetailTable();
                $dataDeal = [
                    'sms_campaign_id' => $insert,
                    'pipeline_code' => $input['pipeline_code'],
                    'journey_code' => $input['journey_code'],
                    'amount' => (float)str_replace(',', '', $input['amount']),
                    'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                    'created_by' => Auth::id()
                ];
                $smsDealId = $mSmsDeal->add($dataDeal);
                // insert deal_detail, order detail
                if (isset($input['arrObject'])) if ($input['arrObject'] != null) {
                    foreach ($input['arrObject'] as $key => $value) {
                        $value['price'] = (float)str_replace(',', '', $value['price']);
                        $value['amount'] = (float)str_replace(',', '', $value['amount']);
                        $value['discount'] = (float)str_replace(',', '', $value['discount']);
                        $value['sms_deal_id'] = $smsDealId;
                        $value['created_by'] = Auth::id();
                        $dealDetailId = $mSmsDealDetail->add($value);
                    }
                }
            }
            return response()->json(['error' => 0, 'id' => $insert]);
        }
    }

    public function indexSendSms()
    {
        $smsCampaign = $this->smsCampaign->getOptionCustomerCare();
        $branch = $this->branch->getBranch();
        return view('admin::marketing.sms.campaign.send-sms', [
            'smsCampaign' => $smsCampaign,
            'branch' => $branch,
        ]);
    }

    public function removeAction($id)
    {
        $this->smsCampaign->remove($id);
        $this->smsLog->cancelLogCampaign($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function getInfoSmsCampaign(Request $request)
    {
        $data = $this->smsCampaign->getItem($request->id);
        if ($data != null) {
            $brandName = '';
            $getItemBandName = $this->brandName->getItem($data->brandname_id);
            if ($getItemBandName != null) {
                $brandName = $getItemBandName->name;
            }
            $result = [
                'brandname_id' => $brandName,
                'value' => $data->value, 'status' => $data->status
            ];
            return response()->json($result);
        }
    }

    public function searchCustomerAction(Request $request)
    {
        $keyword = $request->keyword;
        $birthday = $request->birthday;
        $gender = $request->gender;
        $branchId = $request->branch;
        if ($birthday != '') {
            $birthdayFormat = Carbon::createFromFormat('d/m/Y', $birthday)->format('Y-m-d');
        } else {
            $birthdayFormat = null;
        }
        $listCustomerAdd = $request->session()->get('listCustomerId');

        $data = $this->customers->searchCustomerPhoneEmail($keyword, $birthdayFormat, $gender, $branchId,[],[]);

        $listCustomer = [];
        if ($request->session()->has('arrCustomerId')) {
            $listCustomer = $request->session()->get('arrCustomerId');
        }

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'full_name' => $item['full_name'],
                'customer_id' => $item['customer_id'],
                'phone' => $item['phone1'],
                'birthday' => $item['birthday'] != null ?  date("d/m/Y", strtotime($item['birthday'])) : null,
                'gender' => $item['gender'],
                'gender_name' => $item['gender'] == 'male' ? __('Nam')  : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                'branch_name' => $item['branch_name'],
                'is_checked' => isset($listCustomer[$item['customer_id']]) ? 1 : 0
            ];
        }

        return response()->json(['result' => $result]);
    }

    /**
     * Lấy danh sách KH trong nhóm KH
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerGroupAction(Request $request)
    {
        $filterTypeGroup = $request->filter_type_group;
        $customerGroupFilter = $request->customer_group_filter;

        $data = $this->customers->searchCustomerGroupFilter($filterTypeGroup,$customerGroupFilter);


        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'full_name' => $item['full_name'],
                'customer_id' => $item['customer_id'],
                'phone' => $item['phone1'],
                'birthday' => $item['birthday'] != null ?  date("d/m/Y", strtotime($item['birthday'])) : null,
                'gender' => $item['gender'],
                'gender_name' => $item['gender'] == 'male' ? __('Nam')  : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                'branch_name' => $item['branch_name'],
                'is_checked' => 0
            ];
        }

        return response()->json(['result' => $result]);
    }

    public function searchCustomerLeadAction(Request $request)
    {
        $input = $request->all();
        $listCustomerAdd = $request->session()->get('listCustomerId');
        $input['listCustomerAddPhone'] = $listCustomerAdd;
        $arr_data = [];
        $listCustomerLead = [];
        if ($request->session()->has('arrCustomerLeadId')) {
            $listCustomerLead = $request->session()->get('arrCustomerLeadId');
        }
        $data = $this->smsCampaign->searchCustomerLeadFilter($input);
        $arr_data = [];
        foreach ($data as $item) {
            $arr_data[] = [
                'phone' => $item['phone'],
                'full_name' => $item['full_name'],
                'sale_name' => $item['sale_name'],
                'sale_status' => $item['sale_status'],
                'customer_lead_id' => $item['customer_lead_id'],
                'customer_type' => $item['customer_type'],
                'customer_source_name' => $item['customer_source_name'],
                'pipeline_name' => $item['pipeline_name'],
                'journey_name' => $item['journey_name'],
                'is_checked' => isset($listCustomerLead[$item['customer_lead_id']]) ? 1 : 0
            ];
        }
        return response()->json([
            'arr_data' => $arr_data,
        ]);
    }

    //Phân trang (tất cả item).
    public function pagingAction(Request $request)
    {
        $page = $request->page;
//        var_dump($page);
        //Danh sách chi nhánh.
        $list = $this->smsCampaign->list2();

        $smsLog = $this->smsLog->getAll();

        $listSmsCampaign = $this->listSmsCampaign($list, $smsLog);
        $result = collect($listSmsCampaign)->forPage($page, 10);

        $contents = view('admin::marketing.sms.campaign.paging.paging', [
            'data' => $listSmsCampaign,
            'LIST' => $result,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function addAction()
    {
//        $branch = $this->branch->getBranch();
        $branchOption = $this->branch->getBranch();
        return view('admin::marketing.sms.campaign.add', ['branch' => $branchOption]);
    }

    public function filterAction(Request $request)
    {
        $keyWord = $request->keyWord;
        $createdBy = $request->createdBy;
        $sentBy = $request->sentBy;
        $status = $request->status;
        $daySent = null;

        if ($request->daySent != null) {
            $daySent = Carbon::createFromFormat('d/m/Y', $request->daySent)->format('Y-m-d');
        }
        $createdAt = null;
        if ($request->createdAt != null) {
            $createdAt = Carbon::createFromFormat('d/m/Y', $request->createdAt)->format('Y-m-d');
        }

        $filters = ['search_keyword' => $keyWord,
            'created_by' => $createdBy,
            'sent_by' => $sentBy,
            'status' => $status,
            'day_sent' => $daySent,
            'created_at' => $createdAt];
        $listCampaign = $this->smsCampaign->getListCampaign($filters);

        $smsLog = $this->smsLog->getAll();

        $listSmsCampaign = $this->listSmsCampaign($listCampaign, $smsLog);

        $result = collect($listSmsCampaign)->forPage(1, 10);
        $contents = view('admin::marketing.sms.campaign.filter', [
            'data' => $listSmsCampaign,
            'LIST' => $result,
            'page' => 1
        ])->render();
        return $contents;
    }

    public function pagingFilterAction(Request $request)
    {
        $page = intval($request->page);
        $keyWord = $request->keyWord;
        $createdBy = $request->createdBy;
        $sentBy = $request->sentBy;
        $status = $request->status;
        $daySent = $request->daySent;
        $createdAt = $request->createdAt;
        $filters = ['search_keyword' => $keyWord,
            'created_by' => $createdBy,
            'sent_by' => $sentBy,
            'status' => $status,
            'day_sent' => $daySent,
            'created_at' => $createdAt];
        $listCampaign = $this->smsCampaign->getListCampaign($filters);

        $smsLog = $this->smsLog->getAll();

        $listSmsCampaign = $this->listSmsCampaign($listCampaign, $smsLog);

        $result = collect($listSmsCampaign)->forPage($page, 10);
        $contents = view('admin::marketing.sms.campaign.paging.paging-filter', [
            'data' => $listSmsCampaign,
            'LIST' => $result,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function editAction($id,Request $request)
    {
        $mCustomerSource = new CustomerSourceTable();

        $optionCustomerSource = $mCustomerSource->getOption();
        $mPipeline = new PipelineTable();
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $campaign = $this->smsCampaign->getItem($id);
        $branch = $this->branch->getBranchOption();
        $listLog = $this->smsLog->getLogCampaign($id);
        $request->session()->forget('arrCustomerId');
        $request->session()->forget('listCustomerId');
        $arrCustomer = [];
        if (count($listLog) != 0) {
            foreach ($listLog as $item) {
                $arrCustomer[$item['phone']] = $item['phone'];
            }
        }
        $request->session()->put('listCustomerId',$arrCustomer);
        if ($campaign != null) {
            if ($campaign->status == 'new') {
                $daySent = '';
                $timeSent = '';
                if ($campaign->value != null) {
                    $daySent = explode(' ', $campaign->value)[0];
                    $timeSent = explode(' ', $campaign->value)[1];
                }

                return view('admin::marketing.sms.campaign.edit',
                    [
                        'campaign' => $campaign,
                        'daySent' => $daySent != '' ? Carbon::createFromFormat('Y-m-d', $daySent)->format('d/m/Y'): '',
                        'timeSent' => $timeSent,
                        'id' => $id,
                        'branch' => $branch,
                        'optionBranch' => $branch,
                        "optionPipeline" => $optionPipeline,
                        "optionCustomerSources" => $optionCustomerSource,
                        'listLog' => $listLog
                    ]);
            } else {
                return redirect()->route('admin.sms.sms-campaign');
            }
        } else {
            return redirect()->route('admin.sms.sms-campaign');
        }
    }

    public function detailAction($id)
    {
        $campaign = $this->smsCampaign->getItem($id);
        $campaignDetail = $this->smsLog->getLogDetailCampaign($id);
        $result = collect($campaignDetail)->forPage(1, 10);
        if ($campaign != null) {
            $logSuccess = 0;
            $logError = 0;
            $listLog = [];
            foreach ($campaignDetail as $key => $value) {
                if (empty($value['error_code'])) {
                    if ($value['sms_status'] == 'sent') {
                        $logSuccess += 1;
                    }
                } else {
                    $logError += 1;
                }
//                    $temp['customer'] = $value['customer_name'];
//                    $temp['phone'] = $value['phone'];
//                    $temp['message'] = $value['message'];
//                    $temp['created_by'] = '';
//                    $temp['sent_by'] = '';
//
//                    $createdBy = $this->staff->getItem($value['created_by']);
//                    if ($createdBy != null) {
//                        $temp['created_by'] = $createdBy->full_name;
//                    }

//                    $sentBy = $this->staff->getItem($value['created_by']);
//                    if ($createdBy != null) {
//                    $temp['sent_by'] = null;
//                    }

//                    $temp['sms_status'] = $value['sms_status'];
//                    $temp['created_at'] = $value['created_at'];
//                    $temp['time_sent'] = $value['time_sent'];
//                    $temp['error_code'] = $value['error_code'];
//                    $listLog[] = $temp;
            }
            return view('admin::marketing.sms.campaign.detail',
                [
                    'campaign' => $campaign,
                    'data' => $campaignDetail,
                    'logSuccess' => $logSuccess,
                    'logError' => $logError,
                    'totalSms' => count($campaignDetail),
                    "page" => 1,
                    'LIST' => $result
                ]);
        } else {
            return redirect()->route('admin.sms.sms-campaign');
        }
    }

    public function submitEditAction(Request $request)
    {
        $input = $request->all();
        $is_deal_created = $request->is_deal_created;
        $cost = str_replace(",", "", $request->cost);
        $id = $request->id;
        $name = $request->name;
        $content = $request->contents;
//        $dateTime = $request->dateTime;
        $dateSend = $request->dateSend;
        $timeSend = $request->timeSend;
        $isNow = $request->isNow;
        $slug = str_slug($name);
        $checkSlug = $this->smsCampaign->checkSlugName($slug, $id);
        $dateTimeNow = date('Y-m-d H:i:s');


        $valueUpdate = null;

        if (!empty($dateSend)) {
            $valueUpdate = Carbon::createFromFormat('d/m/Y', $dateSend)->format('Y-m-d') . " " . $timeSend;
        } else {
            $valueUpdate = Carbon::now()->format('Y-m-d H:i');
        }

        if ($checkSlug != null) {
            return response()->json(['error' => 'slug']);
        } else {
            $data = [
                'is_deal_created' => $is_deal_created,
                'name' => $name,
                'cost' => $cost,
                'status' => 'new',
                'content' => $content,
                'slug' => $slug,
                'value' => $valueUpdate,
                'is_now' => $isNow,
                'updated_by' => Auth::id(),
                'updated_at' => $dateTimeNow,
            ];
            if ($isNow == 1) {
                $data['value'] = '';
            }
            $this->smsCampaign->edit($data, $id);
            // remove deal, deal detail
            $mSmsDeal = new SmsDealTable();
            $mSmsDealDetail = new SmsDealDetailTable();
            $smsDealItem = $mSmsDeal->getItem($id);
            $mSmsDeal->removeItem($id);
            if(isset($smsDealItem['sms_deal_id']) != ''){
                $mSmsDealDetail->removeItem($smsDealItem['sms_deal_id']);
            }
            if($request->is_deal_created == 1){
                $dataDeal = [
                    'sms_campaign_id' => $id,
                    'pipeline_code' => $input['pipeline_code'],
                    'journey_code' => $input['journey_code'],
                    'amount' => (float)str_replace(',', '', $input['amount']),
                    'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                    'created_by' => Auth::id()
                ];
                $smsDealId = $mSmsDeal->add($dataDeal);
                // insert deal_detail, order detail
                if (isset($input['arrObject'])) if ($input['arrObject'] != null) {
                    foreach ($input['arrObject'] as $key => $value) {
                        $value['price'] = (float)str_replace(',', '', $value['price']);
                        $value['amount'] = (float)str_replace(',', '', $value['amount']);
                        $value['discount'] = (float)str_replace(',', '', $value['discount']);
                        $value['sms_deal_id'] = $smsDealId;
                        $value['created_by'] = Auth::id();
                        $dealDetailId = $mSmsDealDetail->add($value);
                    }
                }
            }
            return response()->json(['error' => 0]);
        }
    }

    /**
     * Lưu ds cá KH/KHTN vào sms_log
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSaveLogAction(Request $request)
    {
        $arrayLogDelete = $request->arrayLogDelete;
        $array = $request->array;
        $campaignId = $request->id;

        $campaign = $this->smsCampaign->getItem($campaignId);
        $time = '';
        //
        if ($campaign->is_now == 0) {
            $time = $campaign->value;
        }
        if ($campaign->is_now == 1) {
            $time = date('Y-m-d H:i:s');
        }
        if ($array != null) {
            $result = array_chunk($array, 6, false);
            foreach ($result as $key => $value) {
                $data = [
                    'brandname' => '',
                    'campaign_id' => $campaignId,
                    'phone' => $value[2],
                    'customer_name' => $value[1],
                    'message' => $value[3],
                    'type_customer' => $value[4],
                    'object_type' => $value[4],
                    'object_id' => $value[5],
                    'sms_status' => 'new',
                    'sms_type' => '',
                    'error_code' => '',
                    'time_sent' => $time,
                    'created_by' => Auth::id(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                if ($campaign->is_now == 1) {
                    $data['time_sent'] = null;
                    $this->smsLog->add($data);
                } else {
                    $this->smsLog->add($data);
                }
            }
        }

        if ($arrayLogDelete != null) {
            foreach ($arrayLogDelete as $key => $value) {
                $this->smsLog->remove($value);
            }
        }
        $request->session()->forget('arrCustomerId');
        $request->session()->forget('arrCustomerLeadId');
        return response()->json(['error' => 0]);
    }

    /**
     * Xoá KH đã chọn khỏi ds
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeLogAction(Request $request)
    {
//        $this->smsLog->remove($request->id);
//        $detail = $this->smsLog->getItem($request->id);

        $phone = $request->id;
        $listCustomerAdd = $request->session()->get('listCustomerId');

        if (isset($listCustomerAdd[$phone])) {
            unset($listCustomerAdd[$phone]);
        }

        $request->session()->put('listCustomerId',$listCustomerAdd);

        return response()->json(['error' => 0]);
    }

    //export file sample excel
    public function exportFileAction($type)
    {
//        return Excel::download(new CollectionExport(), 'file_mau.csv');
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new CollectionExport(), 'file_mau.xlsx');
//        return Excel::download(new CollectionExport(), 'export.csv');
//        return Excel::download(new CollectionExport(), function($excel) {
//
//            $excel->sheet('Sheetname', function($sheet) {
//
//                $sheet->fromArray(array(
//                    array('data1', 'data2'),
//                    array('data3', 'data4')
//                ));
//
//            });
//
//        })->export('xls');
    }


    public function importFileExcelAction(Request $request)
    {
        $file = $request->file('file');

        if (isset($file)) {
//            $desFile = UPLOAD_FILE_EXCEL . basename($file->getClientOriginalName());
            $typeFileExcel = $file->getClientOriginalExtension();
            $arrayCustomer = [];
            if ($typeFileExcel == "xlsx") {
                $reader = ReaderFactory::create(Type::XLSX);
                $reader->open($file);
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $key => $row) {
                        if ($key == 1) {

                        } elseif ($key != 1 && $row[0] != '' && $row[1] != '') {
                            $arrayCustomer[] = [
                                'customer_name' => $row[0],
                                'phone' => $row[1],
                                'birthday' => '',
                                'gender' => '',

                            ];

                        }
                    }
                }

                $reader->close();
            }
            return response()->json($arrayCustomer);
        }
    }

    public function listDetailAction(Request $request)
    {
//        var_dump(1);
//        $filter = $request->only(['id', 'page']);
//        $card_list = $this->smsLog->getLogDetailCampaign($filter['id'],$filter["service_card_id"]);
//        return view('admin::service-card.inc.table-card-list', ['LIST' => $card_list]);
    }

    public function pagingDetailAction(Request $request)
    {
        $campaignDetail = $this->smsLog->getLogDetailCampaign($request->id);
        $result = collect($campaignDetail)->forPage($request->page, 10);
        $contents = view('admin::marketing.sms.campaign.paging.paging-detail', [
            'data' => $campaignDetail,
            'LIST' => $result,
            'page' => $request->page
        ])->render();
        return $contents;
    }

    /**
     * Xử lý lưu session khi click chọn customer/group
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCustomer(Request $request) {
        $param = $request->all();
        $listCustomerId = [];
        if ($request->session()->has('arrCustomerId')) {
            $listCustomerId = $request->session()->get('arrCustomerId');
        }

        if (isset($param['customer_id'])) {
            if (isset($param['check']) && $param['check'] == 1) {
                $customerId = $param['customer_id'];
                if (isset($param['status']) && $param['status'] == 'add') {
                    foreach ($customerId as $item) {
                        if (!isset($listCustomerId[$item])){
                            $listCustomerId[$item] = $item;
                        }
                    }
                } else {
                    foreach ($customerId as $item) {
                        if (isset($listCustomerId[$item])){
                            unset($listCustomerId[$item]);
                        }
                    }
                }
            } else {
                $customerId = json_decode($param['customer_id'],true);
                foreach ($customerId as $item) {
                    if (isset($listCustomerId[$item])){
                        unset($listCustomerId[$item]);
                    } else {
                        $listCustomerId[$item] = $item;
                    }
                }
            }

            $request->session()->put('arrCustomerId',$listCustomerId);
        }

        return response()->json($listCustomerId);
    }

    /**
     * Xử lý lưu session khi click chọn lead
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCustomerLead(Request $request) {
        $param = $request->all();
        $listCustomerId = [];
        if ($request->session()->has('arrCustomerLeadId')) {
            $listCustomerId = $request->session()->get('arrCustomerLeadId');
        }
        if (isset($param['customer_lead_id'])) {
            if (isset($param['check']) && $param['check'] == 1) {
                $customerId = $param['customer_lead_id'];
                if (isset($param['status']) && $param['status'] == 'add') {
                    foreach ($customerId as $item) {
                        if (!isset($listCustomerId[$item])){
                            $listCustomerId[$item] = $item;
                        }
                    }
                }
                else {
                    foreach ($customerId as $item) {
                        if (isset($listCustomerId[$item])){
                            unset($listCustomerId[$item]);
                        }
                    }
                }
            }
            else {
                $customerId = json_decode($param['customer_lead_id'],true);
                foreach ($customerId as $item) {
                    if (isset($listCustomerId[$item])){
                        unset($listCustomerId[$item]);
                    }
                    else {
                        $listCustomerId[$item] = $item;
                    }
                }
            }
            $request->session()->put('arrCustomerLeadId',$listCustomerId);
        }

        return response()->json($listCustomerId);
    }

    public function deleteSession(Request $request) {
        $request->session()->forget('arrCustomerId');
        $request->session()->forget('arrCustomerLeadId');
    }

    public function appendAction(Request $request)
    {
        $mCustomerLead = new CustomerLeadTable();
        $item_campaign = $this->smsCampaign->getItem($request->campaign_id);
        $data_list = [];
        $listCustomer = [];
        $listCustomerLead = [];
        if ($request->session()->has('arrCustomerId')) {
            $listCustomer = $request->session()->get('arrCustomerId');
        }

        if ($request->session()->has('arrCustomerLeadId')) {
            $listCustomerLead = $request->session()->get('arrCustomerLeadId');
        }
        $listCustomerAdd = $request->session()->get('listCustomerId');

//        foreach ($request->list as $value) {
        foreach ($listCustomer as $value) {
            $customer_list = $this->customers->getItem($value);

            $gender_sub = null;
            if ($customer_list['gender'] == 'male') {
                $gender_sub = __('Anh');
            } else if ($customer_list['gender'] == 'female') {
                $gender_sub = __('Chị');
            } else {
                $gender_sub = __('Anh/Chị');
            }
            if ($customer_list['birthday'] != null) {
                $birthday = date('d/m/Y', strtotime($customer_list['birthday']));
            } else {
                $birthday = '';
            }
            //Lấy tên trong chuỗi full name
            $string = $customer_list['full_name'];
            $pieces = explode(' ', $string);
            $last_name = array_pop($pieces);
            //replace giá trị của tham số
            $search = array('{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{CUSTOMER_BIRTHDAY}');
            $replace = array($last_name, $customer_list['full_name'], $gender_sub, $birthday);
            $subject = $item_campaign['content'];
            $returnValue = str_replace($search, $replace, $subject);
            $data_list[] = [
                'customer_id' => $customer_list['customer_id'],
                'customer_name' => $customer_list['full_name'],
                'email' => $customer_list['email'],
                'content' => $returnValue,
                'phone' => $customer_list['phone1'],
            ];

            $listCustomerAdd[$customer_list['phone1']] = $customer_list['phone1'];

        }

        $request->session()->put('listCustomerId',$listCustomerAdd);
        foreach ($listCustomerLead as $value) {
            $customer_lead_list = $mCustomerLead->getInfo($value);
            $gender_sub = null;
            if ($customer_lead_list['gender'] == 'male') {
                $gender_sub = __('Anh');
            } else if ($customer_lead_list['gender'] == 'female') {
                $gender_sub = __('Chị');
            } else {
                $gender_sub = __('Anh/Chị');
            }
            if ($customer_lead_list['birthday'] != null) {
                $birthday = date('d/m/Y', strtotime($customer_lead_list['birthday']));
            } else {
                $birthday = '';
            }
            //Lấy tên trong chuỗi full name
            $string = trim($customer_lead_list['full_name']);
            $pieces = explode(' ', $string);
            $last_name = array_pop($pieces);
            //replace giá trị của tham số
            $search = array('{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{CUSTOMER_BIRTHDAY}');
            $replace = array($last_name, $customer_lead_list['full_name'], $gender_sub, $birthday);
            $subject = $item_campaign['content'];
            $returnValue = str_replace($search, $replace, $subject);
            $data_list[] = [
                'customer_lead_id' => $customer_lead_list['customer_lead_id'],
                'customer_name' => $customer_lead_list['full_name'],
                'email' => $customer_lead_list['email'],
                'content' => $returnValue,
                'phone' => $customer_lead_list['phone'],
            ];

            $listCustomerAdd[$customer_lead_list['phone']] = $customer_lead_list['phone'];
        }

        $request->session()->put('listCustomerId',$listCustomerAdd);
        return response()->json([
            'data_list' => $data_list
        ]);

    }

    /**
     * Popup tạo deal khi click "thêm thong tin deal"
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupCreateDeal(Request  $request){
        $data = $this->smsCampaign->popupCreateDeal($request->all());

        return response()->json($data);
    }

    /**
     * Popup chỉnh sửa deal khi click "thêm thong tin deal"
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupEditDeal(Request  $request){
        $data = $this->smsCampaign->popupEditDeal($request->all());

        return response()->json($data);
    }
}