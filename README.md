## Minecraft
An extreemly basic minecraft service container

## Supported Architectures

The container is based on openjdk:15-jdk-alpine and will likely compile for any of Alpines supported architectures, however, the only image that is compiled and tagged is the `linux/amd64`. Feel free to make a PR to expand the build process in Makefile. 

## Usage

The container is built to run a singe minecraft server instance and to be adminstrated using RCON. There are no screens, no supervisors or other "service managers" The image starts, grabs the jar file for the server you specified if a minecraft_server.jar doesn't already exist and runs. 

If you want to run Forge, Spigot or some other flavor you'll need to patch the minecraft_server.jar file outside of this image. Don't forget to update the minecraft_server.jar.sha1 as we do check it before launching to make sure things haven't been corrupted somehow.  

Here are some example snippets to help you get started creating a container. 

### docker

```
docker create \
  --name=minecraft \
  -v /path/to/minecraft/instance:/minecraft \
  -p 25565:25565
  -p 25575:25575
  indemnity83/minecraft
```

## Environment Variables

 - `MC_VERSION` - [default: latest] The Minecraft version to download if `JAR_FILE` does not exist
 - `JAVA_XMX` - [default: 1024m] Specifies the maximum memory allocation pool for the minecraft server
 - `JAVA_XMS` - [default: 1024m] Specifies the minitial memory allocation pool for the minecraft server
 - `JAVA_ARGS` - Additional java arguments to be passed on launch
 - `JAR_FILE` - [default: minecraft_server.jar] The name of the jar file to execute
 - `JAR_ARGS` - Additional minecraft arguments to be passed on launch

## Minecraft Startup

Minecraft requires that you accept their EULA before running the server. This means the first time you run the container with an empty volume, it will fail. Simply find the 'eula.txt' file that was created and mark your acceptance. You may want to take this opportunity to update the `server.properties` file too. Once you're ready, restart the container and your world should be created.

Once the container is running the easiest way to interact with it is using RCON. But you can also attach the container using `docker attach` if that is your style. 

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)
