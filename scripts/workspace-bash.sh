#!/bin/bash

cd "$(dirname "$0")"; cd ../../; # Makes sure we're in the right path for referring to docker commands.

if [ "$(expr substr $(uname -s) 1 5)" == "MINGW" ]; then
    winpty docker exec -it php-apache bash
else
    docker exec -it php-apache bash
fi
