<?php

/**
 * @package EMO_BUPW
 * ========================
 * ADMIN PAGE
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

if ( ! function_exists( 'emo_bupw_add_admin_page' )) {
	add_action( 'admin_menu', 'emo_bupw_add_admin_page' );
	function emo_bupw_add_admin_page() {
		//Generate ECB Admin Page
		add_menu_page(
			__( 'Update whole prices', 'emo-bulk-update-prices-for-woocommerce' ),
			__( 'Prices management', 'emo-bulk-update-prices-for-woocommerce' ),
			'manage_woocommerce', 'emo_bupw_slug',
			'emo_bupw_update_prices_create_page',
			EMO_BUPW_URI . 'assets/img/logo-icon.png',
			110
		);

		//Generate update prices by file page
		add_submenu_page(
			'emo_bupw_slug',
			__( 'Update whole prices', 'emo-bulk-update-prices-for-woocommerce' ),
			__( 'Update whole prices', 'emo-bulk-update-prices-for-woocommerce' ),
			'manage_woocommerce',
			'emo_bupw_slug',
			'emo_bupw_update_prices_create_page'
		);

		//Generate group update prices
		add_submenu_page(
			'emo_bupw_slug',
			__( 'Group price update', 'emo-bulk-update-prices-for-woocommerce' ),
			__( 'Group price update', 'emo-bulk-update-prices-for-woocommerce' ),
			'manage_woocommerce',
			'group_price_update',
			'emo_bupw_group_price_update'
		);

		//Generate group on-sale prices
		add_submenu_page(
			'emo_bupw_slug',
			__( 'Group discount', 'emo-bulk-update-prices-for-woocommerce' ),
			__( 'Group discount', 'emo-bulk-update-prices-for-woocommerce' ),
			'manage_woocommerce',
			'group_discount',
			'emo_bupw_group_discount'
		);


	}
}

//Template submenu functions
if ( ! function_exists( 'emo_bupw_update_prices_create_page' )) {
	function emo_bupw_update_prices_create_page() {
		$url_template = EMO_BUPW_DIR . '/includes/templates/update-prices-admin.php';
		require_once $url_template;
	}
}

if ( ! function_exists( 'emo_bupw_group_price_update' )) {
	function emo_bupw_group_price_update() {
		$url_template = EMO_BUPW_DIR . '/includes/templates/group-price-update.php';
		require_once $url_template;
	}
}

if ( ! function_exists( 'emo_bupw_group_discount' )) {
	function emo_bupw_group_discount() {
		$url_template = EMO_BUPW_DIR . '/includes/templates/group-discount.php';
		require_once $url_template;
	}
}