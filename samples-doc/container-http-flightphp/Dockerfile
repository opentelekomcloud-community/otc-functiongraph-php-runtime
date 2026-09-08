# see: https://hub.docker.com/_/composer
FROM composer:2@sha256:4d71c3c2109c61d5415544264b59ad4087e4c5b7244481723664138fd36d5040 AS composer

# see: https://hub.docker.com/_/php/tags?name=alpine
FROM php:8.2.33-zts-alpine3.23@sha256:58b967d06f56a75d652162f48205bb03d2302f18322bcdbc24ffe17c749cd322

ARG DOCKER_BUILD_TIMESTAMP=unknown
ENV DOCKER_BUILD_TIMESTAMP=${DOCKER_BUILD_TIMESTAMP}

ENV HOME=/home/paas_user \
    GROUP_ID=1003 \
    GROUP_NAME=paas_user \
    USER_ID=1003 \
    USER_NAME=paas_user \
    TZ=Etc/UTC

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN apk add --no-cache tzdata && \
    mkdir -p ${HOME} && \
    # add group with specific GID
    addgroup -g ${GROUP_ID} ${GROUP_NAME} && \
    # add user with specific UID and GID
    adduser -u ${USER_ID} -G ${GROUP_NAME} -D ${USER_NAME}

WORKDIR ${HOME}

# Copy composer.json and source code
COPY composer.json ${HOME}
COPY src ${HOME}/src

# Install dependencies
RUN cd ${HOME} && \
    composer install --no-dev --no-interaction --optimize-autoloader

# Copy entrypoint script
COPY ./entrypoint.sh ${HOME}/entrypoint.sh

# adjust permissions
RUN chown -R ${USER_ID}:${GROUP_ID} ${HOME} && \ 
    chmod -R 550 ${HOME}

EXPOSE 8000

# switch to non root user
USER ${USER_NAME}

ENTRYPOINT ["sh", "/home/paas_user/entrypoint.sh"]
