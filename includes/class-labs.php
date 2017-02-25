<?php
/**
 * Implements the AffiliateWP Labs component.
 *
 * @since 2.0.4
 */
class Affiliate_WP_Labs {

	/**
	 * Customizer panel ID (if needed).
	 *
	 * @access private
	 * @since  2.0.4
	 * @var    string
	 */
	private $customizer_panel_id = 'affwp_labs';

	/**
	 * Registered labs features.
	 *
	 * @access private
	 * @since  2.0.4
	 * @var    array
	 */
	private $features = array();

	/**
	 * Sets up the labs feature bootstrap and feature loader.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ), 50 );
		add_action( 'after_setup_theme',  array( $this, 'load_files'         )     );
		add_action( 'after_setup_theme',  array( $this, 'init_classes'       )     );
	}

	/**
	 * Retrieves the Customizer panel ID for use by Labs features.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @return string Customizer panel ID.
	 */
	public function get_panel_id() {
		return $this->customizer_panel_id;
	}

	/**
	 * Fires Labs actions requiring the customizer.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer instance.
	 */
	public function customize_register( $wp_customize ) {

		/**
		 * Fires on the core {@see 'customize_register'} action.
		 *
		 * @since 2.0.4
		 *
		 * @param \WP_Customize_Manager $wp_customize Customizer instance.
		 */
		do_action( 'affwp_labs_customize_register', $wp_customize );

		// Only register the panel if a feature has added a section against it.
		if ( has_action( 'affwp_labs_customize_register' ) ) {
			$wp_customize->add_panel( $this->customizer_panel_id, array(
				// Title deliberately not translatable.
				'title'           => 'AffiliateWP',
				'type'            => 'affwp_labs',
				'active_callback' => '__return_true',
				'description'     => '',
			) );
		}
	}

	/**
	 * Loads labs feature files.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function load_files() {
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/abstracts/class-affwp-labs-feature.php';

		$files = wp_list_pluck( $this->get_features(), 'file' );

		if ( ! empty( $files ) ) {
			foreach ( $files as $file ) {
				require_once( $file );
			}
		}
	}

	/**
	 * Instantiates labs feature classes if available.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function init_classes() {

		$classes = wp_list_pluck( $this->get_features(), 'class' );

		foreach ( $classes as $class ) {
			if ( class_exists( $class ) ) {
				new $class;
			}
		}
	}

	/**
	 * Registers a new labs feature for the loader.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @param string $feature_id   Uniuqe feature ID.
	 * @param array  $feature_args {
	 *     Arguments for registering a new labs feature.
	 *
	 *     @type string $class Feature class name.
	 *     @type string $file  Feature class file path.
	 * }
	 */
	public function register_feature( $feature_id, $feature_args ) {
		if ( ! array_key_exists( $feature_id, $this->get_features() ) ) {
			$this->features[ $feature_id ] = array(
				'class' => $feature_args['class'],
				'file'  => $feature_args['file']
			);
		}
	}

	/**
	 * Retrieves the list of registered features and their corresponding classes.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @return array Registered features.
	 */
	public function get_features() {
		return $this->features;
	}

	/**
	 * Only intended for use by tests.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function _reset_features() {
		if ( ! defined( 'WP_TESTS_DOMAIN' ) ) {
			_doing_it_wrong( 'This method is only intended for use in phpunit tests', '2.0.4' );
		} else {
			$this->features = array();
		}
	}
}
