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
	 * Labs features registry.
	 *
	 * @access public
	 * @since  2.0.4
	 * @var    \AffWP\Labs\Registry
	 */
	public $registry;

	/**
	 * Sets up the labs feature bootstrap and feature loader.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function __construct() {
		require_once AFFILIATEWP_PLUGIN_DIR . 'includes/labs/class-affwp-labs-registry.php';

		$this->registry = new \AffWP\Labs\Registry;

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

		$files = wp_list_pluck( $this->registry->get_features(), 'file' );

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

		$classes = wp_list_pluck( $this->registry->get_features(), 'class' );

		foreach ( $classes as $class ) {
			if ( class_exists( $class ) ) {
				new $class;
			}
		}
	}

}
