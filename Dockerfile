FROM idawnlight/php:8.0.3

LABEL maintainer="idawnlight <idawn@live.com>"

ADD ./ /app

WORKDIR /app

RUN cp config.example.php config.php \
  && composer install --prefer-dist --no-progress --optimize-autoloader

EXPOSE 9501

CMD ["php", "index.php"]