<?php
/**
 * Defines a common functions that any class responsible for
 * reading a file.
 */
interface EWPU_Read_File_Interface {

	/**
	 * @param array $arg define any arguments that are needed to read a file
	 * @return array | false
	 */
	public function readFile(array $arg):array | false ;
}