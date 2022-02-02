#!/usr/bin/env bash

# Directory to self https://stackoverflow.com/a/246128/933065
PLUGIN_DIR="$( cd "$( dirname $( dirname "${BASH_SOURCE[0]}" ) )" &> /dev/null && pwd )"
PLUGIN_SLUG=$(basename $PLUGIN_DIR)
PLUGIN_PHP_PATH="${PLUGIN_DIR}/${PLUGIN_SLUG}.php"

# Zip details defaults.
ZIP_FILE_NAME="${PLUGIN_SLUG}.zip"
ZIP_FILE_APPEND_VERSION=false
PLUGIN_ZIP_DIR="${PLUGIN_DIR%/}/"

# Colors.
COLOR_RED='\033[0;31m'
COLOR_GREEN='\033[0;32m'
COLOR_ORANGE='\033[0;33m'
COLOR_OFF='\033[0m'

function display_help_mssg {
	echo "Create a zip file of the plugin which can be installed via the dashboard."
	echo ""
	echo  '-h, --help     Print help this message'
	echo  '-o, --output   Specify output directory or file'
	echo  '-a, --append   Append the plguin version number in the zip file name.'
	echo ""
	printf "  ${COLOR_ORANGE}./create-zip.sh ${COLOR_OFF}\n"
	echo "      Most basic use, create a zip of the plugin inside the plugin directory."
	echo "      wp-content/plugins/PLUGINSLUG/PLUGINSLUG.zip"
	echo ""
	printf "  ${COLOR_ORANGE}./create-zip.sh -o ~/Downloads ${COLOR_OFF} specify output dir\n"
	echo "      ~/Downloads/PLUGINSLUG.zip"
	echo ""
	printf "  ${COLOR_ORANGE}./create-zip.sh -o ~/Downloads/specific.zip ${COLOR_OFF} specify dir and zip name\n"
	echo "      ~/Downloads/specific.zip"
	echo ""
	printf "  ${COLOR_ORANGE}./create-zip.sh -a ${COLOR_OFF} append plugin version number to zip\n"
	echo "      wp-content/plugins/PLUGINSLUG/PLUGINSLUG-1.0.3.zip"
	echo ""
	printf "  ${COLOR_ORANGE}./create-zip.sh -a -o ~/Downloads/specific.zip ${COLOR_OFF} everything\n"
	echo "      ~/Downloads/specific-1.0.3.zip"
	echo ""
}

while test $# -gt 0; do
  case "$1" in
    -h|--help)
		display_help_mssg
		exit 0
		;;
    -o|--output)
      shift
      if test $# -gt 0; then
      	if [[ "${1}" == *.zip ]]; then
        		ZIP_FILE_NAME=$(basename "${1}")
        		PLUGIN_ZIP_DIR="${1%${ZIP_FILE_NAME}}" # set path without zip file name
        	else
        		PLUGIN_ZIP_DIR="${1}"
        	fi
        	PLUGIN_ZIP_DIR="${PLUGIN_ZIP_DIR%/}/"
      else
        echo "no output directory specified"
        exit 1
      fi
      shift
      ;;
    -a|--append)
    	ZIP_FILE_APPEND_VERSION=true;
    	shift
		;;
    *)
      break
      ;;
  esac
done

# Check if the plugin main file exists.
# Main plugin file should be the same as the directory name.
if [[ ! -f ${PLUGIN_PHP_PATH} ]]; then
	printf  "${COLOR_RED}ERROR:${COLOR_OFF} No file with name ${COLOR_GREEN}${PLUGIN_SLUG}${COLOR_OFF}.php exists in ${COLOR_GREEN}${PLUGIN_DIR}${COLOR_OFF}\n"
	exit 1; # error
fi
VERSION=$(grep  'Version:.*' ${PLUGIN_PHP_PATH} | sed -E "s/.* ([.0-9])/\\1/")
if [[ $ZIP_FILE_APPEND_VERSION = true ]]; then
	#new zip name.
	ZIP_FILE_NAME="${ZIP_FILE_NAME%.zip}-${VERSION}.zip"
fi
PLUGIN_ZIP_PATH="${PLUGIN_ZIP_DIR}${ZIP_FILE_NAME}"

# remove existing zip file (if exists)
if [[ -f "${PLUGIN_ZIP_PATH}" ]]; then
	rm ${PLUGIN_ZIP_PATH}
	printf "Removing existing ${COLOR_ORANGE}${PLUGIN_ZIP_PATH}${COLOR_OFF}\n"
fi

# Build
if [[ -f ${PLUGIN_DIR}/composer.lock ]]; then
	cd ${PLUGIN_DIR}/
	printf "Installing ${COLOR_ORANGE}composer${COLOR_OFF} packages (no-dev)\n"
	composer install --quiet --no-dev
fi
if [[ -f ${PLUGIN_DIR}/package.json ]]; then
	cd ${PLUGIN_DIR}/
	printf "Building ${COLOR_ORANGE}npm${COLOR_OFF} packages\n"
	npm install
	npm run build
fi

# Go one dir above the plugin DIR, only way to set the correct root for ZIP
cd ${PLUGIN_DIR}/../

# Info
printf  "Plugin Version: ${COLOR_GREEN}${VERSION}${COLOR_OFF}\n"
printf  "Creating zip: ${COLOR_GREEN}${ZIP_FILE_NAME}${COLOR_OFF} in ${COLOR_GREEN}${PLUGIN_ZIP_DIR}${COLOR_OFF}\n"
# Finally zip it up.
zip -r "${PLUGIN_ZIP_PATH}" ./${PLUGIN_SLUG} -x \
${PLUGIN_SLUG}/${PLUGIN_SLUG}*.zip \
${PLUGIN_SLUG}/.distignore* \
${PLUGIN_SLUG}/.editorconfig* \
${PLUGIN_SLUG}/.git\* \
${PLUGIN_SLUG}/.gitignore* \
${PLUGIN_SLUG}/.idea\* \
${PLUGIN_SLUG}/.phpcs.xml.dist \
${PLUGIN_SLUG}/.phpstan.php \
${PLUGIN_SLUG}/.wordpress-org\* \
${PLUGIN_SLUG}/create-zip.sh \
${PLUGIN_SLUG}/composer.json \
${PLUGIN_SLUG}/composer.lock \
${PLUGIN_SLUG}/docs\* \
${PLUGIN_SLUG}/package.json \
${PLUGIN_SLUG}/package-lock.json \
${PLUGIN_SLUG}/phpstan.neon.dist \
${PLUGIN_SLUG}/README.md \
${PLUGIN_SLUG}/readme.md \
${PLUGIN_SLUG}/scoper\* \
${PLUGIN_SLUG}/scoper.inc.php \
${PLUGIN_SLUG}/node_modules\* \
${PLUGIN_SLUG}/vendor\* \

# restore composer to development state.
if [[ -f ${PLUGIN_DIR}/composer.lock ]]; then
	cd ${PLUGIN_DIR}/
	printf "restoring ${COLOR_ORANGE}composer${COLOR_OFF} development packages\n"
	composer install --quiet --dev
fi
