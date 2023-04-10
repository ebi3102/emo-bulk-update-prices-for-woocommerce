<?php
/**
 * @package EWPU
 * ========================
 * Notice Template
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

 namespace EMO_BUPW;
 if (!class_exists('EMO_BUPW_Notice_Template')) {
	 class EMO_BUPW_Notice_Template {
		 private static $template = null;
		 private static $massage = null;
		 private static $noticeType = null;
		 private static $is_dismissible = false;

		 private function __construct() {
		 }

		 /**
		  * @return string|null
		  */
		 private static function render_template() {
			 if ( self::$is_dismissible ) {
				 $is_dismissible = 'is-dismissible';
				 $closeButton    = "<button type='button' class='notice-dismiss'><span class='screen-reader-text'>" .
				                   esc_html(__( 'Dismiss this warning', 'emo-bulk-update-prices-for-woocommerce' )) . "</span></button>";
			 } else {
				 $is_dismissible = '';
				 $closeButton    = '';
			 }
			 self::$template = "<div class='notice notice-" . esc_attr(self::$noticeType) . " settings-error " . esc_attr($is_dismissible) . "'>";
			 self::$template .= "<p><strong><span style='display: block; margin: 0.5em 0.5em 0 0; clear: both;'>
        <span style='display: block; margin: 0.5em 0.5em 0 0; clear: both;'>";
			 self::$template .= esc_html(self::$massage) . "</span></strong></p>" . $closeButton . "</div>";

			 return wp_kses_post(self::$template);
		 }

		 /**
		  * Render Success Notice massage
		  *
		  * @param string $massage
		  * @param bool $is_dismissible
		  *
		  * @return string|null
		  */
		 public static function success( string $massage, bool $is_dismissible = true ) {
			 self::$noticeType     = 'success';
			 self::$is_dismissible = $is_dismissible;
			 self::$massage        = $massage;

			 return self::render_template();

		 }

		 /**
		  * Render Warning Notice massage
		  *
		  * @param string $massage
		  * @param bool $is_dismissible
		  *
		  * @return string|null
		  */
		 public static function warning( string $massage, bool $is_dismissible = true ) {
			 self::$noticeType     = 'warning';
			 self::$is_dismissible = $is_dismissible;
			 self::$massage        = $massage;

			 return self::render_template();

		 }
	 }
 }
