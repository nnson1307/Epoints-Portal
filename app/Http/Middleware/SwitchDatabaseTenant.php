<?php

namespace App\Http\Middleware;

use App\Models\PiospaBrandTable;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\ConfigTable;
use MyCore\Helper\OpensslCrypt;

/**
 * Class SwitchDatabaseTenant
 * @package App\Http\Middleware
 * @author DaiDP
 * @since Sep, 2019
 */
class SwitchDatabaseTenant
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {

        $configDB = $this->configDB($request);
        if ($configDB != 200) {
            abort($configDB);
        }

        return $next($request);
    }

    /**
     * Parse connect string to array
     *
     * @param $str
     * @return array
     */
    protected function parseConnStr($str)
    {
        $arrPart = explode(';', $str);
        $arrParams = [];
        foreach ($arrPart as $item) {
            list($key, $val) = explode('=', $item, 2);
            $key = strtolower($key);

            $arrParams[$key] = $val;
        }

        return $arrParams;
    }

    protected function configDB($request)
    {
        $domain = request()->getHost();

        $arrConfigConnStr = config('epoint-connstr', []);

        // Kiểm tra không tìm thấy cấu hình của tenant thì trả về lỗi 404
        if (empty($arrConfigConnStr[$domain])) {
            return 404;
        }

        $conStr = $arrConfigConnStr[$domain];

        // Kiểm tra connect string không đủ thông tin bắt buộc thì trả về lỗi 404
        $arrParams = $this->parseConnStr($conStr);

        if (
            empty($arrParams['server'])
            || empty($arrParams['database'])
            || empty($arrParams['user'])
        ) {
            return 404;
        }
        $idTenant = $arrParams['tenant_id'];


        session(['idTenant' => $idTenant]);

        session(['brand_code' => $arrParams['brand_code']]);

        // Thiết lập cấu hình database
        config([
            'database.connections.mysql' => [
                'driver' => 'mysql',
                'host' => $arrParams['server'],
                'port' => $arrParams['port'] ?? 3306,
                'database' => $arrParams['database'],
                'username' => $arrParams['user'],
                'password' => $arrParams['password'] ?? '',
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => env('DB_CHARSET', 'utf8mb4'),
                'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
                'prefix' => env('DB_PREFIX', ''),
                'strict' => env('DB_STRICT_MODE', false),
                'engine' => env('DB_ENGINE', null),
                'timezone' => env('DB_TIMEZONE', '+07:00'),
            ]
        ]);
        \DB::purge('mysql'); // Clear cache config. See: https://stackoverflow.com/a/37705096

        //Lấy cấu hình site
        $this->configSite();


        return 200;
    }

    /**
     *  Lấy cấu hình site
     *
     */
    protected function configSite()
    {
        
        if (session()->has('config_system') == false) {
            $config = new ConfigTable();
            session(['config_system' => [
                'logo' => $config->getInfoByKey("logo"),
                'short_logo' => $config->getInfoByKey("short_logo"),
                'decimal_number' => $config->getInfoByKey("decimal_number"),
                'text_login' => $config->getInfoByKey("text_login"),
                'script_header' => $config->getInfoByKey("script_header"),
                'oncall_key' => $config->getInfoByKey("oncall_key")['value'],
                'oncall_secret' => $config->getInfoByKey("oncall_secret")['value'],
                'email_provider' => DB::table('email_provider')->first(),
                'lang_site' => $config->getInfoByKey("lang_site")["value"],
                'timezone' => $config->getInfoByKey("timezone")["value"],
                'aws_access_key_id' => $config->getInfoByKey("aws_access_key_id")["value"],
                'aws_secret_access_key' => $config->getInfoByKey("aws_secret_access_key")["value"],
                'aws_default_region' => $config->getInfoByKey("aws_default_region")["value"],
                'aws_bucket' => $config->getInfoByKey("aws_bucket")["value"],
                'home_page_portal' => $config->getInfoByKey("home_page_portal")["value"] ?? null,
                'decimal_quantity' => $config->getInfoByKey("decimal_quantity")["value"] ?? 0,
            ]]);
        }
       
        \Illuminate\Support\Facades\Config::set('config', [
            'logo' => session('config_system')['logo'],
            'short_logo' => session('config_system')['short_logo'],
            'decimal_number' => session('config_system')['decimal_number'],
            'text_login' => session('config_system')['text_login'],
            'script_header' => session('config_system')['script_header'],
            'decimal_quantity' => session('config_system')['decimal_quantity'] ?? 0,
        ]);

        session(['key_service' => session('config_system')['oncall_key']]);

        session(['secret_service' => session('config_system')['oncall_secret']]);

        //Set email config
        if (session('config_system')['email_provider']->is_actived == 1) {
            if (session('config_system')['email_provider']->type == 'gmail') //checking type
            {
                $config = array(
                    'driver' => 'smtp',
                    'host' => 'smtp.gmail.com',
                    'port' => 587,
                    'from' => array(
                        'address' => session('config_system')['email_provider']->email,
                        'name' => session('config_system')['email_provider']->name_email
                    ),
                    'encryption' => 'tls',
                    'username' => session('config_system')['email_provider']->email,
                    'password' => Crypt::decryptString(session('config_system')['email_provider']->password),
                    //                        'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend' => false,
                );
                Config::set('mail', $config);
            } else {
                $config = array(
                    'driver' => 'smtp',
                    'host' => 'email-smtp.us-east-1.amazonaws.com',
                    'port' => 587,
                    'from' => array(
                        'address' => 'ducvu.q7@gmail.com',
                        'name' => session('config_system')['email_provider']->name_email
                    ),
                    'encryption' => 'tls',
                    'username' => 'AKIAIXTFJ4TCRAJPWBLQ',
                    'password' => 'BEe+lxl6lgHwge9bbv4TS+rbACjBCnmrkZhjxJdMVMvz',
                );
                Config::set('mail', $config);
            }
        }
        $config = new ConfigTable();
//        \App::setLocale(session('config_system')['lang_site']);
        \App::setLocale($config->getInfoByKey("lang_site")["value"]);

        date_default_timezone_set(session('config_system')['timezone']);

    }
}
