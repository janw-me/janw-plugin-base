<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\App;

/**
 * Example: an admin settings page using the WordPress Settings API.
 *
 * Registers a single text option under Tools → Example Settings. Copy to app/,
 * then register at the bottom of the main plugin file:
 *
 *   add_action( 'admin_menu', array( Settings::instance(), 'register_page' ) );
 *   add_action( 'admin_init', array( Settings::instance(), 'register_settings' ) );
 *   add_filter( 'plugin_action_links_' . JANW_PLUGIN_BASE_SLUG . '/' . JANW_PLUGIN_BASE_SLUG . '.php', array( Settings::class, 'action_link' ) );
 */
class Settings {
	use Singleton;

	/**
	 * Settings group (passed to settings_fields()).
	 */
	private const OPTION_GROUP = 'janwpb_settings';

	/**
	 * The option name stored in the database.
	 */
	private const OPTION_NAME = 'janwpb_example_text';

	/**
	 * Register the admin menu page.
	 */
	public function register_page(): void {
		\add_submenu_page(
			'tools.php',
			\__( 'Example Settings', 'janw-plugin-base' ),
			\__( 'Example Settings', 'janw-plugin-base' ),
			'manage_options',
			JANW_PLUGIN_BASE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the setting, its section and its field.
	 */
	public function register_settings(): void {
		\register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);

		\add_settings_section( 'janwpb_main', '', '__return_empty_string', JANW_PLUGIN_BASE_SLUG );

		\add_settings_field(
			self::OPTION_NAME,
			\__( 'Example text', 'janw-plugin-base' ),
			array( $this, 'render_field' ),
			JANW_PLUGIN_BASE_SLUG,
			'janwpb_main'
		);
	}

	/**
	 * Render the settings page wrapper.
	 */
	public function render_page(): void {
		?>
		<div class="wrap">
			<h1><?php echo \esc_html( \get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				\settings_fields( self::OPTION_GROUP );
				\do_settings_sections( JANW_PLUGIN_BASE_SLUG );
				\submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render the single text input field.
	 */
	public function render_field(): void {
		$value = (string) \get_option( self::OPTION_NAME, '' );
		\printf(
			'<input type="text" name="%1$s" value="%2$s" class="regular-text" />',
			\esc_attr( self::OPTION_NAME ),
			\esc_attr( $value )
		);
	}

	/**
	 * Add a "Settings" link on the plugins screen.
	 *
	 * @param string[] $links Existing plugin action links.
	 * @return string[]
	 */
	public static function action_link( array $links ): array {
		$url           = \admin_url( 'tools.php?page=' . JANW_PLUGIN_BASE_SLUG );
		$settings_link = '<a href="' . \esc_url( $url ) . '">' . \esc_html__( 'Settings', 'janw-plugin-base' ) . '</a>';
		\array_unshift( $links, $settings_link );

		return $links;
	}
}
