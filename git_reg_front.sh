#!/bin/bash

cd /var/www/reg_front && git pull origin master && git pull fetch && git checkout -f origin master && killall node && yarn install && yarn dev
