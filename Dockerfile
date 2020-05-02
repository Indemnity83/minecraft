FROM openjdk:8-jdk-alpine
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
RUN apk --no-cache add jq wget shadow nodejs npm supervisor

# Copy the init script into the container
COPY init.sh /bin/init
COPY supervisord.conf /etc/supervisord.conf

# Setup an unprivlidged user
RUN useradd -r -u $UID -o minecraft && \
    groupmod -g $GID -o minecraft

# Deploy rcon web admin
WORKDIR /app
COPY rcon-web-admin/ /app
RUN npm install && \
    node src/main.js install-core-widgets

# Setup folders and permissions
RUN mkdir -p /minecraft && \
    mkdir -p /app && \
    chown -R minecraft:minecraft /run && \
    chown -R minecraft:minecraft /minecraft && \
    chown -R minecraft:minecraft /app

# Run as unprivlidged user in default path
WORKDIR /minecraft
USER minecraft

# Expose the application surface
VOLUME /minecraft
EXPOSE 25565
EXPOSE 25575

# Start the server
CMD ["sh", "-c", "/bin/init && /usr/bin/supervisord"]
