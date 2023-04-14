<?php
/**
 * @package EMO_BUPW
 * ========================
 * CSV CREATOR
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

 namespace EMO_BUPW\Repository\File_Handlers;
 use EMO_BUPW\Repository\File_Handlers\EMO_BUPW_File_Handler;
 use EMO_BUPW\Repository\File_Handlers\EMO_BUPW_Read_File_Interface;
 use EMO_BUPW\Repository\File_Handlers\EMO_BUPW_Write_File_Interface;
 if (!class_exists('EMO_BUPW_Csv_Handler')) {
	 class EMO_BUPW_Csv_Handler extends EMO_BUPW_File_Handler implements EMO_BUPW_Read_File_Interface, EMO_BUPW_Write_File_Interface {
		 /**
		  * Write array content on a csv file that each element of $content puts on each column of csv file
		  *
		  * @param array $arg {
		  *      argument parameters for writing on csv file
		  *
		  * @type int $fields = 1000
		  * @type string $separator =  ","
		  * @type string $enclosure = "\""
		  * @type string $escape = "\\"
		  * }
		  *
		  * @return array|false Returns an indexed array containing the fields read on success, or false on failure.
		  *
		  */
		 public function readFile( array $arg )  {
			 $length    = ( $arg['length'] ) ? $arg['length'] : 1000;
			 $separator = ( $arg['separator'] ) ? $arg['separator'] : ',';
			 $enclosure = ( $arg['enclosure'] ) ? $arg['enclosure'] : "\"";
			 $escape    = ( $arg['escape'] ) ? $arg['escape'] : "\\";

			 return fgetcsv( $this->handler, $length, $separator, $enclosure, $escape );
		 }


		 /**
		  * Write array content on a csv file that each element of $content puts on each column of csv file
		  *
		  * @param array $arg {
		  *      argument parameters for writing on csv file
		  *
		  * @type array $content the array data that should be written on csv file
		  * @type string $separator =  ","
		  * @type string $enclosure = "\""
		  * @type string $escape = "\\"
		  * @type string $eol = "\n"
		  * }
		  *
		  */
		 public function writeToFile( array $arg ) {
			 $content   = ( $arg['content'] ) ? $arg['content'] : '';
			 $separator = ( $arg['separator'] ) ? $arg['separator'] : ',';
			 $enclosure = ( $arg['enclosure'] ) ? $arg['enclosure'] : "\"";
			 $escape    = ( $arg['escape'] ) ? $arg['escape'] : "\\";
			 $eol       = ( $arg['eol'] ) ? $arg['eol'] : "\n";
			 fputcsv( $this->handler, $content, $separator, $enclosure, $escape );
		 }
	 }
 }