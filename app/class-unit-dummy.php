<?php

namespace Janw\Plugin_Base\App;

/**
 * Class UnitTest
 *
 * @package Janw\Plugin_Base\App
 */
class Unit_Dummy {

	/**
	 * Create an error on purpose.
	 *
	 * @param bool $force force y/n.
	 *
	 * @return false|\WP_Error
	 */
	public static function create_error( bool $force = true ) {
		if ( $force ) {
			return new \WP_Error();
		}

		return false;
	}

	/**
	 * Check a post_type.
	 *
	 * @param int|\WP_Post|null $post      A (potential) post.
	 * @param string|null       $post_type If given, check if the post is current post_type.
	 *
	 * @return bool|string|null
	 */
	public static function check_post_type( $post, string $post_type = null ) {
		$post = get_post( $post );
		if ( is_null( $post ) ) {
			return null;
		}

		if ( null === $post_type ) {
			return $post->post_type;
		}

		return ( $post_type === $post->post_type );
	}
}
