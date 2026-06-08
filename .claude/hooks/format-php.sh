#!/usr/bin/env bash
#
# Claude Code PostToolUse hook: auto-format a PHP file right after it's edited.
#
# Wired up in .claude/settings.json for Edit|Write|MultiEdit. It mirrors the
# project toolchain's scoping so it never reformats a file differently than
# `composer ci` would:
#   - phpcbf (WordPress standard)  -> every tracked PHP file
#   - php-cs-fixer                 -> only app/ and templates/ (its configured scope)
#
# To disable: remove the "hooks" block from .claude/settings.json.
# The hook always exits 0 so a missing tool or non-PHP edit is a silent no-op.
set -u

# Claude Code sends the tool payload as JSON on stdin. Parse with php (always
# present in this project) so we don't depend on jq being installed.
FILE="$(php -r '$d = json_decode(file_get_contents("php://stdin"), true); echo $d["tool_input"]["file_path"] ?? "";' 2>/dev/null)"

# Only act on PHP files that exist.
case "$FILE" in
	*.php) ;;
	*) exit 0 ;;
esac
[ -f "$FILE" ] || exit 0

# WordPress-standard autoformat — same scope as `composer phpcbf`.
if [ -x vendor/bin/phpcbf ]; then
	php vendor/bin/phpcbf --standard=phpcs.xml.dist "$FILE" >/dev/null 2>&1
fi

# php-cs-fixer only inside its configured finder (app/ and templates/).
case "$FILE" in
	*/app/*|*/templates/*)
		if [ -x vendor/bin/php-cs-fixer ]; then
			php vendor/bin/php-cs-fixer fix "$FILE" --config=phpcs-fixer.dist.php --quiet >/dev/null 2>&1
		fi
		;;
esac

exit 0
