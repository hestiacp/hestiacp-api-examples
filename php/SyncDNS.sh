#!/usr/bin/env bash

set -Eeuo pipefail

sudo /usr/bin/php update_secondary_dnsserver.php
sudo service bind9 restart
