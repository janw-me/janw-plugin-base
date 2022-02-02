# Useage

Before starting replace:

- `composer.json`
	- name
  	- description
	- packages (yeah really)

- `readme.txt`
	- line 1: with plugin title

- `.phpcs.xml.dist`
	- prefixes
	- text_domain

- `janw-plugin-base.php`
    - rename (obvious)
    - the headers

- Plugin wide seach-replace
	- Constants: _JANW_BASE_PLUGIN_
	- Namespace & Package: _Janw\Base_Plugin_

- clear this file.

## By default php 7.2
If needed update in

- `janw-base-plugin`
- `composer.json`
- `.phpcs.xml.dist`
- `readme.txt`


# create plugin zip
Bundled inside is a script to create a plugin zip file.

 - `composer run createzip` Will create a zip named `plugin-slug.zip` in the plugin folder.
 - `composer run createzip-in-downloads` Will create a zip named `plugin-slug-0.1.0.zip` in the Downloads folder.
 - `composer run createzip-with-version` Will create a zip named `plugin-slug-0.1.0.zip` in the plugin folder.


Run `composer run createzip -- -h` for a list of the arguments.
