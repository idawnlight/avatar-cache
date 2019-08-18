FROM metowolf/php:7.3.8

LABEL maintainer="idawnlight <idawn@live.com>"

RUN cd /\
    && wget https://github.com/idawnlight/avatar-cache/archive/master.zip\
    && unzip master.zip\
    && rm -f master.zip\
    && mv avatar-cache-master/ www/\
    && cd /www\
    && composer install

WORKDIR /www

EXPOSE 9501

CMD ["php", "index.php"]