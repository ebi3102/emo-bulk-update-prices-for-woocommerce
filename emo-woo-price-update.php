<?php

/**
 * @package EWPU
 * Plugin Name: Emo Woocommerce Update Prices
 * Plugin URI:
 * Description: EWPU is a bulk price updater plugin specially made for WooCommerce products
 * Version: 1.0.0
 * Author: Ebrahim Moeini
 * Author URI: https://emoeini.com
 * License:
 * Text Domain: emo_ewpu
 * Domain Path: /languages
 **/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$upload_base = wp_upload_dir();

// define URIs and directories
define('EWPU_URI', plugin_dir_url( __FILE__ ));
define('EWPU_DIR', __DIR__ );
define('EWPU_CREATED_DIR', $upload_base['basedir'].'/emo_ewpu/CreatedFiles');
define('EWOU_UPLOAD_DIR', $upload_base['basedir'].'/emo_ewpu/uploadedFiles');
define('EWPU_CREATED_URI', $upload_base['baseurl'].'/emo_ewpu/CreatedFiles');
define('EWOU_UPLOAD_URI', $upload_base['baseurl'].'/emo_ewpu/uploadedFiles');

// define option meta_keys
define('REGULARMETAKEY', '_regular_price_history');
define('SALEMETAKEY', '_sale_price_history');
define('MAINVAR','_main_variation');
define('FEATUREPRODUCT', '_feature_product_cat');

global $emo_ewpu_timeSeparator;
global $emo_ewpu_dateSeparator;
$emo_ewpu_timeSeparator = '/';
$emo_ewpu_dateSeparator = '-';
define('DATAFORMAT', 'Y'.$emo_ewpu_dateSeparator.'m'.$emo_ewpu_dateSeparator.'d'.$emo_ewpu_timeSeparator.'h:i:s');

include_once("includes/wp_functions.php");
include_once("includes/functions-admin.php");
include_once("includes/enqueue.php");


//register scripts
//add_action('wp_enqueue_scripts', 'emo_ewpu_scripts');
add_action('admin_enqueue_scripts', 'emo_ewpu_scripts');

add_action('wp_enqueue_scripts', 'emo_ewpu_scripts');

/**
 * Load the plugin text domain for translation.
 */
add_action( 'init', 'emo_ewpu_load_textdomain' );

function emo_ewpu_load_textdomain() {
    load_plugin_textdomain( 'emo_ewpu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}