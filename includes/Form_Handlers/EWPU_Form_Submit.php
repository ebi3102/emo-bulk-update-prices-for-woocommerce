<?php

namespace EmoWooPriceUpdate\Form_Handlers;


interface EWPU_Form_Submit {
	/**
	 * Handle the submission of a form and return the response as an array
	 * @param array $args
	 *
	 * @return array
	 */
	public function submit( array $args):array;
}