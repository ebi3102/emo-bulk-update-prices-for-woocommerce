<?php

namespace EmoWooPriceUpdate\Repository\File_Handlers;

class EWPU_Products_Price_List_Creator implements EWPU_Write_File_Interface {

	private $file;

	public function __construct(EWPU_Write_File_Interface $file){
		$this->file = $file;
	}

	/**
	 * @inheritDoc
	 */
	public function writeToFile( array $arg ) {
		// TODO: Implement writeToFile() method.
	}
}