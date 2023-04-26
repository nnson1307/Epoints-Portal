<?php

namespace Modules\Warranty\Repository\WarrantyCard;

use App\Jobs\SaveLogZns;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Warranty\Http\Api\SendNotificationApi;
use Modules\Warranty\Models\CustomerTable;
use Modules\Warranty\Models\EmailConfigTable;
use Modules\Warranty\Models\EmailLogTable;
use Modules\Warranty\Models\EmailProviderTable;
use Modules\Warranty\Models\ProductChildTable;
use Modules\Warranty\Models\ServiceCardTable;
use Modules\Warranty\Models\ServiceTable;
use Modules\Warranty\Models\SmsConfigTable;
use Modules\Warranty\Models\SmsLogTable;
use Modules\Warranty\Models\SmsProviderTable;
use Modules\Warranty\Models\StaffTable;
use Modules\Warranty\Models\WarrantyCardTable;
use Modules\Warranty\Models\WarrantyImageTable;
use Modules\Warranty\Models\WarrantyPackageTable;

class WarrantyCardRepo implements WarrantyCardRepoInterface
{
    protected $warrantyCard;
    public function __construct(WarrantyCardTable $warrantyCard)
    {
        $this->warrantyCard = $warrantyCard;
    }

    public function list(array $filters = [])
    {
        $list = $this->warrantyCard->getList($filters);

        if (count($list->items()) > 0) {
            $mProductChild = new ProductChildTable();
            $mService = new ServiceTable();
            $mServiceCard = new ServiceCardTable();

            foreach ($list->items() as $v) {
                if ($v['object_type'] == 'product') {
                    $obj = $mProductChild->getProduct($v['object_code']);
                    $v['object_name'] = $obj['product_child_name'];
                } elseif ($v['object_type'] == 'service') {
                    $obj = $mService->getService($v['object_code']);
                    $v['object_name'] = $obj['service_name'];
                } elseif ($v['object_type'] == 'service_card') {
                    $obj = $mServiceCard->getServiceCard($v['object_code']);
                    $v['object_name'] = $obj['name'];
                }
            }
        }

        return [
            "list" => $list,
        ];
    }

    /**
     * Data view edit
     *
     * @param $warrantyCardId
     * @return array|mixed
     */
    public function dataViewEdit($warrantyCardId)
    {
        $mWarrantyImage = new WarrantyImageTable();
        //Lấy thông tin thẻ bảo hành
        $getInfo = $this->warrantyCard->getInfoById($warrantyCardId);

        if ($getInfo['object_type'] == 'product') {
            $mProductChild = new ProductChildTable();
            $obj = $mProductChild->getProduct($getInfo['object_code']);
            $getInfo['object_name'] = $obj['product_child_name'];
        } elseif ($getInfo['object_type'] == 'service') {
            $mService = new ServiceTable();
            $obj = $mService->getService($getInfo['object_code']);
            $getInfo['object_name'] = $obj['service_name'];
        } elseif ($getInfo['object_type'] == 'service_card') {
            $mServiceCard = new ServiceCardTable();
            $obj = $mServiceCard->getServiceCard($getInfo['object_code']);
            $getInfo['object_name'] = $obj['name'];
        }
        //Lấy ảnh của phiếu bảo hành
        $listImage = $mWarrantyImage->getImageByCardCode($getInfo['warranty_card_code']);

        //Kiểm tra phiếu bảo hành được sử dụng ko
        $isUse = 1;

        if ($getInfo['status'] != 'actived') {
            $isUse = 0;
        } else {
            $dateNow = Carbon::now()->format('Y-m-d');

            if ($getInfo['quota'] != 0 && $getInfo['quota'] <= $getInfo['count_using'] && $getInfo['date_expired'] != null && $getInfo['date_expired'] < $dateNow) {
                $isUse = 0;
            }
        }

        return [
            'data' => $getInfo,
            'listImage' => $listImage,
            'isUse' => $isUse
        ];
    }

    /**
     * Cập nhật thẻ bảo hành
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //Lấy thông tin thẻ bảo hành
            $info = $this->warrantyCard->getInfo($input['warrantyCardCode']);

            $mWarrantyImage = new WarrantyImageTable();
            $dataUpdate = [
                'status' => $input['status'],
                'object_serial' => $input['objectSerial'],
                'object_note' => $input['objectNote'],
            ];
            if ($input['status'] == 'actived') {
                // Lấy thông tin gói bảo hành -> lấy thời gian bảo hành
                $mWarranty = new WarrantyPackageTable();
                $getWarranty = $mWarranty->getInfoById($input['packedId']);
                $dataUpdate['date_actived'] = date('Y-m-d H:i');
                $dataUpdate['date_expired'] = ($getWarranty['time'] != 0) ? Carbon::now()->addDay($getWarranty['time']) : null;

                //Lưu log ZNS
                SaveLogZns::dispatch('active_warranty_card', $info['customer_id'], $info['warranty_card_id']);
            }
            $this->warrantyCard->editByCode($dataUpdate, $input['warrantyCardCode']);
            // Xoá các ảnh cũ (is_deleted = 1)
            $mWarrantyImage->editByCode(['is_deleted' => 1], $input['warrantyCardCode']);

            // Thêm ảnh mới
            if (isset($input['arrImageOld']) && count($input['arrImageOld']) > 0) {
                foreach ($input['arrImageOld'] as $v) {
                    $linkAvatar = $v;
                    $tmp = [
                        'warranty_card_code' => $input['warrantyCardCode'],
                        'link' => $linkAvatar,
                    ];
                    $mWarrantyImage->add($tmp);
                }
            }
            if (isset($input['arrImageNew']) && count($input['arrImageNew']) > 0) {
                foreach ($input['arrImageNew'] as $v) {
                    $linkAvatar = $v;
                    $tmp = [
                        'warranty_card_code' => $input['warrantyCardCode'],
                        'link' => $linkAvatar,
                    ];
                    $mWarrantyImage->add($tmp);
                }
            }
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Huỷ thẻ bảo hành điện tử
     *
     * @param $input
     * @return array|mixed
     */
    public function cancel($input)
    {
        try {
            $warrantyCardId = $input['warrantyCardId'];
            // recheck status card
            $getInfo = $this->warrantyCard->getInfoById($warrantyCardId);
            if ($getInfo['status'] != 'new') {
                return [
                    'error' => true,
                    'message' => __('Hủy thất bại')
                ];
            }
            // update status
            $this->warrantyCard->edit(['status' => 'cancel'], $warrantyCardId);

            return [
                'error' => false,
                'message' => __('Hủy thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hủy thất bại')
            ];
        }
    }

    /**
     * Kích hoạt thẻ bảo hành
     *
     * @param $input
     * @return array|mixed
     */
    public function active($input)
    {
        try {
            $mCustomer = new CustomerTable();
            // status = new mới cho kích hoạt
            $cardInfo = $this->warrantyCard->getInfoById($input['warrantyCardId']);
            if ($cardInfo['status'] == 'new') {
                $mWarranty = new WarrantyPackageTable(); // lấy số ngày bảo hành
                $warrantyInfo = $mWarranty->getInfoByCode($cardInfo['warranty_packed_code']);
                if ($warrantyInfo != null) {
                    $this->warrantyCard->edit([
                        'status' => 'actived',
                        'date_actived' => date('Y-m-d H:i'),
                        'date_expired' => ($warrantyInfo['time'] != 0) ? Carbon::now()->addDay($warrantyInfo['time']) : null
                    ], $input['warrantyCardId']);
                } else {
                    return [
                        'error' => true,
                        'message' => __('Kích hoạt thất bại')
                    ];
                }
            }
            $customer = $mCustomer->getInfo($cardInfo['customer_code']);
            // send email (insert log)
            $this->insertEmailLog($input['warrantyCardId'], $cardInfo, $customer);
            // send sms (insert log)
            $this->insertSmsLog($input['warrantyCardId'], $cardInfo, $customer);
            // send notification
            $mNotification = new SendNotificationApi();
            $mNotification->sendNotification([
                'key' => 'warranty',
                'customer_id' => $customer['customer_id'],
                'object_id' => $input['warrantyCardId']
            ]);
            //Lưu log ZNS
            SaveLogZns::dispatch('active_warranty_card', $customer['customer_id'], $input['warrantyCardId']);

            return [
                'error' => false,
                'message' => __('Kích hoạt thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Kích hoạt thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    /**
     * Insert sms log
     *
     * @param $warrantyCardId
     * @param $cardInfo
     * @param $customer
     */
    public function insertSmsLog($warrantyCardId, $cardInfo, $customer)
    {
        $mSmsLog = new SmsLogTable();
        $mSmsConfig = new SmsConfigTable();
        $smsConfig = $mSmsConfig->getItemByKey('active_warranty_card');
        if ($smsConfig['is_active'] == 1) {
            $mSmsProvider = new SmsProviderTable();
            $brandName = $mSmsProvider->getItem(1)->value;
            $content = $smsConfig['content'];

            $gender = __('Anh');
            if ($customer['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($customer['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{WARRANTY_CARD_CODE}', '{DATETIME}'],
                [ $customer['customer_name'] . ' ', $gender . ' ', $cardInfo['warranty_card_code'], $cardInfo['date_expired'] ], $content);

            // insert
            $dataSmsLog = [
                'brandname' => $brandName,
                'phone' => $customer['phone'],
                'customer_name' => $customer['customer_name'],
                'message' => $message,
                'sms_type' => 'warranty_actived',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $warrantyCardId,
                'object_type' => 'warranty',
            ];
            $idSmsLog = $mSmsLog->add($dataSmsLog);
        }
    }

    /**
     * Insert email log
     *
     * @param $warrantyCardId
     * @param $cardInfo
     * @param $customer
     */
    public function insertEmailLog($warrantyCardId, $cardInfo, $customer)
    {
        $mEmailLog = new EmailLogTable();
        $mEmailConfig = new EmailConfigTable();

        $emailConfig = $mEmailConfig->getItemByKey('active_warranty_card');
        if ($emailConfig['is_actived'] == 1) {
            $mEmailProvider = new EmailProviderTable();
            $brandName = $mEmailProvider->getItem(1)->name_email;
            $content = $emailConfig['content'];
            $gender = __('Anh');
            if ($customer['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($customer['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{gender}' ,'{full_name}', '{card_code}', '{time}'],
                [ $gender, $customer['customer_name'], $cardInfo['warranty_card_code'], $cardInfo['date_expired'] ], $content);
            // insert
            $dataEmailLog = [
                'email' => $customer['email'],
                'customer_name' => $customer['customer_name'],
                'email_status' => 'new',
                'email_type' => 'warranty_actived',
                'object_id' => $warrantyCardId,
                'object_type' => 'warranty',
                'content_sent' => $message,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
            ];
            $idEmailLog = $mEmailLog->add($dataEmailLog);
        }
    }

    /**
     * Load tab chi tiết phiếu bảo hành
     *
     * @param $input
     * @return mixed|void
     */
    public function loadTabDetail($input)
    {
        switch ($input['tab_view']) {
            case 'info':
                $mWarrantyImage = new WarrantyImageTable();

                //Lấy thông tin phiếu bảo hành
                $getInfo = $this->warrantyCard->getInfoById($input['warranty_card_id']);

                if ($getInfo['object_type'] == 'product') {
                    $mProductChild = new ProductChildTable();
                    $obj = $mProductChild->getProduct($getInfo['object_code']);
                    $getInfo['object_name'] = $obj['product_child_name'];
                } elseif ($getInfo['object_type'] == 'service') {
                    $mService = new ServiceTable();
                    $obj = $mService->getService($getInfo['object_code']);
                    $getInfo['object_name'] = $obj['service_name'];
                } elseif ($getInfo['object_type'] == 'service_card') {
                    $mServiceCard = new ServiceCardTable();
                    $obj = $mServiceCard->getServiceCard($getInfo['object_code']);
                    $getInfo['object_name'] = $obj['name'];
                }

                //Lấy list hình ảnh của phiếu bảo hành
                $listImage = $mWarrantyImage->getImageByCardCode($getInfo['warranty_card_code']);

                return [
                    'data' => $getInfo,
                    'listImage' => $listImage
                ];

                break;
            case 'maintenance':
                //Lấy thông tin phiếu bảo hành
                $getInfo = $this->warrantyCard->getInfoById($input['warranty_card_id']);

                return [
                    'data' => $getInfo,
                    'FILTER' => $this->maintenanceFilters()
                ];

                break;
        }
    }

    /**
     * Load filter tab phiếu bảo trì
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function maintenanceFilters()
    {
        $mStaff = app()->get(StaffTable::class);

        //Lấy option người thực hiện
        $optionStaff = $mStaff->getStaff()->toArray();

        $staff = array_combine(
            array_column($optionStaff, 'staff_id'),
            array_column($optionStaff, 'staff_name')
        );

        $groupStaff = (['' => __('Chọn người thực hiện')]) + $staff;

        return [
            'maintenance$staff_id' => [
                'data' => $groupStaff
            ],
            'maintenance$status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'new' => __('Mới'),
                    'received' => __('Đã nhận hàng'),
                    'processing' => __('Đang xử lý'),
                    'ready_delivery' => __('Sẵn sàng trả hàng'),
                    'finish' => __('Hoàn tất'),
                    'cancel' => __('Đã hủy'),
                ]
            ]
        ];
    }
}