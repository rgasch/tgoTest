<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;

class SentMessages extends Command
{
    private $pagesize = 10;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfergo:sentMessages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show sent messages';

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
        $messages = Message::getLastMessages($this->pagesize);
        dump ($messages->toArray());
    }
}
