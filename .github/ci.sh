#!/usr/bin/env bash
set -euo pipefail

# Default options
RUN_CHANGED=false
TOOLS="rector,php-cs-fixer,phpcbf,phpcs,phpstan"

# Parse arguments
while [[ $# -gt 0 ]]; do
    case "$1" in
        --help)
            cat <<EOF
Usage: bash .github/ci.sh [OPTIONS]

Options:
  --changed              Run tools on changed files only (staged, unstaged, untracked)
  --tools=TOOL1,TOOL2    Run only specific tools: rector, php-cs-fixer, phpcbf, phpcs, phpstan
  --help                 Show this help message

Examples:
  bash .github/ci.sh
                         Run all tools on all files

  bash .github/ci.sh --changed
                         Run all tools on changed files only

  bash .github/ci.sh --tools=phpstan,rector
                         Run phpstan and rector on all files

  bash .github/ci.sh --changed --tools=phpstan,rector
                         Run phpstan and rector on changed files only
EOF
            exit 0
            ;;
        --changed)
            RUN_CHANGED=true
            shift
            ;;
        --tools=*)
            TOOLS="${1#--tools=}"
            shift
            ;;
        *)
            echo "Unknown option: $1" >&2
            exit 1
            ;;
    esac
done

# Determine files to check
if [[ "$RUN_CHANGED" == true ]]; then
    CHANGED=$(git diff --name-only HEAD 2>/dev/null; git diff --name-only --cached 2>/dev/null; git ls-files --others --exclude-standard 2>/dev/null)
    FILES=$(echo "$CHANGED" | sort -u | grep '\.php$' || true)
    if [[ -z "$FILES" ]]; then
        echo "No changed PHP files."
        exit 0
    fi
    echo "Running CI on changed files:"
else
    FILES="."
    echo "Running CI on all files:"
fi
echo "$FILES" | head -20 | sed 's/^/  /'
[[ $(echo "$FILES" | wc -l) -gt 20 ]] && echo "  ... and more"

# Run selected tools
IFS=',' read -ra TOOL_ARRAY <<< "$TOOLS"
for TOOL in "${TOOL_ARRAY[@]}"; do
    TOOL=$(echo "$TOOL" | xargs) # Trim whitespace
    case "$TOOL" in
        rector)
            echo "→ Running rector..."
            if [[ "$RUN_CHANGED" == true ]]; then
                # shellcheck disable=SC2086
                php vendor/bin/rector process $FILES --config=phprector.php
            else
                php vendor/bin/rector process --config=phprector.php
            fi
            ;;
        php-cs-fixer)
            echo "→ Running php-cs-fixer..."
            if [[ "$RUN_CHANGED" == true ]]; then
                # shellcheck disable=SC2086
                php vendor/bin/php-cs-fixer fix $FILES --config=phpcs-fixer.dist.php
            else
                php vendor/bin/php-cs-fixer fix --config=phpcs-fixer.dist.php
            fi
            ;;
        phpcbf)
            echo "→ Running phpcbf..."
            if [[ "$RUN_CHANGED" == true ]]; then
                # shellcheck disable=SC2086
                php vendor/bin/phpcbf $FILES || true
            else
                php vendor/bin/phpcbf || true
            fi
            ;;
        phpcs)
            echo "→ Running phpcs..."
            if [[ "$RUN_CHANGED" == true ]]; then
                # shellcheck disable=SC2086
                php vendor/bin/phpcs $FILES
            else
                php vendor/bin/phpcs
            fi
            ;;
        phpstan)
            echo "→ Running phpstan..."
            if [[ "$RUN_CHANGED" == true ]]; then
                # shellcheck disable=SC2086
                php vendor/bin/phpstan analyse $FILES
            else
                php vendor/bin/phpstan analyse
            fi
            ;;
        *)
            echo "Unknown tool: $TOOL" >&2
            exit 1
            ;;
    esac
done

echo "✓ CI passed"
