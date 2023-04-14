<?php
/**
 * Defines a common functions that any class responsible for
 * writing on a file.
 */

namespace EMO_BUPW\Repository\File_Handlers;
if (!interface_exists('EMO_BUPW_Write_File_Interface')) {
	interface EMO_BUPW_Write_File_Interface {

		/**
		 * @param array $arg define any arguments that are needed to write on a file
		 *
		 * @return void
		 */
		public function writeToFile( array $arg );
	}
}