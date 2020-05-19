#/bin/sh

java -version
nginx -v
php --version

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/servers/default

touch storage/database.sqlite

# Generate a random app key if one isn't set
if [ -z "$APP_KEY" ]
then
    php artisan key:generate --force
fi

# Generate a random websocket secret if one isn't set
if [ -z "$PUSHER_APP_SECRET" ]
then
    export PUSHER_APP_SECRET=$(date | md5sum)
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

cd storage/servers/default

if [ ! -f "minecraft_server.jar" ]; then
    ## Get Mojang's version manifest
    wget -q --show-progress -O version_manifest.json -r https://launchermeta.mojang.com/mc/game/version_manifest.json

    ## If version isn't set, or is set to 'latest' we need to set it to the actual version number
    if [ -z "${MC_VERSION}" ] || [ "$MC_VERSION" == "latest" ]; then
        MC_VERSION=`jq -r '.latest.release' version_manifest.json`
    fi

    ## Get the game version manifest
    wget -q --show-progress -O $MC_VERSION.json `jq -r '.versions | .[] | select(.id=="'${MC_VERSION}'") | .url' version_manifest.json`

    ## Download and check the minecraft server jar
    wget -q --show-progress -O $JAR_FILE `jq -r '.downloads.server.url' ${MC_VERSION}.json`

    ## Get the hash
    echo "`jq -r '.downloads.server.sha1' ${MC_VERSION}.json`  minecraft_server.jar" > $JAR_FILE.sha1
fi

## Verify the jar file if we can
if [ -f $JAR_FILE.sha1 ]; then
    sha1sum -c $JAR_FILE.sha1
fi
