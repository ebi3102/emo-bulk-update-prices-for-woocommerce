<?php
/**
 * Base class for Handling files.
 *
 */

 namespace EMO_BUPW\Repository\File_Handlers;
 if (!class_exists('EMO_BUPW_File_Handler')) {
	 class EMO_BUPW_File_Handler {
		 protected $handler = null;

		 /**
		  * Constructor
		  *
		  * @param string $filename
		  * @param string $mode
		  * @param false $use_include_path
		  */
		 public function __construct( string $filename, string $mode, bool $use_include_path = false ) {
			 $this->handler = fopen( $filename, $mode, $use_include_path );
		 }

		 /**
		  * Close an open file pointer
		  *
		  */
		 public function closeFile() {
			 fclose( $this->handler );
		 }
	 }
 }