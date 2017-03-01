<?php
namespace AffWP\Labs;

/**
 * Abstract base class for defining a Labs feature.
 *
 * @since 2.0.4
 */
abstract class Feature {

	/**
	 * Runs during instantiation of a single Labs feature.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function __construct() {
		add_filter( 'affwp_settings_labs', array( $this, 'register_labs_setting' ) );
	}

	/**
	 * Registers the labs setting.
	 *
	 * @access public
	 * @since  2.0.4
	 * @abstract
	 *
	 * @param array $settings Labs settings.
	 * @return array Modified labs settings.
	 */
	abstract public function register_labs_setting( $settings );

}
