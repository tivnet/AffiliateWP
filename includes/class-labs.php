<?php
/**
 * Implements the AffiliateWP Labs component.
 *
 * @since 2.0.4
 */
class Affiliate_WP_Labs {

	/**
	 * Registered labs features.
	 *
	 * @access private
	 * @since  1.0
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
//		if ( ! function_exists( 'affiliatewp_labs' ) ) {
//			// Admin notice.
//			return;
//		}

		add_action( 'admin_init', array( $this, 'load_files' ) );
		add_action( 'admin_init', array( $this, 'init_classes' ) );
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
				log_it( $file );
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
	 * @since  1.0
	 *
	 * @param array $feature_args {
	 *     Arguments for registering a new labs feature.
	 *
	 *     @type string $id    Feature ID.
	 *     @type string $class Feature class name.
	 *     @type string $file  Feature class file path.
	 * }
	 */
	public function register_feature( $feature_args ) {
		if ( ! array_key_exists( $feature_args['id'], $this->get_features() ) ) {
			$this->features[ $feature_args['id'] ] = array(
				'class' => $feature_args['class'],
				'file'  => $feature_args['file']
			);
		}
	}

	/**
	 * Retrieves the list of registered features and their corresponding classes.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return array Registered features.
	 */
	public function get_features() {
		return $this->features;
	}

}
