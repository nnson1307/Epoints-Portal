<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Models\NotificationDetailTable;
use Modules\Admin\Models\NotificationLogTable;

class SaveNotificationJob implements ShouldQueue
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
    public function handle()
    {
        $mSendNoti = new SendNotificationApi();

        switch ($this->data['key']) {
            //Chúc mừng sinh nhật
            case 'customer_birthday':
                $list_cus = DB::table('customers')
                    ->select('full_name', 'birthday', 'gender', 'email', 'customer_id', 'point')
                    ->whereDay('birthday', date('d'))
                    ->whereMonth('birthday', date('m'))
                    ->where('is_deleted', 0)->get();

                foreach ($list_cus as $item) {
                    $mSendNoti->sendNotification([
                        'key' => $this->data['key'],
                        'customer_id' => $item->customer_id,
                        'object_id' => ''
                    ]);
                }
                break;
            //Nhắc lịch
            case 'appointment_R':
                $list_appointment = DB::table('customer_appointments')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_appointments.customer_id')
                    ->select('customers.full_name',
                        'customers.birthday',
                        'customers.gender',
                        'customers.email',
                        'customer_appointments.date',
                        'customer_appointments.time',
                        'customer_appointments.customer_appointment_id',
                        'customers.customer_id'
                    )
                    ->where('customer_appointments.date', date('Y-m-d'))
                    ->where('customer_appointments.time', '>=', date('H:i'))->get();

                foreach ($list_appointment as $item) {
                    $mSendNoti->sendNotification([
                        'key' => $this->data['key'],
                        'customer_id' => $item->customer_id,
                        'object_id' => $item->customer_appointment_id
                    ]);
                }
                break;
            //Thẻ dịch vụ sắp hết hạn
            case 'service_card_nearly_expired':
                $day_plus = strtotime(date("Y-m-d H:i", strtotime(date("Y-m-d H:i") . '+ ' . 1 . 'days')));
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
                        'customer_service_cards.customer_service_card_id',
                        'customers.customer_id'
                    )
                    ->where('customer_service_cards.expired_date', $day_where)->get();

                foreach ($list_service_card as $item) {
                    $mSendNoti->sendNotification([
                        'key' => $this->data['key'],
                        'customer_id' => $item->customer_id,
                        'object_id' => $item->customer_service_card_id
                    ]);
                }
                break;
            //Thẻ dịch vụ hết hạn
            case 'service_card_expired':
                $list_service_card = DB::table('customer_service_cards')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
                    ->select(
                        'customers.full_name',
                        'customers.gender',
                        'customers.birthday',
                        'customers.email',
                        'customer_service_cards.card_code',
                        'customer_service_cards.expired_date',
                        'customer_service_cards.customer_service_card_id',
                        'customer_service_cards.customer_id'
                    )
                    ->where('customer_service_cards.expired_date', '<', date('Y-m-d'))->get();

                foreach ($list_service_card as $item) {
                    $mSendNoti->sendNotification([
                        'key' => $this->data['key'],
                        'customer_id' => $item->customer_id,
                        'object_id' => $item->customer_service_card_id
                    ]);
                }
                break;
        }
    }
}
