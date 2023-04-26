<?php

use Carbon\Carbon;

define('PAGING_ITEM_PER_PAGE', 10);
define('LOGIN_HOME_PAGE', 'dashbroad');
define('TEMP_PATH', '/temp_upload/');
define('SERVICE_CARD_PATH', 'uploads/admin/service_card/');
define('STORE_UPLOADS_PATH', 'uploads/admin/store/');
define('SERVICES_UPLOADS_PATH', 'uploads/services/services/');
define('STAFF_UPLOADS_PATH', 'uploads/admin/staff/');
define('CUSTOMER_UPLOADS_PATH', 'uploads/admin/customer/');
define('SERVICE_UPLOADS_PATH', 'uploads/admin/service/');
define('BRANCH_UPLOADS_PATH', 'uploads/admin/branch/');
define('PRODUCT_UPLOADS_PATH', 'uploads/admin/product/');
define('SPA_INFO_UPLOADS_PATH', 'uploads/admin/spa-info/');
define('BANNER_UPLOADS_PATH', 'uploads/admin/banner/');
define('CONFIG_SERVICE_CARD', 'uploads/admin/config-print-service-card/');
define('SEND_EMAIL_CARD', 'uploads/admin/send-email-card/');
define('VOUCHER_PATH', 'uploads/admin/voucher/');
define('NOTIFICATION_PATH', 'uploads/notification/config/');
define('USER_CARRIER_PATH', 'uploads/delivery/user-carrier/');
define('NEW_PATH', 'uploads/admin/new/');
define('DELIVERY_HISTORY_PATH', 'uploads/admin/delivery-history/');
define('CONFIG_GENERAL_PATH', 'uploads/admin/config-general/');
define('PROMOTION_PATH', 'uploads/promotion/promotion/');
define('CUSTOMER_LEAD_PATH', 'uploads/customer-lead/customer-lead/');
define('CONFIG_EMAIL_TEMPLATE', 'uploads/admin/config-email-template/');
define('UPLOAD_FILE_EXCEL', 'uploads/admin/excel/');
define('CODE_SUCCESS', 0);
define('LOYALTY_API_URL', env('LOYALTY_API_URL'));
define('PIOSPA_QUEUE_URL', env('PIOSPA_QUEUE_URL'));
define('END_POINT_PAGING', 10);
define('DOMAIN_PIOSPA', env('DOMAIN_PIOSPA'));
define('Logo', 'asd');
define('LIMIT_ITEM', 50);
define('FILTER_ITEM_PAGE', 10);
define('TICKET_UPLOADS_PATH', 'uploads/admin/ticket/');
define('WORK_UPLOADS_PATH', 'uploads/admin/manager-work/');
define('TICKET_STATUS_NEW', 1);
define('TICKET_STATUS_INPROCESS', 2);
define('TICKET_STATUS_SUCCESS', 3);
define('TICKET_STATUS_CLOSE', 4);
define('TICKET_STATUS_CANCLE', 5);
define('TICKET_STATUS_REOPEN', 6);

define('SEND_NOTIFY_CUSTOMER', 'notify_customer');
define('SEND_NOTIFY_STAFF', 'notify_staff');
define('SEND_EMAIL_CUSTOMER', 'email_customer');
define('SEND_SMS_CUSTOMER', 'sms_customer');
define('SEND_ZNS_CUSTOMER', 'zns_customer');

define('STAFF_API_URL', env('STAFF_API_URL'));
define('STAFF_QUEUE_URL', env('STAFF_QUEUE_URL'));
// constant survey  //
define('MAX_SIZE_INSERT_ARRAY', 500);
define('LIST_FULL', 1000000000);
define('CODE_IMPORT', '%s%04d%s');
define('EXPORT', 'export');
define('PAGING_ITEM_PER_PAGE_POPUP', 10);

if (!function_exists('subString')) {
    function subString($value, $limit = 50, $end = '...')
    {
        return \Illuminate\Support\Str::limit($value, $limit, $end);
    }
}

if (!function_exists('getValueByLang')) {
    function getValueByLang($fieldName, $locale = null)
    {
        if (!$locale) $locale = App::getLocale();
        return $fieldName . $locale;
    }
}

if (!function_exists('_dd')) {
    function _dd(...$args)
    {
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        call_user_func_array('dd', $args);
    }
}

if (!function_exists('getThu')) {
    function getThu($date)
    {
        $weekMap = [
            0 => 'CN',
            1 => 'T2',
            2 => 'T3',
            3 => 'T4',
            4 => 'T5',
            5 => 'T6',
            6 => 'T7',
        ];
        $now = Carbon::now();
        // $now->
        return $weekMap[$date->dayOfWeek];
    }
}

/**
 * Tính toán thời gian xử lý
 *
 * @param $startTime
 * @param $endTime
 * @return array
 */
if (!function_exists('calcBusyCalendar')) {
    function calcBusyCalendar($startTime, $endTime)
    {
        $full = 86399; // 24h
        $start = time_to_decimal($startTime);
        $end = time_to_decimal($endTime);

        $left = intval($start / $full * 100);
        $process = ceil(($end - $start) / $full * 100);

        return compact('left', 'process');
    }
}

/**
 * Convert time into decimal time.
 *
 * @param string $time The time to convert
 *
 * @return integer The time as a decimal value.
 */
if (!function_exists('time_to_decimal')) {
    function time_to_decimal($time)
    {
        $timeArr = explode(':', $time);
        $decTime = ($timeArr[0] * 3600) + ($timeArr[1] * 60) + ($timeArr[2]);

        return $decTime;
    }
}
if (!function_exists('timeAgo')) {
    function timeAgo($timestamp)
    {
        $time_ago        = strtotime($timestamp);
        $current_time    = time();
        $time_difference = $current_time - $time_ago;
        $seconds         = $time_difference;

        $minutes = round($seconds / 60); // value 60 is seconds
        $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
        $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;
        $weeks   = round($seconds / 604800); // 7*24*60*60;
        $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
        $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

        if ($seconds <= 60) {

            return "vừa nhận";
        } else if ($minutes <= 60) {

            if ($minutes == 1) {

                return "1 phút trước";
            } else {

                return "$minutes phút";
            }
        } else if ($hours <= 24) {

            if ($hours == 1) {

                return "1 giờ trước";
            } else {

                return "$hours giờ";
            }
        } else if ($days <= 7) {

            if ($days == 1) {

                return "hôm qua";
            } else {

                return "$days ngày";
            }
        } else if ($weeks <= 4.3) {

            if ($weeks == 1) {

                return "1 tuần trước";
            } else {

                return "$weeks tuần";
            }
        } else if ($months <= 12) {

            if ($months == 1) {

                return "1 tháng trước";
            } else {

                return "$months tháng";
            }
        } else {

            if ($years == 1) {

                return "1 năm trước";
            } else {

                return "$years năm";
            }
        }
    }
}
// function of Phieu
/**
 * thời gian xử lý
 *
 * @param $giờ
 * @return array
 */
if (!function_exists('processTime')) {
    function processTime($hour)
    {
        $time = '';
        if ($hour != null) {
            if ($hour >= 24) {
                if ($hour % 24 == 0) {
                    $time = ($hour / 24) . ' Ngày';
                } else {
                    $time = floor($hour / 24) . ' Ngày ' . ($hour % 24) . ' Giờ';
                }
            } else {
                $time = $hour . ' Giờ';
            }
        }
        return $time;
    }
}
/**
 * loại ticket
 *
 * @param $level
 * @return array
 */
if (!function_exists('getTypeTicket')) {
    function getTypeTicket($type = 'list')
    {
        if ($type == 'list') {
            return \DB::table('ticket_issue_group')->select("ticket_issue_group_id", "name")->where("is_active", 1)->get()->pluck("name", "ticket_issue_group_id")->toArray();
        }
    }
}
/**
 * Cấp độ sự cố
 *
 * @param $level
 * @return array
 */
if (!function_exists('levelIssue')) {
    function levelIssue($level = 'list')
    {
        if ($level == 'list') {
            return [
                1 => 'Cấp 1',
                2 => 'Cấp 2',
                3 => 'Cấp 3',
                4 => 'Cấp 4',
                5 => 'Cấp 5',
            ];
        }
        return 'Cấp ' . $level;
    }
}

/**
 * Cấp độ sự cố
 *
 * @param $level
 * @return array
 */
if (!function_exists('getPriority')) {
    function getPriority($priority = 'list')
    {
        $arr = [
            'N' => 'Thấp',
            'L' => 'Bình thường',
            'H' => 'Cao',
        ];
        if ($priority == 'list') {
            return $arr;
        }
        return isset($arr[$priority]) ? $arr[$priority] : '';
    }
}
/**
 * Tạo thẻ a
 *
 * @param $level
 * @return array
 */
if (!function_exists('createATag')) {
    function createATag($link = '#', $text = "")
    {
        return $html = '<a href="' . $link . '">' . $text . '</a>';
    }
}

/**
 * Tạo thẻ a
 *
 * @param $level
 * @return array
 */
if (!function_exists('getOption')) {
    function getOption($data = '#', $select_value = "")
    {
        if (!$data) {
            return [];
        }
        foreach ($data as $key => $value) {
            $selected = ($select_value == $key) ? ' selected' : '';
            $html = '<option value="' + $key + '"' + $selected + '>' . $value . '</option>';
        }
        return $html;
    }
}

/**
 * cắt chuỗi file
 *
 * @param $level
 * @return array
 */
if (!function_exists('fileNameCustom')) {
    function fileNameCustom($file_name)
    {
        $arr = explode("/", $file_name);
        $arr = array_reverse($arr);
        $file_name = $arr[0];
        return $file_name;
    }
}

/**
 * cắt chuỗi file
 *
 * @param $level
 * @return array
 */
if (!function_exists('processTimeTicketFilter')) {
    function processTimeTicketFilter()
    {
        return [
            1 => "Nhỏ hơn 1 giờ",
            2 => "1 giờ - 3 giờ",
            3 => "3 giờ - 5 giờ",
            4 => "Lớn hơn 5 giờ",
            5 => "1 ngày",
            6 => "2 ngày",
            7 => "Lớn hơn 2 ngày"
        ];
    }
}

/**
 * lấy domain
 */
if (!function_exists('getDomain')) {
    function getDomain()
    {
        if (
            isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
        ) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        return $protocol . str_replace('*', session()->get('brand_code'), $_SERVER['SERVER_NAME']);
    }
}

/**
 * loại bỏ các kí tự đặt biệt
 */
if (!function_exists('stripTagParam')) {
    function stripTagParam(&$params)
    {
        foreach ($params as $key => $value) {
            if (!is_array($value)) {
                $params[$key] = strip_tags($value);
            }
        }
        return $params;
    }
}

/**
 * tạo ra mã code 
 */

if (!function_exists('getCode')) {
    function getCode($type = 'CODE_IMPORT', $number, $number2 = null)
    {
        $now = Carbon::now()->format('Ymd');
        if ($number2) return sprintf($type, $now, $number, $number2);
        return sprintf($type, $now, $number, '');
    }
}
