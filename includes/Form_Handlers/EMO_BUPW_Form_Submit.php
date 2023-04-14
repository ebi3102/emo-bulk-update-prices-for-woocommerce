<?php

namespace EMO_BUPW\Form_Handlers;

if (!interface_exists('EMO_BUPW_Form_Submit')) {
	interface EMO_BUPW_Form_Submit {
		/**
		 * Handle the submission of a form and return the response as an array
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		public function submit( array $args ): array;
	}
}