<?php
/**
 * @package EMO_BUPW
 * Plugin Name: Emo Bulk Update Prices for WooCommerce
 * Plugin URI:
 * Description: Emo Bulk Update Prices for WooCommerce is a bulk price updater plugin specially made for WooCommerce products
 * Version: 1.1.1
 * Author: Ebrahim Moeini
 * Author URI: https://emoeini.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 * Domain Path: /languages
 **/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$upload_base = wp_upload_dir();

// define URIs and directories
define('EMO_BUPW_URI', plugin_dir_url( __FILE__ ));
define('EMO_BUPW_DIR', __DIR__ );
define('EMO_BUPW_CREATED_DIR', $upload_base['basedir'] . '/emo_bupw/CreatedFiles/');
define('EMO_BUPW_UPLOAD_DIR', $upload_base['basedir'] . '/emo_bupw/uploadedFiles/');
define('EMO_BUPW_CREATED_URI', $upload_base['baseurl'] . '/emo_bupw/CreatedFiles/');
define('EMO_BUPW_UPLOAD_URI', $upload_base['baseurl'] . '/emo_bupw/uploadedFiles/');

if ( ! function_exists( 'emo_ewpu_init' ) ) {
	add_action( 'plugins_loaded', 'emo_ewpu_init', 11 );

	function emo_ewpu_init() {

        if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'emo_ewpu_notice_wc' );
			return;
		}

        //Check and create essential directories 
        if (!file_exists(EMO_BUPW_CREATED_DIR))
            mkdir(EMO_BUPW_CREATED_DIR, 0777, true);
        if (!file_exists(EMO_BUPW_UPLOAD_DIR))
            mkdir(EMO_BUPW_UPLOAD_DIR, 0777, true);
        

        if(is_admin()){
	        // Include the autoloader that we can dynamically include the rest of the classes.
	        require_once EMO_BUPW_DIR . '/vendor/autoload.php';

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
            load_plugin_textdomain( 'emo-bulk-update-prices-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
    }
} else {
	add_action( 'admin_notices', 'emo_ewpu_notice_faulty' );
}

if ( ! function_exists( 'emo_ewpu_notice_wc' ) ) {
	function emo_ewpu_notice_wc() {
		?>
        <div class="error">
            <p><strong>Emo Woo Update Prices</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'emo_ewpu_notice_faulty' ) ) {
	function emo_ewpu_notice_faulty() {
		?>
        <div class="error">
            <p>Seems there is an error in installation of <strong>Emo Woo Update Prices</strong>. Please
                delete the plugin an install it again.</p>
        </div>
		<?php
	}
}