<?php

namespace App\Profiles;

interface Profile
{
    /**
     * Profile constructor.
     *
     * @param string $version the profile version
     */
    public function __construct(string $version);

    /**
     * Install profile into the given directory.
     *
     * @param string $directory
     * @return string
     */
    public function installInto(string $directory): string;
}
