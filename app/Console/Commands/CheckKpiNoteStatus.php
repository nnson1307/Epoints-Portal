<?php

namespace App\Console\Commands;

use App\Jobs\CheckKpiNoteStatusJob;
use Illuminate\Console\Command;

class CheckKpiNoteStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epoint:check-kpi-note-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quét bảng kpi_note tìm phiếu giao có thời gian tạo bằng hiện tại thì đổi status';

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
        dispatch(new CheckKpiNoteStatusJob());
    }
}