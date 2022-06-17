#!/usr/bin/env bash

# Directory to self https://stackoverflow.com/a/246128/933065
PLUGIN_DIR="$(cd "$(dirname $(dirname "${BASH_SOURCE[0]}"))" &>/dev/null && pwd)"
PLUGIN_SLUG=$(basename $PLUGIN_DIR)
PLUGIN_PHP_PATH="${PLUGIN_DIR}/${PLUGIN_SLUG}.php"

# Colors.
COLOR_RED='\033[0;31m'
COLOR_GREEN='\033[0;32m'
COLOR_ORANGE='\033[0;33m'
COLOR_OFF='\033[0m'

CURRENT_VERSION=$(grep -oP 'Version:\s*\K.*\w' $PLUGIN_PHP_PATH)
# Explode the current version.
NUMBER=(${CURRENT_VERSION//./ })
function display_help_mssg() {
	echo "Update the version number in all files."
	echo ""
	echo  '-h, --help     Print help this message'
	echo '--major         Update the major version number, the first number.'
	echo '--minor         Update the minor version number, the middle number.'
	echo '--patch         Update the patch version number, the last number.'
	printf "  ${COLOR_ORANGE}./update-version.sh --major${COLOR_OFF}\n"
	printf "      Will set the next major version. ${COLOR_GREEN}0.9.19${COLOR_OFF} will become ${COLOR_GREEN}1.0.0${COLOR_OFF}\n"
	printf "  ${COLOR_ORANGE}./update-version.sh --minor${COLOR_OFF}\n"
	printf "      Will set the next minor version. ${COLOR_GREEN}0.9.19${COLOR_OFF} will become ${COLOR_GREEN}0.10.0${COLOR_OFF}\n"
	printf "  ${COLOR_ORANGE}./update-version.sh --patch${COLOR_OFF}\n"
	printf "      Will set the next patch version. ${COLOR_GREEN}0.9.19${COLOR_OFF} will become ${COLOR_GREEN}0.9.20${COLOR_OFF}\n"
	printf "  ${COLOR_ORANGE}./update-version.sh 0.9.19-beta${COLOR_OFF}\n"
	printf "      manually set the version number to ${COLOR_GREEN}0.9.19-beta${COLOR_OFF}\n"
	echo ""
}

if [[ -z ${1+x} ]]; then
    echo "Please enter a version number. Check the --help option for details.";
    exit 1;
fi

while test $# -gt 0; do
	case "$1" in
	-h | --help)
		display_help_mssg
		exit 0
		;;
	--major)
		NEW_VERSION="$((${NUMBER[0]} + 1)).0.0"
		shift
		;;
	--minor)
		NEW_VERSION="${NUMBER[0]}.$((${NUMBER[1]} + 1)).0"
		shift
		;;
	--patch)
		NEW_VERSION="${NUMBER[0]}.${NUMBER[1]}.$((${NUMBER[2]} + 1))"
		shift
		;;
	*)
		if test $# -gt 0; then
			NEW_VERSION=${1}
		fi
		break
		;;
	esac
done

# Implode the version number
#NEW_VERSION=${NUMBER[@]}
#NEW_VERSION=${NEW_VERSION// /.}

echo -e "Current version: ${COLOR_GREEN}${CURRENT_VERSION}${COLOR_OFF}"
echo -e "Updating to: ${COLOR_ORANGE}${NEW_VERSION}${COLOR_OFF}"

# Regex to capture the full version.
VERSION_REGEX="([0-9.]+?[-a-z0-9]+)"
sed -i -E            "s#(Version:\s+)${VERSION_REGEX}#\1${NEW_VERSION}#g" ${PLUGIN_PHP_PATH}
sed -i -E "s#(define.*_VERSION',\s+')${VERSION_REGEX}#\1${NEW_VERSION}#g" ${PLUGIN_PHP_PATH}
sed -i -E        "s#(Stable tag:\s+)${VERSION_REGEX}#\1${NEW_VERSION}#g" "${PLUGIN_DIR}/readme.txt"

# Stable tag: 1.2.0
