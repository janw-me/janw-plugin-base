<?php
/**
 * Class SampleTest
 *
 * @package Janw_Plugin_Base
 */

use Janw\Plugin_Base\App\Unit_Dummy;

/**
 * Sample test case.
 */
class Unit_Dummy_Test extends WP_UnitTestCase {

	/**
	 * Test function.
	 *
	 * @see Unit_Dummy::create_error
	 */
	public function test_create_error() {
		$this->assertWPError( Unit_Dummy::create_error( true ) );
		$this->assertWPError( Unit_Dummy::create_error() );
		$this->assertFalse( Unit_Dummy::create_error( false ) );
	}

	/**
	 * Test function
	 *
	 * @see Unit_Dummy::check_post_type
	 */
	public function test_check_post_type() {
		/**
		 * The factory can create pretty much every WP entity.
		 */
		$post_id = $this->factory()->post->create(
			array(
				'post_type' => 'post',
			)
		);

		$__mssg__ = 'Check post type based on post_ID';
		self::assertEquals( Unit_Dummy::check_post_type( $post_id ), 'post', $__mssg__ );

		$__mssg__ = 'Check post type based on post';
		self::assertEquals( Unit_Dummy::check_post_type( get_post( $post_id ) ), 'post', $__mssg__ );

		$__mssg__ = 'Check that post type matches.';
		self::assertTrue( Unit_Dummy::check_post_type( get_post( $post_id ), 'post' ), $__mssg__ );

		$__mssg__ = 'Check an invalid post_ID';
		self::assertNull( Unit_Dummy::check_post_type( 9001 ), $__mssg__ );

		$__mssg__ = 'not passing a post.';
		self::assertNull( Unit_Dummy::check_post_type( null ), $__mssg__ );
	}
}
