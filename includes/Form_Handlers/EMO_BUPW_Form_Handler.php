<?php

namespace EMO_BUPW\Form_Handlers;

use EMO_BUPW\EWPU_Form_Error;
if(! trait_exists('EMO_BUPW_Form_Handler')) {
	trait EMO_BUPW_Form_Handler {
		/**
		 * Check the security and required items of the form
		 *
		 * @param array $checker_items {
		 *      The parameters that must be checked before handling a form
		 *
		 * @type string $submit_status the name of submit button
		 * @type array $security {     the fields of WordPress nonce checker
		 * @type string nonce
		 * @type string nonce action
		 * @type array $requirements the fields of form that are required
		 *
		 * @return array|void
		 */
		protected function requirement_checker( array $checker_items ) {
			$errors[] = EWPU_Form_Error::submit_status( $checker_items['submit_status'] );
			$errors[] = EWPU_Form_Error::nonce_inspection( $checker_items['security'][0], ( $checker_items['security'][1] ) ? $checker_items['security'][1] : - 1 );
			foreach ( $checker_items['requirements'] as $item ) {
				$errors[] = EWPU_Form_Error::requirement_inspection( $item );
			}
			foreach ( $errors as $error ) {
				if ( $error ) {
					return [ 'error' => $error ];
				}
			}
		}
	}
}