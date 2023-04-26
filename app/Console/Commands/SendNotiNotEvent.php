<?php

namespace App\Console\Commands;

use App\Jobs\SaveNotificationJob;
use Illuminate\Console\Command;
use Modules\Notification\Models\ConfigNotificationTable;

class SendNotiNotEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-noti-not-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mConfig = new ConfigNotificationTable();

        $arrNotEvent = ['appointment_R', 'customer_birthday', 'service_card_nearly_expired', 'service_card_expired'];

        foreach ($arrNotEvent as $v) {
            $config = $mConfig->getInfo($v);

            if ($config['is_active'] == 1) {
                SaveNotificationJob::dispatch([
                    'key' => $v
                ]);
            }
        }
    }
}
