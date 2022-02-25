<?php

namespace Janw\Plugin_Base\App;

/**
 * Class Ajax
 *
 * @package Janw\Plugin_Base\App
 */
class Ajax {
	use Singleton;

	/**
	 * Replace
	 *
	 * @return void json is echoed.
	 */
	public function rename_call() {
		if ( empty( filter_input( INPUT_GET, 'image', FILTER_SANITIZE_STRING ) ) ) {
			wp_send_json_error( new \WP_Error( 400, __( 'Missing required parameter "image".', 'janw-plugin-base' ) ) );
		}

		wp_send_json_success( array( 'key' => 'value' ) );
	}
}
