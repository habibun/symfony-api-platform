#!/bin/bash

# show current configuration
symfony console debug:config api_platform
symfony console config:dump api_platform

# encode password
symfony console security:encode

