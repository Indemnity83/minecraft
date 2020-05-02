FROM openjdk:15-jdk-alpine
LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

ENV MC_VERSION=latest

ENV JAVA_XMX=1024m
ENV JAVA_XMS=1024m
ENV JAVA_ARGS=

ENV JAR_FILE=minecraft_server.jar
ENV JAR_ARGS=

# Install packages
RUN apk --no-cache add jq wget

COPY init.sh /bin/init
RUN mkdir -p /minecraft

VOLUME /minecraft
EXPOSE 25565
EXPOSE 25575

WORKDIR /minecraft
CMD /bin/init
