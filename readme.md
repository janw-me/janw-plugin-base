This is my starter plugin that I use for every project.

# Installing

Clone this repo in an empty directory, remove the git and start your own git.

	git clone git@github.com:janw-me/janw-plugin-base.git .
	rm -rf .git
	git init

See `examples/` for ready-to-copy feature patterns (settings page, AJAX, cron) and `AGENTS.md` for the project conventions.

# Setup

Rename the boilerplate for your project with the bundled script (run once, from the plugin root):

	composer setup -- --name "My Plugin" --slug my-plugin --namespace "Acme\\My"

	# or call it directly:
	bin/setup.sh --name "My Plugin" --slug my-plugin --namespace "Acme\\My"

This replaces every placeholder (slug, constants, namespace, function/hook prefixes, plugin name),
renames the main file and the language file, and then removes itself. Run `bin/setup.sh --help` to
see the optional flags (`--prefix`, `--short-prefix`, `--const-prefix`, `--description`).

Afterwards:

- Review `composer.json` — the `janw-me` vendor name and the dev `packages` (double-check them).
- Review the plugin header in `<your-slug>.php` and the title line in `readme.txt`.
- Remove this _Installing_ / _Setup_ section.
- Run `composer install && composer ci && composer test`.

See `AGENTS.md` for the project conventions and day-to-day commands.

## Change php version
Default is set to 8.2, to update it change:

- `janw-plugin-base.php`
- `composer.json` (2 places)
- `phpcs.xml.dist`
- `readme.txt`
- `phpstan.neon.dist`
- `phpcs-fixer.dist.php`

## Change WP version
The default is 6.6, to update is change:

- `janw-plugin-base.php`
- `phpcs.xml.dist`
- `readme.txt`

# Bundled commands

Inside composer several extra tools have been added. See `AGENTS.md` for the full development loop.

Code quality (the toolchain is `rector → php-cs-fixer → phpcbf → phpcs → phpstan`):
- `composer ci`            Run the whole toolchain on every file.
- `composer ci:changed`    Run the whole toolchain on changed files only.
- `composer phpcbf`        Run phpcbf, the WordPress-standard autoformatter.
- `composer phpfixer`      Run php-cs-fixer, the secondary autoformatter.
- `composer phpcs`         Run phpcs, checks style against the WordPress Coding Standard.
- `composer phpstan`       Run phpstan, static analysis (types, docblocks, unknown symbols).
- `composer phprector`     Run rector, automated refactors.

Tests:
- `composer test`          Fast PHPUnit unit suite (no WordPress, no database).
- `composer test:install`  Install the WordPress test library (run once before `test:wp`).
- `composer test:wp`       WordPress integration suite (needs `test:install`).

Creating a plugin zip:
- `composer createzip`            Create `plugin-slug.zip` in the plugin folder.
- `composer createzip:downloads`  Create `plugin-slug-0.1.0.zip` in `~/Downloads`.

Versioning & release:
- `composer bumpversion:patch|minor|major`  Run CI, then bump the version everywhere.
- `composer publish-version`                Commit, tag, and push the new version.
