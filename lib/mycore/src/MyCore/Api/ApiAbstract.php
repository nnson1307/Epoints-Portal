<?php

namespace MyCore\Api;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * User: daidp
 * Date: 11/15/2018
 * Time: 11:08 AM
 */
abstract class ApiAbstract
{
//    protected $baseUrlApi = BASE_URL_API;

    /**
     * @return Client
     */
    protected function getClient()
    {
        $jwt = session('authen_token');

        $client = new Client([
            'base_uri' => asset('/'),
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả
     * @param $url
     * @param $params
     * @return mixed
     * @throws ApiException
     */
    protected function baseClient($url, $params, $stripTags = true)
    {
        try {
            if ($stripTags) $params = $this->stripTagData($params);

            $oClient = $this->getClient();

            $rsp = $oClient->post($url, [
                'json' => $params,
            ]);

            $token = $rsp->getHeader('AUTH_TOKEN');
            if (!empty($token)) {
                session(['authen_token' => str_replace('Bearer ', '', current($token))]);
//                \MasterConstant::createSSO(str_replace('Bearer ', '', current($token)));
            }


            return json_decode($rsp->getBody(), true);
        } catch (\Exception $ex) {
            // create a log channel
            $log = new Logger('portal');
            $log->pushHandler(new StreamHandler(storage_path("logs/laravel-portal-". date('Y-m-d').".log"), Logger::INFO));

            $log->error('PIO ERR | Connection Error By Api: ' . $url);
            $log->error('PIO ERR | Connection Content: ' . $ex->getMessage());

            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    protected function baseClientUpload($url, $params)
    {
        try {
            $oClient = $this->getClient();
            $rsp = $oClient->post($url, [
                'multipart' => [$params]
            ]);

            $token = $rsp->getHeader('AUTH_TOKEN');
            if (!empty($token)) {
                session(['authen_token' => str_replace('Bearer ', '', current($token))]);
//                \MasterConstant::createSSO(str_replace('Bearer ', '', current($token)));
            }
            return json_decode($rsp->getBody(), true);
        } catch (\Exception $ex) {
            // create a log channel
            $log = new Logger('portal');
            $log->pushHandler(new StreamHandler(storage_path("logs/laravel-portal-". date('Y-m-d').".log"), Logger::INFO));

            $log->error('PIO ERR | Connection Error By Api: ' . $url);
            $log->error('PIO ERR | Connection Content: ' . $ex->getMessage());

            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    /**
     * hỗ trợ striptag data
     * @param $arrData
     * @return array
     */
    protected function stripTagData($arrData)
    {
        $arrResult = [];
        foreach ($arrData as $key => $item) {
            $arrResult[$key] = strip_tags($item);
        }

        return $arrResult;
    }


    /**
     * @return Client
     */
    protected function getClientLoyaltyApi()
    {
        $jwt = session('authen_token');

        $domain = request()->getHost();

        $brandCode = session()->get('brand_code');

        $client = new Client([
            'base_uri' => sprintf(LOYALTY_API_URL, $brandCode),
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả
     * @param $url
     * @param $params
     * @return mixed
     * @throws ApiException
     */
    protected function baseClientLoyaltyApi($url, $params, $stripTags = true)
    {
        try
        {
            if ($stripTags) $params = $this->stripTagData($params);

            // create a log channel
            $log = new Logger('portal');
            $log->pushHandler(new StreamHandler(storage_path("logs/laravel-portal-". date('Y-m-d').".log"), Logger::INFO));

            $log->info('API:'.$url);
            $log->info('Input:'.json_encode($params));

            $oClient = $this->getClientLoyaltyApi();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            $log->info('Output:'.$rsp->getBody());

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            } else {
                return $result;
            }
        }
        catch (\Exception $ex)
        {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    protected function getClientPushNotification()
    {
        $client = new Client([
            'base_uri'    => PIOSPA_QUEUE_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
        ]);

        return $client;
    }

    protected function baseClientPushNotification($url, $params, $stripTags = true)
    {
        try
        {
            if ($stripTags) $params = $this->stripTagData($params);

            // create a log channel
            $log = new Logger('portal');
            $log->pushHandler(new StreamHandler(storage_path("logs/laravel-portal-". date('Y-m-d').".log"), Logger::INFO));

            $log->info('API:'.$url);
            $log->info('Input:'.json_encode($params));


            $oClient = $this->getClientPushNotification();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            $log->info('Output:'.$rsp->getBody());

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            } else {
                return $result;
            }
        }
        catch (\Exception $ex)
        {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    protected function getClientStaffPushNotification()
    {
        $client = new Client([
            'base_uri'    => STAFF_QUEUE_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
        ]);

        return $client;
    }

    protected function baseClientStaffPushNotification($url, $params, $stripTags = true)
    {
        try
        {
            if ($stripTags) $params = $this->stripTagData($params);

            // create a log channel
            $log = new Logger('portal');
            $log->pushHandler(new StreamHandler(storage_path("logs/laravel-portal-". date('Y-m-d').".log"), Logger::INFO));

            $log->info('API:'.$url);
            $log->info('Input:'.json_encode($params));


            $oClient = $this->getClientStaffPushNotification();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            $log->info('Output:'.$rsp->getBody());

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            } else {
                return $result;
            }
        }
        catch (\Exception $ex)
        {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    /**
     * @return Client
     */
    protected function getClientStaffApi()
    {
        $jwt = session('authen_token');

        $client = new Client([
            'base_uri' => env('STAFF_API_URL'),
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả
     * @param $url
     * @param $params
     * @return mixed
     * @throws ApiException
     */
    protected function baseClientStaffApi($url, $params, $stripTags = true)
    {
        try
        {
            if ($stripTags) $params = $this->stripTagData($params);

            // create a log channel
            $log = new Logger('portal');
            $log->pushHandler(new StreamHandler(storage_path("logs/laravel-portal-". date('Y-m-d').".log"), Logger::INFO));

            $log->info('API:'.$url);
            $log->info('Input:'.json_encode($params));

            $oClient = $this->getClientLoyaltyApi();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            $log->info('Output:'.$rsp->getBody());

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            } else {
                return $result;
            }
        }
        catch (\Exception $ex)
        {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    /**
     *
     *
     * @return Client
     */
    protected function getClientShareService()
    {
        $jwt = session('authen_token');

        $client = new Client([
            'base_uri' => env('BASE_URL_SHARE_SERVICE'),
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'tenant' => session('brand_code'),
                'key' => session('key_service'),
                'secret' => session('secret_service')
            ]
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả
     *
     * @param $url
     * @param $params
     * @param bool $stripTags
     * @return mixed
     * @throws ApiException
     */
    protected function baseClientShareService($url, $params, $stripTags = true)
    {
        try {
            if ($stripTags) $params = $this->stripTagData($params);

            $oClient = $this->getClientShareService();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            return json_decode($rsp->getBody(), true);
        }
        catch (\Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }
}
