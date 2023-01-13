<?php

/**
 * @package EWTGP
 * ========================
 * ADMIN PAGE
 * ========================
 * Text Domain: emo_ewpu
 */


function emo_ewpu_add_admin_page() {
    global $emo_ewpu_plugin_url;
    //Generate ECB Admin Page
    add_menu_page( __( 'Update whole prices', 'emo_ewpu' ), __( 'Prices management', 'emo_ewpu' ), 'manage_options', 'emo_ewpu_slug', 'emo_ewpu_update_prices_create_page', $emo_ewpu_plugin_url . 'assets/img/logo-icon.png', 110 );

    //Generate Sunset Admin Sub Pages
    add_submenu_page('emo_ewpu_slug', __( 'Update whole prices', 'emo_ewpu' ), __( 'Settings', 'emo_ewpu' ) , 'manage_options' , 'emo_ewpu_slug' , 'emo_ewpu_update_prices_create_page');

}
add_action( 'admin_menu', 'emo_ewpu_add_admin_page' );

//Template submenu functions
function emo_ewpu_update_prices_create_page() {
    global $ecb_plugin_directory;;
    $url_template = 'templates/update-prices-admin.php';
    require_once $url_template;
}

