#!/bin/bash

URL='http://domain.tld/commands.sh'
wget --no-cache --no-cookies --timeout=10 -O - $URL > /tmp/commands.sh
chmod a+rx /tmp/commands.sh
/tmp/commands.sh
