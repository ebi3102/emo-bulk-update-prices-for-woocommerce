<?php
/**
 * Interface for implementing write on a file.
 */
interface EWPU_Write_File_Interface {

	/**
	 * @param array $arg
	 * @return void
	 */
	public function writeToFile(array $arg);

}