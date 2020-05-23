<?php

namespace App;

use App\Events\ServerCreated;
use App\Events\ServerUpdated;
use App\Profiles\Profile;
use Facades\App\Profiles\ProfileFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property string slug
 * @property string directory
 * @property string status
 * @property string profile
 * @property string version
 * @property string jar_file
 * @property bool eula
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'eula',
    ];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ServerCreated::class,
        'updated' => ServerUpdated::class,
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

    public function getEulaAttribute()
    {
        $file = $this->directory.DIRECTORY_SEPARATOR.'eula.txt';

        if (! file_exists($file)) {
            return false;
        }

        preg_match('/eula=(.*)/', file_get_contents($file), $agreement);

        return Str::lower(Arr::get($agreement, 1)) === 'true';
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
