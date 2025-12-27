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

# Are there any changes?
if [[ -z $(git status -s) ]]; then
	echo -e "${COLOR_RED}No changes to commit.${COLOR_OFF}"
	exit 1
fi

CURRENT_VERSION=$(grep -oP 'Version:\s*\K.*\w' $PLUGIN_PHP_PATH)
# Explode the current version.
echo $CURRENT_VERSION

COMMIT_MESSAGE="Version $CURRENT_VERSION"
git add .
git commit -m "$COMMIT_MESSAGE"
git push origin
git tag -a $CURRENT_VERSION -m "$COMMIT_MESSAGE"
git push origin $CURRENT_VERSION
echo -e "Version ${COLOR_GREEN}${CURRENT_VERSION}${COLOR_OFF} committed and tagged."
