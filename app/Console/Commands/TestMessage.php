<?php

namespace App\Console\Commands;

use App\MessageServiceProviders\PushyMessageService;
use App\MessageServiceProviders\TwilioMessageService;
use Illuminate\Console\Command;

class TestMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfergo:testMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test message';

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
     * @return int
     */
    public function handle()
    {
        $pushyDeviceToken = 'cdd92f4ce847efa5c7f';
        $messageSender = new TwilioMessageService();
        $rc = $messageSender->send('+385 91 433 8001', trans('transfergo.helloWorld'));
        dump ($rc);
    }
}
