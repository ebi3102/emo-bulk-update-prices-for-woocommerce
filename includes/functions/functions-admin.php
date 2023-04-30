<?php

/**
 * @package EMO_BUPW
 * ========================
 * ADMIN PAGE
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

use EMO_BUPW\Repository\EMO_BUPW_Request_Handler;
use EMO_BUPW\Templates\EMO_BUPW_Form_Elements_Injection;
use EMO_BUPW\Templates\EMO_BUPW_Product_Category_Option_list;


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
			__( 'Update by CSV', 'emo-bulk-update-prices-for-woocommerce' ),
			__( 'Update by CSV', 'emo-bulk-update-prices-for-woocommerce' ),
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

        new EMO_BUPW_Form_Elements_Injection();

		if(EMO_BUPW_Request_Handler::get_POST('btnSubmit')){
			$result = emo_bupw_get_price_update_data();
		}

		if( EMO_BUPW_Request_Handler::get_POST('btnSubmit') && @!$result['error']){
			$successMassage = esc_html(__('Your changes have been applied successfully. Please check the ', 'emo-bulk-update-prices-for-woocommerce'));
			$successMassage .= "<a href='".esc_url($result['filePath'])."'>".esc_html($result['fileName'])."</a>";
			$successMassage .= esc_html(__(' to check the correctness of the updated changes', 'emo-bulk-update-prices-for-woocommerce'));
		}else{
			$successMassage = '';
		}

		if( EMO_BUPW_Request_Handler::get_POST('btnSubmit') && @$result['error']){
			$errorMessage = $result['error']->get_error_message();
		}else{
			$errorMessage = '';
		}

		require_once EMO_BUPW_DIR . '/includes/templates/group-price-update.php';;
	}
}

if ( ! function_exists( 'emo_bupw_group_discount' )) {
	function emo_bupw_group_discount() {
		$months = new WP_Locale();


		$options_html = EMO_BUPW_Product_Category_Option_list::render_template();

		if(EMO_BUPW_Request_Handler::get_POST('btnSubmit')){
			$result = emo_bupw_get_group_discount_data();
		}

		if( EMO_BUPW_Request_Handler::get_POST('btnSubmit') && @!$result['error']){
			$successMassage = esc_html(__('Your changes have been applied successfully. Please check the ', 'emo-bulk-update-prices-for-woocommerce'));
			$successMassage .= "<a href='".esc_url($result['filePath'])."'>".esc_html($result['fileName'])."</a>";
			$successMassage .= esc_html(__(' to check the correctness of the updated changes', 'emo-bulk-update-prices-for-woocommerce'));
		}else{
			$successMassage = '';
		}
		if( EMO_BUPW_Request_Handler::get_POST('btnSubmit') && @$result['error']){
			$errorMessage = $result['error']->get_error_message();
		}else{
			$errorMessage = '';
		}

		require_once  EMO_BUPW_DIR . '/includes/templates/group-discount.php';

	}
}