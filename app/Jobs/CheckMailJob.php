<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Admin\Http\Api\LoyaltyApi;

use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\NotificationAutoConfigTable;

class CheckMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $type;
    protected $type_key;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct
    (
        $type,
        $type_key,
        $id
    ) {
        $this->type = $type;
        $this->type_key = $type_key;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $check_provider = DB::table('email_provider')->first();
        if ($check_provider->is_actived == 1) {
            $email_config = DB::table('email_config')
                ->select('key', 'value', 'title', 'content', 'is_actived', 'time_sent')
                ->get();
            if ($this->type == 'is_event') {
                foreach ($email_config as $item) {
                    if ($this->type_key == $item->key) {
                        if ($item->is_actived == 1) {
                            $data = [
                                'key' => $item->key,
                                'value' => $item->value,
                                'title' => $item->title,
                                'content' => $item->content,
                                'time_sent' => $item->time_sent,
                                'id' => $this->id
                            ];
                            SendMailAutoJob::dispatch($data);
                        }
                    }
                }
            } else {
                $type_event = ['birthday', 'remind_appointment',
                    'service_card_nearly_expired', 'service_card_expires'];

                foreach ($type_event as $i) {
                    foreach ($email_config as $item) {
                        if ($i == $item->key) {
                            if ($item->is_actived == 1) {
                                $data = [
                                    'key' => $item->key,
                                    'value' => $item->value,
                                    'title' => $item->title,
                                    'content' => $item->content,
                                    'time_sent' => $item->time_sent
                                ];
                                SendMailAutoJob::dispatch($data);
                            }
                        }
                    }
                }
            }
        }
    }
}
