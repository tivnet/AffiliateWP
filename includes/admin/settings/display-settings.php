<?php
/**
 * Admin Options Page
 *
 * @package     AffiliateWP
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2014, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @since 1.0
 * @return void
 */
function affwp_settings_admin() {

	$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], affwp_get_settings_tabs() ) ? $_GET[ 'tab' ] : 'general';

	ob_start();
	?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<?php affwp_navigation_tabs( affwp_get_settings_tabs(), $active_tab, array( 'settings-updated' => false ) ); ?>
		</h2>
		<div id="tab_container">
			<form method="post" action="options.php">
				<table class="form-table">
				<?php
				settings_fields( 'affwp_settings' );
				do_settings_fields( 'affwp_settings_' . $active_tab, 'affwp_settings_' . $active_tab );
				?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div><!-- #tab_container-->
	</div><!-- .wrap -->
	<?php
	echo ob_get_clean();
}


/**
 * Retrieves the settings tabs.
 *
 * @since 1.0
 *
 * @return array $tabs Settings tabs.
 */
function affwp_get_settings_tabs() {

	$tabs = array(
		'general'      => __( 'General', 'affiliate-wp' ),
		'integrations' => __( 'Integrations', 'affiliate-wp' ),
		'emails'       => __( 'Emails', 'affiliate-wp' ),
		'misc'         => __( 'Misc', 'affiliate-wp' ),
		'labs'         => __( 'Labs', 'affiliate-wp' ),
	);

	/**
	 * Filters the list of settings tabs.
	 *
	 * @param array $tabs Settings tabs.
	 */
	return apply_filters( 'affwp_settings_tabs', $tabs );
}
