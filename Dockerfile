FROM idawnlight/php:7.4.13

LABEL maintainer="idawnlight <idawn@live.com>"

ADD ./ /app

WORKDIR /app

RUN composer install --no-dev

EXPOSE 9501

CMD ["php", "index.php"]