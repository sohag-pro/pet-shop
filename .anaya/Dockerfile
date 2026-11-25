# .anaya/Dockerfile — Anaya campaign environment (generated from the repo's stack).
# PHP 8.3 · Node 22 · extensions: bcmath gd intl mbstring pcntl pdo_mysql pdo_sqlite zip
FROM docker.io/library/php:8.3-cli-bookworm

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/download/2.13.2/install-php-extensions /usr/local/bin/install-php-extensions

RUN apt-get update \
 && apt-get install -y --no-install-recommends ca-certificates curl git unzip xz-utils \
 && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions bcmath gd intl mbstring pcntl pdo_mysql pdo_sqlite zip

COPY --from=docker.io/library/composer:2 /usr/bin/composer /usr/bin/composer

# Official Node.js binary for the 22.x line, checksum-verified against SHASUMS256.txt.
RUN set -eux; \
    base="https://nodejs.org/dist/latest-v22.x"; \
    curl -fsSL "$base/SHASUMS256.txt" -o /tmp/SHASUMS256.txt; \
    file="$(grep -oE 'node-v[0-9.]+-linux-x64\.tar\.xz' /tmp/SHASUMS256.txt | head -n1)"; \
    curl -fsSL "$base/$file" -o "/tmp/$file"; \
    (cd /tmp && grep " $file\$" SHASUMS256.txt | sha256sum -c -); \
    tar -xJf "/tmp/$file" -C /usr/local --strip-components=1 --no-same-owner; \
    rm -f "/tmp/$file" /tmp/SHASUMS256.txt; \
    node --version; npm --version

RUN useradd --create-home --uid 1000 --shell /bin/bash anaya

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer \
    COMPOSER_NO_INTERACTION=1

WORKDIR /work
CMD ["sleep", "infinity"]
