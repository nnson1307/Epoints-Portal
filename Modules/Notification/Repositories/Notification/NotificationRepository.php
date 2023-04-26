<?php


namespace Modules\Notification\Repositories\Notification;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\CustomerGroupFilterTable;
use Modules\Admin\Models\NewTable;
use Modules\Admin\Models\ProductTable;
use Modules\Admin\Models\ServiceTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\Notification\Models\NotificationTypeTable;
use Modules\Notification\Models\BrandTable;
use Modules\Notification\Models\FaqTable;
use Modules\Notification\Models\FaqGroupTable;
use Modules\Notification\Models\NotificationTemplateTable;
use Modules\Notification\Models\NotificationTemplateDealTable;
use Modules\Notification\Models\NotificationTemplateDealDetailTable;
use Modules\Notification\Models\NotificationDetailTable;
use Modules\Notification\Models\NotificationQueueTable;
use Modules\Notification\Models\NotificationTable;
use Modules\Notification\Models\ProductChildTable;
use Modules\Notification\Models\UserTable;
use Modules\Notification\Models\MyStoreFilterGroupTable;
use Modules\Notification\Http\Api\PushNotification;
use Carbon\Carbon;
use Modules\Promotion\Models\PromotionMasterTable;

class NotificationRepository implements NotificationRepositoryInterface
{
    /**
     * Khai báo biến
     */
    protected $notificationType;
    protected $notificationTemplate;
    protected $notificationDetail;
    protected $notificationQueue;
    protected $brand;
    protected $faq;
    protected $faqGroup;
    protected $user;
    protected $notification;
    protected $pushNotification;
    protected $filterGroup;
    protected $customerGroupFilterTable;
    protected $product;
    protected $service;
    protected $promotion;
    protected $news;
    protected $productChild;

    public function __construct(
        UserTable $mUser,
        BrandTable $mBrand,
        FaqTable $mFaq,
        FaqGroupTable $mFaqGroup,
        NotificationTypeTable $mNotificationType,
        NotificationQueueTable $mNotificationQueue,
        NotificationDetailTable $mNotificationDetail,
        NotificationTemplateTable $mNotificationTemplate,
        NotificationTable $mNotification,
        PushNotification $apiPushNotification,
        MyStoreFilterGroupTable $mFilterGroup,
        CustomerGroupFilterTable $customerGroupFilterTable,
        ProductTable $product,
        ServiceTable $service,
        PromotionMasterTable $promotion,
        NewTable $news,
        ProductChildTable $productChild
    )
    {
        $this->productChild = $productChild;
        $this->news = $news;
        $this->promotion = $promotion;
        $this->service = $service;
        $this->product = $product;
        $this->user = $mUser;
        $this->brand = $mBrand;
        $this->faq = $mFaq;
        $this->faqGroup = $mFaqGroup;
        $this->notificationType = $mNotificationType;
        $this->notificationQueue = $mNotificationQueue;
        $this->notificationDetail = $mNotificationDetail;
        $this->notificationTemplate = $mNotificationTemplate;
        $this->notification = $mNotification;
        $this->pushNotification = $apiPushNotification;
        $this->filterGroup = $mFilterGroup;
        $this->customerGroupFilterTable = $customerGroupFilterTable;
    }

    /**
     * Lấy dánh sách thông báo
     *
     * @param $filter
     * @return mixed
     */
    public function getNotiList($filter)
    {
        $notiList = $this->notificationTemplate->getListNew($filter);

        $arrListIdDetail = collect($notiList->items())->pluck('notification_detail_id');

        $arrCountNoti = $this->notification->getAllByDetailTemplate($arrListIdDetail);

        return [
            'noti_list' => $notiList,
            'noti_count' => $arrCountNoti
        ];
    }

    /**
     * Lấy danh sách notification type
     *
     * @return mixed
     */
    public function getNotificationTypeList($filter = [])
    {
        return $this->notificationType->getList($filter);
    }

    /**
     * Lấy danh sách chi tiết đích đến
     *
     * @param $data
     * @return mixed
     */
    public function getDetailEndPoint($data)
    {
        $mProductChild = new ProductChildTable();
        $mService = new ServiceTable();
        $mPromotion = new PromotionMasterTable();
        $mNews = new NewTable();
        if ($data['filter']['detail_type'] == "product_detail") {
            return \View::make('notification::notification.component.product_list', [
                'LIST' => $mProductChild->getList($data['filter'])
            ]);
        } elseif ($data['filter']['detail_type'] == "service_detail") {
            unset($data['filter']['detail_type']);
            return \View::make('notification::notification.component.service_list', [
                'LIST' => $mService->getList($data['filter'])
            ]);
        } elseif ($data['filter']['detail_type'] == "promotion_detail") {
            unset($data['filter']['detail_type']);
            return \View::make('notification::notification.component.promotion_list', [
                'LIST' => $mPromotion->getList($data['filter'])
            ]);
        } elseif ($data['filter']['detail_type'] == "news_detail") {
            unset($data['filter']['detail_type']);
            return \View::make('notification::notification.component.news_list', [
                'LIST' => $mNews->getList($data['filter'])
            ]);
        }
    }

    /**
     * Lấy danh sách nhóm
     *
     * @param $data
     * @return array
     */
    public function getGroupList($data)
    {
        return $this->customerGroupFilterTable->getList($data['filter']);
    }

    /**
     * Lưu thông báo
     *
     * @param $data
     * @return mixed|string
     */
    public function store($data)
    {
        try {
            if ($data['send_to'] == 'all') {
                $fromType = 'all';
                $fromTypeObject = null;
            } else {
                $fromType = 'group';
                $fromTypeObject = $data['group_id'];
            }
            $insertTemplate = [
                'is_deal_created' => $data['is_deal_created'],
                'cost' => str_replace(",", "", $data['cost']),
                'action_group' => $data['action_group'],
                'notification_type_id' => $data['notification_type_id'],
                'title' => strip_tags($data['title']),
                'title_short' => strip_tags($data['short_title']),
                'description' => strip_tags($data['feature']),
                'from_type' => $fromType,
                'from_type_object' => $fromTypeObject,
                'is_actived' => 1,
                'send_status' => 'pending'
            ];
            $insertDetail = [
                'content' => $data['content'],
                'background' => $data['background'],
                'tenant_id' => $data['tenant_id']
            ];

            $insertDetail['is_brand'] = isset($data['is_brand']) ? $data['is_brand'] : 0;

            if (isset($data['action_name']) && $data['action_name'] != null) {
                $insertTemplate['action_name'] = strip_tags($data['action_name']);
                $insertDetail['action_name'] = strip_tags($data['action_name']);
                $insertDetail['action'] = $data['end_point'];

                if ($insertDetail['is_brand'] && $data['end_point'] == 'brand') {
                    $arrTenant = $this->brand->getDetailBy($insertDetail['tenant_id']);
                    $data['end_point_detail'] = $arrTenant['brand_id'];
                }

                if (isset($data['end_point_detail']) && $data['end_point_detail'] != null) {
                    // nếu là brand
                    if ($data['end_point'] == 'product_detail') {
                        $item = $this->productChild->getItem($data['end_point_detail']);
                        $array = [
                            'object_id' => $item['product_id'],
                            'object_code' => $item['product_code']
                        ];
                        $json = json_encode($array);
                        $insertDetail['action_params'] = $json;
                    }
                    elseif ($data['end_point'] == 'service_detail') { // nếu là faq
                        $item = $this->service->getItem($data['end_point_detail']);
                        $array = [
                            'object_id' => $item['service_id'],
                            'object_code' => $item['service_code']
                        ];
                        $json = json_encode($array);
                        $insertDetail['action_params'] = $json;
                    }
                    elseif ($data['end_point'] == 'promotion_detail') { // nếu là faq
                        $item = $this->promotion->getInfo($data['end_point_detail']);
                        $array = [
                            'object_id' => $item['promotion_id'],
                            'object_code' => $item['promotion_code']
                        ];
                        $json = json_encode($array);
                        $insertDetail['action_params'] = $json;
                    }
                    elseif ($data['end_point'] == 'news_detail') { // nếu là marketing
                        $item = $this->news->getItem($data['end_point_detail']);
                        $array = [
                            'object_id' => $item['new_id'],
                            'product' => $item['product'],
                            'service' => $item['service']
                        ];
                        $json = json_encode($array);
                        $insertDetail['action_params'] = $json;
                    }
                    else {
                        if ($insertDetail['is_brand'] == 1) {
                            $brand = $this->brand->getItemByTenantId($data['tenant_id']);
                            $array = [
                                'brand_id' => $brand['brand_id'],
                                'brand_url' => $brand['brand_url'],
                                'brand_name' => $brand['brand_name']
                            ];
                            $json = json_encode($array);
                            $insertDetail['action_params'] = $json;
                        }
                    }
                }
                else {
                    if ($insertDetail['is_brand'] == 1) {
                        $brand = $this->brand->getItemByTenantId($data['tenant_id']);
                        $array = [
                            'brand_id' => $brand['brand_id'],
                            'brand_url' => $brand['brand_url'],
                            'brand_name' => $brand['brand_name']
                        ];
                        $json = json_encode($array);
                        $insertDetail['action_params'] = $json;
                    }
                }
            }
            if ($data['send_time_radio'] == 0) {
                $insertTemplate['send_type'] = 'immediately';
                $insertTemplate['send_at'] = Carbon::now()->toDateTimeString();
            } else {
                $insertTemplate['send_type'] = 'schedule';
                if ($data['schedule_time'] == 'specific_time') {
                    $insertTemplate['schedule_option'] = 'specific'; //
                    $insertTemplate['send_at'] = strip_tags($data['specific_time']);
                } else {
                    $insertTemplate['schedule_option'] = 'none'; //
                $currentTime = Carbon::now();
                    if ($data['time_type'] == 'hour') {
                    $currentTime->addHours($data['non_specific_time']);
                        $insertTemplate['schedule_value_type'] = 'hours';
                    } elseif ($data['time_type'] == 'minute') {
                    $currentTime->addMinutes($data['non_specific_time']);
                        $insertTemplate['schedule_value_type'] = 'minute';
                    } elseif ($data['time_type'] == 'day') {
                    $currentTime->addDays($data['non_specific_time']);
                        $insertTemplate['schedule_value_type'] = 'day';
                    }
                    $insertTemplate['schedule_value'] = $data['non_specific_time'];
                    $insertTemplate['is_actived'] = 0;
                    $insertTemplate['send_at'] = $currentTime;
                }
            }
            $detail = $this->notificationDetail->createNotiDetail($insertDetail);

            $insertTemplate['notification_detail_id'] = $detail->notification_detail_id;
            $notiTemplateId = $this->notificationTemplate->createNotiTemplate($insertTemplate);

            if($data['is_deal_created'] == 1){
                $mEmailDeal = new NotificationTemplateDealTable();
                $mEmailDealDetail = new NotificationTemplateDealDetailTable();
                $dataDeal = [
                    'notification_template_id' => $notiTemplateId,
                    'pipeline_code' => $data['pipeline_code'],
                    'journey_code' => $data['journey_code'],
                    'amount' => (float)str_replace(',', '', $data['amount']),
                    'closing_date' => Carbon::createFromFormat('d/m/Y', $data['end_date_expected'])->format('Y-m-d H:i'),
                    'created_by' => Auth::id()
                ];
                $emailDealId = $mEmailDeal->add($dataDeal);
                // insert deal_detail, order detail
                if (isset($data['arrObject'])) if ($data['arrObject'] != null) {
                    $data['arrObject'] = json_decode($data['arrObject']);
                    foreach ($data['arrObject'] as $key => $value) {
                        $value = (array)$value;
                        $value['price'] = (float)str_replace(',', '', $value['price']);
                        $value['amount'] = (float)str_replace(',', '', $value['amount']);
                        $value['discount'] = (float)str_replace(',', '', $value['discount']);
                        $value['notification_template_deal_id'] = $emailDealId;
                        $value['created_by'] = Auth::id();
                        $dealDetailId = $mEmailDealDetail->add($value);
                    }
                }
            }
            $noti = $this->notificationTemplate->getOneByDetailId($insertTemplate['notification_detail_id']);

            if ($data['is_brand'] == 0) {
                if ($insertTemplate['send_at'] != null) {
                    if ($insertTemplate['from_type'] == 'all') { // gửi tất cả
                        $apiData = [
                            "title" => $insertTemplate['title'],
                            "message" => $insertTemplate['description'],
                            "avatar" => "",
                            "detail_id" => $insertTemplate['notification_detail_id'],
                            "schedule" => $noti['send_at']
                        ];

                        try {
//                            $result = $this->pushNotification->pushAllMyStore($apiData);
//                            if ($result) {
                                return 'success';
//                            }
                        } catch (\Exception $e) {
                            return 'push_fail';
                        }
                    } else { // gửi nhóm
                        $apiData = [
                            "group_id" => $insertTemplate['from_type_object'],
                            "tenant_id" => null,
                            "title" => $insertTemplate['title'],
                            "message" => $insertTemplate['description'],
                            "avatar" => "",
                            "detail_id" => $insertTemplate['notification_detail_id'],
                            "schedule" => $noti['send_at']
                        ];

                        try {
//                            $result = $this->pushNotification->pushGroupMyStore($apiData);
//                            if ($result) {
                                return 'success';
//                            }
                        } catch (\Exception $e) {
                            return 'push_fail';
                        }
                    }
                }

                return 'success';
            } else {
                return $noti;
            }
        } catch (\Exception $e) {
            return 'failed';
        }
    }

    /**
     * Lấy chi tiết thông báo
     *
     * @param $id
     * @return mixed
     */
    public function getNotiById($id)
    {
        return $this->notificationDetail->getOne($id);
    }

    /**
     * Cập nhật thông báo
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        if ($data['send_to'] == 'all') {
            $fromType = 'all';
            $fromTypeObject = null;
        } else {
            $fromType = 'group';
            $fromTypeObject = $data['group_id'];
        }
        $insertTemplate = [
            'action_group' => $data['action_group'],
            'notification_type_id' => $data['notification_type_id'],
            'title' => strip_tags($data['title']),
            'title_short' => strip_tags($data['short_title']),
            'description' => strip_tags($data['feature']),
            'from_type' => $fromType,
            'from_type_object' => $fromTypeObject
        ];
        $insertDetail = [
            'content' => $data['content'],
            'background' => $data['background'],
        ];

        $insertDetail['is_brand'] = isset($data['is_brand']) ? $data['is_brand'] : 0;

        if (isset($data['action_name']) && $data['action_name'] != null) {
            $insertTemplate['action_name'] = strip_tags($data['action_name']);
            $insertDetail['action_name'] = strip_tags($data['action_name']);
            $insertDetail['action'] = $data['end_point'];
            if (isset($data['end_point_detail']) && $data['end_point_detail'] != null) {
                if ($data['end_point'] == 'product_detail') {
                    $item = $this->productChild->getItem($data['end_point_detail']);
                    $array = [
                        'object_id' => $item['product_id'],
                        'object_code' => $item['product_code']
                    ];
                    $json = json_encode($array);
                    $insertDetail['action_params'] = $json;
                }
                elseif ($data['end_point'] == 'service_detail') { // nếu là faq
                    $item = $this->service->getItem($data['end_point_detail']);
                    $array = [
                        'object_id' => $item['service_id'],
                        'object_code' => $item['service_code']
                    ];
                    $json = json_encode($array);
                    $insertDetail['action_params'] = $json;
                }
                elseif ($data['end_point'] == 'promotion_detail') { // nếu là faq
                    $item = $this->promotion->getInfo($data['end_point_detail']);
                    $array = [
                        'object_id' => $item['promotion_id'],
                        'object_code' => $item['promotion_code']
                    ];
                    $json = json_encode($array);
                    $insertDetail['action_params'] = $json;
                }
                elseif ($data['end_point'] == 'news_detail') { // nếu là marketing
                    $item = $this->news->getItem($data['end_point_detail']);
                    $array = [
                        'object_id' => $item['new_id'],
                        'product' => $item['product'],
                        'service' => $item['service']
                    ];
                    $json = json_encode($array);
                    $insertDetail['action_params'] = $json;
                }
                else {
                    if ($insertDetail['is_brand'] == 1) {
                        $brand = $this->brand->getItemByTenantId($data['tenant_id']);
                        $array = [
                            'brand_id' => $brand['brand_id'],
                            'brand_url' => $brand['brand_url'],
                            'brand_name' => $brand['brand_name']
                        ];
                        $json = json_encode($array);
                        $insertDetail['action_params'] = $json;
                    }
                }
            } else {
                if ($insertDetail['is_brand'] == 1) {
                    $brand = $this->brand->getItemByTenantId($data['tenant_id']);
                    $array = [
                        'brand_id' => $brand['brand_id'],
                        'brand_url' => $brand['brand_url'],
                        'brand_name' => $brand['brand_name']
                    ];
                    $json = json_encode($array);
                    $insertDetail['action_params'] = $json;
                }
            }
        }
        if ($data['send_time_radio'] == 0) {
            $insertTemplate['send_type'] = 'immediately';
            $insertTemplate['send_at'] = Carbon::now()->toDateTimeString();
            $insertTemplate['is_actived'] = 1;
        } else {
            $insertTemplate['send_type'] = 'schedule';
            if ($data['schedule_time'] == 'specific_time') {
                $insertTemplate['schedule_option'] = 'specific'; //
                $insertTemplate['send_at'] = strip_tags($data['specific_time']);
                $insertTemplate['is_actived'] = 1;
                $insertTemplate['schedule_value_type'] = null;
                $insertTemplate['schedule_value'] = null;
            } else {
                $insertTemplate['schedule_option'] = 'none'; //
//                $currentTime = Carbon::now();
                if ($data['time_type'] == 'hour') {
//                    $currentTime->addHours($data['non_specific_time']);
                    $insertTemplate['schedule_value_type'] = 'hours';
                } elseif ($data['time_type'] == 'minute') {
//                    $currentTime->addMinutes($data['non_specific_time']);
                    $insertTemplate['schedule_value_type'] = 'minute';
                } elseif ($data['time_type'] == 'day') {
//                    $currentTime->addDays($data['non_specific_time']);
                    $insertTemplate['schedule_value_type'] = 'day';
                }
                $insertTemplate['schedule_value'] = $data['non_specific_time'];
                $insertTemplate['is_actived'] = 0;
                $insertTemplate['send_at'] = null;
                $insertTemplate['send_status'] = 'not';
            }
        }

        $detail = $this->notificationDetail->updateNotiDetail($id, $insertDetail);
        $this->notificationTemplate->updateNotiTemplate($id, $insertTemplate);
        $notiTemplate = $this->notificationTemplate->getOneByDetailId($id);
        // remove deal, deal detail
        $mEmailDeal = new NotificationTemplateDealTable();
        $mEmailDealDetail = new NotificationTemplateDealDetailTable();
        $emailDealItem = $mEmailDeal->getItem($notiTemplate['notification_template_id']);
        $mEmailDeal->removeItem($notiTemplate['notification_template_id']);
        if(isset($emailDealItem['notification_template_deal_id']) != ''){
            $mEmailDealDetail->removeItem($emailDealItem['notification_template_deal_id']);
        }
        if($data['is_deal_created'] == 1){
            $dataDeal = [
                'notification_template_id' => $notiTemplate['notification_template_id'],
                'pipeline_code' => $data['pipeline_code'],
                'journey_code' => $data['journey_code'],
                'amount' => (float)str_replace(',', '', $data['amount']),
                'closing_date' => Carbon::createFromFormat('d/m/Y', $data['end_date_expected'])->format('Y-m-d H:i'),
                'created_by' => Auth::id()
            ];
            $emailDealId = $mEmailDeal->add($dataDeal);
            // insert deal_detail, order detail
            if (isset($data['arrObject'])) if ($data['arrObject'] != null) {
                $data['arrObject'] = json_decode($data['arrObject']);
                foreach ($data['arrObject'] as $key => $value) {
                    $value = (array)$value;
                    $value['price'] = (float)str_replace(',', '', $value['price']);
                    $value['amount'] = (float)str_replace(',', '', $value['amount']);
                    $value['discount'] = (float)str_replace(',', '', $value['discount']);
                    $value['notification_template_deal_id'] = $emailDealId;
                    $value['created_by'] = Auth::id();
                    $dealDetailId = $mEmailDealDetail->add($value);
                }
            }
        }
        $noti = $this->notificationTemplate->getOneByDetailId($id);
        // xóa queue cũ nếu có
        $this->notificationQueue->deleteNotiQueue($id);

        if ($data['is_brand'] == 0) {
            if ($insertTemplate['from_type'] == 'all' && $noti['send_at'] != null) { // gửi tất cả
                $apiData = [
                    "title" => $insertTemplate['title'],
                    "message" => $insertTemplate['description'],
                    "avatar" => "",
                    "detail_id" => $id,
                    "schedule" => $noti['send_at']
                ];

                try {
//                    $result = $this->pushNotification->pushAllMyStore($apiData);
//                    if ($result) {
                        return 'success';
//                    }
                } catch (\Exception $e) {
                    return 'push_fail';
                }
            } elseif ($insertTemplate['from_type'] == 'group' && $noti['send_at'] != null) { // gửi nhóm
                $apiData = [
                    "group_id" => $insertTemplate['from_type_object'],
                    "tenant_id" => null,
                    "title" => $insertTemplate['title'],
                    "message" => $insertTemplate['description'],
                    "avatar" => "",
                    "detail_id" => $id,
                    "schedule" => $noti['send_at']
                ];

                try {
//                    $result = $this->pushNotification->pushGroupMyStore($apiData);
//                    if ($result) {
                        return 'success';
//                    }
                } catch (\Exception $e) {
                    return 'push_fail';
                }
            }
        } else {
            return $noti;
        }


        return 'success';
    }

    /**
     * Cập nhật hoạt động is_actived
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateIsActived($id, $data)
    {
        $sendAt = null;
        $noti = $this->notificationTemplate->getOneByDetailId($id);
        $notiLog = $this->notification->getFirst($id);
        $now = Carbon::now();

        function nonSpecificTime($type, $value)
        {
            $currentTime = Carbon::now();
            if ($type == 'hours') {
                $currentTime->addHours($value);
            } elseif ($type == 'minute') {
                $currentTime->addMinutes($value);
            } elseif ($type == 'day') {
                $currentTime->addDays($value);
            }
            return $currentTime;
        }

        // nếu giờ active nhỏ hơn giờ hiện tại
        if ($noti['send_at'] != null && $noti['send_at'] <= $now && $noti['is_actived'] == 0) {
            return [
                'status' => false,
                'message' => 'fail',
            ];
        }
        // nếu đã gửi và có log
        if ($notiLog) {
            return [
                'status' => false,
                'message' => 'sent',
            ];
        }

        if ($data['check'] == 1) {
            $isSend = 'pending';
            if ($data['non_specific_value'] != null && $data['non_specific_type'] != null) {
                $sendAt = nonSpecificTime($data['non_specific_type'], $data['non_specific_value'])
                    ->toDateTimeString();
                $arrUpdate = [
                    'is_actived' => $data['check'],
                    'send_status' => $isSend,
                    'send_at' => $sendAt
                ];
            } else {
                $arrUpdate = [
                    'is_actived' => $data['check'],
                    'send_status' => $isSend,
                    'send_at' => $noti['send_at']
                ];
            }
            $this->notificationTemplate->updateNotiTemplate($id, $arrUpdate);
            $noti = $this->notificationTemplate->getOneByDetailId($id);
            if ($data['is_brand'] == 0) {
                // push notification
                if ($arrUpdate['send_at'] != null) {
                    if ($noti['from_type'] == 'all') { // gửi tất cả
                        $apiData = [
                            "title" => $noti['title'],
                            "message" => $noti['description'],
                            "avatar" => "",
                            "detail_id" => $noti['notification_detail_id'],
                            "schedule" => $arrUpdate['send_at']
                        ];

                        try {
                            $result = $this->pushNotification->pushAllMyStore($apiData);
                        } catch (\Exception $e) {
                            return 'push_fail';
                        }
                    } else { // gửi nhóm
                        $apiData = [
                            "group_id" => $noti['from_type_object'],
                            "tenant_id" => null,
                            "title" => $noti['title'],
                            "message" => $noti['description'],
                            "avatar" => "",
                            "detail_id" => $noti['notification_detail_id'],
                            "schedule" => $noti['send_at']
                        ];

                        try {
                            $result = $this->pushNotification->pushGroupMyStore($apiData);
                        } catch (\Exception $e) {
                            return 'push_fail';
                        }
                    }
                }
                return [
                    'noti' => $noti,
                    'sendAt' => $sendAt,
                    'status' => true,
                    'message' => 'success'
                ];
            } else {
                return [
                    'noti' => $noti,
                    'sendAt' => $sendAt,
                    'status' => true,
                    'message' => 'success'
                ];
            }
        } else {
            $isSend = 'not';
            if ($data['non_specific_value'] != null && $data['non_specific_type'] != null) {
                $arrUpdate = [
                    'is_actived' => $data['check'],
                    'send_status' => $isSend,
                    'send_at' => null
                ];

                if ($noti['schedule_value_type'] == 'hours') {
                    $time_type = 'Giờ';
                } elseif ($noti['schedule_value_type'] == 'minute') {
                    $time_type = 'Phút';
                } elseif ($noti['schedule_value_type'] == 'day') {
                    $time_type = 'Ngày';
                }
                $sendAt = $noti['schedule_value'] . ' ' . $time_type;
            } else {
                $arrUpdate = [
                    'is_actived' => $data['check'],
                    'send_status' => $isSend,
                    'send_at' => $noti['send_at']
                ];
            }
            $this->notificationQueue->deleteNotiQueue($id);
            $this->notificationTemplate->updateNotiTemplate($id, $arrUpdate);

            return [
                'noti' => [],
                'sendAt' => $sendAt,
                'status' => true,
                'message' => 'success'
            ];
        }
    }

    /**
     * Xóa theo detail id
     *
     * @param $id
     */
    public function destroy($id)
    {
        $detail = $this->notificationDetail->deleteNotiDetail($id);
        if ($detail == 1) {
            $this->notificationTemplate->deleteNotiTemplate($id);
            $this->notificationQueue->deleteNotiQueue($id);
        }

        return 'success';
    }

    /**
     * Lấy thông tin theo end point
     *
     * @param $data
     * @return array
     */
    public function getEndPointJson($data)
    {
        $brand = $this->brand->getItemByTenantId($data['tenant_id']);
        $array = [
            'brand_id' => $brand['brand_id'],
            'brand_url' => $brand['brand_url'],
            'brand_name' => $brand['brand_name']
        ];

        if ($data['end_point'] == 'faq_brand') { // nếu là faq
            $array['faq_type'] = 'faq';
            $array['faq_id'] = $data['end_point_detail'];
            $array['faq_title'] = $data['end_point_detail_click'];
        } elseif ($data['end_point'] == 'market') { // nếu là marketing
            $array['campaign_id'] = $data['end_point_detail'];
            $array['promo_name'] = $data['end_point_detail_click'];
        } elseif ($data['end_point'] == 'product') { // nếu là marketing
            $array['product_id'] = $data['end_point_detail'];
            $array['product_name'] = $data['end_point_detail_click'];
        } elseif ($data['end_point'] == 'url') { // nếu là faq
            $array['url'] = $data['end_point_linkdetail_click'];
        }
        $json = json_encode($array);
        return [
            'json' => $json,
            'status' => true
        ];
    }

    /**
     * Lấy id và name của chi tiết đích đến
     *
     * @param $noti
     * @param $acParams
     * @return array|mixed
     */
    public function getObjectNameDetailEndPoint($noti, $acParams)
    {
        // region get name of end point detail
        $mProductChild = new ProductChildTable();
        $mService = new ServiceTable();
        $mPromotion = new PromotionMasterTable();
        $mNews = new NewTable();
        $objectId = 0;
        $objectName = '';
        switch ($noti['action']){
            case 'product_detail':
                $objectId = $acParams['object_id'];
                $objectName = $mProductChild->getItem($acParams['object_id'])['product_name'];
                break;
            case 'service_detail':
                $objectId = $acParams['object_id'];
                $objectName = $mService->getItem($acParams['object_id'])['service_name'];
                break;
            case 'promotion_detail':
                $objectId = $acParams['object_id'];
                $objectName = $mPromotion->getInfo($acParams['object_id'])['promotion_name'];
                break;
            case 'news_detail':
                $objectId = $acParams['object_id'];
                $objectName = $mNews->getItem($acParams['object_id'])['title_vi'];
                break;
        }
        return [
            'object_name' => $objectName,
            'object_id' =>$objectId
        ];
    }

    /**
     * Popup tạo deal khi "thêm thông tin deal"
     *
     * @param $input
     * @return array|mixed
     */
    public function popupCreateDeal($input)
    {
        $mPipeline = new PipelineTable();
        $optionPipeline = $mPipeline->getOption('DEAL');

        $html = \View::make('notification::notification.popup-create-deal', [
            "optionPipeline" => $optionPipeline,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Popup chỉnh sửa khi thực hiện chỉnh sửa chiến dịch và click thêm thông tin deal
     *
     * @param $input
     * @return array|mixed
     */
    public function popupEditDeal($input)
    {
        $mPipeline = new PipelineTable();
        $mJourney = new JourneyTable();
        $mEmailDeal = new NotificationTemplateDealTable();
        $mEmailDealDetail = new NotificationTemplateDealDetailTable();
        $item = $mEmailDeal->getItem($input['notification_template_id']);
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);
        $listObject = $mEmailDealDetail->getList($item['notification_template_deal_id']);


        $html = \View::make('notification::notification.popup-edit-deal', [
            "optionPipeline" => $optionPipeline,
            "optionJourney" => $optionJourney,
            "listObject" => $listObject,
            "item" => $item,
        ])->render();

        return [
            'html' => $html
        ];
    }
}
