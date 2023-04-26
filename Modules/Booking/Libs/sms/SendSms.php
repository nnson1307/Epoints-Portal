<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/25/2019
 * Time: 6:24 PM
 */

namespace Modules\Booking\Libs\sms;

use Modules\Admin\Models\SmsProviderTable;

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
#header("Access-Control-Allow-Origin: https://prod2.giadinhnestle.com.vn");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: X-CSRF-Token");
header("Content-type: application/json; charset=utf-8");

class SendSms
{
//    private $_URL_API_SMS = 'http://cloudsms.vietguys.biz:8088/api/index.php';
    private $_URL_API_SMS_VIETGUYS = 'http://cloudsms.vietguys.biz:8088/api/index.php';
    private $_URL_API_SMS_FPT = '';
    private $_URL_API_SMS_VIETTEL = '';
    private $_URL_API_SMS_VHT = '';
    private $_URL_API_SMS_ST = '';

    private $_ERROR_CODE = [
        '-1' => 'Chưa truyền đầy đủ tham số',
        '-2' => 'Máy chủ đang bận',
        '-3' => 'Không tìm thấy tài khoản người dùng',
        '-4' => 'Tài khoản bị khóa',
        '-5' => 'Thông tin xác thực chưa chính xác',
        '-6' => 'Chưa kích hoạt tính năng gửi qua API',
        '-7' => 'IP bị giới hạn truy cập',
        '-8' => 'Tên thương hiệu chưa khai báo',
        '-9' => 'Tài khoản hết credits gửi tin',
        '-10' => 'Số điện thoại chưa chính xác',
        '-11' => 'Số điện thoại nằm trong danh sách từ chối nhận tin',
        '-12' => 'Hết credits gửi tin',
        '-13' => 'Tên thương hiệu chưa khai báo',
        '-14' => 'Số kí tự vượt quá 459 kí tự (lỗi tin nhắn dài)',
        '-16' => 'Gửi trùng số điện thoại, thương hiệu, nội dung trong 01 phút',
        '-17' => 'quá số lượng tin trong 1 ngày cho 1 tk.',
        '-18' => 'spam keyword.',
        '-19' => 'quá số lượng tin trong 1 ngày cho 1 số điện thoại.',
    ];
    protected $smsProvider;

    public function __construct()
    {
        $this->smsProvider = new SmsProviderTable();
    }

    public function send($config)
    {
        $provider = $this->smsProvider->getItem(1);

        $_URL_API_SMS=$this->_URL_API_SMS_VIETGUYS;
        $phone = $config['phone'];
        $message = $config['message'];
        $idTransaction = $config['idTransaction'];

        $_USER_NAME = $config['_USER_NAME'];
        $_PASSWORD = $config['_PASSWORD'];
        $_BRAND_NAME = $config['_BRAND_NAME'];

        try {

            if (trim($phone) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Phone invalid'
                ]);
            }
//
            if (trim($message) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Message invalid'
                ]);
            }

            if (trim($_URL_API_SMS) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'API sms null'
                ]);
            }
            $phone = $this->_validPhone($phone);

            if (!$phone) {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Phone invalid'
                ]);
            }

            $sendSMS = [
                'u' => $_USER_NAME,
                'pwd' => $_PASSWORD,
                'from' => $_BRAND_NAME,
                'phone' => $phone,
                'sms' => NString::removeDiacriticalMarks($message),
                'bid' => $idTransaction
            ];

            $oURL = new Curl();

            $oURL->setPostParams($sendSMS);

            $result = $oURL->execute($_URL_API_SMS);
            $code = (int)$result;

            if ($code >= 0) {
                $arrResult = [
                    'error' => false,
                    'errorCode' => 0,
                    'data' => $result,
                    'message' => 'Send SMS success'
                ];
            } else {
                $arrResult = [
                    'error' => true,
                    'errorCode' => $code,
                    'data' => null,
                    'message' => isset($this->_ERROR_CODE[$code]) ? $this->_ERROR_CODE[$code] : 'Lỗi không xác định'
                ];
            }
        } catch (\Exception $ex) {
            $arrResult = [
                'error' => true,
                'errorCode' => 0,
                'data' => null,
                'message' => $ex->getMessage()
            ];
        }

        return json_encode($arrResult);
    }

    protected function _validPhone($phone)
    {

        $oPhoneFilter = new PhoneFilter();
        return $oPhoneFilter->filter($phone);
    }

}