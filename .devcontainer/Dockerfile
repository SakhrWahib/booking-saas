FROM mcr.microsoft.com/devcontainers/php:1-8.3-bookworm

# مصدر Yarn في الصورة مفتاحه منتهي ويُفشل apt-get update، فنحذفه
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip bcmath \
    && rm -rf /var/lib/apt/lists/*
