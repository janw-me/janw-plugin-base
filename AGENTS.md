# Agent guide — janw-plugin-base

Conventions and commands for anyone (human or AI) working in this repo. This is the single source of
truth for house style; keep it current. Claude Code loads it via the one-line `@AGENTS.md` in
`CLAUDE.md`.

## What this is

A WordPress **plugin boilerplate**. You normally don't develop *this* repo — you clone it, run
`bin/setup.sh` to rename it for a new project, then build on top. Until setup is run, names like
`janw-plugin-base`, `Janw\Plugin_Base`, `JANW_PLUGIN_BASE_*` and `janwpb` are placeholders.

## Commands — the feedback loop

| Command | What it does |
| --- | --- |
| `composer ci:changed` | Run the full toolchain on **changed files only** (staged, unstaged, untracked). Use this constantly. |
| `composer ci` | Same toolchain across the whole repo. |
| `composer test` | Fast PHPUnit **unit** suite — no WordPress, no DB, sub-second. |
| `composer test:wp` | WordPress integration suite (needs `composer test:install` once). |
| `composer phpcbf` / `composer phpfixer` | Auto-**fix** style (phpcbf, then php-cs-fixer). |
| `composer phpcs` / `composer phpstan` / `composer phprector` | **Check** style / types / refactors. |

The toolchain (see `.github/ci.sh`) runs in this order: `rector → php-cs-fixer → phpcbf → phpcs →
phpstan`. The first three **rewrite** files; the last two only **report**. So the loop is: edit →
`composer ci:changed` (it auto-fixes what it can) → fix whatever phpcs/phpstan still report → repeat.

**Definition of done for any change:** `composer ci:changed` and `composer test` both pass.

## Architecture

- **`janw-plugin-base.php`** — the entry file. Defines the four `JANW_PLUGIN_BASE_*` constants,
  registers the autoloader, and registers all hooks. The trailing comment
  `// Add the rest of the hooks & filters.` is the **extension point**: new `add_action` /
  `add_filter` calls go there.
- **`app/`** — autoloaded PHP classes. One class (or trait) per file.
- **`templates/`** — view files `require`d by classes (created as needed; excluded from some sniffs).
- **`examples/`** — reference implementations (settings page, ajax, cron) in current house style.
  Not loaded at runtime; copy into `app/` and wire a hook to use one.

## Autoloader & file naming — the #1 gotcha

The autoloader (`app/class-plugin.php::autoloader`) maps classes under the `…\App` sub-namespace to
files:

- `Janw\Plugin_Base\App\Foo_Bar` → `app/class-foo-bar.php`
- a **trait** of the same name → `app/trait-foo-bar.php`
- a sub-namespace `…\App\Admin\Foo_Bar` → `app/admin/class-foo-bar.php`

Rule: lowercase, `_` → `-`, `\` → `/`, prefix `class-` (or `trait-`). **If the file isn't found at the
exact computed path, the autoloader calls `wp_die()`** — you get a white screen, not a clean "class
not found". So the filename must match the class name precisely. The `/new-class` slash command scaffolds a new
class at the correct path automatically.

## Coding standards (enforced by the toolchain)

- **WordPress Coding Standards (WPCS 3)** via phpcs. Tabs for indentation.
- **Long array syntax** `array( … )` — never `[ … ]`.
- **Backslash-prefix native functions and constants**: `\str_starts_with()`, `\DIRECTORY_SEPARATOR`,
  `\file_exists()`. php-cs-fixer's `native_function_invocation` / `native_constant_invocation`
  enforce this in `app/` and `templates/`.
- `declare( strict_types=1 );` at the top of every class file.
- `snake_case` for functions/variables; `Title_Case_With_Underscores` for class names.
- **Prefix all globals** (functions, hooks, options, etc.) with `janw_plugin_base` or `janwpb`.
- Text domain is `janw-plugin-base` (must be a string literal in i18n calls).
- **No Yoda conditions** (`$x === 'a'`, not `'a' === $x`).
- `phpstan.php` declares the four plugin constants as strings so PHPStan (level 8) resolves them.

## Singleton pattern

`app/trait-singleton.php` turns a class into a singleton:

```php
class Foo {
	use Singleton;

	protected function init(): void {
		// optional; runs once, from the constructor. Register hooks here.
	}
}
Foo::instance(); // shared instance; constructor is final/private
```

`__clone`, `__wakeup`, `__construct` are locked. Override behaviour through `init()`, not the
constructor.

## Add a feature (recipe)

1. Create `app/class-<name>.php` (`/new-class <Name>` scaffolds it with the right header & naming).
2. Put logic in the class; if it needs hooks at construction, `use Singleton;` and register them in
   `init()`.
3. Register the entry hook at the bottom of `janw-plugin-base.php`.
4. `composer ci:changed && composer test`.

Common building blocks have worked examples in `examples/` (current house style — copy, don't merge):

| You want… | Start from | Wire with |
| --- | --- | --- |
| Admin settings page | `examples/class-settings.php` | `admin_menu`, `admin_init` |
| AJAX endpoint | `examples/class-ajax.php` | `wp_ajax_…` / `wp_ajax_nopriv_…` |
| Scheduled task | `examples/class-cron.php` | `wp_schedule_event` + a prefixed action hook |

## Tests

- **`composer test`** — fast unit suite in `tests/unit/`, no WordPress, no database. Add tests here
  whenever the logic doesn't truly need WordPress. The `Singleton_Test` is the worked example.
- **`composer test:wp`** — integration suite in `tests/wp/` using `WP_UnitTestCase` + the factory.
  Run `composer test:install` once first (installs the WP test library into a temp dir + a test DB).
- **Naming exception:** PHPUnit derives the test class from the file name, so **test files are named
  after their class** — `Singleton_Test.php` holds `class Singleton_Test` (no `class-` prefix). This is
  the one place we deviate from `class-*.php`; `tests/` is excluded from the file-name sniff. Plain
  classes/fixtures under `tests/` still follow the normal `class-*.php` rule (e.g.
  `tests/unit/fixtures/class-singleton-fixture.php`).

## Setup, versioning, release

- **Rename the boilerplate:** `bin/setup.sh` (or `composer setup`) — replaces the placeholders, renames
  files, fills the plugin header, and removes boilerplate-only bits. Do this once per new project.
- **Bump version:** `composer bumpversion:patch|minor|major` (runs CI first, updates the header,
  constant, and `readme.txt` changelog). `composer publish-version` commits, tags, and pushes.
- **Build a zip:** `composer createzip` (or `createzip:downloads`). `.distignore` controls what ships.

## Don't

- Don't edit `vendor/`.
- Don't switch to `[]` short arrays or unprefixed native calls — the toolchain will revert/flag them.
- Don't add hooks anywhere except the bottom of `janw-plugin-base.php` (keeps wiring in one place).
- Don't merge the stale `feature/*` branches; they predate the current standards. Use `examples/`.
