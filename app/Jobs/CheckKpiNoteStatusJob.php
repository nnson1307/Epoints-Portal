<?php

namespace App\Jobs;

use App\Models\KpiNoteTable;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckKpiNoteStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $table   = app()->get(KpiNoteTable::class);
        $kpiData = $table->list();

        foreach ($kpiData as $item) {
            if ($item['effect_month'] == intval(Carbon::now()->format('m'))) {
                $table->updateStatus($item['kpi_note_id'], 0);
            }
            elseif ($item['effect_month'] < intval(Carbon::now()->format('m'))) {
                $table->updateStatus($item['kpi_note_id'], 1);
            }
        }
    }
}