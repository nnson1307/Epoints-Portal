<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/26/2019
 * Time: 1:38 PM
 */

namespace Modules\Admin\Repositories\SendSms;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\sms\SendSms;
use Modules\Admin\Libs\SmsFpt\index;
use Modules\Admin\Models\SmsLogTable;
use Modules\Admin\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\Admin\Repositories\SmsProvider\SmsProviderRepositoryInterface;

class SendSmsRepository implements SendSmsRepositoryInterface
{
    protected $smsProvider;
    protected $smsConfig;
    protected $smsLog;
    protected $sendSms;
    protected $sendSmsFpt;

    public function __construct(
        SmsProviderRepositoryInterface $smsProvider,
        SmsConfigRepositoryInterface $smsConfig,
        SmsLogTable $smsLog,
        SendSms $sendSms,
        index $sendSmsFpt
    )
    {
        $this->smsProvider = $smsProvider;
        $this->smsConfig = $smsConfig;
        $this->smsLog = $smsLog;
        $this->sendSms = $sendSms;
        $this->sendSmsFpt = $sendSmsFpt;
    }

    public function sendOneSms($idLog)
    {
        $smsSettingBrandName = $this->smsProvider->getItem(1);

        if ($smsSettingBrandName != null) {
            if ($smsSettingBrandName->is_actived == 1) {
                if ($smsSettingBrandName->provider == 'vietguys') {
                    $listLog = $this->smsLog->getItem($idLog);
                    $idTransaction = 1;
                    if ($listLog != null) {
                        $arrayConfig['phone'] = $listLog['phone'];
                        $arrayConfig['message'] = $listLog['message'];
                        $arrayConfig['_USER_NAME'] = $smsSettingBrandName->account;
                        $arrayConfig['_PASSWORD'] = $smsSettingBrandName->password;
                        $arrayConfig['_BRAND_NAME'] = $smsSettingBrandName->value;
                        $arrayConfig['idTransaction'] = $idTransaction;

                        $sendSms = $this->sendSms->send($arrayConfig);
                        $response = json_decode($sendSms, true);
                        if ($response['error'] == false) {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id()
                            ];
                        } else {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id(),
                                'error_code' => $response['errorCode'],
                                'error_description' => $response['message']
                            ];
                        }
                        $this->smsLog->edit($data, $idLog);
                    }
                } else if ($smsSettingBrandName->provider == 'fpt') {
                    $this->sendSmsFpt($idLog, $smsSettingBrandName->value);
                }

            }
        }
    }

    public function sendSmsFpt($idLog, $brandName)
    {
        $listLog = $this->smsLog->getItem($idLog);
        if ($listLog != null) {
            $arrayConfig['Phone'] = $listLog['phone'];
            $arrayConfig['BrandName'] = $brandName;
            $arrayConfig['Message'] = $listLog['message'];


            $sendSms = $this->sendSmsFpt->send($arrayConfig);
            $response = json_decode($sendSms, true);
            
            if ($response['errorrss'] == false) {
                $data = [
                    'sms_status' => 'sent',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'time_sent_done' => date('Y-m-d H:i:s'),
                    'sent_by' => Auth::id()
                ];
            } else {
                $data = [
                    'sms_status' => 'sent',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'time_sent_done' => date('Y-m-d H:i:s'),
                    'sent_by' => Auth::id(),
                    'error_code' => $response['errorCode'],
                    'error_description' => $response['errorDescription']
                ];
            }
            $this->smsLog->edit($data, $idLog);
        }
    }
}