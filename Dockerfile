FROM metowolf/php:7.3.8

LABEL maintainer="idawnlight <idawn@live.com>"

ADD ./ /www

WORKDIR /www

RUN composer install

EXPOSE 9501

CMD ["php", "index.php"]