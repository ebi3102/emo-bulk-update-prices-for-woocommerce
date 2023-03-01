<?php

namespace EmoWooPriceUpdate\Form_Handlers;


use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Write_File_Interface;

class EWPU_Form_Products_Price_List implements EWPU_Form_Field_Setter, EWPU_Form_Submit {



	use  EWPU_Form_Handler;

	/**
	 * @inheritDoc
	 */
	function field_setter( $fields ): void {
		// TODO: Implement field_setter() method.
	}

	public function submit( array $args ): array {
		// TODO: Implement submit() method.
	}
}