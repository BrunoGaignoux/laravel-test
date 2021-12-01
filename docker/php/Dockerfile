FROM php:8.0.8-fpm-alpine

WORKDIR /app
COPY . .
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli pdo pdo_mysql
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
COPY ./docker/php/wait-for /usr/local/bin/wait-for
RUN chmod +x /usr/local/bin/wait-for
COPY composer.json composer.lock .
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs
COPY ./docker/php/entrypoint.sh /

ENTRYPOINT ["/entrypoint.sh"]
