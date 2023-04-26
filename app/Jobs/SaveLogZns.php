<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Admin\Http\Api\ZnsApi;
use Modules\ZNS\Models\ZnsClientTable;
use Modules\ZNS\Repositories\Config\ConfigRepositoryInterface;

class SaveLogZns implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $key;
    protected $customerId;
    protected $objectId;

    /**
     * Create a new job instance.
     *
     * SaveLogZns constructor.
     * @param $key
     * @param $customerId
     * @param $objectId
     */
    public function __construct(
        $key,
        $customerId,
        $objectId
    ) {
        $this->key = $key;
        $this->customerId = $customerId;
        $this->objectId = $objectId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(in_array('zns.config', session('routeList'))) {
            $mZnsClient = app()->get(ZnsClientTable::class);
            //Check key ZNS
            $getKey = $mZnsClient->getClient();

            if ($getKey != null && $getKey['token'] != null) {
                $mZnsApi = app()->get(ZnsApi::class);
                //Lưu log ZNS
                $mZnsApi->saveLogTriggerEvent([
                    "key" => $this->key,
                    "user_id" => $this->customerId,
                    "object_id" => $this->objectId
                ]);
            }
        }
    }
}
