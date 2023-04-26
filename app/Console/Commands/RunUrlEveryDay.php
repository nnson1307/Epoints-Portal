<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 02/06/2022
 * Time: 14:37
 */

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Http\Api\Service;
use MyCore\Helper\OpensslCrypt;


class RunUrlEveryDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epoint:run-url-every-day';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Lấy connection string từ Azure cache về máy.';


    /**
     * Execute the console command
     *
     * @param TenantManagementInterface $umSDK
     */
    public function handle()
    {
//        Log::info('Chạy mail + sms');

        $oApi = new Service();
        $arrBrand = $oApi->getAllBrand();


        if(!$arrBrand) return 'not ok';

        foreach ($arrBrand as $brand){

            $domain = sprintf(env('DOMAIN_BRAND','branddev.retailpro.io'), $brand['brand_code']);
            $oClient = new Client([
                'base_uri'    => $domain,
                'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            ]);

            $rsp = $oClient->get('cron-job-get-salary');

        }

//        Log::info('End mail + sms');
    }
}