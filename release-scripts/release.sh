#!/usr/bin/env bash

# Make sure ./vagrant-composer.sh is installed
if ! [ -x "$(command -v ./vagrant-composer.sh)" ]; then
  echo './vagrant-composer.sh is not installed. Install it from http://getcomposr.org' >&2
  exit 1
fi

# Clean up uploads
rm -fr ${WORKING_DIRECTORY}/example/app/upload/*

# Clean up vendor
remove_vendor

# Install ./vagrant-composer.sh dependencies
./vagrant-composer.sh install

# Generate documentation with phpDocumentor version 2.8.2
rm -r ${WORKING_DIRECTORY}/docs/api
${WORKING_DIRECTORY}/vendor/bin/phpdoc --template=responsive

rm -r ${WORKING_DIRECTORY}/docs/parser

# Now we need to remove vendor directory because shitty ./vagrant-composer.sh
# has some bugs it needs to work out before we can use ./vagrant-composer.sh update --no-dev
remove_vendor

# Make sure ve are running latest version of ./vagrant-composer.sh
./vagrant-composer.sh self-update

# Remove dev dependencies
./vagrant-composer.sh install --no-dev

# Build release tar
rm -v ${WORKING_DIRECTORY}/example/web/dds-hashcode*.tar.gz

LIB_ARCH_EXLCUDES="--exclude=example/web/dds-hashcode*.tar.gz --exclude=hashcode-lib/tests --exclude=hashcode-lib/phpdoc.xml"
tar ${LIB_ARCH_EXLCUDES} -czvf ${LIB_ARCHIVE} hashcode-lib example vendor ./vagrant-composer.sh.json bower.json VagrantFile server-config ansible

# Clean up repository after build
# hg addremove
