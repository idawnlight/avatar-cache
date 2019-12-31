FROM metowolf/php:7.4.1

LABEL maintainer="idawnlight <idawn@live.com>"

ADD ./ /app

WORKDIR /app

RUN composer install --no-dev

EXPOSE 9501

CMD ["php", "index.php"]