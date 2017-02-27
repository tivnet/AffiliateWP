<?php
namespace AffWP\Labs;

/**
 * Implements a registry for labs features.
 *
 * @since 2.0.4
 *
 * @see \AffWP\Registry
 */
class Registry extends \AffWP\Registry {

	/**
	 * Initializes the features registry.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function init() {}

	/**
	 * Registers a new labs feature for the loader.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @param string $feature_id Unique feature ID.
	 * @param array  $attributes {
	 *     Feature attributes.
	 *
	 *     @type string $class Feature class name.
	 *     @type string $file  Feature class file path.
	 * }
	 * @return true Always true.
	 */
	public function register_feature( $feature_id, $attributes ) {
		return $this->add_item( $feature_id, $attributes );
	}

	/**
	 * Removes a labs feature from the registry by ID.
	 *
	 * @access public
	 * @since  2.0.4
	 *
	 * @param string $feature_id Feature ID.
	 */
	public function remove_process( $feature_id ) {
		$this->remove_item( $feature_id );
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
		return $this->get_items();
	}

	/**
	 * Only intended for use by tests.
	 *
	 * @access public
	 * @since  2.0.4
	 */
	public function _reset_features() {
		$this->_reset_items();
	}

}
