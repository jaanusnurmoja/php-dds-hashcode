#!/usr/bin/env bash

WORKING_DIRECTORY=`pwd`
LIB_VERSION=$(php -r 'include __DIR__."/vendor/autoload.php"; echo \SK\Digidoc\Digidoc::version();')
LIB_ARCHIVE=./example/web/dds-hashcode-${LIB_VERSION}.tar.gz

remove_vendor() {
  rm ./composer.lock
  rm -fr ./vendor
}
