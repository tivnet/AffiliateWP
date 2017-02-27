<?php
namespace AffWP\Labs;

use AffWP\Tests\UnitTestCase;

/**
 * Tests for the Labs API.
 *
 * @group labs
 */
class Tests extends UnitTestCase {

	/**
	 * Labs test fixture.
	 *
	 * @access protected
	 * @var    \Affiliate_WP_Labs
	 */
	protected static $labs;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$labs = new \Affiliate_WP_Labs;
	}

	/**
	 * Runs after each test to reset the features array.
	 *
	 * @access public
	 */
	public function tearDown() {
		self::$labs->registry->_reset_features();

		parent::tearDown();
	}

	/**
	 * @covers \Affiliate_WP_Labs::register_feature()
	 */
	public function test_register_feature_should_register_the_feature() {
		self::$labs->register_feature( 'foobar', array(
			'class' => 'Foo\Bar',
			'file'  => 'path/to/foobar.php'
		) );

		$this->assertArrayHasKey( 'foobar', self::$labs->registry->get_features() );
	}

	/**
	 * @covers \Affiliate_WP_Labs::get_features()
	 */
	public function test_get_features_should_be_empty_with_no_registered_features() {
		$this->assertEqualSets( array(), self::$labs->registry->get_features() );
	}

	/**
	 * @covers \Affiliate_WP_Labs::get_features()
	 */
	public function test_get_features_should_return_registered_features() {
		$feature = array(
			'foobar' => array(
				'class' => 'Foo\Bar',
				'file'  => 'path/to/foobar.php'
			)
		);

		// Add a feature.
		self::$labs->register_feature( 'foobar', array(
			'class' => 'Foo\Bar',
			'file'  => 'path/to/foobar.php'
		) );

		// Confirm the feature is retrieved.
		$this->assertEqualSets( $feature, self::$labs->registry->get_features() );
	}
}
