<?php

namespace Janw\Plugin_Base\App;

/**
 * Class Admin
 *
 * @package Janw\Plugin_Base\App
 */
class Settings {
	use Singleton;

	/**
	 * Add a settings link to the plugin on the plugin page
	 *
	 * @param string[] $actions An array of plugin action links. By default this can include 'activate', 'delete', 'network_only', ....
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 *
	 * @return string[]
	 */
	public static function plugin_link( array $actions, string $plugin_file ): array {
		$this_plugin_file = JANW_PLUGIN_BASE_SLUG . DIRECTORY_SEPARATOR . JANW_PLUGIN_BASE_SLUG . '.php';
		if ( $plugin_file !== $this_plugin_file ) {
			return $actions; // wrong plugin.
		}

		$href          = admin_url( 'tools.php?page=' . JANW_PLUGIN_BASE_SLUG );
		$settings_link = '<a href="' . $href . '">' . __( 'Settings' ) . '</a>'; // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		array_unshift( $actions, $settings_link );

		return $actions;
	}

	/**
	 * Register a menu page.
	 */
	public function register_menu_page(): void {
		add_submenu_page(
			'tools.php',
			__( 'Settings title page', 'janw-plugin-base' ),
			_x( 'Example Settings', 'Side Menu Item', 'janw-plugin-base' ),
			'manage_options',
			JANW_PLUGIN_BASE_SLUG,
			array( $this, 'register_menu_page_render' ),
		);
	}

	/**
	 * Register the render of the menu page.
	 */
	public function register_menu_page_render(): void {
		// Optional; setup other variables.
		require JANW_PLUGIN_BASE_DIR . 'templates' . DIRECTORY_SEPARATOR . 'settings-page.php';
	}

	/**
	 * Register setting section and settings.
	 *
	 * The section is defined in here.
	 * And the underlying settings are also registered here.
	 */
	public function register_settings(): void {
		// register the global setting.
		add_settings_section(
			'page-section',
			'', // Section title, A bit useless if we only have 1 section.
			'__return_empty_string', // An optional callback for a description, also not needed here.
			'janw-plugin-base_page'
		);

		$this->register_setting_checkboxes();
		$this->register_settings_textarea();
	}

	/**
	 * Register the checkboxes_example setting.
	 */
	protected function register_setting_checkboxes(): void {
		$option_name = 'checkboxes_example';

		// Register the overall setting.
		register_setting(
			'janw-plugin-base',
			$option_name,
			array(
				'sanitize_callback' => array( $this, 'sanitize_checkboxes_example' ),
				'default'           => array(),
			),
		);

		$title = __( 'Example Checkbox Title', 'janw-plugin-base' );

		// The arguments passed to the render function.
		// How these arguments are used depends on the template used.
		$settings_field_render_args = array(
			'template'    => JANW_PLUGIN_BASE_DIR . 'templates' . DIRECTORY_SEPARATOR . 'settings-page' . DIRECTORY_SEPARATOR . 'checkboxes-example.php',
			'title'       => $title,
			'option_name' => $option_name,
			'inputs'      => array(
				'morning'   => array( 'label' => __( 'Morning', 'janw-plugin-base' ) ),
				'afternoon' => array( 'label' => __( 'Afternoon', 'janw-plugin-base' ) ),
				'evening'   => array( 'label' => __( 'Evening', 'janw-plugin-base' ) ),
			),
			'values'      => get_option( $option_name ),
		);

		// Actual register the field on the options page.
		add_settings_field(
			$option_name, // Same as option name.
			$title, // Title used in the left column.
			array( $this, 'settings_field_render' ),
			'janw-plugin-base_page',
			'page-section',
			$settings_field_render_args
		);
	}

	/**
	 * Register the textarea_example setting.
	 */
	protected function register_settings_textarea():void {
		$option_name = 'textarea_example';
		register_setting(
			'janw-plugin-base',
			$option_name,
			array( 'default' => '' )
		);

		// The arguments passed to the render function.
		// How these arguments are used depends on the template used.
		$settings_field_render_args = array(
			'template'    => JANW_PLUGIN_BASE_DIR . 'templates' . DIRECTORY_SEPARATOR . 'settings-page' . DIRECTORY_SEPARATOR . 'textarea-example.php',
			'label_for'   => JANW_PLUGIN_BASE_SLUG . '-' . md5( __FILE__ . __LINE__ ), // Input ID.
			'value'       => get_option( $option_name ),
			'name'        => $option_name,
			'description' => __( 'Text explaining what\'s happening.', 'janw-plugin-base' ),
		);

		// Actual register the field on the options page.
		add_settings_field(
			$option_name,
			__( 'Example Textarea Title', 'janw-plugin-base' ),  // Title used in the left column.
			array( $this, 'settings_field_render' ),
			'janw-plugin-base_page',
			'page-section',
			$settings_field_render_args
		);
	}

	/**
	 * Render the settings field.
	 * Simply pass the variables to the arguments.
	 *
	 * @param mixed[] $args all arguments that are needed in the template.
	 */
	public function settings_field_render( array $args ): void {
		extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		unset( $template ); // Don't need it in the templates.

		require $args['template'];
	}

	/**
	 * Sanitize the checkboxes_example, cast all sub values to boolean.
	 *
	 * @param mixed $values The raw option value.
	 *
	 * @return null|bool[] null on error.
	 */
	public function sanitize_checkboxes_example( $values ) {
		if ( ! is_array( $values ) ) {
			return null; // Error, Invalid input.
		}
		foreach ( $values as &$value ) {
			$value = (bool) $value;
		}

		return $values;
	}
}
