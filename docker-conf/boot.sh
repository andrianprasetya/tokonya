#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}

# path project. see on Dockerfile 
path_project=/var/www/html

if [ "$role" = "app" ]; then

    if [[ ! -d $path_project/vendor ]]
    then
        cd $path_project
        printf "\nInstalling composer...\n"
        composer install

        printf "\nstarting execute composer dump-autoload...\n"
        composer dump-autoload

        printf "\nstarting execute php artisan config:clear...\n"
        php artisan config:clear

        printf "\nstarting execute php artisan cache:clear...\n"
        php artisan cache:clear

        printf "\nstarting execute php artisan route:clear...\n"
        php artisan route:clear 

        printf "\nsSet installed...\n"
    fi

    cd ~
    printf "\nstart apache2...\n"
    apache2ctl -D FOREGROUND

elif [ "$role" = "queue" ]; then

    echo "Executing queue..."
    sleep 60
    echo "Running the queue..."
    php $path_project/artisan queue:work --verbose --daemon
    printf "\nSuccess execute...\n"

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
