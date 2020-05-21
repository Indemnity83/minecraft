<?php

namespace App\Profiles;

use App\Server;
use Illuminate\Support\Str;

class ProfileFactory
{
    /**
     * @param Server $server
     * @return Profile
     * @throws ProfileException
     */
    public function make(Server $server): Profile
    {
        $class = 'App\\Profiles\\'.Str::studly($server->profile);

        if (! class_exists($class)) {
            throw new ProfileException('Profile not found for '.$server->profile);
        }

        return new $class($server->version);
    }
}
