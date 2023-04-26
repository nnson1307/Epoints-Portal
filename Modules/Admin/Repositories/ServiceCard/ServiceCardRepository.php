<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/11/2018
 * Time: 10:31 AM
 */

namespace Modules\Admin\Repositories\ServiceCard;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceCardSoldAccrualLogTable;
use Modules\Admin\Models\ServiceCardSoldImageTable;

class ServiceCardRepository implements ServiceCardRepositoryInterface
{
    protected $service_card;
    const IS_RESERVE = 1;

    public function __construct(ServiceCard $card)
    {
        $this->service_card = $card;
    }

    public function list(array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->service_card->getList($filters);
    }

    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->service_card->add($data);
    }

    public function getServiceCardInfo($id)
    {
        // TODO: Implement getServiceCardInfo() method.
        return $this->service_card->getServiceCardInfo($id);
    }

    public function edit($id, array $data)
    {
        // TODO: Implement edit() method.
        return $this->service_card->edit($id, $data);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        return $this->service_card->remove($id);
    }

    public function getServiceCardDetail($id)
    {
        // TODO: Implement getServiceCardDetail() method.
        return $this->service_card->getServiceCardDetail($id);
    }

    public function searchServiceCard($data)
    {
        // TODO: Implement searchServiceCard() method.
        return $this->service_card->getServiceCardSearch($data);
    }

    public function getListAdd($categoryId, $search, $page)
    {
        // TODO: Implement getListAdd() method.
        return $this->service_card->getListAdd($categoryId, $search, $page);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id)
    {
        return $this->service_card->getItemDetail($id);
    }

    public function getServiceCardOrder($code)
    {
        // TODO: Implement getServiceCardOrder() method.
        return $this->service_card->getServiceCardOrder($code);
    }

    /*
     *  get option
     */
    public function getOption()
    {
        $array = [];
        $data = $this->service_card->getOption();
        foreach ($data as $item) {
            $array[$item['service_card_id']] = $item['name'];
        }
        return $array;
    }

    public function getAllServiceCard()
    {
        return $this->service_card->getAllServiceCard();
    }

    //Chi tiết thẻ dịch vụ
    public function detail($id)
    {
        return $this->service_card->detail($id);
    }

    public function filter($keyWord, $status, $cardType, $cardGroup)
    {
        return $this->service_card->filter($keyWord, $status, $cardType, $cardGroup);
    }

    //Kiểm tra tên thẻ.
    public function checkName($name, $id, $groupId)
    {
        return $this->service_card->checkName($name, $id, $groupId);
    }

    //Lấy danh sách thẻ đã bán.
    public function getServiceCardSold($cardType)
    {
        return $this->service_card->getServiceCardSold($cardType);
    }

    //Lấy danh sách thẻ hết hạn theo ngày truyền vào.
    public function serviceCardNearlyExpireds($datetime)
    {
        return $this->service_card->serviceCardNearlyExpireds($datetime);
    }

    //Lấy danh sách các thẻ hết số lần sử dụng
    public function serviceCardOverNumberUseds($id)
    {
        return $this->service_card->serviceCardOverNumberUseds($id);
    }

    //Lấy danh sách thẻ hết hạn hôm nay.
    public function serviceCardExpireds()
    {
        return $this->service_card->serviceCardExpireds();
    }

    //Lấy nhóm thẻ thông qua id thẻ.
    public function getServiceGroup($id)
    {
        return $this->service_card->getServiceGroup($id);
    }

    /**
     * Lưu ảnh trước khi điều trị, sau khi điều trị (thẻ dịch vụ đã bán), và xoá những ảnh đã loại bỏ
     *
     * @param $input
     * @return array|mixed
     */
    public function saveImageServiceCardSold($input)
    {
        try {
            $mServiceCardSoldImage = new ServiceCardSoldImageTable();
            // Xoá ảnh trừ ảnh cũ
            if (isset($input['arrayImageOld'])) {
                $arrImageOld = $input['arrayImageOld'];
                $mServiceCardSoldImage->deleteImageSCSold($input['customerServiceCardCode'], $input['orderCode'], $input['type'], $arrImageOld);
            } else {
                $mServiceCardSoldImage->deleteImageSCSold($input['customerServiceCardCode'], $input['orderCode'], $input['type'], []);
            }
            if (isset($input['arrayImage'])) {
                // Insert ảnh mới
                $arrImage = $input['arrayImage'];
                foreach ($arrImage as $value) {
                    $data = [
                        'customer_service_card_code' => $input['customerServiceCardCode'],
                        'type' => $input['type'],
                        'order_code' => $input['orderCode'],
                        'link' => $value,
                        'is_deleted' => 0,
                        'created_at' => date('Y-m-d')
                    ];
                    $mServiceCardSoldImage->store($data);
                }
            }
            // Get list image return
            $listImage = $mServiceCardSoldImage->getListImageByCode($input['customerServiceCardCode'], $input['orderCode'], $input['type']);

            return [
                'error' => false,
                'data' => $listImage,
                'type' => $input['type'],
                'message' => __('Thêm ảnh thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm ảnh thất bại')
//                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách hình ảnh theo card code và order code
     *
     * @param $cardCode
     * @param $orderCode
     * @return mixed
     */
    public function getImageServiceCardSold($cardCode, $orderCode)
    {
        $mServiceCardSoldImage = new ServiceCardSoldImageTable();
        return $mServiceCardSoldImage->getListImageByCode($cardCode, $orderCode);
    }

    /**
     * Lấy hình ảnh theo input cho view carousel
     *
     * @param $input
     * @return mixed
     */
    public function getImageForCarousel($input)
    {
        $mServiceCardSoldImage = new ServiceCardSoldImageTable();
        return $mServiceCardSoldImage->getListImageByCode($input['cardCode'], $input['orderCode'], $input['type']);
    }

    /**
     * Bảo lưu thẻ dịch vụ đã bán
     *
     * @param $input
     * @return array|mixed
     */
    public function reserveServiceCard($input)
    {
        try {
            if (isset($input['card_code']) && $input['card_code'] != null) {
                $mCustomerServiceCard = new CustomerServiceCardTable();
                $cardCode = $input['card_code'];
                $getCard = $mCustomerServiceCard->getCardByCode($cardCode);
                $valid = $this->validateCard($getCard);
                if ($valid['error'] == true) {
                    return $valid;
                }
                // Tính số ngày sử dụng thẻ còn lại
                $dateExpiredOld = Carbon::parse($getCard['expired_date']);
                $dateNow = Carbon::now()->format('Y-m-d');
                $now = Carbon::parse($dateNow);
                $diffDays = $dateExpiredOld->diffInDays($now);
                // Cập nhật data
                $dataUpdate = [
                    'expired_date' => null,
                    'is_reserve' => self::IS_RESERVE,
                    'date_reserve' => date('Y-m-d'),
                    'number_days_remain_reserve' => $diffDays
                ];
                $mCustomerServiceCard->editByCode($dataUpdate, $cardCode);
            }
            return [
                'error' => false,
                'message' => __('Bảo lưu thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Bảo lưu thất bại')
            ];
        }
    }

    /**
     * Mở bảo lưu thẻ liệu trình (thẻ dịch vụ đã bán)
     *
     * @param $input
     * @return array|mixed
     */
    public function openReserveServiceCard($input)
    {
        try {
            if (isset($input['card_code']) && $input['card_code'] != null) {
                $mCustomerServiceCard = new CustomerServiceCardTable();
                $cardCode = $input['card_code'];
                $getCard = $mCustomerServiceCard->getCardByCode($cardCode);
                // Tính lại ngày hết hạn, lấy ngày hiện tại + number_days_remain_reserve
                $now = Carbon::now();
                $dateExpiredNew = $now->addDays((int)$getCard['number_days_remain_reserve'])->format('Y-m-d');

                $dataUpdate = [
                    'is_reserve' => 0,
                    'number_days_remain_reserve' => null,
                    'expired_date' => $dateExpiredNew
                ];
                $mCustomerServiceCard->editByCode($dataUpdate, $cardCode);
            }
            return [
                'error' => false,
                'message' => __('Mở bảo lưu thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Mở bảo lưu thất bại')
            ];
        }
    }

    /**
     * Kiểm trả thẻ còn sử dụng được không
     *
     * @param $infoCard
     * @return array|false[]
     */
    private function validateCard($infoCard)
    {
        //Kiểm tra thẻ đã kích hoạt chưa
        if ($infoCard['is_actived'] == 0) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình chưa được kích hoạt')
            ];
        }
        //Kiểm tra hạn sử dụng
        $dataNow = Carbon::now()->format('Y-m-d');
        $dateExpired = Carbon::parse($infoCard['expired_date'])->format('Y-m-d');
        if ($infoCard['expired_date'] != null && $dataNow >= $dateExpired) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình hết hạn sử dụng')
            ];
        }
        //Kiểm tra số lần sử dụng
        if ($infoCard['number_using'] != 0 && $infoCard['number_using'] <= $infoCard['count_using']) {
            return [
                'error' => true,
                'message' => __('Thẻ liệu trình hết số lần sử dụng')
            ];
        }

        return [
            'error' => false
        ];
    }

    /**
     * modal cộng dồn thẻ liệu trình
     *
     * @param $input
     * @return array|mixed
     */
    public function modalAccrualSCSold($input)
    {
        if (isset($input['cardCode']) && $input['cardCode'] != null) {
            $listCardCanAccrual = [];
            // lấy thông tin thẻ dịch vụ đã bán
            $mCustomerServiceCard = new CustomerServiceCardTable();
            $cardCode = $input['cardCode'];
            $getCard = $mCustomerServiceCard->getCardByCode($cardCode);
            // lấy những thẻ có thể cộng dồn được ngoại trừ thẻ đó
            if ($getCard != null) {
                $listCardCanAccrual = $mCustomerServiceCard->getListCardCanAccrual($getCard);
            }
            $html = \View::make('admin::service-card.sold.service-card.modal-accrual', [
                'listCardCanAccrual' => $listCardCanAccrual,
                'cardCodeCurrent' => $cardCode
            ])->render();

            return [
                'html' => $html
            ];
        }
    }

    /**
     * submit cộng dồn thẻ liệu trình
     *
     * @param $input
     * @return array|mixed
     */
    public function submitAccrualSCSold($input)
    {
        DB::beginTransaction();
        try {
            $mCustomerServiceCard = new CustomerServiceCardTable();
            $mCustomerSCSLog = new ServiceCardSoldAccrualLogTable();
            $cardCurrent = $input['cardCode'];
            $cardToAccrual = $input['cardToAccrual'];
            // Lấy số lần sử dụng còn lại, số ngày còn lại cộng dồn vào card curent
            $getCardToAccrualInfo = $mCustomerServiceCard->getCardByCode($cardToAccrual);
            // Lấy thông thẻ hiện tại
            $getCardCurrentInfo = $mCustomerServiceCard->getCardByCode($cardCurrent);

            if ($getCardToAccrualInfo != null && $getCardCurrentInfo != null) {
                $numberUsing = 0;
                $numOfDays = null;
                $expiredDate = null;


                //Tính số lần sử dụng còn lại
                if ($getCardToAccrualInfo['number_using'] == 0 || $getCardCurrentInfo['number_using'] == 0) {
                    $numberUsing = 0;
                } else {
                    $numberUsing = intval($getCardCurrentInfo['number_using']) + intval($getCardToAccrualInfo['number_using'] - $getCardToAccrualInfo['count_using']);
                }
                //Tính ngày sử dụng còn lại
                if ($getCardToAccrualInfo['expired_date'] == null || $getCardCurrentInfo['expired_date'] == null) {
                    $expiredDate = null;
                } else {
                    // số ngày còn lại
                    $dateExpired = Carbon::parse($getCardToAccrualInfo['expired_date']);
                    $now = Carbon::now();
                    $numOfDays = $dateExpired->diffInDays($now);
                    // cộng dồn
                    $dateExpiredOld = Carbon::parse($getCardCurrentInfo['expired_date']);
                    $expiredDate = $dateExpiredOld->addDays($numOfDays)->format('Y-m-d');
                }

                $dataUpdate = [
                    'number_using' => $numberUsing,
                    'expired_date' => $expiredDate
                ];
                $mCustomerServiceCard->editByCode($dataUpdate, $cardCurrent);

                // Cập nhật lại cardToAccrual
                $mCustomerServiceCard->editByCode([
                    'is_deleted' => 1,
                    'note' => 'Cộng dồn vào thẻ có mã code là ' .$cardCurrent
                ], $cardToAccrual);
                // Lưu log
                $dataLog = [
                    'card_code_destination' => $cardCurrent,
                    'card_code_target' => $cardToAccrual,
                    'number_of_days' => $numOfDays,
                    'number_of_uses' => $numberUsing,
                    'created_by' => Auth::id(),
                    'created_at' => date('Y-m-d')
                ];
                $mCustomerSCSLog->add($dataLog);

                DB::commit();
                return [
                    'error' => false,
                    'message' => __('Cộng dồn thẻ thành công')
                ];
            }
            DB::commit();
            return [
                'error' => true,
                'message' => __('Không tìm thấy thẻ')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Cộng dồn thẻ thất bại')
            ];
        }
    }
}