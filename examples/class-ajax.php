<?php
declare( strict_types=1 );

namespace Janw\Plugin_Base\App;

/**
 * Example: an admin-ajax endpoint.
 *
 * Copy to app/, then register the hooks at the bottom of the main plugin file:
 *
 *   add_action( 'wp_ajax_janwpb_example', array( Ajax::instance(), 'handle' ) );
 *   add_action( 'wp_ajax_nopriv_janwpb_example', array( Ajax::instance(), 'handle' ) );
 */
class Ajax {
	use Singleton;

	/**
	 * Handle the AJAX request and send a JSON response.
	 */
	public function handle(): void {
		\check_ajax_referer( 'janwpb_example' );

		$image = isset( $_GET['image'] ) ? \sanitize_text_field( \wp_unslash( $_GET['image'] ) ) : '';
		if ( $image === '' ) {
			\wp_send_json_error( new \WP_Error( 'missing_parameter', \__( 'Missing required parameter "image".', 'janw-plugin-base' ) ), 400 );
		}

		\wp_send_json_success( array( 'image' => $image ) );
	}
}
