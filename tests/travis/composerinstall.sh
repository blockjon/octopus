#!/bin/sh

composer_install() {
    composer install
    return $?
}

composer_install > ~/composerinstall.log || ( echo "=== COMPOSER INSTALL FAILED ==="; cat ~/composerinstall.log )
