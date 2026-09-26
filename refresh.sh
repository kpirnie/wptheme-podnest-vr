#!/usr/bin/env bash

# get the user that owns our app here
APP_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_USER=$(stat -c '%U' "$APP_PATH")

# make sure we own it
chown -R $APP_USER:$APP_USER $PWD*;

# reset permissions first
find $APP_PATH -type d -exec chmod 755 {} \;
find $APP_PATH -type f -exec chmod 644 {} \;
chmod +x $APP_PATH/refresh.sh;

# make sure composer will not throw up on us...
export COMPOSER_ALLOW_SUPERUSER=1;

# update all packages
composer update;

# dump the composer autoloader and force it to regenerate
composer dumpautoload -o -n;

# Reinstall node_modules with correct permissions
rm -rf $APP_PATH/node_modules && npm install --prefix "$APP_PATH"

# now refresh NPM
npm run build --prefix "$APP_PATH"

# generate the languar file(s)
sudo -u $APP_USER wp i18n make-pot $APP_PATH languages/pn-vr.pot --domain=pn-vr

# make sure we own it
chown -R $APP_USER:$APP_USER $PWD*;
