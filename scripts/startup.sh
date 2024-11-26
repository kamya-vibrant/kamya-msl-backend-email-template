#!/bin/bash

cd "$(dirname "$0")"; cd ../; # Makes sure we're in the right path for referring to docker commands.

# To get folder name tip from https://stackoverflow.com/a/17072017/687274
docker-compose --project-name msl -f docker-compose.yml up -d

echo "";
echo "Finally: running a composer install";
if [ "$(expr substr $(uname -s) 1 5)" == "MINGW" ]; then
    //winpty docker exec -it php-apache composer install
else
    //docker exec -it php-apache composer install
fi
