<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $subject;
    public $content;
    public $type_sent;
    public $id_add;
    public $email_template_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $subject, $content, $type_sent, $id_add, $email_template_id)
    {
        //
        $this->name = $name;
        $this->subject = $subject;
        $this->content = $content;
        $this->type_sent = $type_sent;
        $this->id_add = $id_add;
        $this->email_template_id = $email_template_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //Lấy cấu hình template
        $config_template = DB::table('config_email_template')
            ->select('id', 'logo', 'website', 'background_header', 'color_header',
                'background_body', 'color_body', 'background_footer', 'color_footer', 'image')
            ->where('id', 1)->first();
        //Lấy cấu hình thông tin spa
        $spa_info =
            DB::table('spa_info')->leftJoin('province', 'province.provinceid', '=', 'spa_info.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'spa_info.districtid')
            ->select('spa_info.id',
                'spa_info.name',
                'spa_info.code',
                'spa_info.phone',
                'spa_info.is_actived',
                'spa_info.is_deleted',
                'spa_info.email',
                'spa_info.hot_line',
                'spa_info.provinceid',
                'spa_info.districtid',
                'spa_info.address',
                'spa_info.slogan',
                'spa_info.bussiness_id',
                'spa_info.logo',
                'spa_info.fanpage',
                'spa_info.zalo',
                'spa_info.instagram_page',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name')
            ->where('spa_info.id', 1)
            ->first();
        //Lấy cấu hình thời gian làm việc
        $timeWorking =
            DB::table('time_working')
            ->select('eng_name', 'vi_name', 'start_time', 'end_time')
            ->where("is_actived", 1)
            ->get();

        switch ($this->type_sent) {
            case 'paysuccess':
                $order = DB::table('orders')
                    ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
                    ->leftJoin('receipts', 'receipts.order_id', '=', 'orders.order_id')
                    ->leftJoin('staffs', 'staffs.staff_id', '=', 'orders.created_by')
                    ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
                    ->select('orders.total',
                        'orders.discount',
                        'orders.amount',
                        'customers.full_name as customer_name',
                        'customers.phone1 as customer_phone',
                        'customers.email as customer_email',
                        'branches.branch_name',
                        'staffs.full_name as staff_name',
                        'orders.created_at',
                        'receipts.note')
                    ->where('orders.order_id', $this->id_add)->first();
                $order_detail = DB::table('order_details')
                    ->select('object_id', 'object_name', 'price', 'quantity', 'discount', 'amount', 'object_type', 'object_code')
                    ->where('order_id', $this->id_add)->get();

                $data = [];
                foreach ($order_detail as $item) {

                    if ($item->object_type == 'service') {
                        $image = DB::table('services')->select('service_avatar')
                            ->where('service_id', $item->object_id)->first();
                        $data[] = [
                            'object_name' => $item->object_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'discount' => $item->discount,
                            'amount' => $item->amount,
                            'image' => $image->service_avatar,
                            'object_type' => $item->object_type,
                        ];
                    } else if ($item->object_type == 'product') {
                        $image = DB::table('product_childs')
                            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
                            ->select('products.avatar as avatar')
                            ->where('product_childs.product_child_id', $item->object_id)->first();
                        $data[] = [
                            'object_name' => $item->object_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'discount' => $item->discount,
                            'amount' => $item->amount,
                            'image' => $image->avatar,
                            'object_type' => $item->object_type
                        ];
                    } else if ($item->object_type == 'service_card') {
                        $image = DB::table('service_cards')
                            ->select('image')
                            ->where('service_cards.service_card_id', $item->object_id)->first();
                        $data[] = [
                            'object_name' => $item->object_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'discount' => $item->discount,
                            'amount' => $item->amount,
                            'image' => $image->image,
                            'object_type' => $item->object_type
                        ];
                    } else if ($item->object_type == 'member_card') {
                        $image = DB::table('customer_service_cards')
                            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
                            ->select('service_cards.image')
                            ->where('customer_service_cards.card_code', $item->object_code)->first();
                        $data[] = [
                            'object_name' => $item->object_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'discount' => $item->discount,
                            'amount' => $item->amount,
                            'image' => $image->image,
                            'object_type' => $item->object_type
                        ];
                    }
                }

                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'order' => $order,
                        'order_detail' => $data,
                        'type' => 'paysuccess',
                        'title' => $this->subject,
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'order' => $order,
                        'order_detail' => $data,
                        'type' => 'paysuccess',
                        'title' => $this->subject,
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'order' => $order,
                        'order_detail' => $data,
                        'type' => 'paysuccess',
                        'title' => $this->subject,
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'order' => $order,
                        'order_detail' => $data,
                        'type' => 'paysuccess',
                        'title' => $this->subject,
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                }

                break;
            case 'new_appointment':
                $custapp = DB::table('customer_appointments')->where('customer_appointment_id', $this->id_add)->first();

                $listService = DB::table('customer_appointments')
                    ->select(
                        'services.service_name as service_name',
                        'services.service_avatar as service_avatar',
                        'sbr1.new_price as new_price'
                    )
                    ->leftJoin('customer_appointment_details', 'customer_appointment_details.customer_appointment_id', '=', 'customer_appointments.customer_appointment_id')
                    ->leftJoin('services', 'services.service_id', '=', 'customer_appointment_details.service_id')
                    ->leftJoin('service_branch_prices as sbr1', 'sbr1.service_id', '=', 'services.service_id')
//                    ->leftJoin('service_branch_prices as sbr2', 'sbr2.branch_id', '=', 'customer_appointments.branch_id')
                    ->where('customer_appointments.customer_appointment_id', $this->id_add)
                    ->where('sbr1.branch_id',$custapp->branch_id)
                    ->get();

                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'title' => $this->subject,
                        'id' => Crypt::encryptString($this->id_add),
                        'type' => 'new_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'listService'=>$listService,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'title' => $this->subject,
                        'id' => Crypt::encryptString($this->id_add),
                        'type' => 'new_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'listService'=>$listService,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'title' => $this->subject,
                        'id' => Crypt::encryptString($this->id_add),
                        'type' => 'new_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'listService'=>$listService,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'title' => $this->subject,
                        'id' => Crypt::encryptString($this->id_add),
                        'type' => 'new_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'listService'=>$listService,
                        'timeWorking' => $timeWorking
                    ]);
                }


                break;
            case 'cancel_appointment':
                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'title' => 'Thông báo hủy lịch hẹn',
                        'id' => $this->id_add,
                        'type' => 'cancel_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'title' => 'Thông báo hủy lịch hẹn',
                        'id' => $this->id_add,
                        'type' => 'cancel_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'title' => 'Thông báo hủy lịch hẹn',
                        'id' => $this->id_add,
                        'type' => 'cancel_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'title' => 'Thông báo hủy lịch hẹn',
                        'id' => $this->id_add,
                        'type' => 'cancel_appointment',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                }


                break;
            case 'new_customer':
                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'title' => 'Chào mừng bạn đến với Piospa',
                        'id' => $this->id_add,
                        'type' => 'new_customer',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'title' => 'Chào mừng bạn đến với Piospa',
                        'id' => $this->id_add,
                        'type' => 'new_customer',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'title' => 'Chào mừng bạn đến với Piospa',
                        'id' => $this->id_add,
                        'type' => 'new_customer',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'title' => 'Chào mừng bạn đến với Piospa',
                        'id' => $this->id_add,
                        'type' => 'new_customer',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                }


                break;
            case 'service_card_over_number_used':
                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'title' => $this->subject,
                        'id' => $this->id_add,
                        'type' => 'service_card_over_number_used',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'title' => $this->subject,
                        'id' => $this->id_add,
                        'type' => 'service_card_over_number_used',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'title' => $this->subject,
                        'id' => $this->id_add,
                        'type' => 'service_card_over_number_used',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'title' => $this->subject,
                        'id' => $this->id_add,
                        'type' => 'service_card_over_number_used',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                }


                break;
            case 'print_card':
                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'title' => 'Danh sách thẻ dịch vụ đã mua',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'type' => 'print_card',
                        'content_sent' => explode(';', $this->content),
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'title' => 'Danh sách thẻ dịch vụ đã mua',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'type' => 'print_card',
                        'content_sent' => explode(';', $this->content),
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'title' => 'Danh sách thẻ dịch vụ đã mua',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'type' => 'print_card',
                        'content_sent' => explode(';', $this->content),
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'title' => 'Danh sách thẻ dịch vụ đã mua',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'type' => 'print_card',
                        'content_sent' => explode(';', $this->content),
                        'timeWorking' => $timeWorking
                    ]);
                }
                break;
            default:
                if ($this->email_template_id == 1) {
                    return $this->view('admin::marketing.email.template-email.template-1.index', [
                        'title' => '',
                        'type' => 'campaign',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 2) {
                    return $this->view('admin::marketing.email.template-email.template-2.index', [
                        'title' => '',
                        'type' => 'campaign',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 3) {
                    return $this->view('admin::marketing.email.template-email.template-3.index', [
                        'title' => '',
                        'type' => 'campaign',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                } else if ($this->email_template_id == 4) {
                    return $this->view('admin::marketing.email.template-email.template-4.index', [
                        'title' => '',
                        'type' => 'campaign',
                        'config_template' => $config_template,
                        'spa_info' => $spa_info,
                        'timeWorking' => $timeWorking
                    ]);
                }

        }
    }
}
