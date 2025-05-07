#!/usr/bin/env bash

set -Eeuo pipefail

sudo /usr/bin/php update_secondary_dnsserver.php
sudo service bind9 restart

# Tested on a 2 doller per month IONOS VM running Ubuntu with bind9, php-cli, php-curl installed
# akutra.tm@leapmaker.com for more information