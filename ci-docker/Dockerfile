FROM ubuntu:20.04
MAINTAINER "Gaurav Rao<gr19490@gmail.com>"

ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Kiev
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libpng-dev

RUN apt-get update && apt install -y nginx \
        vim \
        curl \
        git \
        openssl \
        iputils-ping \
        ufw\
        wget\
        libssl-dev \
        supervisor



#ARG DEBIAN_FRONTEND=noninteractive
RUN apt install  -y ca-certificates apt-transport-https software-properties-common
RUN  add-apt-repository ppa:ondrej/php

RUN apt install -y php8.0-cli php8.0-fpm php8.0-curl php8.0-gd php8.0-mysql php8.0-mbstring zip unzip

RUN apt-get install -y zip \
        unzip \
        php8.0-xdebug \
        php8.0-mysql php8.0-gd  php8.0-imap  php8.0-opcache php8.0-readline php8.0-xml


WORKDIR /var/www/html/ci/

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN chmod 755 /usr/bin/composer


RUN sed -i '120 a listen = 9000'  /etc/php/8.0/fpm/pool.d/www.conf


ADD after.sh /usr/local/bin/after.sh
RUN chmod 777 /usr/local/bin/after.sh
ENTRYPOINT /usr/local/bin/after.sh

RUN apt install -y procps






EXPOSE 80
EXPOSE 443

