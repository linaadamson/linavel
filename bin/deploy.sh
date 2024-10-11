#!/usr/bin/env bash

APP_NAME=linavel
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
ROOT="$(dirname "${SCRIPT_DIR}")"

cd "${ROOT}"

branch=$1
if [ -z "$branch" ]; then
    branch=main
fi
git pull origin $branch

chown -R www-data:www-data .

cp -f bin/apache.conf /etc/apache2/sites-available/${APP_NAME}.conf
a2ensite ${APP_NAME}
service apache2 restart
