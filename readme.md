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

`./create-zip.sh`

Run `./create-zip.sh -h` for detailed options.
