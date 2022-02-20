This is my starter plugin that I use for every project.

# Installing

Clone this repo in an empty directory, remove the git and start your own git.

	git clone git@github.com:janw-me/janw-plugin-base.git .
	rm -rf .git
	git init

# Setup

Before actual starting there are several places that need to be renamed,
This will rename most things:

	mv janw-plugin-base.php your-slug.php
	find ./ -type f -not -path "./.git/*" -exec sed -i 's/janw-plugin-base/your-slug/g' {} \;
	find ./ -type f -not -path "./.git/*" -exec sed -i 's/JANW_PLUGIN_BASE/YOUR_SLUG/g' {} \;
	find ./ -type f -not -path "./.git/*" -exec sed -i 's/Janw\\Plugin_Base/Your\\Slug/g' {} \;



- `composer.json`
	- name (the user should probably change)
  	- description
	- packages (yes please double check)
- `readme.txt`
	- line 1: with plugin title
- `.phpcs.xml.dist`
	- prefixes
	- text_domain
- `janw-plugin-base.php`
    - rename file (should have been done automatically)
    - Check all _plugin headers_
- Plugin wide seach-replace
	- Constants: _JANW_PLUGIN_BASE_
	- Namespace & Package: _Janw\Plugin_Base_
- clear this section and the install section.

## Change php version
Default is set to 7.3, to update it change:

- `janw-base-plugin.php`
- `composer.json`
- `.phpcs.xml.dist`
- `readme.txt`
- `phpstan.neon.dist`

## Change WP version
The default is 5.8, to update is change:

- `janw-base-plugin.php`
- `.phpcs.xml.dist`
- `readme.txt`

# Bundled commands

Inside composer several extra tools have been added:

Code formatting:
- `composer run phpcbf`                  Run the phpcbf, an autoformatter.
- `composer run phpcs`                   Run phpcs, Checks style and syntax agianst theh WordPress coding stadard.
- `composer run lint`                    Run php linter, Checks syntax.
- `composer run phpstan`                 Run phpstan, Checks styntax, docblock, non existing functions/classes.
- `composer run ci`                      Run all the above syntax checkers at once.

Creating plugin zip:
- `composer run createzip`               Will create a zip named 'plugin-slug.zip' in the plugin folder.
- `composer run createzip-in-downloads`  Will create a zip named 'plugin-slug-0.1.0.zip' in the plugin folder.
- `composer run createzip-with-version`  Will create a zip named 'plugin-slug-0.1.0.zip' in the Downloads folder.
