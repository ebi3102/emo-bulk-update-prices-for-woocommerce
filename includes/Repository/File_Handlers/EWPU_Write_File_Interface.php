<?php
/**
 * Defines a common functions that any class responsible for
 * writing on a file.
 */

 namespace EmoWooPriceUpdate\Repository\File_Handlers;
interface EWPU_Write_File_Interface {

	/**
	 * @param array $arg define any arguments that are needed to write on a file
	 * @return void
	 */
	public function writeToFile(array $arg);
}