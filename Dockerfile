FROM idawnlight/php:7.4.13

LABEL maintainer="idawnlight <idawn@live.com>"

ADD ./ /app

WORKDIR /app

RUN composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader

EXPOSE 9501

CMD ["php", "index.php"]