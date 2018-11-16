# justorderit

## Description

A product database where you can order a product and use a cronjob to send an email with the orders to process.

Not ready for prime time. Use at your own risk.

![justorderit](https://user-images.githubusercontent.com/3043706/48621130-ddcd6780-e9a2-11e8-8ca6-f0a409732108.png)

## Installation

### Create MySQL database and a user

~~~bash
mysql -uroot -p
MySQL> create datbase justorderit;
MySQL> grant usage on *.* to justorderit@localhost identified by 'YOUR_PASSWORD';
# remove the \ before the * it's just here to prevent bad syntax highlighting
MySQL> grant all privileges on justorderit.\* to justorderit@localhost;
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

## Dev

Starting the dev server:

~~~bash
php bin/console server:run
~~~

Clearing the cache on the prod server after an update:

~~~bash
rm -rf var/cache/prod/
~~~
