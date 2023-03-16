#!/bin/bash

# show current configuration
symfony console debug:config api_platform
symfony console config:dump api_platform

# encode password
symfony console security:encode


# run test
symfony php bin/phpunit

# create db for test
symfony console doctrine:database:create --env=test

# create table for test
symfony console doctrine:schema:create --env=test
