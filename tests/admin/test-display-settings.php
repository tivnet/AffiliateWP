<?php
namespace AffWP\Settings;

use AffWP\Tests\UnitTestCase;

/**
 * Tests for functionality defined in display-settings.php.
 *
 * @group settings
 * @group functions
 */
class Display_Tests extends UnitTestCase {

	/**
	 * Settings instance.
	 *
	 * @access protected
	 * @var    Affiliate_WP_Settings
	 * @static
	 */
	protected static $settings;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$settings = new \Affiliate_WP_Settings;

		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
	}

	/**
	 * @covers ::affwp_get_settings_tabs()
	 */
	public function test_get_settings_tabs_should_return_array_of_settings_tabs() {
		$tabs = array(
			'general'      => __( 'General', 'affiliate-wp' ),
			'integrations' => __( 'Integrations', 'affiliate-wp' ),
			'emails'       => __( 'Emails', 'affiliate-wp' ),
			'misc'         => __( 'Misc', 'affiliate-wp' ),
			'labs'         => __( 'Labs', 'affiliate-wp' ),
		);

		$this->assertEqualSets( $tabs, affwp_get_settings_tabs() );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_should_always_return_a_string() {
		$result = affwp_get_settings_tab_description( null );

		$this->assertTrue( is_string( $result ) );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_should_always_return_an_empty_string_for_an_invalid_tab() {
		$result = affwp_get_settings_tab_description( 'foo' );

		$this->assertSame( '', $result );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_with_labs_should_return_labs_description() {
		$description  = '<p>' . __( 'By choosing to enable Labs features, you agree to participate in anonymized usage data collection to help us further develop and improve features for AffiliateWP.', 'affiliate-wp' ) . '</p>';
		$description .= '<p>' . __( 'Data collected will differ from feature to feature, but typically will include settings configuration, behavioral data around frequency and type of use, and other metrics. All data collected will be anonymized to protect your privacy.', 'affiliate-wp' ) . '</p>';

		$result = affwp_get_settings_tab_description( 'labs' );

		$this->assertEquals( $description, $result );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_with_general_tab_should_return_empty_string() {
		$this->assertSame( '', affwp_get_settings_tab_description( 'general' ) );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_with_integrations_tab_should_return_empty_string() {
		$this->assertSame( '', affwp_get_settings_tab_description( 'integrations' ) );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_with_emails_tab_should_return_empty_string() {
		$this->assertSame( '', affwp_get_settings_tab_description( 'emails' ) );
	}

	/**
	 * @covers ::affwp_get_settings_tab_description()
	 */
	public function test_get_settings_tab_description_with_misc_tab_should_return_empty_string() {
		$this->assertSame( '', affwp_get_settings_tab_description( 'misc' ) );
	}

}
