#!/bin/bash
composer install --no-interaction --prefer-dist
php -S 0.0.0.0:$PORT -t public