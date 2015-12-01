#!/usr/bin/env bash

# Package for SK

PACKAGE_NAME=checkout.tar
DROPBOX_OUTPUT_DIRECTORY=${HOME}/Dropbox/tarne
EXCLUDE_DIRS="--exclude=node_modules --exclude=bower_components --exclude=.idea --exclude=.vagrant --exclude=vendor"

tar ${EXCLUDE_DIRS} -zcvf ${DROPBOX_OUTPUT_DIRECTORY}/${PACKAGE_NAME} .
cp ${LIB_ARCHIVE} ${DROPBOX_OUTPUT_DIRECTORY}/
