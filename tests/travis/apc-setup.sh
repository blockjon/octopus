#!/bin/sh

install_apc() {
    if [ "$(expr "$TRAVIS_PHP_VERSION" ">=" "5.5")" -eq 1 ]; then

	echo "extension = apc.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
	echo "apc.enable_cli=on" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini

    fi

    return $?
}



install_apc > ~/apc.log || ( echo "=== APC BUILD FAILED ==="; cat ~/apc.log )

