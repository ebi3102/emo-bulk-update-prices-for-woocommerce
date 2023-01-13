<?php

/**
 * @package EWPU
 * Plugin Name: Emo Woocommerce Update Price
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
global $emo_ewpu_plugin_url;
global $emo_ewpu_plugin_directory;
$emo_ewpu_plugin_url = plugin_dir_url( __FILE__ );
$emo_ewpu_plugin_directory = __DIR__ ;

global $emo_ewpu_createdFiles_dir;
global $emo_ewpu_uploadedFiles_dir;
global $emo_ewpu_createdFiles_url;
global $emo_ewpu_uploadedFiles_url;

$upload_base = wp_upload_dir();
$emo_ewpu_createdFiles_dir = $upload_base['basedir'].'/emo_ewpu/CreatedFiles';
$emo_ewpu_uploadedFiles_dir = $upload_base['basedir'].'/emo_ewpu/uploadedFiles';
$emo_ewpu_createdFiles_url = $upload_base['baseurl'].'/emo_ewpu/CreatedFiles';
$emo_ewpu_uploadedFiles_url = $upload_base['baseurl'].'/emo_ewpu/uploadedFiles';

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