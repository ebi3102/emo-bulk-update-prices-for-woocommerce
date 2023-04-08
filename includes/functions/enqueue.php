<?php

/**
 * @package EMO_BUPW
 * ========================
 * ADMIN ENQUEUE FUNCTIONS
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */


/*
 *Load styles and scripts
 *
 */
if ( ! function_exists( 'emo_bupw_scripts' )) {
	function emo_bupw_scripts( $hook ) {
		if ( $hook == 'toplevel_page_emo_bupw_slug' ) {
			wp_register_style( 'emo_bupw_admin_style', EMO_BUPW_URI . 'assets/css/style.admin.css', array(), '1.0.0', 'all' );
			wp_enqueue_style( 'emo_bupw_admin_style' );
		}
	}
}
