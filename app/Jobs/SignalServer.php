<?php

namespace App\Jobs;

use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class SignalServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Server
     */
    public $server;

    public $signal;

    /**
     * Create a new job instance.
     *
     * @param Server $server
     * @param $signal
     * @throws \Exception
     */
    public function __construct(Server $server, $signal)
    {
        if (! in_array($signal, ['start', 'stop', 'restart'])) {
            throw new InvalidParameterException("$signal is not a valid signal");
        }

        $this->server = $server;
        $this->signal = $signal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        exec("supervisorctl {$this->signal} {$this->server->slug}");
    }
}
