# Image
FROM php:7.2-cli

# Starting from scratch
RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Dependencies
RUN apt-get update

# Supervisor
RUN apt-get install -y supervisor
COPY supervisord.conf /etc/supervisor/supervisord.conf

# Zip
RUN apt-get install -y zlib1g-dev && docker-php-ext-install zip

# Curl
RUN apt-get install -y libcurl3-dev curl && docker-php-ext-install curl

# MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crontab
RUN apt-get update && apt-get install -y cron
ADD ./cron.d/schedule /etc/cron.d
RUN chmod -R 644 /etc/cron.d
RUN touch /var/log/cron.log
RUN crontab /etc/cron.d/schedule

# Redis
RUN pecl install redis && docker-php-ext-enable redis

#--------------------------------------------------------------------------
# Clean up
#--------------------------------------------------------------------------

RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set up default directory
WORKDIR /etc/supervisor/conf.d/

# Run after container is up
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
