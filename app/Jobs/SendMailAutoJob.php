<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Models\EmailLogTable;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\PointHistory\PointHistoryRepoInterface;
use Modules\Admin\Repositories\PointRewardRule\PointRewardRuleRepositoryInterface;

class SendMailAutoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        LoyaltyApi $loyaltyApi,
        PointHistoryRepoInterface $pointHistory,
        PointRewardRuleRepositoryInterface $pointReward
    ) {
        switch ($this->data['key']) {
            case 'birthday':
                $list_cus = DB::table('customers')
                    ->select('full_name', 'birthday', 'gender', 'email', 'customer_id', 'point')
                    ->whereDay('birthday', date('d'))
                    ->whereMonth('birthday', date('m'))
                    ->where('is_deleted', 0)->get();
                //Config Rule Birthday
                $configBirthday = $pointReward->getRuleByCode('birthday');
                foreach ($list_cus as $item) {
                    $gender_sub = null;
                    if ($item->gender == 'male') {
                        $gender_sub = __('Anh');
                    } else if ($item->gender == 'female') {
                        $gender_sub = __('Chị');
                    } else {
                        $gender_sub = __('Anh/Chị');
                    }
                    if ($item->birthday != null) {
                        $birthday = date('d/m/Y', strtotime($item->birthday));
                    } else {
                        $birthday = '';
                    }
                    //Lấy tên trong chuỗi full name
                    $string = $item->full_name;
                    $pieces = explode(' ', $string);
                    $last_name = array_pop($pieces);
                    //replace giá trị của tham số
                    $search = array('{name}', '{full_name}', '{gender}', '{birthday}', '{email}');
                    $replace = array($last_name, $item->full_name, $gender_sub, $birthday, $item->email);
                    $subject = $this->data['content'];
                    $returnValue = str_replace($search, $replace, $subject);
                    //Dữ liệu lưu log
                    if ($item->email != null) {
                        $data = [
                            'customer_name' => $item->full_name,
                            'email' => $item->email,
                            'email_status' => 'new',
                            'email_type' => 'birthday',
                            'content_sent' => $returnValue,
                            'time_sent' => date('Y-m-d') . ' ' . $this->data['time_sent'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'object_id' => $item->customer_id,
                            'object_type' => 'customer',
                            'created_at' => date('Y-m-d H:i'),
                            'updated_at' => date('Y-m-d H:i'),
                        ];

                        $mEmailLog = new EmailLogTable();
                        //Check đã insert vào bảng log  chưa
                        $checkLog = $mEmailLog->checkLogExist("birthday", "customer", $item->customer_id);

                        if ($checkLog == null) {
                            $id = DB::table('email_log')->insertGetId($data);
                        }
                    }
                    //Plus Point Event Birthday
                    if ($configBirthday['is_actived'] == 1) {
                        $customer_history = $pointHistory->getHistoryByDescription($item->customer_id,'birthday');
                        if ($customer_history == null) {
                            $loyaltyApi->plusPointEvent(['customer_id' => $item->customer_id, 'rule_code' => 'birthday', 'object_id' => '']);
                        }
                    }
                }
                break;
            case 'remind_appointment':
                $list_appointment = DB::table('customer_appointments')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
                    ->select('customers.full_name',
                        'customers.birthday',
                        'customers.gender',
                        'customers.email',
                        'customer_appointments.date',
                        'customer_appointments.time',
                        'customer_appointments.customer_appointment_id')
                    ->where('customer_appointments.date', date('Y-m-d'))
                    ->where('customer_appointments.time', '>=', date('H:i'))->get();
                foreach ($list_appointment as $item) {
                    $gender_sub = null;
                    $email = null;
                    if ($item->gender == 'male') {
                        $gender_sub = __('Anh');
                    } else if ($item->gender == 'female') {
                        $gender_sub = __('Chị');
                    } else {
                        $gender_sub = __('Anh/Chị');
                    }
                    if ($item->birthday != null) {
                        $birthday = date('d/m/Y', strtotime($item->birthday));
                    } else {
                        $birthday = '';
                    }
                    if ($item->email != null) {
                        $email = $item->email;
                    } else {
                        $email = '';
                    }
                    //Lấy tên trong chuỗi full name
                    $string = $item->full_name;
                    $pieces = explode(' ', $string);
                    $last_name = array_pop($pieces);
                    //replace giá trị của tham số
                    $search = array('{name}', '{full_name}', '{gender}', '{birthday}', '{time}', '{name_spa}', '{email}');
                    $replace = array($last_name, $item->full_name, $gender_sub, $birthday,
                        date('d/m/Y H:i', strtotime($item->date . ' ' . $item->time)), 'Piospa', $email);
                    $subject = $this->data['content'];
                    $returnValue = str_replace($search, $replace, $subject);
                    //Dữ liệu lưu log
                    $hours = date('H', strtotime($item->time));
                    $minute = date('i', strtotime($item->time));
                    if ($item->email != null) {
                        $data = [
                            'customer_name' => $item->full_name,
                            'email' => $item->email,
                            'email_status' => 'new',
                            'email_type' => 'remind_appointment',
                            'content_sent' => $returnValue,
                            'time_sent' => date('Y-m-d') . ' ' . date("H:i", strtotime($hours - $this->data['value'] . ':' . $minute)),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'object_id' => $item->customer_appointment_id,
                            'object_type' => 'customer_appointment',
                            'created_at' => date('Y-m-d H:i'),
                            'updated_at' => date('Y-m-d H:i'),
                        ];

                        $mEmailLog = new EmailLogTable();
                        //Check đã insert vào bảng log  chưa
                        $checkLog = $mEmailLog->checkLogExist("remind_appointment", "customer_appointment", $item->customer_appointment_id);

                        if ($checkLog == null) {
                            $id = DB::table('email_log')->insertGetId($data);
                        }
                    }
                }
                break;
            case 'service_card_nearly_expired':
                $day_plus = strtotime(date("Y-m-d H:i", strtotime(date("Y-m-d H:i") . '+ ' . $this->data['value'] . 'days')));
                $day_where = strftime("%Y-%m-%d", $day_plus);
                $list_service_card = DB::table('customer_service_cards')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
                    ->select(
                        'customers.full_name',
                        'customers.gender',
                        'customers.birthday',
                        'customers.email',
                        'customer_service_cards.card_code',
                        'customer_service_cards.expired_date',
                        'customer_service_cards.customer_service_card_id')
                    ->where('customer_service_cards.expired_date', $day_where)->get();
                foreach ($list_service_card as $item) {
                    $gender_sub = null;
                    $email = null;
                    if ($item->gender == 'male') {
                        $gender_sub = __('Anh');
                    } else if ($item->gender == 'female') {
                        $gender_sub = __('Chị');
                    } else {
                        $gender_sub = __('Anh/Chị');
                    }
                    if ($item->birthday != null) {
                        $birthday = date('d/m/Y', strtotime($item->birthday));
                    } else {
                        $birthday = '';
                    }
                    if ($item->email != null) {
                        $email = $item->email;
                    } else {
                        $email = '';
                    }
                    //Lấy tên trong chuỗi full name
                    $string = $item->full_name;
                    $pieces = explode(' ', $string);
                    $last_name = array_pop($pieces);
                    //replace giá trị của tham số
                    $search = array('{name}', '{full_name}', '{gender}', '{birthday}', '{time}', '{code_card}', '{email}');
                    $replace = array($last_name, $item->full_name, $gender_sub, $birthday,
                        date('d/m/Y', strtotime($item->expired_date)), $item->card_code, $email);
                    $subject = $this->data['content'];
                    $returnValue = str_replace($search, $replace, $subject);
                    //Dữ liệu lưu log
                    if ($item->email != null) {
                        $data = [
                            'customer_name' => $item->full_name,
                            'email' => $item->email,
                            'email_status' => 'new',
                            'email_type' => 'service_card_nearly_expired',
                            'content_sent' => $returnValue,
                            'time_sent' => date('Y-m-d') . ' ' . '8:00',
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'object_id' => $item->customer_service_card_id,
                            'object_type' => 'service_card',
                            'created_at' => date('Y-m-d H:i'),
                            'updated_at' => date('Y-m-d H:i'),
                        ];

                        $mEmailLog = new EmailLogTable();
                        //Check đã insert vào bảng log  chưa
                        $checkLog = $mEmailLog->checkLogExist("service_card_nearly_expired", "service_card", $item->customer_service_card_id);

                        if ($checkLog == null) {
                            $id = DB::table('email_log')->insertGetId($data);
                        }
                    }
                }
                break;
            case 'service_card_expires':
                $list_service_card = DB::table('customer_service_cards')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
                    ->select(
                        'customers.full_name',
                        'customers.gender',
                        'customers.birthday',
                        'customers.email',
                        'customer_service_cards.card_code',
                        'customer_service_cards.expired_date',
                        'customer_service_cards.customer_service_card_id')
                    ->where('customer_service_cards.expired_date', date('Y-m-d'))->get();
                foreach ($list_service_card as $item) {
                    $gender_sub = null;
                    $email = null;
                    if ($item->gender == 'male') {
                        $gender_sub = __('Anh');
                    } else if ($item->gender == 'female') {
                        $gender_sub = __('Chị');
                    } else {
                        $gender_sub = __('Anh/Chị');
                    }
                    if ($item->birthday != null) {
                        $birthday = date('d/m/Y', strtotime($item->birthday));
                    } else {
                        $birthday = '';
                    }
                    if ($item->email != null) {
                        $email = $item->email;
                    } else {
                        $email = '';
                    }
                    //Lấy tên trong chuỗi full name
                    $string = $item->full_name;
                    $pieces = explode(' ', $string);
                    $last_name = array_pop($pieces);
                    //replace giá trị của tham số
                    $search = array('{name}', '{full_name}', '{gender}', '{birthday}', '{time}', '{code_card}', '{email}');
                    $replace = array($last_name, $item->full_name, $gender_sub, $birthday,
                        date('d/m/Y', strtotime($item->expired_date)), $item->card_code, $email);
                    $subject = $this->data['content'];
                    $returnValue = str_replace($search, $replace, $subject);
                    //Dữ liệu lưu log
                    if ($item->email != null) {
                        $data = [
                            'customer_name' => $item->full_name,
                            'email' => $item->email,
                            'email_status' => 'new',
                            'email_type' => 'service_card_expires',
                            'content_sent' => $returnValue,
                            'time_sent' => date('Y-m-d') . ' ' . '8:00',
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'object_id' => $item->customer_service_card_id,
                            'object_type' => 'service_card',
                            'created_at' => date('Y-m-d H:i'),
                            'updated_at' => date('Y-m-d H:i'),
                        ];

                        $mEmailLog = new EmailLogTable();
                        //Check đã insert vào bảng log  chưa
                        $checkLog = $mEmailLog->checkLogExist("service_card_expires", "service_card", $item->customer_service_card_id);

                        if ($checkLog == null) {
                            $id = DB::table('email_log')->insertGetId($data);
                        }
                    }
                }
                break;
            case 'new_customer':
                $customer = DB::table('customers')
                    ->select('full_name', 'gender', 'email', 'birthday', 'customer_id')
                    ->where('customer_id', $this->data['id'])->first();

                $gender_sub = null;
                if ($customer->gender == 'male') {
                    $gender_sub = __('Anh');
                } else if ($customer->gender == 'female') {
                    $gender_sub = __('Chị');
                } else {
                    $gender_sub = __('Anh/Chị');
                }
                if ($customer->birthday != null) {
                    $birthday = date('d/m/Y', strtotime($customer->birthday));
                } else {
                    $birthday = '';
                }
                //Lấy tên trong chuỗi full name
                $string = $customer->full_name;
                $pieces = explode(' ', $string);
                $last_name = array_pop($pieces);
                //replace giá trị của tham số
                $search = array('{name}', '{full_name}', '{gender}', '{birthday}', '{email}', '{name_spa}');
                $replace = array($last_name, $customer->full_name, $gender_sub, $birthday,
                    $customer->email, 'Piospa');
                $subject = $this->data['content'];
                $returnValue = str_replace($search, $replace, $subject);
                //Dữ liệu lưu log
                if ($customer->email != null) {
                    $data = [
                        'customer_name' => $customer->full_name,
                        'email' => $customer->email,
                        'email_status' => 'new',
                        'email_type' => 'new_customer',
                        'content_sent' => $returnValue,
                        'created_at' => date('Y-m-d H:i'),
                        'updated_at' => date('Y-m-d H:i'),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'object_id' => $customer->customer_id,
                        'object_type' => 'customer'
                    ];
                    $id = DB::table('email_log')->insertGetId($data);
//                    $this->sendMailEvent($id,$this->data['id']);
                }

                break;
            case 'new_appointment':
                $app = DB::table('customer_appointments')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
                    ->select(
                        'customers.full_name',
                        'customers.gender',
                        'customers.email',
                        'customers.birthday',
                        'customer_appointments.date',
                        'customer_appointments.time',
                        'customer_appointments.customer_appointment_code',
                        'customer_appointments.customer_appointment_id')
                    ->where('customer_appointments.customer_appointment_id', $this->data['id'])->first();
                $gender_sub = null;
                if ($app->gender == 'male') {
                    $gender_sub = __('Anh');
                } else if ($app->gender == 'female') {
                    $gender_sub = __('Chị');
                } else {
                    $gender_sub = __('Anh/Chị');
                }
                if ($app->birthday != null) {
                    $birthday = date('d/m/Y', strtotime($app->birthday));
                } else {
                    $birthday = '';
                }
                //Lấy tên trong chuỗi full name
                $string = $app->full_name;
                $pieces = explode(' ', $string);
                $last_name = array_pop($pieces);
                //replace giá trị của tham số
                $search = array('{name}',
                    '{full_name}',
                    '{gender}',
                    '{birthday}',
                    '{email}',
                    '{day_appointment}',
                    '{time_appointment}',
                    '{code_appointment}',
                    '{name_spa}');
                $replace = array($last_name,
                    $app->full_name,
                    $gender_sub,
                    $birthday,
                    $app->email,
                    date('d/m/Y', strtotime($app->date)),
                    date('H:i', strtotime($app->time)),
                    $app->customer_appointment_code,
                    'Piospa');
                $subject = $this->data['content'];
                $returnValue = str_replace($search, $replace, $subject);
                //Dữ liệu lưu log
                if ($app->email != null) {
                    $data = [
                        'customer_name' => $app->full_name,
                        'email' => $app->email,
                        'email_status' => 'new',
                        'email_type' => 'new_appointment',
                        'content_sent' => $returnValue,
                        'created_at' => date('Y-m-d H:i'),
                        'updated_at' => date('Y-m-d H:i'),
                        'created_by' => Auth::id() ? Auth::id() : 0,
                        'updated_by' => Auth::id() ? Auth::id() : 0,
                        'object_id' => $app->customer_appointment_id,
                        'object_type' => 'customer_appointment'
                    ];

                    $id = DB::table('email_log')->insertGetId($data);
//                    $this->sendMailEvent($id,$this->data['id']);
                }

                break;
            case 'cancel_appointment':
                $app = DB::table('customer_appointments')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
                    ->select(
                        'customers.full_name',
                        'customers.gender',
                        'customers.email',
                        'customers.birthday',
                        'customer_appointments.date',
                        'customer_appointments.time',
                        'customer_appointments.customer_appointment_code',
                        'customer_appointments.customer_appointment_id')
                    ->where('customer_appointments.customer_appointment_id', $this->data['id'])->first();

                $gender_sub = null;
                if ($app->gender == 'male') {
                    $gender_sub = __('Anh');
                } else if ($app->gender == 'female') {
                    $gender_sub = __('Chị');
                } else {
                    $gender_sub = __('Anh/Chị');
                }
                if ($app->birthday != null) {
                    $birthday = date('d/m/Y', strtotime($app->birthday));
                } else {
                    $birthday = '';
                }
                //Lấy tên trong chuỗi full name
                $string = $app->full_name;
                $pieces = explode(' ', $string);
                $last_name = array_pop($pieces);
                //replace giá trị của tham số
                $search = array('{name}',
                    '{full_name}',
                    '{gender}',
                    '{birthday}',
                    '{email}',
                    '{code_appointment}',
                    '{name_spa}');
                $replace = array($last_name,
                    $app->full_name,
                    $gender_sub,
                    $birthday,
                    $app->email,
                    $app->customer_appointment_code,
                    'Piospa');
                $subject = $this->data['content'];
                $returnValue = str_replace($search, $replace, $subject);
                //Dữ liệu lưu log
                if ($app->email != null) {
                    $data = [
                        'customer_name' => $app->full_name,
                        'email' => $app->email,
                        'email_status' => 'new',
                        'email_type' => 'cancel_appointment',
                        'content_sent' => $returnValue,
                        'created_at' => date('Y-m-d H:i'),
                        'updated_at' => date('Y-m-d H:i'),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'object_id' => $app->customer_appointment_id,
                        'object_type' => 'customer_appointment'
                    ];
                    $id = DB::table('email_log')->insertGetId($data);
//                    $this->sendMailEvent($id,$this->data['id']);
                }
                break;
            case 'paysuccess':
                $app = DB::table('orders')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
                    ->select(
                        'customers.full_name',
                        'customers.gender',
                        'customers.email',
                        'customers.birthday',
                        'orders.order_code',
                        'orders.order_id')
                    ->where('orders.order_id', $this->data['id'])->first();

                $gender_sub = null;
                if ($app->gender == 'male') {
                    $gender_sub = __('Anh');
                } else if ($app->gender == 'female') {
                    $gender_sub = __('Chị');
                } else {
                    $gender_sub = __('Anh/Chị');
                }
                if ($app->birthday != null) {
                    $birthday = date('d/m/Y', strtotime($app->birthday));
                } else {
                    $birthday = '';
                }
                //Lấy tên trong chuỗi full name
                $string = $app->full_name;
                $pieces = explode(' ', $string);
                $last_name = array_pop($pieces);
                //replace giá trị của tham số
                $search = array('{name}',
                    '{full_name}',
                    '{gender}',
                    '{birthday}',
                    '{email}',
                    '{order_code}',
                    '{name_spa}');
                $replace = array($last_name,
                    $app->full_name,
                    $gender_sub,
                    $birthday,
                    $app->email,
                    $app->order_code,
                    'Piospa');
                $subject = $this->data['content'];
                $returnValue = str_replace($search, $replace, $subject);
                //Dữ liệu lưu log
                if ($app->email != null) {
                    $data = [
                        'customer_name' => $app->full_name,
                        'email' => $app->email,
                        'email_status' => 'new',
                        'email_type' => 'paysuccess',
                        'content_sent' => $returnValue,
                        'created_at' => date('Y-m-d H:i'),
                        'updated_at' => date('Y-m-d H:i'),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'object_id' => $app->order_id,
                        'object_type' => 'order'
                    ];
                    $id = DB::table('email_log')->insertGetId($data);
//                    $this->sendMailEvent($id,$this->data['id']);
                }
                break;
            case 'service_card_over_number_used':
                $sv_card = DB::table('customer_service_cards')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
                    ->select('customers.full_name',
                        'customers.gender',
                        'customers.birthday',
                        'customers.email',
                        'customer_service_cards.card_code',
                        'customer_service_cards.number_using',
                        'customer_service_cards.count_using',
                        'customer_service_cards.customer_service_card_id')
                    ->where('customer_service_cards.customer_service_card_id', $this->data['id'])->first();
                if ($sv_card->number_using != 0) {
                    if ($sv_card->number_using == $sv_card->count_using) {
                        $gender_sub = null;
                        if ($sv_card->gender == 'male') {
                            $gender_sub = __('Anh');
                        } else if ($sv_card->gender == 'female') {
                            $gender_sub = __('Chị');
                        } else {
                            $gender_sub = __('Anh/Chị');
                        }
                        if ($sv_card->birthday != null) {
                            $birthday = date('d/m/Y', strtotime($sv_card->birthday));
                        } else {
                            $birthday = '';
                        }
                        //Lấy tên trong chuỗi full name
                        $string = $sv_card->full_name;
                        $pieces = explode(' ', $string);
                        $last_name = array_pop($pieces);
                        //replace giá trị của tham số
                        $search = array('{name}',
                            '{full_name}',
                            '{gender}',
                            '{birthday}',
                            '{email}',
                            '{card_code}');
                        $replace = array($last_name,
                            $sv_card->full_name,
                            $gender_sub,
                            $birthday,
                            $sv_card->email,
                            $sv_card->card_code);
                        $subject = $this->data['content'];
                        $returnValue = str_replace($search, $replace, $subject);
                        //Dữ liệu lưu log
                        if ($sv_card->email != null) {
                            $data = [
                                'customer_name' => $sv_card->full_name,
                                'email' => $sv_card->email,
                                'email_status' => 'new',
                                'email_type' => 'service_card_over_number_used',
                                'content_sent' => $returnValue,
                                'created_at' => date('Y-m-d H:i'),
                                'updated_at' => date('Y-m-d H:i'),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'object_id' => $sv_card->customer_service_card_id,
                                'object_type' => 'service_card'
                            ];
                            $id = DB::table('email_log')->insertGetId($data);
//                            $this->sendMailEvent($id,$this->data['id']);
                        }
                    }
                }
                break;
            case 'order_success':
                $dataOrder = DB::table('orders')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
                    ->select(
                        'customers.full_name as full_name',
                        'customers.phone1 as phone',
                        'customers.address as address',
                        'customers.customer_avatar as customer_avatar',
                        'customers.customer_id as customer_id',
                        'customers.phone1 as phone1',
                        'customers.gender as gender',
                        'customers.email as email',
                        'orders.order_code as order_code',
                        'orders.amount as amount',
                        'orders.order_id as order_id'
                    )
                    ->where('orders.order_id', $this->data['id'])->first();
                $gender = __('Anh');
                if ($dataOrder->gender == 'male') {
                    $gender = __('Anh');
                } else if ($dataOrder->gender == 'female') {
                    $gender = __('Chị');
                } else {
                    $gender = __('Anh/Chị');
                }
                //replace giá trị của tham số
                $content = '';
                $search = array('{gender}', '{full_name}', '{order_code}', '{email}');
                $replace = array($gender, $dataOrder->full_name, $dataOrder->order_code, $dataOrder->email);
                $subject = $this->data['content'];
                $content = str_replace($search, $replace, $subject);
                if ($dataOrder->email != null) {
                    $data = [
                        'customer_name' => $dataOrder->full_name,
                        'email' => $dataOrder->email,
                        'email_status' => 'new',
                        'email_type' => 'order_success',
                        'content_sent' => $content,
                        'created_at' => date('Y-m-d H:i'),
                        'updated_at' => date('Y-m-d H:i'),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'object_id' => $this->data['id'],
                        'object_type' => 'order'
                    ];
                    $id = DB::table('email_log')->insertGetId($data);
                }
                break;
        }
    }



//    public function sendMailEvent($id_log,$id_add)
//    {
//        $provider = DB::table('email_provider')->select('type','email_template_id')->where('id', 1)->first();
//        $getLog = DB::table('email_log')->select('id', 'email', 'customer_name', 'email_status', 'email_type',
//            'content_sent')->where('id', $id_log)->first();
//        $getConfig = DB::table('email_config')->select('title')->where('key', $getLog->email_type)->first();
//        $name = $getLog->customer_name;
//        $content = $getLog->content_sent;
//        Mail::to($getLog->email)->send(new SendMailable($name, $getConfig->title, $content,$getLog->email_type,$id_add,$provider->email_template_id));
//        $data_edit = [
//            'time_sent_done' => date('Y-m-d H:i'),
//            'provider' => $provider->type,
//            'sent_by' => Auth::id(),
//            'email_status' => 'sent'
//        ];
//        DB::table('email_log')
//            ->where('id', $id_log)
//            ->update($data_edit);
//
//    }

}
