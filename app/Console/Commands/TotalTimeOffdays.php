<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateTotalTimeOffdays;

class TotalTimeOffdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timeoffdays:update-total-time-off-days';

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
        UpdateTotalTimeOffdays::dispatch();
    }
}
