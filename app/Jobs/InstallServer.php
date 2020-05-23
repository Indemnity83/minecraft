<?php

namespace App\Jobs;

use App\Profiles\Vanilla;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InstallServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Server
     */
    public $server;

    /**
     * Create a new job instance.
     *
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        mkdir($this->server->directory);

        $this->server->installProfile();
        $this->writeSupervisorConfig();

        $this->server->status = 'installed';
        $this->server->save();

        exec('supervisorctl update', $output);
    }

    /**
     * Generate the supervisor configuration file.
     *
     * @throws \Throwable
     */
    private function writeSupervisorConfig()
    {
        Storage::disk('supervisor')->put(
            "{$this->server->slug}.conf",
            view('supervisor.config', ['server' => $this->server->fresh()])->render()
        );
    }

    private function downloadServerJarfile()
    {
        $profile = new Vanilla();
        $profile->deployVersionInto($this->server->version, $this->server->directory);
    }
}
