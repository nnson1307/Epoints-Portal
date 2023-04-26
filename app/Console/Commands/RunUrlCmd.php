<?php
namespace App\Console\Commands;

use App\Http\Api\Service;
use App\Models\BrandTable;
use DaiDP\StsSDK\TenantManagement\TenantManagementInterface;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use MyCore\FileManager\Stub;
use MyCore\Helper\OpensslCrypt;
/**
 * Class GetConnectionStringCmd
 * @package App\Console\Commands
 * @author DaiDP
 * @since Sep, 2019
 */
class RunUrlCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epoint:run-url {isReset?}';

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

        $oConstr = new OpensslCrypt(env('OP_SECRET'), env('OP_SALT'));
        $arrConnStr = [];

        $isReset = $this->argument('isReset');

        foreach ($arrBrand as $brand){

            $domain = sprintf(env('DOMAIN_BRAND','branddev.retailpro.io'), $brand['brand_code']);
            $oClient = new Client([
                'base_uri'    => $domain,
                'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            ]);

            if (!$isReset){

                $rsp = $oClient->get('send-sms-log');

                $rsp = $oClient->get('send-email-job');

                $rsp = $oClient->get('run-log-email');

                $rsp = $oClient->get('run-log-sms');
            } else {
                $rsp = $oClient->get('run-reset-rank');
            }

        }

//        Log::info('End mail + sms');
    }
}
