#!/usr/bin/env bash
#
# Rename this boilerplate for a new project: replaces all of the janw-plugin-base
# placeholders, renames the main file + language file, and removes itself.
#
# Run once, from the plugin root, right after cloning. Usually via `composer setup`.
#
# Example:
#   bin/setup.sh \
#     --name "My Awesome Plugin" \
#     --slug my-awesome-plugin \
#     --namespace "Acme\Awesome"
#
set -euo pipefail

PLUGIN_DIR="$(cd "$(dirname "$(dirname "${BASH_SOURCE[0]}")")" &>/dev/null && pwd)"
cd "$PLUGIN_DIR"

NAME=""
SLUG=""
NAMESPACE=""
PREFIX=""
SHORT_PREFIX=""
CONST_PREFIX=""
DESCRIPTION=""
ASSUME_YES=false

usage() {
	cat <<EOF
Rename the boilerplate for a new plugin.

Required:
  --name "Plugin Name"     Human-readable plugin name (replaces "Janw Base Plugin").
  --slug plugin-slug       Lowercase, hyphenated slug (replaces janw-plugin-base).
  --namespace "Vendor\\Pkg" PHP namespace root (replaces Janw\\Plugin_Base).

Optional (derived from --slug when omitted):
  --prefix my_plugin       Function/hook prefix      (replaces janw_plugin_base).
  --const-prefix MY_PLUGIN Constant prefix           (replaces JANW_PLUGIN_BASE).
  --short-prefix myplugin  Short prefix              (replaces janwpb).
  --description "..."       Fills the plugin header + composer description.
  --yes                    Don't prompt for confirmation.
  --help                   Show this message.
EOF
}

while [[ $# -gt 0 ]]; do
	case "$1" in
		--name) NAME="$2"; shift 2 ;;
		--slug) SLUG="$2"; shift 2 ;;
		--namespace) NAMESPACE="$2"; shift 2 ;;
		--prefix) PREFIX="$2"; shift 2 ;;
		--short-prefix) SHORT_PREFIX="$2"; shift 2 ;;
		--const-prefix) CONST_PREFIX="$2"; shift 2 ;;
		--description) DESCRIPTION="$2"; shift 2 ;;
		--yes|-y) ASSUME_YES=true; shift ;;
		--help|-h) usage; exit 0 ;;
		*) echo "Unknown option: $1" >&2; usage; exit 1 ;;
	esac
done

# Validate required inputs.
if [[ -z "$NAME" || -z "$SLUG" || -z "$NAMESPACE" ]]; then
	echo "Error: --name, --slug and --namespace are required." >&2
	usage
	exit 1
fi
if [[ ! "$SLUG" =~ ^[a-z][a-z0-9-]*$ ]]; then
	echo "Error: --slug must be lowercase letters, numbers and hyphens (e.g. my-plugin)." >&2
	exit 1
fi
if [[ ! -f "janw-plugin-base.php" ]]; then
	echo "Error: janw-plugin-base.php not found — has setup already run?" >&2
	exit 1
fi

# Derive defaults.
[[ -n "$PREFIX" ]] || PREFIX="${SLUG//-/_}"
[[ -n "$CONST_PREFIX" ]] || CONST_PREFIX="$(printf '%s' "$PREFIX" | tr '[:lower:]' '[:upper:]')"
[[ -n "$SHORT_PREFIX" ]] || SHORT_PREFIX="${PREFIX//_/}"

echo "About to apply these replacements across the project:"
printf '  %-22s -> %s\n' "Janw Base Plugin" "$NAME"
printf '  %-22s -> %s\n' "janw-plugin-base" "$SLUG"
printf '  %-22s -> %s\n' "Janw\\Plugin_Base" "$NAMESPACE"
printf '  %-22s -> %s\n' "JANW_PLUGIN_BASE" "$CONST_PREFIX"
printf '  %-22s -> %s\n' "janw_plugin_base" "$PREFIX"
printf '  %-22s -> %s\n' "janwpb" "$SHORT_PREFIX"
echo ""

if [[ "$ASSUME_YES" != true ]]; then
	read -r -p "Proceed? [y/N] " answer
	case "$answer" in
		y|Y|yes|YES) ;;
		*) echo "Aborted."; exit 1 ;;
	esac
fi

# In-place sed that works on both GNU and BSD.
if sed --version >/dev/null 2>&1; then
	sed_inplace() { sed -i "$@"; }
else
	sed_inplace() { sed -i '' "$@"; }
fi

escape_search() { printf '%s' "$1" | sed -e 's/[][\.*^$|]/\\&/g'; }
escape_replace() { printf '%s' "$1" | sed -e 's/[\&|]/\\&/g'; }

# Files to touch: everything text-like except VCS, dependencies, caches, binaries
# and this script itself (which holds the search patterns).
collect_files() {
	find . -type f \
		-not -path './.git/*' \
		-not -path './vendor/*' \
		-not -path './node_modules/*' \
		-not -path './.phpunit.cache/*' \
		-not -path './bin/setup.sh' \
		-not -name 'composer.lock' \
		-not -name '*.mo' -not -name '*.zip' -not -name '*.tar.gz' \
		-not -name '*.png' -not -name '*.jpg' -not -name '*.gif'
}

# Gather the file list once and reuse it for every replacement.
mapfile -t FILES < <(collect_files)

replace_token() {
	local search replace file
	search="$(escape_search "$1")"
	replace="$(escape_replace "$2")"
	for file in "${FILES[@]}"; do
		sed_inplace "s|${search}|${replace}|g" "$file"
	done
}

# Order: namespace and constants first (case-sensitive, no overlap with the rest).
replace_token "Janw\\Plugin_Base" "$NAMESPACE"
replace_token "JANW_PLUGIN_BASE" "$CONST_PREFIX"
replace_token "janw_plugin_base" "$PREFIX"
replace_token "janwpb" "$SHORT_PREFIX"
replace_token "janw-plugin-base" "$SLUG"
replace_token "Janw Base Plugin" "$NAME"

if [[ -n "$DESCRIPTION" ]]; then
	replace_token "PLUGIN DESCRIPTION HERE" "$DESCRIPTION"
	replace_token "\"description\": \"Replace\"" "\"description\": \"${DESCRIPTION}\""
fi

# Rename the main plugin file and the language file.
mv "janw-plugin-base.php" "${SLUG}.php"
if [[ -f "languages/janw-plugin-base-nl_NL.po" ]]; then
	mv "languages/janw-plugin-base-nl_NL.po" "languages/${SLUG}-nl_NL.po"
fi

echo ""
echo "Done. Next steps:"
echo "  1. Review readme.md / readme.txt and remove the boilerplate setup notes."
echo "  2. Check composer.json (vendor name) and the plugin header in ${SLUG}.php."
echo "  3. composer install && composer ci && composer test"
echo ""
echo "Removing bin/setup.sh."
rm -- "$0"
