FROM ubuntu:16.04
MAINTAINER "artem@aleksashkin.com" Artem Aleksashkin

# BASE
RUN \
  DEBIAN_FRONTEND=noninteractive apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends\
      ca-certificates\
      apt-utils\
      apt-transport-https\
      locales\
      language-pack-ru-base\
      tzdata\
      cron\
      wget\
      && \
  usermod -u 1000 www-data && \
  groupmod -g 1000 www-data && \
  mkdir -p /var/www/html && \
  mkdir -p /var/www/data && \
  mkdir -p -m 777 /tmp/project && \
  chown -R www-data:www-data /var/www &&\
  echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen &&\
  echo "en_GB.UTF-8 UTF-8" >> /etc/locale.gen &&\
  echo "ru_RU.UTF-8 UTF-8" >> /etc/locale.gen &&\
  echo "ru_RU.CP1251 CP1251" >> /etc/locale.gen &&\
  DEBIAN_FRONTEND=noninteractive dpkg-reconfigure -f noninteractive locales &&\
  ln -snf /usr/share/zoneinfo/Europe/Moscow /etc/localtime &&\
  echo "Europe/Moscow" > /etc/timezone &&\
  DEBIAN_FRONTEND=noninteractive dpkg-reconfigure -f noninteractive tzdata &&\
  rm -rf /var/lib/apt/lists/*

ENV LANG  "ru_RU.UTF-8"
ENV LANGUAGE "ru_RU:ru"
ENV LC_MESSAGES "POSIX"
ENV TZ "Europe/Moscow"
WORKDIR /var/www/html

# PHP
RUN \
  DEBIAN_FRONTEND=noninteractive apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends\
      php7.0\
      php7.0-fpm\
      php7.0-curl\
      php7.0-mysql\
      php7.0-ldap\
      php7.0-xmlrpc\
      php7.0-mcrypt\
      php7.0-gd\
      php7.0-zip\
      php-pear\
      php-xdebug\
      php-bcmath\
      php-memcache\
      php-mbstring\
      php-mongodb\
      && \
      wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar && \
      chmod +x /usr/local/bin/composer && \
      wget -O /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-6.phar && \
      chmod +x /usr/local/bin/phpunit && \
      rm -rf /var/lib/apt/lists/*

# UTILS
RUN \
  DEBIAN_FRONTEND=noninteractive apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends\
      unzip\
      unrar-free\
      p7zip-full\
      mediainfo\
      curl\
      mercurial\
      git\
      && \
  rm -rf /var/lib/apt/lists/*

# MYSQL CLIENT
RUN \
  DEBIAN_FRONTEND=noninteractive apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends\
      mysql-client\
      && \
        rm -rf /var/lib/apt/lists/*

# SETTINGS
RUN \
  rm -f /etc/php/7.0/cli/php.ini && \
  rm -f /etc/php/7.0/fpm/php-fpm.conf && \
  rm -f /etc/php/7.0/fpm/php.ini && \
  rm -f /etc/php/7.0/fpm/conf.d/20-xdebug.ini && \
  rm -f /etc/php/7.0/cli/conf.d/20-xdebug.ini && \
  rm -f /etc/php/7.0/mods-available/xdebug.ini && \
  rm -f /etc/php/7.0/fpm/pool.d/www.conf

COPY . .

RUN \
  rm -rf vendor && \
  rm -rf .hg && \
  rm -f .hgignore && \
  rm -rf templates/modern/design/bower_components && \
  chown www-data:www-data -R .

# CODE & CRON & COMPOSER & BOWER
USER www-data
RUN \
  crontab crontab && \
  composer install

USER root

VOLUME ["/etc/php/7.0/fpm/php-fpm.conf", "/etc/php/7.0/fpm/php.ini", "/etc/php/7.0/fpm/pool.d/www.conf", "/etc/php/7.0/cli/php.ini", "/var/log/php-fpm", "/var/www/html", "/var/www/data", "/tmp/project"]

CMD ["/usr/sbin/php-fpm7.0", "-F"]

EXPOSE 9000
