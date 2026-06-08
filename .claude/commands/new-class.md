---
description: Scaffold a new autoloaded class in app/ following the house conventions.
argument-hint: <Class_Name> [singleton]
---

Scaffold a new plugin class. Arguments: `$ARGUMENTS` (first word = class name in
`Title_Case_With_Underscores`; the optional word `singleton` means it needs the Singleton trait).

If no class name was given, ask for one before doing anything.

Follow `AGENTS.md`. Steps:

1. **Work out the file path from the class name** using the autoloader rule: lowercase, `_` → `-`,
   `\` → `/`, prefix the basename with `class-`. Examples:
   - `Order_Sync` → `app/class-order-sync.php`
   - `Admin\Settings_Page` → `app/admin/class-settings-page.php`

   The path must match exactly — a mismatch makes the autoloader `wp_die()`.

2. **Create the file** with this skeleton, substituting the real class name and a one-line
   description. Keep `declare( strict_types=1 )`, the `Janw\Plugin_Base\App` namespace (plus any
   sub-namespace), tabs, and `array()` long syntax:

   ```php
   <?php
   declare( strict_types=1 );

   namespace Janw\Plugin_Base\App;

   /**
    * <what this class does>.
    */
   class Order_Sync {

   }
   ```

3. **If `singleton` was requested** (the class registers hooks at construction), add `use Singleton;`
   and an `init()` method, and access it via `::instance()`:

   ```php
   class Order_Sync {
   	use Singleton;

   	/**
    	 * Register hooks.
    	 */
   	protected function init(): void {

   	}
   }
   ```

4. **Remind the user to register the entry hook** at the bottom of `janw-plugin-base.php`, after the
   `// Add the rest of the hooks & filters.` line (e.g.
   `add_action( 'init', array( Order_Sync::instance(), 'some_method' ) );`). Don't add WP-API logic
   you weren't asked for — leave the class body ready for the user to fill in.

5. **Run `composer ci:changed`** and report whether it passed.
