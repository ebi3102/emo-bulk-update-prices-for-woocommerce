<?php
/**
 * Defines a common functions that any class responsible for
 * reading a file.
 */

namespace EMO_BUPW\Repository\File_Handlers;
if (!interface_exists('EMO_BUPW_Read_File_Interface')) {
	interface EMO_BUPW_Read_File_Interface {

		/**
		 * @param array $arg define any arguments that are needed to read a file
		 *
		 * @return array | false
		 */
		public function readFile( array $arg ): array|false;
	}
}