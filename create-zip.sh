#!/bin/env bash

#####################################
#  Create a zip which can be installed via the dashboard.
#
#  ###########
#  # Usage.
#
# ./create-zip.sh
#       Zip all files in the plugin directory in a zip in the plugin directory
#
# ./create-zip.sh ~/Downloads
#       Zip all files in ~/Downloads/PLUGINSLUG.ZIP
#
# ./create-zip.sh ~/Downloads/specific.zip
#       Zip all files in ~/Downloads/specific.ZIP
#
#####################################

# Directory to self https://stackoverflow.com/a/246128/933065
PLUGIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
PLUGIN_SLUG=$(basename $PLUGIN_DIR)
PLUGIN_PHP_PATH="${PLUGIN_DIR}/${PLUGIN_SLUG}.php"

# Colors.
COLOR_RED='\033[0;31m'
COLOR_GREEN='\033[0;32m'
COLOR_ORANGE='\033[0;33m'
COLOR_OFF='\033[0m'

# Zip details defaults.
ZIP_FILE_NAME="${PLUGIN_SLUG}.zip"
PLUGIN_ZIP_PATH="${PLUGIN_DIR}/${ZIP_FILE_NAME}"
if [[ ! -z "${1}" ]]; then
	# Override.
	if [[ "${1}" == *.zip ]]; then
		ZIP_FILE_NAME=$(basename "${1}")
		PLUGIN_ZIP_PATH="${1}"
	else
		PLUGIN_ZIP_PATH="${1%/}/${ZIP_FILE_NAME}"
	fi
fi

# Check if the plugin main file exists.
# Main plugin file should be the same as the directory name.
if [[ ! -f ${PLUGIN_PHP_PATH} ]]; then
	printf  "${COLOR_RED}ERROR:${COLOR_OFF} No file with name ${COLOR_GREEN}${PLUGIN_SLUG}${COLOR_OFF}.php exists in ${COLOR_GREEN}${PLUGIN_DIR}${COLOR_OFF}\n"
	exit 1; # error
fi
VERSION=$(grep  'Version:.*' ${PLUGIN_PHP_PATH} | sed -E "s/.* ([.0-9])/\\1/")

# Info
printf  "Creating zip: ${COLOR_GREEN}${ZIP_FILE_NAME}${COLOR_OFF} in ${COLOR_GREEN}${PLUGIN_ZIP_PATH%${ZIP_FILE_NAME}}${COLOR_OFF}\n"
printf  "Version: ${COLOR_GREEN}${VERSION}${COLOR_OFF}\n"

# remove existing zip file (if exists)
if [[ -f "${PLUGIN_ZIP_PATH}" ]]; then
	rm ${PLUGIN_ZIP_PATH}
	printf "Removing existing ${COLOR_ORANGE}${PLUGIN_ZIP_PATH}${COLOR_OFF}\n"
fi

# Build
if [[ -f ./composer.lock ]]; then
	cd ${PLUGIN_DIR}/
	printf "Installing ${COLOR_ORANGE}composer${COLOR_OFF} packages (no-dev)\n"
	composer install --quiet --no-dev
fi
if [[ -f ./package.json ]]; then
	cd ${PLUGIN_DIR}/
	printf "Building ${COLOR_ORANGE}npm${COLOR_OFF} packages\n"
	npm install
	npm run build
fi

# Go one dir above the plugin DIR, only way to set the correct root for ZIP
cd ${PLUGIN_DIR}/../
# Finally zip it up.
zip -r "${PLUGIN_ZIP_PATH}" ./${PLUGIN_SLUG} -x \
${PLUGIN_SLUG}/.wordpress.org \
${PLUGIN_SLUG}/node_modules\* \
${PLUGIN_SLUG}/.git\* \
${PLUGIN_SLUG}/vendor\* \
${PLUGIN_SLUG}/package.json \
${PLUGIN_SLUG}/package-lock.json \
${PLUGIN_SLUG}/composer.json \
${PLUGIN_SLUG}/composer.lock \
${PLUGIN_SLUG}/.distignore* \
${PLUGIN_SLUG}/.editorconfig* \
${PLUGIN_SLUG}/.gitignore* \
${PLUGIN_SLUG}/.idea\* \
${PLUGIN_SLUG}/.phpcs.xml.dist \
${PLUGIN_SLUG}/README.md \
${PLUGIN_SLUG}/readme.md \
${PLUGIN_SLUG}/create-zip.sh \
${PLUGIN_SLUG}/docs\* \
${PLUGIN_SLUG}/.wordpress-org\* \

# restore composer to development state.
if [[ -f ./composer.lock ]]; then
	cd ${PLUGIN_DIR}/
	printf "restoring ${COLOR_ORANGE}composer${COLOR_OFF} development packages\n"
	composer install --quiet --dev
fi
