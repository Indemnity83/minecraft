<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class MinecraftDownloadCommand extends Command
{
    const MOJANG_MANIFEST = 'https://launchermeta.mojang.com/mc/game/version_manifest.json';
    const SERVER_JAR_PATH = 'default/minecraft_server.jar';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minecraft:download
                    {version=latest : The minecraft server version to download}
                    {--force : Force the operation to overwrite an existing server file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the vanilla Minecraft server files.';

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
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     */
    public function handle()
    {
        if ($this->serverFileExists() && (! $this->shouldUseForce())) {
            $this->comment('Server already exists.');

            return 0;
        }

        $manifest = $this->getManifestCollection();
        $versionId = $this->getVersionId($manifest);

        $package = $manifest->get('versions')->firstWhere('id', $versionId);

        if ($package === null) {
            $this->error("No version id matching {$versionId} found.");

            return 1;
        }

        $packageManifest = Http::get($package->get('url'))->json();

        $this->info("Downloading the Minecraft Java Edition {$versionId} server file");
        $this->downloadServerFile(Arr::get($packageManifest, 'downloads.server.url'));
    }

    /**
     * Get the Mojang version manifest as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getManifestCollection()
    {
        // TODO: Handle errors while trying to load the manifest file.
        return collect(HTTP::get(self::MOJANG_MANIFEST)->json())->recursive();
    }

    /**
     * Get the version id being requested.
     *
     * If the argument is 'latest', the latest version
     * is parsed from the manifest file.
     *
     * @return string
     */
    private function getVersionId($manifest)
    {
        if ($this->argument('version') === 'latest') {
            return Arr::get($manifest, 'latest.release');
        }

        return $this->argument('version');
    }

    /**
     * @return bool
     */
    private function serverFileExists()
    {
        return Storage::disk('servers')->exists(self::SERVER_JAR_PATH);
    }

    /**
     * Clear the way to download a new file if requested.
     *
     * @return bool
     */
    private function shouldUseForce()
    {
        if ($this->hasOption('force') && $this->option('force')) {
            return Storage::disk('servers')->delete(self::SERVER_JAR_PATH);
        }

        return false;
    }

    /**
     * Download the file showing progress.
     *
     * @param $url
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     */
    private function downloadServerFile($url)
    {
        $progressBar = $this->output->createProgressBar();

        $stream = fopen($url, 'r', null, $this->streamProgress($progressBar));

        Storage::disk('servers')->writeStream(self::SERVER_JAR_PATH, $stream);

        fclose($stream);

        $progressBar->finish();
        $this->output->newLine(1);
    }

    /**
     * Setup a stream context for downloading files.
     *
     * @param \Symfony\Component\Console\Helper\ProgressBar $progressBar
     * @return resource
     */
    private function streamProgress($progressBar)
    {
        ProgressBar::setFormatDefinition('normal', ' %current_bytes%/%max_bytes% [%bar%] %percent:3s%%');
        ProgressBar::setFormatDefinition('verbose', ' %current_bytes%/%max_bytes% [%bar%] %percent:3s%% %estimated:-6s%');
        ProgressBar::setFormatDefinition('very_verbose', ' %current_bytes%/%max_bytes% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%');
        ProgressBar::setFormatDefinition('debug', ' %current_bytes%/%max_bytes% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');

        $progressBar->setMessage(0, 'max_bytes');
        $progressBar->setMessage(0, 'current_bytes');

        return stream_context_create([], [
            'notification' => function ($notification_code, $severity, $message, $message_code, $bytes_transferd, $bytes_max) use ($progressBar) {
                switch ($notification_code) {
                    case STREAM_NOTIFY_FILE_SIZE_IS:
                        $progressBar->setMessage($this->formatBytes($bytes_max), 'max_bytes');
                        $progressBar->start($bytes_max);
                        break;
                    case STREAM_NOTIFY_PROGRESS:
                        $progressBar->setMessage($this->formatBytes($bytes_transferd), 'current_bytes');
                        $progressBar->setProgress($bytes_transferd);
                        break;
                    case STREAM_NOTIFY_COMPLETED:
                        $progressBar->finish();
                }
            },
        ]);
    }

    /**
     * Format an byte count integer into appropriate units.
     *
     * @param $bytes
     * @param int $precision
     * @return string
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['b', 'kb', 'mb', 'gb', 'tb'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}
