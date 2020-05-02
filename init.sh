#/bin/sh
java -version

if [ ! -f "minecraft_server.jar" ]; then
    ## Get mojang version manifest
    wget -q --show-progress -O version_manifest.json -r https://launchermeta.mojang.com/mc/game/version_manifest.json

    ## If version isn't set, we need to set it to the latest version
    if [ -z "${MC_VERSION}" ] || [ "$MC_VERSION" == "latest" ]; then
        MC_VERSION=`jq -r '.latest.release' version_manifest.json`
    fi

    ## Get the game version manifest
    wget -q --show-progress -O $MC_VERSION.json `jq -r '.versions | .[] | select(.id=="'${MC_VERSION}'") | .url' version_manifest.json`

    ## Download and check the minecraft server jar
    wget -q --show-progress -O $JAR_FILE `jq -r '.downloads.server.url' ${MC_VERSION}.json`

    ## Get the server hash file
    echo "`jq -r '.downloads.server.sha1' ${MC_VERSION}.json`  minecraft_server.jar" > $JAR_FILE.sha1
fi

## Check the minecraft server file is good
if [ -f $JAR_FILE.sha1 ]; then 
    sha1sum -c $JAR_FILE.sha1
fi

## Launch minecraft in a screen
java -Xmx$JAVA_XMX -Xms$JAVA_XMS $JAVA_ARGS -jar $JAR_FILE nogui $JAR_ARGS

