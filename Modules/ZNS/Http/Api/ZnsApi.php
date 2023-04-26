<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/01/2022
 * Time: 10:12
 */

namespace Modules\ZNS\Http\Api;

use GuzzleHttp\Client;
use MyCore\Api\ApiAbstract;

class ZnsApi extends ApiAbstract
{
    /**
     * Get template ZNS về
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getTemplate(array $data = [])
    {
        return $this->baseClientShareService('/noti/zalo/zns/template', $data);
    }

    /**
     * Lấy chi tiết template
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getTemplateDetail(array $data = [])
    {
        return $this->baseClientShareService('/noti/zalo/zns/template/detail', $data);
    }

    /**
     * Get danh sách người quan tâm về
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getCustomerCare(array $data = [])
    {
        return $this->baseClientShareService('/noti/zalo/zns/get-followers', $data);
    }

    /**
     * Get attachment id
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getAttachment($link_s3 = "")
    {
        $url = 'https://openapi.zalo.me/v2.0/oa/upload/image';
        if (str_contains($link_s3, '.gif')) {
            $url = 'https://openapi.zalo.me/v2.0/oa/upload/gif';
        }
        try {
            $oClient = new Client();
            $rsp = $oClient->post($url, [
                'headers' => [
                    'access_token' => 'M-k5P5b2b5LQwOTcN46JMbljXsXUNj0P8FkTUdSyZ2GRaCDhIa6F9nsRwcLcQ9qN88Q3MsCjypTj-8nWEW7aLN_JgZu85-DWKxhzAo0IbLf9_eGmHnRyGJt5g2DgFhn4Qylm3Z4Wl4DsqD424HV2RmhiqbmD6eOkUCBPK2iSjYTkjlPDC1c_97JGoKLq9BniBj2iMceYxYaIYufYQt7r91oTdGreDUvW5-o0Fcr3n4qKweu-Jp7iI0xzkoD04VWG6zQCOcC6x2aSp_D24p6cAbEOutqSH_qHO9UOGZKo_W5vKJQT9mHUM9DK',
                ],
                'multipart' => [
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'file',
                        'contents' => fopen($link_s3, 'r'),
                    ]
                ]
            ]);
            $oData = json_decode($rsp->getBody(), true);
            return $oData['data']['attachment_id'];
        } catch (\Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            dd($ex->getLine());
        }
    }

    public function getTokenuploadFile($link_s3 = "")
    {
        $url = 'https://openapi.zalo.me/v2.0/oa/upload/file';
        try {
            $oClient = new Client();
            $rsp = $oClient->post($url, [
                'headers' => [
                    'access_token' => 'M-k5P5b2b5LQwOTcN46JMbljXsXUNj0P8FkTUdSyZ2GRaCDhIa6F9nsRwcLcQ9qN88Q3MsCjypTj-8nWEW7aLN_JgZu85-DWKxhzAo0IbLf9_eGmHnRyGJt5g2DgFhn4Qylm3Z4Wl4DsqD424HV2RmhiqbmD6eOkUCBPK2iSjYTkjlPDC1c_97JGoKLq9BniBj2iMceYxYaIYufYQt7r91oTdGreDUvW5-o0Fcr3n4qKweu-Jp7iI0xzkoD04VWG6zQCOcC6x2aSp_D24p6cAbEOutqSH_qHO9UOGZKo_W5vKJQT9mHUM9DK',
                ],
                'multipart' => [
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'file',
                        'contents' => fopen($link_s3, 'r'),
                    ]
                ]
            ]);
            $oData = json_decode($rsp->getBody(), true);
            return $oData['data']['token'];
        } catch (\Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            dd($ex->getLine());
        }
    }
}