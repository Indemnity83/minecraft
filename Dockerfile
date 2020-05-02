FROM openjdk:15-jdk-alpine
LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

# Minecraft Version
ENV MC_VERSION=latest

# Java parameters
ENV JAVA_XMX=1024m
ENV JAVA_XMS=1024m
ENV JAVA_ARGS=

# Jar parameters
ENV JAR_FILE=minecraft_server.jar
ENV JAR_ARGS=

# User/Group IDs
ARG UID=99
ARG GID=100

# Install packages
RUN apk --no-cache add jq wget shadow

# Copy the init script into the container
COPY init.sh /bin/init

# Setup an unprivlidged user
RUN useradd -r -u $UID -o minecraft && \
    groupmod -g $GID -o minecraft

# Setup the minecraft volume
RUN mkdir -p /minecraft && \
    chown minecraft:minecraft /minecraft

# Run as unprivlidged user in default path
WORKDIR /minecraft
USER minecraft

# Expose the application surface
VOLUME /minecraft
EXPOSE 25565
EXPOSE 25575

# Start the server
CMD /bin/init
