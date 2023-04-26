<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class UpdateTotalTimeOffdays implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $list = DB::table('staffs')
            ->select('staff_id')
            ->where('is_actived', 1)
            ->where('is_deleted', 0)->get();

        foreach ($list as $item) {
            $old = DB::table('time_off_days_total_log')
                ->select('total', 'total_used', 'time_off_days_total_log_id')
                ->where('staff_id', $item->staff_id)->first();
            if ($old) {
                $data = [
                    'staff_id' => $item->staff_id,
                    'total' => $old->total + 1,
                    'created_at' => date('Y-m-d H:i')
                ];
                DB::table('time_off_days_total_log')
                    ->where('time_off_days_total_log_id', $old->time_off_days_total_log_id)
                    ->update($data);
            } else {
                $data = [
                    'staff_id' => $item->staff_id,
                    'total' => 0,
                    'created_at' => date('Y-m-d H:i')
                ];
                DB::table('time_off_days_total_log')
                    ->insert($data);
            }


        }
    }
}
