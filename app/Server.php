<?php

namespace App;

use App\Profiles\Profile;
use Facades\App\Profiles\ProfileFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property string slug
 * @property string directory
 * @property string status
 * @property string profile
 * @property string version
 * @property string jar_file
 */
class Server extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'version',
    ];

    /**
     * Get the full path of the servers file directory.
     *
     * @return string
     */
    public function getDirectoryAttribute()
    {
        $directory = implode([
            'servers',
            DIRECTORY_SEPARATOR,
            $this->slug,
        ]);

        return storage_path($directory);
    }

    /**
     * @return Profile
     */
    public function profile()
    {
        return ProfileFactory::make($this);
    }

    public function installProfile()
    {
        $this->jar_file = $this->profile()->installInto($this->directory);
        $this->save();
    }
}
