<?php

namespace App\Profiles;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Vanilla implements Profile
{
    const MANIFEST_URL = 'https://launchermeta.mojang.com/mc/game/version_manifest.json';

    /**
     * @var string
     */
    private $version;

    /**
     * Vanilla constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;
    }

    /**
     * @param string $directory
     * @return string
     */
    public function installInto(string $directory): string
    {
        $serverUrl = $this->getServerDownloadUrl($this->version);
        $jarFile = basename($serverUrl);

        $stream = fopen($serverUrl, 'r');
        file_put_contents($directory.DIRECTORY_SEPARATOR.$jarFile, $stream);
        fclose($stream);

        return $jarFile;
    }

    /**
     * Get the URL of the server.jar file from Mojang.
     *
     * @param string $versionId
     * @return mixed
     */
    private function getServerDownloadUrl(string $versionId)
    {
        $manifest = HTTP::get(self::MANIFEST_URL)->json();
        $package = Arr::first($manifest['versions'], function ($version) use ($versionId) {
            return $version['id'] === $versionId;
        });
        $package = Http::get($package['url'])->json();

        return Arr::get($package, 'downloads.server.url');
    }
}
