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
php artisan minecraft:download "$MC_VERSION"
