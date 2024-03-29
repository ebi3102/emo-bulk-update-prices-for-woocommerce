<?php
/**
 * @package EMO_BUPW
 * ========================
 * CSV Row CREATOR
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

 namespace EMO_BUPW\Repository\File_Handlers;
 if (!class_exists('EMO_BUPW_Add_Row_Csv')) {
	 class EMO_BUPW_Add_Row_Csv {
		 private static $createDirectory = EMO_BUPW_CREATED_DIR;
		 private static $handler = null;
		 private static $instance = null;

		 private function __construct() {
		 }

		 public static function openFile( $filename, $mode ) {
			 self::$instance = new Self;
			 self::$handler  = fopen( self::$createDirectory . $filename, $mode );

			 return self::$instance;
		 }

		 public function writeToFile( $content ) {
			 $write = fputcsv( self::$handler, $content );

			 return $this;
		 }

		 public function closeFile() {
			 return fclose( self::$handler );
		 }
	 }
 }
