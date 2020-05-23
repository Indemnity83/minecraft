<?php

namespace App\Console\Commands;

use App\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Mtdowling\Supervisor\EventListener;
use Mtdowling\Supervisor\EventNotification;

class SupervisorListen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supervisor:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proxy Supervisor events into the application';

    /**
     * @var EventListener
     */
    private $listener;

    /**
     * Create a new command instance.
     *
     * @param EventListener $listener
     */
    public function __construct(EventListener $listener)
    {
        parent::__construct();

        $this->listener = $listener;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->listener->listen(function (EventListener $listener, EventNotification $event) {
            $processName = $event->getData('processname');
            $eventName = $event->getData('eventname');

            /** @var Server|null $server */
            $server = Server::where('slug', $processName)->first();

            if ($server) {
                $server->status = Str::of($eventName)->lower()->after('process_state_');
                $server->save();
            }

            return true;
        });
    }
}
