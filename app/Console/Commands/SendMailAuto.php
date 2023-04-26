<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckMailJob;

class SendMailAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spa:send-mail-auto';

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
        CheckMailJob::dispatch('not_event', '', '');
    }
}
