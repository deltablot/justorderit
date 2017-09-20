# justorderit

## Description

A product database where you can order a product and use a cronjob to send an email with the orders to process.

## Installation

### Create MySQL database

~~~bash
mysql -uroot -p
MySQL> create datbase justorderit;
MySQL> exit;
~~~

~~~bash
git clone https://github.com/NicolasCARPi/justorderit/
# install composer
php composer.phar install --no-dev
php bin/console doctrine:schema:update --force
~~~

## Setting up the cronjob

~~~bash
curl https://justorderit/cron
~~~
