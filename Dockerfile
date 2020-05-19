FROM node:latest as build-assets
WORKDIR /app
COPY package*.json tailwind.config.js webpack.mix.js ./
RUN npm install
COPY resources/js ./resources/js
COPY resources/css ./resources/css
COPY .env.docker .env
RUN npm run production

FROM composer:latest as build-vendor
WORKDIR /app
COPY composer.* ./
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader --no-cache

FROM openjdk:8-jdk-alpine
LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

# Container Version
ARG VERSION
ENV APP_VERSION=$VERSION

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
RUN apk --no-cache add jq wget shadow supervisor git composer nginx \
    php php7-fpm php7-json php7-mbstring php7-iconv php7-pcntl php7-posix php7-sodium \
    php7-session php7-xml php7-curl php7-fileinfo php7-gd php7-intl php7-zip \
    php7-simplexml php7-pdo php7-sqlite3 php7-pdo_sqlite php7-exif php7-pdo_mysql \
    php7-pdo_pgsql php7-pdo_odbc php7-dom php7-xmlwriter php7-tokenizer

# Copy the init script into the container
COPY docker/rootfs /

# Setup an unprivlidged user
RUN useradd -r -u $UID -o minecraft && \
    groupmod -g $GID -o minecraft

# Make sure files/folders needed by the processes are accessable when they run under the minecraft user
RUN chown -R minecraft.minecraft /run && \
    chown -R minecraft.minecraft /var/log/php7 && \
    chown -R minecraft.minecraft /var/log/nginx && \
    chown -R minecraft.minecraft /var/lib/nginx

# Publish data volume (linked to application storage)
RUN ln -s /app/storage /data
VOLUME /data

# Deploy web admin
COPY --chown=minecraft:minecraft . /app
COPY --chown=minecraft:minecraft --from=build-vendor /app/vendor /app/vendor
COPY --chown=minecraft:minecraft --from=build-assets /app/public /app/public
COPY --chown=minecraft:minecraft .env.docker /app/.env

# Run as unprivlidged user in default path
USER minecraft
WORKDIR /app

# Finish composer
RUN composer dump-autoload --quiet

EXPOSE 8888
EXPOSE 6001
EXPOSE 25565

# Start the server
CMD ["sh", "-c", "/bin/init.sh && /usr/bin/supervisord"]

# Configure a healthcheck to validate that everything is up & running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
