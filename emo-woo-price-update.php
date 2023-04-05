<?php
/**
 * @package EWPU
 * Plugin Name: Emo Bulk Update Prices for WooCommerce
 * Plugin URI:
 * Description: Emo Bulk Update Prices for WooCommerce is a bulk price updater plugin specially made for WooCommerce products
 * Version: 1.1.1
 * Author: Ebrahim Moeini
 * Author URI: https://emoeini.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: emo_ewpu
 * Domain Path: /languages
 **/
//namespace EMO_Woo_Price_Update;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$upload_base = wp_upload_dir();

// define URIs and directories
define('EWPU_URI', plugin_dir_url( __FILE__ ));
define('EWPU_DIR', __DIR__ );
define('EWPU_CREATED_DIR', $upload_base['basedir'].'/emo_ewpu/CreatedFiles/');
define('EWOU_UPLOAD_DIR', $upload_base['basedir'].'/emo_ewpu/uploadedFiles/');
define('EWPU_CREATED_URI', $upload_base['baseurl'].'/emo_ewpu/CreatedFiles/');
define('EWOU_UPLOAD_URI', $upload_base['baseurl'].'/emo_ewpu/uploadedFiles/');

// define option meta_keys
define('REGULARMETAKEY', '_regular_price_history');
define('SALEMETAKEY', '_sale_price_history');
define('MAINVAR','_main_variation');
define('FEATUREPRODUCT', '_feature_product_cat');

// define date and time formats
define('TIME_SEPARATOR' , '/');
define('DATE_SEPARATOR', '-');
define('DATAFORMAT', 'Y'.DATE_SEPARATOR.'m'.DATE_SEPARATOR.'d'.TIME_SEPARATOR.'h:i:s');

if ( ! function_exists( 'emo_ewpu_init' ) ) {
	add_action( 'plugins_loaded', 'emo_ewpu_init', 11 );

	function emo_ewpu_init() {

        if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'emo_ewpu_notice_wc' );
			return;
		}

        //Check and create essential directories 
        if (!file_exists(EWPU_CREATED_DIR))
            mkdir(EWPU_CREATED_DIR, 0777, true);
        if (!file_exists(EWOU_UPLOAD_DIR))
            mkdir(EWOU_UPLOAD_DIR, 0777, true);
        

        if(is_admin()){
	        // Include the autoloader that we can dynamically include the rest of the classes.
	        require_once EWPU_DIR. '/vendor/autoload.php';

            include_once("includes/functions/form-functions.php");
            include_once("includes/functions/wp_functions.php");
            include_once("includes/functions/functions-admin.php");
            include_once( "includes/functions/enqueue.php" );

            //register scripts
            add_action('admin_enqueue_scripts', 'emo_ewpu_scripts');
        }
        
        /**
         * Load the plugin text domain for translation.
         */
        add_action( 'init', 'emo_ewpu_load_textdomain' );

        function emo_ewpu_load_textdomain() {
            load_plugin_textdomain( 'emo_ewpu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
    }
} else {
	add_action( 'admin_notices', 'emo_ewpu_notice_faulty' );
}

if ( ! function_exists( 'emo_ewpu_notice_wc' ) ) {
	function emo_ewpu_notice_wc() {
		?>
        <div class="error">
            <p><strong>Emo Bulk Update Prices for WooCommerce</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'emo_ewpu_notice_faulty' ) ) {
	function emo_ewpu_notice_faulty() {
		?>
        <div class="error">
            <p>Seems there is an error in installation of <strong>Emo Bulk Update Prices for WooCommerce</strong>. Please
                delete the plugin an install it again.</p>
        </div>
		<?php
	}
}