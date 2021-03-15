<?php

namespace App\Console\Commands;

use App\MessageServiceProviders\DefaultMessageService;
use App\MessageServiceProviders\PriorityMessageService;
use App\MessageServiceProviders\PushyMessageService;
use App\MessageServiceProviders\TwilioMessageService;
use Illuminate\Console\Command;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfergo:sendMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test message using the default client + fallback';

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
        // Attempt to send using default message service
        try {
            $messageSender = new DefaultMessageService();
            $rc = $messageSender->send('+385 91 433 8001', trans('transfergo.helloWorld'));

            // Check for success -> exit if successful
            if ($rc) {
                dump($rc);
                return 0;
            }
        } catch (\Exception $e) { }

        // Previous send failed -> attempt using priority based approach
        // Priorities are kept track in a static variable in the PriorityMessageService class
        $maxTries = 3;
        $count    = 0;
        do {
            try {
                $messageSender = new PriorityMessageService();
                $rc = $messageSender->send('+385 91 433 8001', trans('transfergo.helloWorld'));
            } catch (\Exception $e) { }
        } while (!$rc && $count++ < $maxTries);
    }
}
